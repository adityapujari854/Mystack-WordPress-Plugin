<?php
/**
 * Admin Settings Template
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="mystack-admin-container">
        <div class="mystack-admin-main">
            <div class="postbox">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('API Configuration', 'mystack-ai-support'); ?></h2>
                </div>
                <div class="inside">
                    <form method="post" action="">
                        <?php wp_nonce_field('mystack_save_settings', 'mystack_nonce'); ?>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="mystack_api_key"><?php _e('API Key', 'mystack-ai-support'); ?></label>
                                </th>
                                <td>
                                    <input type="password" id="mystack_api_key" name="mystack_api_key" 
                                           value="<?php echo esc_attr($api_key); ?>" class="regular-text" />
                                    <button type="button" id="toggle-api-key" class="button button-secondary">
                                        <?php _e('Show', 'mystack-ai-support'); ?>
                                    </button>
                                    <p class="description">
                                        <?php _e('Enter your mystack API key. You can generate one in your mystack dashboard.', 'mystack-ai-support'); ?>
                                        <a href="https://app.mystack.ai/settings/api" target="_blank"><?php _e('Get API Key', 'mystack-ai-support'); ?></a>
                                    </p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row">
                                    <label for="mystack_api_url"><?php _e('API URL', 'mystack-ai-support'); ?></label>
                                </th>
                                <td>
                                    <input type="url" id="mystack_api_url" name="mystack_api_url" 
                                           value="<?php echo esc_attr($api_url); ?>" class="regular-text" />
                                    <p class="description">
                                        <?php _e('mystack API base URL. Leave default unless instructed otherwise.', 'mystack-ai-support'); ?>
                                    </p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"></th>
                                <td>
                                    <button type="button" id="test-connection" class="button button-secondary">
                                        <?php _e('Test Connection', 'mystack-ai-support'); ?>
                                    </button>
                                    <span id="connection-status"></span>
                                </td>
                            </tr>
                        </table>
                        
                        <hr>
                        
                        <h3><?php _e('Widget Settings', 'mystack-ai-support'); ?></h3>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <?php _e('Enable Global Widget', 'mystack-ai-support'); ?>
                                </th>
                                <td>
                                    <label>
                                        <input type="checkbox" id="mystack_widget_enabled" name="mystack_widget_enabled" 
                                               value="1" <?php checked($widget_enabled); ?> />
                                        <?php _e('Show floating chat widget on all pages', 'mystack-ai-support'); ?>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row">
                                    <label for="mystack_selected_chatbot"><?php _e('Default Modbot', 'mystack-ai-support'); ?></label>
                                </th>
                                <td>
                                    <select id="mystack_selected_chatbot" name="mystack_selected_chatbot" class="regular-text">
                                        <option value=""><?php _e('Select a modbot', 'mystack-ai-support'); ?></option>
                                    </select>
                                    <button type="button" id="refresh-chatbots" class="button button-secondary">
                                        <?php _e('Refresh', 'mystack-ai-support'); ?>
                                    </button>
                                    <p class="description">
                                        <?php _e('Choose which modbot to use for the global widget and default shortcodes.', 'mystack-ai-support'); ?>
                                    </p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row">
                                    <label for="mystack_widget_position"><?php _e('Widget Position', 'mystack-ai-support'); ?></label>
                                </th>
                                <td>
                                    <select id="mystack_widget_position" name="mystack_widget_position">
                                        <option value="bottom-right" <?php selected($widget_position, 'bottom-right'); ?>>
                                            <?php _e('Bottom Right', 'mystack-ai-support'); ?>
                                        </option>
                                        <option value="bottom-left" <?php selected($widget_position, 'bottom-left'); ?>>
                                            <?php _e('Bottom Left', 'mystack-ai-support'); ?>
                                        </option>
                                        <option value="top-right" <?php selected($widget_position, 'top-right'); ?>>
                                            <?php _e('Top Right', 'mystack-ai-support'); ?>
                                        </option>
                                        <option value="top-left" <?php selected($widget_position, 'top-left'); ?>>
                                            <?php _e('Top Left', 'mystack-ai-support'); ?>
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row">
                                    <label for="mystack_widget_theme"><?php _e('Widget Theme', 'mystack-ai-support'); ?></label>
                                </th>
                                <td>
                                    <select id="mystack_widget_theme" name="mystack_widget_theme">
                                        <option value="light" <?php selected($widget_theme, 'light'); ?>>
                                            <?php _e('Light', 'mystack-ai-support'); ?>
                                        </option>
                                        <option value="dark" <?php selected($widget_theme, 'dark'); ?>>
                                            <?php _e('Dark', 'mystack-ai-support'); ?>
                                        </option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        
                        <?php submit_button(); ?>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="mystack-admin-sidebar">
            <div class="postbox">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Quick Start', 'mystack-ai-support'); ?></h2>
                </div>
                <div class="inside">
                    <ol>
                        <li><?php _e('Get your API key from', 'mystack-ai-support'); ?> <a href="https://app.mystack.ai/settings/api" target="_blank">mystack Dashboard</a></li>
                        <li><?php _e('Enter the API key above and test the connection', 'mystack-ai-support'); ?></li>
                        <li><?php _e('Select a default modbot for the global widget', 'mystack-ai-support'); ?></li>
                        <li><?php _e('Enable the global widget or use shortcodes in your content', 'mystack-ai-support'); ?></li>
                    </ol>
                </div>
            </div>
            
            <div class="postbox">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Shortcodes', 'mystack-ai-support'); ?></h2>
                </div>
                <div class="inside">
                    <h4><?php _e('Modbot', 'mystack-ai-support'); ?></h4>
                    <code>[mystack-chatbot id="123"]</code>
                    <p class="description"><?php _e('Embed a specific modbot', 'mystack-ai-support'); ?></p>
                    
                    <h4><?php _e('Ticket Form', 'mystack-ai-support'); ?></h4>
                    <code>[mystack-ticket-form id="456"]</code>
                    <p class="description"><?php _e('Embed a ticket submission form', 'mystack-ai-support'); ?></p>
                    
                    <h4><?php _e('Floating Widget', 'mystack-ai-support'); ?></h4>
                    <code>[mystack-widget]</code>
                    <p class="description"><?php _e('Add a floating chat widget to specific pages', 'mystack-ai-support'); ?></p>
                </div>
            </div>
            
            <div class="postbox">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Support', 'mystack-ai-support'); ?></h2>
                </div>
                <div class="inside">
                    <p><?php _e('Need help? Contact our support team:', 'mystack-ai-support'); ?></p>
                    <ul>
                        <li><a href="https://docs.mystack.ai" target="_blank"><?php _e('Documentation', 'mystack-ai-support'); ?></a></li>
                        <li><a href="https://support.mystack.ai" target="_blank"><?php _e('Support Center', 'mystack-ai-support'); ?></a></li>
                        <li><a href="mailto:support@mystack.ai"><?php _e('Email Support', 'mystack-ai-support'); ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.mystack-admin-container {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

.mystack-admin-main {
    flex: 2;
}

.mystack-admin-sidebar {
    flex: 1;
}

.mystack-admin-sidebar .postbox {
    margin-bottom: 20px;
}

#connection-status {
    margin-left: 10px;
    font-weight: bold;
}

#connection-status.success {
    color: #46b450;
}

#connection-status.error {
    color: #dc3232;
}

.mystack-loading {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #007cba;
    border-radius: 50%;
    animation: mystack-spin 1s linear infinite;
    margin-left: 10px;
}

@keyframes mystack-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Toggle API key visibility
    $('#toggle-api-key').click(function() {
        const input = $('#mystack_api_key');
        const button = $(this);
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            button.text('<?php echo esc_js(__('Hide', 'mystack-ai-support')); ?>');
        } else {
            input.attr('type', 'password');
            button.text('<?php echo esc_js(__('Show', 'mystack-ai-support')); ?>');
        }
    });
    
    // Test API connection
    $('#test-connection').click(function() {
        const button = $(this);
        const status = $('#connection-status');
        const apiKey = $('#mystack_api_key').val();
        const apiUrl = $('#mystack_api_url').val();
        
        if (!apiKey || !apiUrl) {
            status.removeClass('success').addClass('error')
                  .text('<?php echo esc_js(__('Please enter API key and URL', 'mystack-ai-support')); ?>');
            return;
        }
        
        button.prop('disabled', true);
        status.removeClass('success error').html('<span class="mystack-loading"></span>');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'mystack_test_connection',
                api_key: apiKey,
                api_url: apiUrl,
                nonce: '<?php echo wp_create_nonce('mystack_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    status.removeClass('error').addClass('success')
                          .text('<?php echo esc_js(__('Connection successful!', 'mystack-ai-support')); ?>');
                    loadChatbots();
                } else {
                    let errorMessage = '<?php echo esc_js(__('Connection failed', 'mystack-ai-support')); ?>';
                    
                    if (response.data) {
                        if (typeof response.data === 'string') {
                            errorMessage = response.data;
                        } else if (response.data.message) {
                            errorMessage = response.data.message;
                            if (response.data.debug) {
                                console.log('mystack API Debug Info:', response.data.debug);
                            }
                        }
                    }
                    
                    status.removeClass('success').addClass('error').text(errorMessage);
                }
            },
            error: function() {
                status.removeClass('success').addClass('error')
                      .text('<?php echo esc_js(__('Connection failed', 'mystack-ai-support')); ?>');
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    });
    
    // Load chatbots
    function loadChatbots() {
        const select = $('#mystack_selected_chatbot');
        const currentValue = '<?php echo esc_js($selected_chatbot); ?>';
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'mystack_get_chatbots',
                nonce: '<?php echo wp_create_nonce('mystack_nonce'); ?>'
            },
            success: function(response) {
                if (response.success && response.data) {
                    select.empty().append('<option value=""><?php echo esc_js(__('Select a chatbot', 'mystack-ai-support')); ?></option>');
                    
                    $.each(response.data, function(index, chatbot) {
                        const option = $('<option></option>')
                            .attr('value', chatbot.id)
                            .text(chatbot.name);
                        
                        if (chatbot.id === currentValue) {
                            option.prop('selected', true);
                        }
                        
                        select.append(option);
                    });
                }
            }
        });
    }
    
    // Refresh chatbots
    $('#refresh-chatbots').click(function() {
        loadChatbots();
    });
    
    // Load chatbots on page load if API key exists
    if ($('#mystack_api_key').val()) {
        loadChatbots();
    }
});
</script>