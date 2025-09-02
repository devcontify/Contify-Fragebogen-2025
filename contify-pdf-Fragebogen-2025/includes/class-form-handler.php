<?php

if (!defined('ABSPATH')) {
    exit;
}

class CON_FRA_2025_Form_Handler {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_ajax_con_fra_2025_submit', array($this, 'handle_form_submission'));
        add_action('wp_ajax_nopriv_con_fra_2025_submit', array($this, 'handle_form_submission'));
        add_action('init', array($this, 'maybe_handle_form_submission'));
    }
    
    public function maybe_handle_form_submission() {
        if (!isset($_POST['con_fra_2025_submit']) || !wp_verify_nonce($_POST['con_fra_2025_nonce'], 'con_fra_2025_form')) {
            return;
        }
        
        $this->handle_form_submission();
    }
    
    public function handle_form_submission() {
        if (defined('DOING_AJAX') && DOING_AJAX) {
            if (!wp_verify_nonce($_POST['con_fra_2025_nonce'], 'con_fra_2025_form')) {
                wp_die('Security check failed', 'contify-pdf-Fragebogen-2025');
            }
        }
        
        $form_data = $this->sanitize_form_data($_POST);
        
        if ($this->validate_form_data($form_data)) {
            $submission_id = $this->save_submission($form_data);
            
            if ($submission_id) {
                $email_handler = CON_FRA_2025_Email_Handler::get_instance();
                $email_sent = $email_handler->send_notification_email($form_data, $submission_id);
                
                if (defined('DOING_AJAX') && DOING_AJAX) {
                    wp_send_json_success(array(
                        'message' => __('Fragebogen erfolgreich übermittelt!', 'contify-pdf-Fragebogen-2025'),
                        'submission_id' => $submission_id
                    ));
                } else {
                    wp_redirect(add_query_arg('con_fra_success', '1', wp_get_referer()));
                    exit;
                }
            }
        } else {
            if (defined('DOING_AJAX') && DOING_AJAX) {
                wp_send_json_error(array(
                    'message' => __('Bitte füllen Sie alle Pflichtfelder aus.', 'contify-pdf-Fragebogen-2025')
                ));
            }
        }
    }
    
    private function sanitize_form_data($data) {
        $sanitized = array();
        
        $text_fields = array(
            'text_count', 'word_count', 'target_group', 'user_intention', 
            'text_goals', 'values_norms', 'usps', 'text_orientation', 
            'cooperations', 'language_style', 'keywords', 'heading_max_chars',
            'paragraph_requirements', 'additional_notes'
        );
        
        $checkbox_fields = array(
            'w_questions', 'google_suggest', 'wdf_idf_analysis', 'language_nogo',
            'wording_restrictions', 'cta_in_text', 'cta_separated', 'cta_button',
            'address_du', 'address_sie', 'address_neutral', 'intonation_wir',
            'intonation_ich', 'intonation_firma_de', 'intonation_firma',
            'intonation_neutral', 'abbreviations_expand', 'units_expand',
            'keywords_provided', 'keywords_research', 'image_suggestions',
            'meta_info', 'tables_support', 'quotes_support', 'lists_support',
            'heading_limit', 'paragraph_limit', 'delivery_word', 'delivery_html',
            'delivery_special'
        );
        
        $textarea_fields = array(
            'language_nogo_details', 'wording_details'
        );
        
        foreach ($text_fields as $field) {
            if (isset($data[$field])) {
                $sanitized[$field] = sanitize_text_field($data[$field]);
            }
        }
        
        foreach ($checkbox_fields as $field) {
            $sanitized[$field] = isset($data[$field]) ? 1 : 0;
        }
        
        foreach ($textarea_fields as $field) {
            if (isset($data[$field])) {
                $sanitized[$field] = sanitize_textarea_field($data[$field]);
            }
        }
        
        return $sanitized;
    }
    
    private function validate_form_data($data) {
        $required_fields = array('text_count', 'word_count', 'target_group');
        
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }
        
        return true;
    }
    
    private function save_submission($data) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'con_fra_2025_submissions';
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'submission_data' => wp_json_encode($data),
                'submission_time' => current_time('mysql'),
                'user_ip' => $this->get_client_ip()
            ),
            array('%s', '%s', '%s')
        );
        
        return $result ? $wpdb->insert_id : false;
    }
    
    private function get_client_ip() {
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = explode(',', $ip)[0];
                }
                if (filter_var(trim($ip), FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return trim($ip);
                }
            }
        }
        
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    }
    
    public function create_database_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'con_fra_2025_submissions';
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            submission_data longtext NOT NULL,
            submission_time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            user_ip varchar(45) DEFAULT '' NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // dbDelta ist bereits in der Hauptdatei inkludiert
        dbDelta($sql);

        // Fehlerbehandlung
        if ($wpdb->last_error) {
            return new WP_Error('db_creation_error', $wpdb->last_error);
        }

        return true;
    }
}