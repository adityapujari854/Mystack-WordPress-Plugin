<?php
/**
 * mystack Frontend Class
 * 
 * Handles frontend functionality and widget embedding
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class mystack_Frontend {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_footer', array($this, 'render_global_widget'));
        add_action('wp_head', array($this, 'add_meta_tags'));
        add_filter('the_content', array($this, 'auto_embed_widget'));
    }
    
    /**
     * Render global widget if enabled
     */
    public function render_global_widget() {
        $widget_enabled = get_option('mystack_widget_enabled', false);
        $modbot_id = get_option('mystack_selected_modbot', '');
        
        if (!$widget_enabled || empty($modbot_id)) {
            return;
        }
        
        $api_key = get_option('mystack_api_key', '');
        if (empty($api_key)) {
            return;
        }
        
        $position = get_option('mystack_widget_position', 'bottom-right');
        $theme = get_option('mystack_widget_theme', 'light');
        $api_url = get_option('mystack_api_url', 'https://api.mystack.ai');
        
        // Generate unique widget ID
        $widget_id = 'mystack-global-widget';
        
        ?>
        <!-- mystack Global Widget -->
        <div id="<?php echo esc_attr($widget_id); ?>" class="mystack-global-widget" 
             data-modbot-id="<?php echo esc_attr($modbot_id); ?>"
             data-position="<?php echo esc_attr($position); ?>"
             data-theme="<?php echo esc_attr($theme); ?>"
             data-api-url="<?php echo esc_attr($api_url); ?>">
        </div>
        
        <style>
        .mystack-global-widget {
            position: fixed;
            z-index: 9999;
        }
        
        .mystack-global-widget[data-position="bottom-right"] {
            bottom: 20px;
            right: 20px;
        }
        
        .mystack-global-widget[data-position="bottom-left"] {
            bottom: 20px;
            left: 20px;
        }
        
        .mystack-global-widget[data-position="top-right"] {
            top: 20px;
            right: 20px;
        }
        
        .mystack-global-widget[data-position="top-left"] {
            top: 20px;
            left: 20px;
        }
        
        .mystack-widget-button {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #007cba;
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            transition: all 0.3s ease;
        }
        
        .mystack-widget-button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }
        
        .mystack-widget-chat {
            position: absolute;
            width: 350px;
            height: 500px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            display: none;
            overflow: hidden;
        }
        
        .mystack-global-widget[data-position="bottom-right"] .mystack-widget-chat {
            bottom: 80px;
            right: 0;
        }
        
        .mystack-global-widget[data-position="bottom-left"] .mystack-widget-chat {
            bottom: 80px;
            left: 0;
        }
        
        .mystack-global-widget[data-position="top-right"] .mystack-widget-chat {
            top: 80px;
            right: 0;
        }
        
        .mystack-global-widget[data-position="top-left"] .mystack-widget-chat {
            top: 80px;
            left: 0;
        }
        
        @media (max-width: 768px) {
            .mystack-widget-chat {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                width: 100% !important;
                height: 100% !important;
                border-radius: 0 !important;
            }
        }
        
        .mystack-widget-header {
            background: #007cba;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .mystack-widget-close {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .mystack-widget-body {
            height: calc(100% - 60px);
            overflow: hidden;
        }
        
        .mystack-widget-iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        </style>
        
        <script>
        (function() {
            const widget = document.getElementById('<?php echo esc_js($widget_id); ?>');
            const modbotId = widget.dataset.modbotId;
            const apiUrl = widget.dataset.apiUrl;
            const theme = widget.dataset.theme;
            
            // Create widget button
            const button = document.createElement('button');
            button.className = 'mystack-widget-button';
            button.innerHTML = '💬';
            button.setAttribute('aria-label', 'Open chat');
            
            // Create chat container
            const chatContainer = document.createElement('div');
            chatContainer.className = 'mystack-widget-chat';
            
            // Create header
            const header = document.createElement('div');
            header.className = 'mystack-widget-header';
            header.innerHTML = `
                <span><?php echo esc_js(__('Chat with us', 'mystack-ai-support')); ?></span>
                <button class="mystack-widget-close" aria-label="Close chat">×</button>
            `;
            
            // Create body
            const body = document.createElement('div');
            body.className = 'mystack-widget-body';
            
            // Create iframe
            const iframe = document.createElement('iframe');
            iframe.className = 'mystack-widget-iframe';
            iframe.src = apiUrl + '/embed/modbot/' + modbotId + '?theme=' + theme + '&widget=true';
            iframe.title = '<?php echo esc_js(__('mystack Modbot', 'mystack-ai-support')); ?>';
            
            // Assemble widget
            body.appendChild(iframe);
            chatContainer.appendChild(header);
            chatContainer.appendChild(body);
            widget.appendChild(button);
            widget.appendChild(chatContainer);
            
            // Event listeners
            button.addEventListener('click', function() {
                chatContainer.style.display = 'block';
                button.style.display = 'none';
            });
            
            header.querySelector('.mystack-widget-close').addEventListener('click', function() {
                chatContainer.style.display = 'none';
                button.style.display = 'flex';
            });
            
            // Handle escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && chatContainer.style.display === 'block') {
                    chatContainer.style.display = 'none';
                    button.style.display = 'flex';
                }
            });
            
            // Handle mobile back button
            if (window.history && window.history.pushState) {
                button.addEventListener('click', function() {
                    window.history.pushState({mystackWidget: true}, '', window.location.href);
                });
                
                window.addEventListener('popstate', function(e) {
                    if (chatContainer.style.display === 'block') {
                        chatContainer.style.display = 'none';
                        button.style.display = 'flex';
                    }
                });
            }
        })();
        </script>
        <?php
    }
    
    /**
     * Add meta tags for mystack integration
     */
    public function add_meta_tags() {
        $api_key = get_option('mystack_api_key', '');
        if (empty($api_key)) {
            return;
        }
        
        echo '<meta name="mystack:site" content="' . esc_attr(get_site_url()) . '">' . "\n";
        echo '<meta name="mystack:version" content="' . esc_attr(mystack_PLUGIN_VERSION) . '">' . "\n";
    }
    
    /**
     * Auto-embed widget based on content
     */
    public function auto_embed_widget($content) {
        // Check if we should auto-embed based on post type or other conditions
        $auto_embed = apply_filters('mystack_auto_embed_widget', false);
        
        if (!$auto_embed) {
            return $content;
        }
        
        $modbot_id = get_option('mystack_selected_modbot', '');
        if (empty($modbot_id)) {
            return $content;
        }
        
        // Add widget at the end of content
        $widget_shortcode = '[mystack-widget]';
        $content .= do_shortcode($widget_shortcode);
        
        return $content;
    }
    
    /**
     * Get widget configuration for JavaScript
     */
    public static function get_widget_config() {
        return array(
            'api_url' => get_option('mystack_api_url', 'https://api.mystack.ai'),
            'modbot_id' => get_option('mystack_selected_modbot', ''),
            'widget_enabled' => get_option('mystack_widget_enabled', false),
            'widget_position' => get_option('mystack_widget_position', 'bottom-right'),
            'widget_theme' => get_option('mystack_widget_theme', 'light'),
            'site_url' => get_site_url(),
            'rest_url' => rest_url('mystack/v1/'),
            'nonce' => wp_create_nonce('wp_rest')
        );
    }
}