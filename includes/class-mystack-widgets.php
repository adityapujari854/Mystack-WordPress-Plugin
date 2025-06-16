<?php
/**
 * mystack Widgets Class
 * 
 * Handles WordPress widget functionality
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class mystack_Widgets {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('widgets_init', array($this, 'register_widgets'));
    }
    
    /**
     * Register widgets
     */
    public function register_widgets() {
        register_widget('mystack_Modbot_Widget');
        register_widget('mystack_Ticket_Form_Widget');
    }
}

/**
 * mystack Modbot Widget
 */
class mystack_Modbot_Widget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'mystack_modbot_widget',
            __('mystack Modbot', 'mystack-ai-support'),
            array(
                'description' => __('Display a mystack modbot in your sidebar or widget area', 'mystack-ai-support')
            )
        );
    }
    
    /**
     * Widget output
     */
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        $modbot_id = $instance['modbot_id'];
        $theme = $instance['theme'] ?? 'light';
        $height = $instance['height'] ?? '400px';
        
        if (empty($modbot_id)) {
            return;
        }
        
        echo $args['before_widget'];
        
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        // Use shortcode to render modbot
        echo do_shortcode('[mystack-modbot id="' . esc_attr($modbot_id) . '" theme="' . esc_attr($theme) . '" height="' . esc_attr($height) . '"]');
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget form
     */
    public function form($instance) {
        $title = $instance['title'] ?? __('Chat with us', 'mystack-ai-support');
        $modbot_id = $instance['modbot_id'] ?? '';
        $theme = $instance['theme'] ?? 'light';
        $height = $instance['height'] ?? '400px';
        
        // Get available modbots
        $api = new mystack_API();
        $modbots = $api->get_modbots();
        
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'mystack-ai-support'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('modbot_id'); ?>"><?php _e('Modbot:', 'mystack-ai-support'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('modbot_id'); ?>" name="<?php echo $this->get_field_name('modbot_id'); ?>">
                <option value=""><?php _e('Select a modbot', 'mystack-ai-support'); ?></option>
                <?php if (!is_wp_error($modbots) && is_array($modbots)): ?>
                    <?php foreach ($modbots as $modbot): ?>
                        <option value="<?php echo esc_attr($modbot['id']); ?>" <?php selected($modbot_id, $modbot['id']); ?>>
                            <?php echo esc_html($modbot['name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled><?php _e('Unable to load modbots', 'mystack-ai-support'); ?></option>
                <?php endif; ?>
            </select>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('theme'); ?>"><?php _e('Theme:', 'mystack-ai-support'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('theme'); ?>" name="<?php echo $this->get_field_name('theme'); ?>">
                <option value="light" <?php selected($theme, 'light'); ?>><?php _e('Light', 'mystack-ai-support'); ?></option>
                <option value="dark" <?php selected($theme, 'dark'); ?>><?php _e('Dark', 'mystack-ai-support'); ?></option>
            </select>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height:', 'mystack-ai-support'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo esc_attr($height); ?>" placeholder="400px" />
            <small><?php _e('e.g., 400px, 50vh', 'mystack-ai-support'); ?></small>
        </p>
        <?php
    }
    
    /**
     * Update widget
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['modbot_id'] = (!empty($new_instance['modbot_id'])) ? sanitize_text_field($new_instance['modbot_id']) : '';
        $instance['theme'] = (!empty($new_instance['theme'])) ? sanitize_text_field($new_instance['theme']) : 'light';
        $instance['height'] = (!empty($new_instance['height'])) ? sanitize_text_field($new_instance['height']) : '400px';
        
        return $instance;
    }
}

/**
 * mystack Ticket Form Widget
 */
class mystack_Ticket_Form_Widget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'mystack_ticket_form_widget',
            __('mystack Ticket Form', 'mystack-ai-support'),
            array(
                'description' => __('Display a mystack ticket form in your sidebar or widget area', 'mystack-ai-support')
            )
        );
    }
    
    /**
     * Widget output
     */
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        $form_id = $instance['form_id'];
        $theme = $instance['theme'] ?? 'light';
        
        if (empty($form_id)) {
            return;
        }
        
        echo $args['before_widget'];
        
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        // Use shortcode to render ticket form
        echo do_shortcode('[mystack-ticket-form id="' . esc_attr($form_id) . '" theme="' . esc_attr($theme) . '"]');
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget form
     */
    public function form($instance) {
        $title = $instance['title'] ?? __('Submit a Ticket', 'mystack-ai-support');
        $form_id = $instance['form_id'] ?? '';
        $theme = $instance['theme'] ?? 'light';
        
        // Get available ticket forms
        $api = new mystack_API();
        $forms = $api->get_ticket_forms();
        
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'mystack-ai-support'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('form_id'); ?>"><?php _e('Ticket Form:', 'mystack-ai-support'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('form_id'); ?>" name="<?php echo $this->get_field_name('form_id'); ?>">
                <option value=""><?php _e('Select a form', 'mystack-ai-support'); ?></option>
                <?php if (!is_wp_error($forms) && is_array($forms)): ?>
                    <?php foreach ($forms as $form): ?>
                        <option value="<?php echo esc_attr($form['id']); ?>" <?php selected($form_id, $form['id']); ?>>
                            <?php echo esc_html($form['name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled><?php _e('Unable to load forms', 'mystack-ai-support'); ?></option>
                <?php endif; ?>
            </select>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('theme'); ?>"><?php _e('Theme:', 'mystack-ai-support'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('theme'); ?>" name="<?php echo $this->get_field_name('theme'); ?>">
                <option value="light" <?php selected($theme, 'light'); ?>><?php _e('Light', 'mystack-ai-support'); ?></option>
                <option value="dark" <?php selected($theme, 'dark'); ?>><?php _e('Dark', 'mystack-ai-support'); ?></option>
            </select>
        </p>
        <?php
    }
    
    /**
     * Update widget
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['form_id'] = (!empty($new_instance['form_id'])) ? sanitize_text_field($new_instance['form_id']) : '';
        $instance['theme'] = (!empty($new_instance['theme'])) ? sanitize_text_field($new_instance['theme']) : 'light';
        
        return $instance;
    }
}