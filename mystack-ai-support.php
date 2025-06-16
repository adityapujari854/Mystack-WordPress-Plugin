<?php

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('mystack_PLUGIN_VERSION', '1.0.0');
define('mystack_PLUGIN_URL', plugin_dir_url(__FILE__));
define('mystack_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('mystack_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main mystack AI Support Plugin Class
 */
class mystackAISupport {
    
    /**
     * Plugin instance
     */
    private static $instance = null;
    
    /**
     * Get plugin instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Load text domain
        load_plugin_textdomain('mystack-ai-support', false, dirname(mystack_PLUGIN_BASENAME) . '/languages');
        
        // Initialize components
        $this->load_dependencies();
        $this->init_hooks();
    }
    
    /**
     * Load plugin dependencies
     */
    private function load_dependencies() {
        require_once mystack_PLUGIN_PATH . 'includes/class-mystack-admin.php';
        require_once mystack_PLUGIN_PATH . 'includes/class-mystack-api.php';
        require_once mystack_PLUGIN_PATH . 'includes/class-mystack-shortcodes.php';
        require_once mystack_PLUGIN_PATH . 'includes/class-mystack-widgets.php';
        require_once mystack_PLUGIN_PATH . 'includes/class-mystack-frontend.php';
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Admin hooks
        if (is_admin()) {
            new mystack_Admin();
        }
        
        // Frontend hooks
        new mystack_Frontend();
        new mystack_Shortcodes();
        new mystack_Widgets();
        
        // API hooks
        new mystack_API();
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_frontend_scripts() {
        wp_enqueue_script(
            'mystack-frontend',
            mystack_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            mystack_PLUGIN_VERSION,
            true
        );
        
        wp_enqueue_style(
            'mystack-frontend',
            mystack_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            mystack_PLUGIN_VERSION
        );
        
        // Localize script with API settings
        $api_key = get_option('mystack_api_key', '');
        $api_url = get_option('mystack_api_url', 'https://api.mystack.ai');
        
        wp_localize_script('mystack-frontend', 'mystack_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mystack_nonce'),
            'api_key' => $api_key,
            'api_url' => $api_url
        ));
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        // Only load on mystack admin pages
        if (strpos($hook, 'mystack') === false) {
            return;
        }
        
        wp_enqueue_script(
            'mystack-admin',
            mystack_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            mystack_PLUGIN_VERSION,
            true
        );
        
        wp_enqueue_style(
            'mystack-admin',
            mystack_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            mystack_PLUGIN_VERSION
        );
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Create database tables if needed
        $this->create_tables();
        
        // Set default options
        add_option('mystack_api_key', '');
        add_option('mystack_api_url', 'https://api.mystack.ai');
        add_option('mystack_widget_enabled', false);
        add_option('mystack_widget_position', 'bottom-right');
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Create database tables
     */
    private function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Table for storing mystack configurations
        $table_name = $wpdb->prefix . 'mystack_configs';
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            config_key varchar(100) NOT NULL,
            config_value longtext NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY config_key (config_key)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

// Initialize the plugin
mystackAISupport::get_instance();