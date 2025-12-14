<?php
/**
 * Plugin Name: WooCommerce Dynamic Cart Counter
 * Plugin URI: https://github.com/wpshamimsweb/woocommerce-dynamic-cart-counter
 * Description: Real-time cart counter for WooCommerce that updates instantly without page refresh. Works with both Classic and Block-based carts. Use shortcode [wc_cart_button] to display anywhere.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://amarwp.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wc-dynamic-cart
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * WC requires at least: 4.0
 * WC tested up to: 8.5
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WC_DYNAMIC_CART_VERSION', '1.0.0');
define('WC_DYNAMIC_CART_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WC_DYNAMIC_CART_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Check if WooCommerce is active
 */
function wc_dynamic_cart_check_woocommerce() {
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', 'wc_dynamic_cart_woocommerce_missing_notice');
        return false;
    }
    return true;
}
add_action('plugins_loaded', 'wc_dynamic_cart_check_woocommerce');

/**
 * Admin notice if WooCommerce is not active
 */
function wc_dynamic_cart_woocommerce_missing_notice() {
    ?>
    <div class="notice notice-error">
        <p><strong>WooCommerce Dynamic Cart Counter</strong> requires WooCommerce to be installed and active.</p>
    </div>
    <?php
}

/**
 * Enqueue plugin styles and scripts
 */
function wc_dynamic_cart_enqueue_assets() {
    // Enqueue dashicons for cart icon
    wp_enqueue_style('dashicons');
    
    // Enqueue jQuery
    wp_enqueue_script('jquery');
    
    // Enqueue plugin CSS
    wp_enqueue_style(
        'wc-dynamic-cart-style',
        WC_DYNAMIC_CART_PLUGIN_URL . 'assets/css/style.css',
        array(),
        WC_DYNAMIC_CART_VERSION
    );
}
add_action('wp_enqueue_scripts', 'wc_dynamic_cart_enqueue_assets');

/**
 * AJAX handler to get current cart count
 */
function wc_dynamic_cart_get_count() {
    if (function_exists('WC') && WC()->cart) {
        $count = WC()->cart->get_cart_contents_count();
        wp_send_json_success($count);
    } else {
        wp_send_json_error('WooCommerce not available');
    }
}
add_action('wp_ajax_get_cart_count', 'wc_dynamic_cart_get_count');
add_action('wp_ajax_nopriv_get_cart_count', 'wc_dynamic_cart_get_count');

/**
 * Update cart count fragment for AJAX add to cart
 */
function wc_dynamic_cart_fragments($fragments) {
    ob_start();
    $count = WC()->cart->get_cart_contents_count();
    ?>
    <span class="wc-dynamic-cart-count" <?php echo ($count == 0) ? 'style="display:none;"' : ''; ?>>
        <?php echo esc_html($count); ?>
    </span>
    <?php
    $fragments['.wc-dynamic-cart-count'] = ob_get_clean();
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'wc_dynamic_cart_fragments');

/**
 * Cart button shortcode
 * Usage: [wc_cart_button]
 * 
 * Attributes:
 * - icon: Icon class (default: dashicons-cart)
 * - text: Text to display (default: empty)
 * - show_count: Show count badge (default: yes)
 * - class: Additional CSS classes
 * 
 * Examples:
 * [wc_cart_button]
 * [wc_cart_button text="Cart"]
 * [wc_cart_button icon="dashicons-shopping-bag" text="My Cart"]
 * [wc_cart_button show_count="no" text="View Cart"]
 */
function wc_dynamic_cart_button_shortcode($atts) {
    // Check if WooCommerce is active
    if (!function_exists('WC') || !WC()->cart) {
        return '';
    }
    
    // Parse attributes
    $atts = shortcode_atts(array(
        'icon' => 'dashicons-cart',
        'text' => '',
        'show_count' => 'yes',
        'class' => '',
    ), $atts, 'wc_cart_button');
    
    $cart_count = WC()->cart->get_cart_contents_count();
    $show_count = strtolower($atts['show_count']) === 'yes';
    
    ob_start();
    ?>
    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="wc-dynamic-cart-button <?php echo esc_attr($atts['class']); ?>">
        <?php if (!empty($atts['icon'])) : ?>
            <span class="dashicons <?php echo esc_attr($atts['icon']); ?>"></span>
        <?php endif; ?>
        
        <?php if (!empty($atts['text'])) : ?>
            <span class="wc-dynamic-cart-text"><?php echo esc_html($atts['text']); ?></span>
        <?php endif; ?>
        
        <?php if ($show_count) : ?>
            <span class="wc-dynamic-cart-count" <?php echo ($cart_count == 0) ? 'style="display:none;"' : ''; ?>>
                <?php echo esc_html($cart_count); ?>
            </span>
        <?php endif; ?>
    </a>
    <?php
    return ob_get_clean();
}
add_shortcode('wc_cart_button', 'wc_dynamic_cart_button_shortcode');

