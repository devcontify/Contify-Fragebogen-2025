<?php

if (!defined('ABSPATH')) {
    exit;
}

class CON_FRA_2025_Admin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'init_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    public function add_admin_menu() {
        add_options_page(
            __('Contify Fragebogen 2025', 'contify-pdf-Fragebogen-2025'),
            __('Contify Fragebogen', 'contify-pdf-Fragebogen-2025'),
            'manage_options',
            'con-fra-2025-settings',
            array($this, 'render_admin_page')
        );
        
        add_management_page(
            __('Fragebogen Einreichungen', 'contify-pdf-Fragebogen-2025'),
            __('Fragebogen Einreichungen', 'contify-pdf-Fragebogen-2025'),
            'manage_options',
            'con-fra-2025-submissions',
            array($this, 'render_submissions_page')
        );
    }
    
    public function init_settings() {
        register_setting('con_fra_2025_settings', 'con_fra_2025_settings', array($this, 'sanitize_settings'));
        
        add_settings_section(
            'con_fra_2025_email_section',
            __('E-Mail Einstellungen', 'contify-pdf-Fragebogen-2025'),
            array($this, 'render_email_section_desc'),
            'con_fra_2025_settings'
        );
        
        add_settings_field(
            'notification_email',
            __('Benachrichtigungs-E-Mail', 'contify-pdf-Fragebogen-2025'),
            array($this, 'render_email_field'),
            'con_fra_2025_settings',
            'con_fra_2025_email_section',
            array('field' => 'notification_email', 'placeholder' => get_option('admin_email'))
        );
        
        add_settings_field(
            'cc_email',
            __('CC E-Mail', 'contify-pdf-Fragebogen-2025'),
            array($this, 'render_email_field'),
            'con_fra_2025_settings',
            'con_fra_2025_email_section',
            array('field' => 'cc_email', 'placeholder' => __('Optional', 'contify-pdf-Fragebogen-2025'))
        );
        
        add_settings_field(
            'bcc_email',
            __('BCC E-Mail', 'contify-pdf-Fragebogen-2025'),
            array($this, 'render_email_field'),
            'con_fra_2025_settings',
            'con_fra_2025_email_section',
            array('field' => 'bcc_email', 'placeholder' => __('Optional', 'contify-pdf-Fragebogen-2025'))
        );
        
        add_settings_section(
            'con_fra_2025_form_section',
            __('Formular Einstellungen', 'contify-pdf-Fragebogen-2025'),
            array($this, 'render_form_section_desc'),
            'con_fra_2025_settings'
        );
        
        add_settings_field(
            'enable_sections',
            __('Aktive Sektionen', 'contify-pdf-Fragebogen-2025'),
            array($this, 'render_sections_field'),
            'con_fra_2025_settings',
            'con_fra_2025_form_section'
        );
    }
    
    public function sanitize_settings($input) {
        $sanitized = array();
        
        if (isset($input['notification_email'])) {
            $sanitized['notification_email'] = sanitize_email($input['notification_email']);
        }
        
        if (isset($input['cc_email'])) {
            $sanitized['cc_email'] = sanitize_email($input['cc_email']);
        }
        
        if (isset($input['bcc_email'])) {
            $sanitized['bcc_email'] = sanitize_email($input['bcc_email']);
        }
        
        if (isset($input['enable_sections'])) {
            $sanitized['enable_sections'] = array_map('intval', $input['enable_sections']);
        }
        
        return $sanitized;
    }
    
    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Contify Fragebogen 2025 Einstellungen', 'contify-pdf-Fragebogen-2025'); ?></h1>
            
            <div class="con-fra-admin-header">
                <p><?php _e('Konfigurieren Sie hier die Einstellungen für Ihren Contify Fragebogen.', 'contify-pdf-Fragebogen-2025'); ?></p>
            </div>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('con_fra_2025_settings');
                do_settings_sections('con_fra_2025_settings');
                submit_button();
                ?>
            </form>
            
            <div class="con-fra-shortcode-info">
                <h3><?php _e('Shortcode', 'contify-pdf-Fragebogen-2025'); ?></h3>
                <p><?php _e('Verwenden Sie diesen Shortcode, um das Formular in Ihren Beiträgen oder Seiten anzuzeigen:', 'contify-pdf-Fragebogen-2025'); ?></p>
                <code>[contify_fragebogen_2025]</code>
                
                <h4><?php _e('Shortcode Optionen:', 'contify-pdf-Fragebogen-2025'); ?></h4>
                <ul>
                    <li><code>title</code> - <?php _e('Titel des Formulars', 'contify-pdf-Fragebogen-2025'); ?></li>
                    <li><code>description</code> - <?php _e('Beschreibung des Formulars', 'contify-pdf-Fragebogen-2025'); ?></li>
                </ul>
                
                <p><strong><?php _e('Beispiel:', 'contify-pdf-Fragebogen-2025'); ?></strong></p>
                <code>[contify_fragebogen_2025 title="Unser Fragebogen" description="Bitte füllen Sie alle Felder aus."]</code>
            </div>
        </div>
        <?php
    }
    
    public function render_submissions_page() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'con_fra_2025_submissions';
        $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $per_page = 20;
        $offset = ($current_page - 1) * $per_page;
        
        if (isset($_GET['action']) && $_GET['action'] === 'export_csv' && wp_verify_nonce($_GET['_wpnonce'], 'con_fra_2025_export')) {
            $this->export_to_csv();
            return;
        }
        
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        $submissions = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name ORDER BY submission_time DESC LIMIT %d OFFSET %d",
                $per_page,
                $offset
            )
        );
        
        $total_pages = ceil($total_items / $per_page);
        
        ?>
        <div class="wrap">
            <h1><?php _e('Fragebogen Einreichungen', 'contify-pdf-Fragebogen-2025'); ?></h1>
            
            <div class="tablenav top">
                <div class="alignleft actions">
                    <a href="<?php echo wp_nonce_url(add_query_arg('action', 'export_csv'), 'con_fra_2025_export'); ?>" 
                       class="button button-secondary">
                        <?php _e('Als CSV exportieren', 'contify-pdf-Fragebogen-2025'); ?>
                    </a>
                </div>
                
                <?php if ($total_pages > 1): ?>
                <div class="tablenav-pages">
                    <span class="displaying-num"><?php printf(__('%d Einträge', 'contify-pdf-Fragebogen-2025'), $total_items); ?></span>
                    <?php
                    echo paginate_links(array(
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '',
                        'prev_text' => '&laquo;',
                        'next_text' => '&raquo;',
                        'total' => $total_pages,
                        'current' => $current_page
                    ));
                    ?>
                </div>
                <?php endif; ?>
            </div>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('ID', 'contify-pdf-Fragebogen-2025'); ?></th>
                        <th><?php _e('Datum', 'contify-pdf-Fragebogen-2025'); ?></th>
                        <th><?php _e('Textanzahl', 'contify-pdf-Fragebogen-2025'); ?></th>
                        <th><?php _e('Wörter', 'contify-pdf-Fragebogen-2025'); ?></th>
                        <th><?php _e('IP-Adresse', 'contify-pdf-Fragebogen-2025'); ?></th>
                        <th><?php _e('Aktionen', 'contify-pdf-Fragebogen-2025'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($submissions)): ?>
                    <tr>
                        <td colspan="6"><?php _e('Keine Einreichungen gefunden.', 'contify-pdf-Fragebogen-2025'); ?></td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($submissions as $submission): ?>
                        <?php $data = json_decode($submission->submission_data, true); ?>
                        <tr>
                            <td><?php echo esc_html($submission->id); ?></td>
                            <td><?php echo esc_html(mysql2date(__('d.m.Y H:i'), $submission->submission_time)); ?></td>
                            <td><?php echo esc_html(substr($data['text_count'] ?? '', 0, 50)); ?></td>
                            <td><?php echo esc_html($data['word_count'] ?? ''); ?></td>
                            <td><?php echo esc_html($submission->user_ip); ?></td>
                            <td>
                                <a href="<?php echo add_query_arg(array('action' => 'view', 'id' => $submission->id)); ?>" 
                                   class="button button-small">
                                    <?php _e('Anzeigen', 'contify-pdf-Fragebogen-2025'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (isset($_GET['action']) && $_GET['action'] === 'view' && isset($_GET['id'])): ?>
            <?php $this->render_submission_detail(intval($_GET['id'])); ?>
        <?php endif; ?>
        <?php
    }
    
    private function render_submission_detail($submission_id) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'con_fra_2025_submissions';
        $submission = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $submission_id));
        
        if (!$submission) {
            echo '<div class="error"><p>' . __('Einreichung nicht gefunden.', 'contify-pdf-Fragebogen-2025') . '</p></div>';
            return;
        }
        
        $data = json_decode($submission->submission_data, true);
        ?>
        <div id="submission-detail" style="margin-top: 30px;">
            <h2><?php printf(__('Einreichung #%d', 'contify-pdf-Fragebogen-2025'), $submission->id); ?></h2>
            <p><strong><?php _e('Eingereicht am:', 'contify-pdf-Fragebogen-2025'); ?></strong> <?php echo mysql2date(__('d.m.Y H:i'), $submission->submission_time); ?></p>
            
            <div class="con-fra-submission-data">
                <?php $this->render_submission_section(__('1. Allgemeine Informationen', 'contify-pdf-Fragebogen-2025'), array(
                    'Anzahl Texte' => $data['text_count'] ?? '',
                    'Wörter' => $data['word_count'] ?? '',
                    'Zielgruppe' => $data['target_group'] ?? '',
                    'Userintention' => $data['user_intention'] ?? '',
                    'Ziele' => $data['text_goals'] ?? '',
                    'Werte/Normen' => $data['values_norms'] ?? '',
                    'USPs' => $data['usps'] ?? '',
                    'Ausrichtung' => $data['text_orientation'] ?? '',
                    'Kooperationen' => $data['cooperations'] ?? ''
                )); ?>
                
                <?php $this->render_submission_section(__('2. Textspezifische Informationen', 'contify-pdf-Fragebogen-2025'), array(
                    'W-Fragen' => ($data['w_questions'] ?? 0) ? 'Ja' : 'Nein',
                    'Google-Suggest' => ($data['google_suggest'] ?? 0) ? 'Ja' : 'Nein',
                    'WDF*IDF-Analyse' => ($data['wdf_idf_analysis'] ?? 0) ? 'Ja' : 'Nein',
                    'Sprachstil' => $data['language_style'] ?? ''
                )); ?>
            </div>
        </div>
        <?php
    }
    
    private function render_submission_section($title, $fields) {
        ?>
        <div class="submission-section">
            <h3><?php echo esc_html($title); ?></h3>
            <table class="form-table">
                <?php foreach ($fields as $label => $value): ?>
                    <?php if (!empty($value)): ?>
                    <tr>
                        <th scope="row"><?php echo esc_html($label); ?></th>
                        <td><?php echo nl2br(esc_html($value)); ?></td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </table>
        </div>
        <?php
    }
    
    public function render_email_section_desc() {
        echo '<p>' . __('Konfigurieren Sie die E-Mail-Adressen für Formular-Benachrichtigungen.', 'contify-pdf-Fragebogen-2025') . '</p>';
    }
    
    public function render_form_section_desc() {
        echo '<p>' . __('Aktivieren oder deaktivieren Sie einzelne Formular-Sektionen.', 'contify-pdf-Fragebogen-2025') . '</p>';
    }
    
    public function render_email_field($args) {
        $options = get_option('con_fra_2025_settings', array());
        $value = isset($options[$args['field']]) ? $options[$args['field']] : '';
        
        printf(
            '<input type="email" name="con_fra_2025_settings[%s]" value="%s" placeholder="%s" class="regular-text" />',
            esc_attr($args['field']),
            esc_attr($value),
            esc_attr($args['placeholder'])
        );
    }
    
    public function render_sections_field() {
        $options = get_option('con_fra_2025_settings', array());
        $enabled_sections = isset($options['enable_sections']) ? $options['enable_sections'] : array(1, 2, 3, 4, 5);
        
        $sections = array(
            1 => __('Allgemeine Informationen', 'contify-pdf-Fragebogen-2025'),
            2 => __('Textspezifische Informationen', 'contify-pdf-Fragebogen-2025'),
            3 => __('Zusätzliche Informationen', 'contify-pdf-Fragebogen-2025'),
            4 => __('CMS Informationen', 'contify-pdf-Fragebogen-2025'),
            5 => __('Anmerkungen', 'contify-pdf-Fragebogen-2025')
        );
        
        foreach ($sections as $id => $title) {
            $checked = in_array($id, $enabled_sections) ? 'checked="checked"' : '';
            printf(
                '<label><input type="checkbox" name="con_fra_2025_settings[enable_sections][]" value="%d" %s /> %s</label><br />',
                $id,
                $checked,
                esc_html($title)
            );
        }
    }
    
    public function enqueue_admin_assets($hook) {
        if (!in_array($hook, array('settings_page_con-fra-2025-settings', 'tools_page_con-fra-2025-submissions'))) {
            return;
        }
        
        wp_enqueue_style(
            'con-fra-2025-admin',
            CON_FRA_2025_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            CON_FRA_2025_VERSION
        );
    }
    
    private function export_to_csv() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'con_fra_2025_submissions';
        $submissions = $wpdb->get_results("SELECT * FROM $table_name ORDER BY submission_time DESC");
        
        $filename = 'contify-fragebogen-' . date('Y-m-d-H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        
        $output = fopen('php://output', 'w');
        
        $headers = array('ID', 'Datum', 'Textanzahl', 'Wörter', 'Zielgruppe', 'IP-Adresse');
        fputcsv($output, $headers);
        
        foreach ($submissions as $submission) {
            $data = json_decode($submission->submission_data, true);
            
            $row = array(
                $submission->id,
                $submission->submission_time,
                $data['text_count'] ?? '',
                $data['word_count'] ?? '',
                $data['target_group'] ?? '',
                $submission->user_ip
            );
            
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit;
    }
}