# Mystack AI Support - WordPress Plugin

A powerful WordPress plugin that integrates mystack's AI-powered customer support platform directly into your WordPress website.

## Features

- **AI Chat Widget**: Floating chat widget that can be displayed on any page
- **Modbot Shortcodes**: Embed specific modbots using `[mystack-chatbot id="123"]`
- **Ticket Form Shortcodes**: Embed ticket submission forms using `[mystack-ticket-form id="456"]`
- **Admin Dashboard**: Easy-to-use interface for managing API keys and configurations
- **Multiple Themes**: Light and dark theme support
- **Responsive Design**: Works perfectly on desktop and mobile devices
- **WordPress Widgets**: Sidebar widgets for modbots and ticket forms
- **REST API Integration**: Secure communication with mystack backend
- **Webhook Support**: Real-time updates and notifications

## Installation

### Method 1: Upload Plugin Files

1. Download the plugin files
2. Upload the `mystack-ai-support` folder to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to **mystack Settings** in your WordPress admin to configure the plugin

### Method 2: WordPress Admin Upload

1. Go to **Plugins > Add New** in your WordPress admin
2. Click **Upload Plugin**
3. Choose the plugin ZIP file and click **Install Now**
4. Activate the plugin
5. Go to **mystack Settings** to configure

## Configuration

### 1. Get Your API Key

1. Log in to your [mystack Dashboard](https://app.mystack.ai)
2. Navigate to **Settings > API Keys**
3. Generate a new API key for WordPress integration
4. Copy the API key

### 2. Configure the Plugin

1. In WordPress admin, go to **mystack Settings**
2. Enter your API key in the **API Key** field
3. Verify the **API URL** (default: `https://api.mystack.ai`)
4. Click **Test Connection** to verify the setup
5. Configure widget settings as needed
6. Save your settings

### 3. Enable Global Widget (Optional)

1. Check **Enable Global Widget** to show a floating chat widget on all pages
2. Select your default modbot from the dropdown
3. Choose widget position (bottom-right, bottom-left, etc.)
4. Select theme (light or dark)
5. Save settings

## Usage

### Shortcodes

#### Modbot Shortcode
```
[mystack-chatbot id="your-chatbot-id"]
```

**Attributes:**
- `id` (required): Your modbot ID from mystack
- `theme`: `light` or `dark` (default: `light`)
- `height`: Height in pixels (default: `500px`)

**Example:**
```
[mystack-chatbot id="abc123" theme="dark" height="600px"]
```

#### Ticket Form Shortcode
```
[mystack-ticket-form id="your-form-id"]
```

**Attributes:**
- `id` (required): Your ticket form ID from mystack
- `theme`: `light` or `dark` (default: `light`)

**Example:**
```
[mystack-ticket-form id="def456" theme="light"]
```

#### Floating Widget Shortcode
```
[mystack-widget]
```

**Attributes:**
- `modbot-id`: Specific modbot ID (uses default if not specified)
- `theme`: `light` or `dark`
- `position`: `bottom-right`, `bottom-left`, `top-right`, `top-left`
- `title`: Widget title text

**Example:**
```
[mystack-widget chatbot-id="abc123" theme="dark" position="bottom-left" title="Need Help?"]
```

### WordPress Widgets

The plugin provides two WordPress widgets:

1. **mystack Modbot Widget**: Add to sidebars to display a modbot
2. **mystack Ticket Form Widget**: Add to sidebars to display a ticket form

To use widgets:
1. Go to **Appearance > Widgets**
2. Find "mystack Modbot" or "mystack Ticket Form"
3. Drag to your desired widget area
4. Configure the widget settings
5. Save

### Managing Chatbots and Forms

#### View Available Modbots
1. Go to **mystack > Modbots** in WordPress admin
2. View all your available modbots
3. Copy shortcodes directly from the interface
4. Preview modbots in a new window

#### View Available Ticket Forms
1. Go to **mystack > Ticket Forms** in WordPress admin
2. View all your available forms
3. Copy shortcodes directly from the interface
4. Preview forms in a new window

## API Integration

The plugin creates REST API endpoints for communication with mystack:

- `POST /wp-json/mystack/v1/chat` - Handle chat messages
- `POST /wp-json/mystack/v1/tickets` - Handle ticket submissions
- `GET /wp-json/mystack/v1/config` - Get widget configuration
- `POST /wp-json/mystack/v1/webhook` - Handle mystack webhooks

## Customization

### Custom CSS

You can customize the appearance by adding CSS to your theme:

```css
/* Customize widget button */
.mystack-widget-button {
    background-color: #your-color !important;
}

/* Customize widget container */
.mystack-widget-container {
    border-radius: 15px !important;
}

/* Customize shortcode containers */
.mystack-modbot-shortcode {
    border: 2px solid #your-color;
}
```

### Hooks and Filters

The plugin provides several WordPress hooks for customization:

#### Actions
- `mystack_before_widget_render` - Before widget renders
- `mystack_after_widget_render` - After widget renders
- `mystack_before_shortcode_render` - Before shortcode renders
- `mystack_after_shortcode_render` - After shortcode renders

#### Filters
- `mystack_widget_config` - Modify widget configuration
- `mystack_api_request_args` - Modify API request arguments
- `mystack_shortcode_attributes` - Modify shortcode attributes

**Example:**
```php
// Customize widget configuration
add_filter('mystack_widget_config', function($config) {
    $config['theme'] = 'dark';
    return $config;
});
```

## Troubleshooting

### Common Issues

#### Connection Failed
- Verify your API key is correct
- Check that your website can make outbound HTTPS requests
- Ensure the API URL is correct
- Check for firewall or security plugin interference

#### Widget Not Appearing
- Verify the global widget is enabled in settings
- Check that a default modbot is selected
- Ensure there are no JavaScript errors in browser console
- Check for theme conflicts

#### Shortcodes Not Working
- Verify the modbot/form ID is correct
- Check that the API connection is working
- Ensure shortcodes are properly formatted
- Check for plugin conflicts

### Debug Mode

To enable debug mode, add this to your `wp-config.php`:

```php
define('mystack_DEBUG', true);
```

This will:
- Enable detailed logging
- Show additional error messages
- Add debug information to browser console

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- cURL extension enabled
- HTTPS enabled (recommended)
- mystack account and API key

## Changelog

### Version 1.0.0
- Initial release
- Chat widget functionality
- Shortcode support
- Admin dashboard
- WordPress widgets
- REST API integration
- Webhook support

## Security

- All API communications use HTTPS
- API keys are stored securely in WordPress database
- Webhook signatures are verified
- Input sanitization and validation
- Nonce verification for admin actions
- Capability checks for admin access

## Privacy

This plugin:
- Connects to mystack servers to provide chat functionality
- May store user chat data according to your mystack settings
- Does not collect additional user data beyond what's necessary for functionality
- Respects WordPress privacy settings