/**
 * Add JavaScript for real-time cart updates
 */
function wc_dynamic_cart_footer_script() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        
        var isUpdating = false;
        
        function refreshCartCount() {
            if (isUpdating) {
                return;
            }
            isUpdating = true;
            
            $.ajax({
                url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
                type: 'POST',
                data: { action: 'get_cart_count' },
                success: function(response) {
                    if (response.success) {
                        var count = response.data;
                        $('.wc-dynamic-cart-count').text(count);
                        
                        if (count == 0) {
                            $('.wc-dynamic-cart-count').hide();
                        } else {
                            $('.wc-dynamic-cart-count').show();
                        }
                    }
                    isUpdating = false;
                },
                error: function(xhr, status, error) {
                    console.error('WC Dynamic Cart Error:', error);
                    isUpdating = false;
                }
            });
        }

        <?php if (is_cart()) : ?>
        // Cart page specific code
        
        // Detect cart type
        var isBlockCart = $('.wp-block-woocommerce-cart').length > 0;
        var isClassicCart = $('form.woocommerce-cart-form').length > 0;

        if (isBlockCart) {
            // Block Cart handlers
            $(document).on('click', '.wc-block-components-quantity-selector__button', function() {
                setTimeout(refreshCartCount, 2000);
            });

            $(document).on('click', '.wc-block-cart-item__remove-link, [aria-label*="Remove"]', function() {
                setTimeout(refreshCartCount, 2000);
            });

            // MutationObserver for Block Cart
            var cartContainer = document.querySelector('.wp-block-woocommerce-cart');
            if (cartContainer) {
                var observer = new MutationObserver(function(mutations) {
                    var shouldUpdate = false;
                    
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'childList' && 
                            (mutation.addedNodes.length > 0 || mutation.removedNodes.length > 0)) {
                            shouldUpdate = true;
                        }
                    });
                    
                    if (shouldUpdate) {
                        setTimeout(refreshCartCount, 1500);
                    }
                });
                
                observer.observe(cartContainer, {
                    childList: true,
                    subtree: true
                });
            }

        } else if (isClassicCart) {
            // Classic Cart handlers
            $(document).on('click', 'a.remove, .product-remove a', function() {
                setTimeout(refreshCartCount, 2500);
            });

            $(document).on('click', 'button[name="update_cart"]', function() {
                setTimeout(refreshCartCount, 2500);
            });

            $(document).on('change', 'input.qty', function() {
                var $updateBtn = $('button[name="update_cart"]');
                if ($updateBtn.length) {
                    $updateBtn.prop('disabled', false);
                    setTimeout(function() {
                        $updateBtn.trigger('click');
                    }, 500);
                }
            });

            // WooCommerce events
            $(document.body).on('updated_wc_div updated_cart_totals removed_from_cart', function() {
                setTimeout(refreshCartCount, 1000);
            });
        }
        <?php endif; ?>
    });
    </script>
    <?php
}
add_action('wp_footer', 'wc_dynamic_cart_footer_script', 9999);

/**
 * Add settings link to plugins page
 */
