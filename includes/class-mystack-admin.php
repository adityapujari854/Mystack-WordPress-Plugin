<?php
/**
 * mystack Admin Class
 * 
 * Handles all admin-related functionality
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class mystack_Admin {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_ajax_mystack_test_connection', array($this, 'test_api_connection'));
        add_action('wp_ajax_mystack_get_modbots', array($this, 'get_modbots'));
        add_action('wp_ajax_mystack_get_ticket_forms', array($this, 'get_ticket_forms'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('mystack AI Support', 'mystack-ai-support'),
            __('mystack', 'mystack-ai-support'),
            'manage_options',
            'mystack-settings',
            array($this, 'admin_page'),
            'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>'),
            30
        );
        
        add_submenu_page(
            'mystack-settings',
            __('Settings', 'mystack-ai-support'),
            __('Settings', 'mystack-ai-support'),
            'manage_options',
            'mystack-settings',
            array($this, 'admin_page')
        );
        
        add_submenu_page(
            'mystack-settings',
            __('Modbots', 'mystack-ai-support'),
            __('Modbots', 'mystack-ai-support'),
            'manage_options',
            'mystack-modbots',
            array($this, 'modbots_page')
        );
        
        add_submenu_page(
            'mystack-settings',
            __('Ticket Forms', 'mystack-ai-support'),
            __('Ticket Forms', 'mystack-ai-support'),
            'manage_options',
            'mystack-ticket-forms',
            array($this, 'ticket_forms_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('mystack_settings', 'mystack_api_key');
        register_setting('mystack_settings', 'mystack_api_url');
        register_setting('mystack_settings', 'mystack_widget_enabled');
        register_setting('mystack_settings', 'mystack_widget_position');
        register_setting('mystack_settings', 'mystack_selected_modbot');
        register_setting('mystack_settings', 'mystack_widget_theme');
    }
    
    /**
     * Main admin page
     */
    public function admin_page() {
        if (isset($_POST['submit'])) {
            $this->save_settings();
        }
        
        $api_key = get_option('mystack_api_key', '');
        $api_url = get_option('mystack_api_url', 'https://azrjkdwesrnarpuipbml.supabase.co/functions/v1/wordpress-api');
        $widget_enabled = get_option('mystack_widget_enabled', false);
        $widget_position = get_option('mystack_widget_position', 'bottom-right');
        $selected_modbot = get_option('mystack_selected_modbot', '');
        $widget_theme = get_option('mystack_widget_theme', 'light');
        
        include mystack_PLUGIN_PATH . 'templates/admin-settings.php';
    }
    
    /**
     * Modbots page
     */
    public function modbots_page() {
        include mystack_PLUGIN_PATH . 'templates/admin-modbots.php';
    }
    
    /**
     * Ticket forms page
     */
    public function ticket_forms_page() {
        include mystack_PLUGIN_PATH . 'templates/admin-ticket-forms.php';
    }
    
    /**
     * Save settings
     */
    private function save_settings() {
        if (!wp_verify_nonce($_POST['mystack_nonce'], 'mystack_save_settings')) {
            wp_die(__('Security check failed', 'mystack-ai-support'));
        }
        
        update_option('mystack_api_key', sanitize_text_field($_POST['mystack_api_key']));
        update_option('mystack_api_url', esc_url_raw($_POST['mystack_api_url']));
        update_option('mystack_widget_enabled', isset($_POST['mystack_widget_enabled']));
        update_option('mystack_widget_position', sanitize_text_field($_POST['mystack_widget_position']));
        update_option('mystack_selected_modbot', sanitize_text_field($_POST['mystack_selected_modbot']));
        update_option('mystack_widget_theme', sanitize_text_field($_POST['mystack_widget_theme']));
        
        add_action('admin_notices', array($this, 'settings_saved_notice'));
    }
    
    /**
     * Settings saved notice
     */
    public function settings_saved_notice() {
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Settings saved successfully!', 'mystack-ai-support') . '</p></div>';
    }
    
    /**
     * Test API connection via AJAX
     */
    public function test_api_connection() {
        check_ajax_referer('mystack_nonce', 'nonce');
        
        $api_key = trim(sanitize_text_field($_POST['api_key']));
        $api_url = esc_url_raw($_POST['api_url']);
        
        if (empty($api_key) || empty($api_url)) {
            wp_send_json_error(__('API key and URL are required', 'mystack-ai-support'));
        }
        
        // Validate API key format - accept only mystack custom format
        if (!preg_match('/^mystack_wp_[a-f0-9]{64}$/', $api_key)) {
            wp_send_json_error(__('Invalid mystack API key format. Expected format: mystack_wp_[64-character hash]', 'mystack-ai-support'));
        }
    
        $response = wp_remote_get($api_url . '/test-connection', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json'
            ),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            wp_send_json_error(__('Connection failed: ', 'mystack-ai-support') . $response->get_error_message());
        }
        
        $status_code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        
        if ($status_code === 200) {
            $data = json_decode($body, true);
            wp_send_json_success(array(
                'message' => __('Connection successful!', 'mystack-ai-support'),
                'data' => $data
            ));
        } else {
            $error_data = json_decode($body, true);
            $error_message = $error_data['message'] ?? 'Authentication failed. Please check your API key.';
            
            // Add debug information for development
            $debug_info = array(
                'status_code' => $status_code,
                'response_body' => $body,
                'api_url' => $api_url . '/test-connection',
                'api_key_length' => strlen($api_key)
            );
            
            wp_send_json_error(array(
                'message' => __($error_message, 'mystack-ai-support'),
                'debug' => $debug_info
            ));
        }
    }
    
    /**
     * Get modbots via AJAX
     */
    public function get_modbots() {
        check_ajax_referer('mystack_nonce', 'nonce');
        
        $api_key = get_option('mystack_api_key', '');
        $api_url = get_option('mystack_api_url', 'https://azrjkdwesrnarpuipbml.supabase.co/functions/v1/wordpress-api');
        
        if (empty($api_key)) {
            wp_send_json_error(__('API key not configured', 'mystack-ai-support'));
        }
        
        $response = wp_remote_get($api_url . '/modbots', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json'
            ),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            wp_send_json_error(__('Failed to fetch modbots: ', 'mystack-ai-support') . $response->get_error_message());
        }
        
        $status_code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        
        if ($status_code === 200) {
            $data = json_decode($body, true);
            wp_send_json_success($data);
        } else {
            wp_send_json_error(__('Failed to fetch modbots', 'mystack-ai-support'));
        }
    }
    
    /**
     * Get ticket forms via AJAX
     */
    public function get_ticket_forms() {
        check_ajax_referer('mystack_nonce', 'nonce');
        
        $api_key = get_option('mystack_api_key', '');
        $api_url = get_option('mystack_api_url', 'https://azrjkdwesrnarpuipbml.supabase.co/functions/v1/wordpress-api');
        
        if (empty($api_key)) {
            wp_send_json_error(__('API key not configured', 'mystack-ai-support'));
        }
        
        $response = wp_remote_get($api_url . '/ticket-forms', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json'
            ),
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            wp_send_json_error(__('Failed to fetch ticket forms: ', 'mystack-ai-support') . $response->get_error_message());
        }
        
        $status_code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        
        if ($status_code === 200) {
            $data = json_decode($body, true);
            wp_send_json_success($data);
        } else {
            wp_send_json_error(__('Failed to fetch ticket forms', 'mystack-ai-support'));
        }
    }
}
