<?php

if (!defined('ABSPATH')) {
    exit;
}

class CON_FRA_2025_Shortcode {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_shortcode('contify_fragebogen_2025', array($this, 'render_form_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }
    
    public function enqueue_assets() {
        if ($this->is_shortcode_present()) {
            wp_enqueue_style(
                'con-fra-2025-styles',
                CON_FRA_2025_PLUGIN_URL . 'assets/css/frontend.css',
                array(),
                CON_FRA_2025_VERSION
            );
            
            wp_enqueue_script(
                'con-fra-2025-scripts',
                CON_FRA_2025_PLUGIN_URL . 'assets/js/frontend.js',
                array('jquery'),
                CON_FRA_2025_VERSION,
                true
            );
            
            wp_localize_script('con-fra-2025-scripts', 'conFra2025Ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('con_fra_2025_form'),
                'messages' => array(
                    'required' => __('Dieses Feld ist erforderlich.', 'contify-pdf-Fragebogen-2025'),
                    'success' => __('Vielen Dank! Ihre Anfrage wurde erfolgreich übermittelt.', 'contify-pdf-Fragebogen-2025'),
                    'error' => __('Es ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.', 'contify-pdf-Fragebogen-2025'),
                    'submitting' => __('Wird übermittelt...', 'contify-pdf-Fragebogen-2025'),
                    'step' => __('Schritt', 'contify-pdf-Fragebogen-2025'),
                    'of' => __('von', 'contify-pdf-Fragebogen-2025')
                )
            ));
        }
    }
    
    private function is_shortcode_present() {
        global $post;
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'contify_fragebogen_2025')) {
            return true;
        }
        return false;
    }
    
    public function render_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => __('Contify Fragebogen 2025', 'contify-pdf-Fragebogen-2025'),
            'description' => __('Bitte füllen Sie alle relevanten Felder aus, damit wir Ihnen ein präzises Angebot erstellen können.', 'contify-pdf-Fragebogen-2025')
        ), $atts, 'contify_fragebogen_2025');
        
        ob_start();
        
        if (isset($_GET['con_fra_success']) && $_GET['con_fra_success'] == '1') {
            echo '<div class="con-fra-success-message">' . 
                 __('Vielen Dank! Ihre Anfrage wurde erfolgreich übermittelt.', 'contify-pdf-Fragebogen-2025') . 
                 '</div>';
            return ob_get_clean();
        }
        
        include CON_FRA_2025_PLUGIN_PATH . 'templates/form-template.php';
        
        return ob_get_clean();
    }
}