function wc_dynamic_cart_plugin_action_links($links) {
    $settings_link = '<a href="' . admin_url('admin.php?page=wc-dynamic-cart-settings') . '">Settings</a>';
    $docs_link = '<a href="https://github.com/wpshamimsweb/woocommerce-dynamic-cart-counter" target="_blank">Documentation</a>';
    array_unshift($links, $settings_link, $docs_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wc_dynamic_cart_plugin_action_links');

/**
 * Add admin menu page
 */
function wc_dynamic_cart_admin_menu() {
    add_submenu_page(
        'woocommerce',
        'Dynamic Cart Counter',
        'Cart Counter',
        'manage_woocommerce',
        'wc-dynamic-cart-settings',
        'wc_dynamic_cart_settings_page'
    );
}
add_action('admin_menu', 'wc_dynamic_cart_admin_menu');

/**
 * Settings page content
 */
function wc_dynamic_cart_settings_page() {
    ?>
    <div class="wrap">
        <h1>WooCommerce Dynamic Cart Counter</h1>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>How to Use</h2>
            
            <h3>1. Shortcode Usage</h3>
            <p>Use the shortcode <code>[wc_cart_button]</code> anywhere on your site to display the cart button.</p>
            
            <h4>Basic Examples:</h4>
            <table class="widefat" style="margin: 15px 0;">
                <thead>
                    <tr>
                        <th>Shortcode</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>[wc_cart_button]</code></td>
                        <td>Cart icon with count badge</td>
                    </tr>
                    <tr>
                        <td><code>[wc_cart_button text="Cart"]</code></td>
                        <td>Cart icon with "Cart" text and count</td>
                    </tr>
                    <tr>
                        <td><code>[wc_cart_button text="View Cart" show_count="no"]</code></td>
                        <td>Cart icon with text, no count badge</td>
                    </tr>
                    
                    <tr>
                        <td><code>[wc_cart_button class="my-custom-class"]</code></td>
                        <td>Add custom CSS class</td>
                    </tr>
                </tbody>
            </table>
            
            <h3>2. Shortcode Attributes</h3>
            <ul>
                <li><strong>icon</strong> - Dashicons class (default: dashicons-cart)</li>
                <li><strong>text</strong> - Text to display next to icon (default: empty)</li>
                <li><strong>show_count</strong> - Show count badge: yes/no (default: yes)</li>
                <li><strong>class</strong> - Additional CSS classes</li>
            </ul>
            
            <h3>3. Available Dashicons</h3>
            <p>You can use any <a href="https://developer.wordpress.org/resource/dashicons/" target="_blank">Dashicon</a> for the cart icon:</p>
            <ul>
                <li><code>dashicons-cart</code> - Shopping cart</li>
                
            </ul>
            
            <h3>4. PHP Template Usage</h3>
            <p>You can also add the cart button directly in your theme template files:</p>
            <pre style="background: #f5f5f5; padding: 15px; border-radius: 4px; overflow-x: auto;"><code>&lt;?php echo do_shortcode('[wc_cart_button]'); ?&gt;</code></pre>
            
            <h3>5. Custom Styling</h3>
            <p>Add custom CSS to your theme to style the cart button:</p>
            <pre style="background: #f5f5f5; padding: 15px; border-radius: 4px; overflow-x: auto;"><code>.wc-dynamic-cart-button {
    /* Your custom styles */
}

.wc-dynamic-cart-count {
    background: #e74c3c;
    color: white;
    /* Customize the count badge */
}</code></pre>
        </div>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>Features</h2>
            <ul>
                <li>✅ Real-time cart count updates without page refresh</li>
                <li>✅ Works with Classic WooCommerce Cart</li>
                <li>✅ Works with Block-based WooCommerce Cart</li>
                <li>✅ Easy shortcode integration</li>
                <li>✅ Customizable icon and text</li>
                <li>✅ Lightweight and fast</li>
                <li>✅ Mobile responsive</li>
            </ul>
        </div>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>Support & Documentation</h2>
            <p>
                <a href="https://github.com/wpshamimsweb/woocommerce-dynamic-cart-counter/" target="_blank" class="button button-secondary">
                    View Documentation on GitHub
                </a>
                <a href="https://github.com/wpshamimsweb/woocommerce-dynamic-cart-counter/issues" target="_blank" class="button button-secondary">
                    Report an Issue
                </a>
            </p>
        </div>
    </div>
    <?php
}

/**
 * Plugin activation hook
 */
function wc_dynamic_cart_activate() {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(
            'This plugin requires WooCommerce to be installed and active.',
            'Plugin Activation Error',
            array('back_link' => true)
        );
    }
}
register_activation_hook(__FILE__, 'wc_dynamic_cart_activate');
