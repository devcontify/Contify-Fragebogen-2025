<?php
/**
 * Plugin Name: Contify PDF Fragebogen 2025
 * Plugin URI: https://example.com/contify-pdf-Fragebogen-2025
 * Description: Ein modernes WordPress-Plugin für den Contify Fragebogen 2025 mit Elementor-Integration.
 * Version: 1.0.0
 * Author: Contify Team
 * Author URI: https://example.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: contify-pdf-Fragebogen-2025
 * Domain Path: /languages
 */

// Verhindere direkten Zugriff
if (!defined('ABSPATH')) {
    exit;
}

// PHP-Version prüfen
if (!defined('CON_FRA_2025_MIN_PHP_VERSION')) {
    define('CON_FRA_2025_MIN_PHP_VERSION', '7.4');
}

if (version_compare(PHP_VERSION, CON_FRA_2025_MIN_PHP_VERSION, '<')) {
    add_action('admin_notices', 'con_fra_2025_php_version_notice');
    function con_fra_2025_php_version_notice() {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php esc_html_e('Das Contify PDF Fragebogen 2025 Plugin erfordert PHP-Version ' . CON_FRA_2025_MIN_PHP_VERSION . ' oder höher.', 'contify-pdf-Fragebogen-2025'); ?></p>
        </div>
        <?php
    }
    return;
}

class Contify_PDF_Fragebogen_2025 {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }
    
    private function define_constants() {
        define('CON_FRA_2025_VERSION', '1.0.1'); // Version erhöht
        define('CON_FRA_2025_PLUGIN_URL', plugin_dir_url(__FILE__));
        define('CON_FRA_2025_PLUGIN_PATH', plugin_dir_path(__FILE__));
        define('CON_FRA_2025_PLUGIN_BASENAME', plugin_basename(__FILE__));
        define('CON_FRA_2025_DB_VERSION_OPTION', 'con_fra_2025_db_version');
    }
    
    private function includes() {
        // Alle benötigten Dateien einbinden
        require_once CON_FRA_2025_PLUGIN_PATH . 'includes/class-form-handler.php';
        require_once CON_FRA_2025_PLUGIN_PATH . 'includes/class-email-handler.php';
        require_once CON_FRA_2025_PLUGIN_PATH . 'includes/class-shortcode.php';
        require_once CON_FRA_2025_PLUGIN_PATH . 'includes/class-admin.php';
    }
    
    private function init_hooks() {
        // Aktivierungs- und Deaktivierungshooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Initialisiere Komponenten
        add_action('plugins_loaded', array($this, 'init'));
    }
    
    public function init() {
        // Plugin initialisieren
        $this->load_textdomain();
        $this->init_classes();
    }
    
    private function init_classes() {
        // Klassen initialisieren
        CON_FRA_2025_Form_Handler::get_instance();
        CON_FRA_2025_Email_Handler::get_instance();
        CON_FRA_2025_Shortcode::get_instance();
        
        if (is_admin()) {
            CON_FRA_2025_Admin::get_instance();
        }
    }
    
    public function activate() {
        // Sicherstellen, dass die dbDelta-Funktion verfügbar ist
        if (!function_exists('dbDelta')) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        // Aktivierungslogik mit Datenbank-Versionskontrolle
        if (version_compare(get_option(CON_FRA_2025_DB_VERSION_OPTION, '0'), CON_FRA_2025_VERSION, '<')) {
            $this->create_database_tables();
            update_option(CON_FRA_2025_DB_VERSION_OPTION, CON_FRA_2025_VERSION);
        }

        $this->flush_rewrite_rules();
    }
    
    private function create_database_tables() {
        require_once CON_FRA_2025_PLUGIN_PATH . 'includes/class-form-handler.php';
        $form_handler = new CON_FRA_2025_Form_Handler();
        $result = $form_handler->create_database_table();

        // Einfache Fehlerbehandlung
        if (is_wp_error($result)) {
            // Protokolliere den Fehler, ohne die Aktivierung zu blockieren
            error_log('Contify Fragebogen DB Error: ' . $result->get_error_message());
        }
    }
    
    public function deactivate() {
        // Deaktivierungslogik
        $this->flush_rewrite_rules();
    }
    
    private function flush_rewrite_rules() {
        flush_rewrite_rules();
    }
    
    public function load_textdomain() {
        load_plugin_textdomain(
            'contify-pdf-Fragebogen-2025',
            false,
            dirname(CON_FRA_2025_PLUGIN_BASENAME) . '/languages'
        );
    }
}

// Initialisiere das Plugin
function contify_pdf_fragebogen_2025() {
    return Contify_PDF_Fragebogen_2025::get_instance();
}

contify_pdf_fragebogen_2025();