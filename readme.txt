=== WooCommerce Dynamic Cart Counter ===
Contributors: Al-Amin Shamim
Tags: woocommerce, cart, ajax, counter, real-time
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Real-time cart counter for WooCommerce that updates instantly without page refresh. Works with both Classic and Block-based carts.

== Description ==

**WooCommerce Dynamic Cart Counter** adds a real-time updating cart counter to your WooCommerce store. The cart count updates instantly when products are added, removed, or quantities change - all without requiring a page refresh.

= Key Features =

* ✅ **Real-time updates** - Cart count updates instantly via AJAX
* ✅ **Zero page refresh** - Seamless user experience
* ✅ **Dual compatibility** - Works with both Classic and Block-based WooCommerce carts
* ✅ **Easy shortcode** - Use `[wc_cart_button]` anywhere on your site
* ✅ **Highly customizable** - Multiple attributes and CSS classes
* ✅ **Lightweight** - No external dependencies, < 5KB
* ✅ **Mobile responsive** - Perfect on all devices
* ✅ **Developer friendly** - Clean, well-documented code

= How It Works =

The plugin uses AJAX to communicate with WooCommerce's cart system and updates the cart count in real-time. It automatically detects whether you're using the Classic WooCommerce cart or the new Block-based cart and applies the appropriate update mechanisms.

= Shortcode Usage =

Basic usage:
`[wc_cart_button]`

With text:
`[wc_cart_button text="Cart"]`

Custom icon:
`[wc_cart_button icon="dashicons-products" text="My Cart"]`

Without count badge:
`[wc_cart_button text="View Cart" show_count="no"]`

= Shortcode Attributes =

* **icon** - Dashicons class name (default: dashicons-cart)
* **text** - Text to display next to the icon (default: empty)
* **show_count** - Show/hide count badge: yes or no (default: yes)
* **class** - Additional CSS classes for styling

= Template Usage =

You can also use it directly in your theme templates:
`<?php echo do_shortcode('[wc_cart_button]'); ?>`

= Styling =

The plugin includes default styles that work with most themes. You can customize the appearance using CSS:

`.wc-dynamic-cart-button` - Main container
`.wc-dynamic-cart-count` - Count badge
`.wc-dynamic-cart-text` - Cart text

Pre-built style classes:
* `square-badge` - Square corners on badge
* `large-badge` - Larger badge size
* `inline-badge` - Badge inline instead of floating
* `button-style` - Button-style cart link

Example:
`[wc_cart_button class="button-style large-badge"]`

= Technical Details =

* Detects cart type automatically (Classic vs Block)
* Uses MutationObserver for React-based block carts
* Event delegation for efficient event handling
* Optimized AJAX calls with debouncing
* Compatible with most caching plugins

= Requirements =

* WordPress 5.0 or higher
* WooCommerce 4.0 or higher
* PHP 7.2 or higher

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/woocommerce-dynamic-cart-counter/`
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the shortcode `[wc_cart_button]` anywhere on your site
4. Visit WooCommerce > Cart Counter for documentation and examples

= Manual Installation =

1. Download the plugin ZIP file
2. Go to WordPress Admin > Plugins > Add New
3. Click "Upload Plugin" and choose the ZIP file
4. Click "Install Now" and then "Activate"

== Frequently Asked Questions ==

= Does this work with the new WooCommerce blocks? =

Yes! The plugin automatically detects whether you're using the Classic WooCommerce cart or the new Block-based cart and works seamlessly with both.

= Will this slow down my site? =

No. The plugin is extremely lightweight (< 5KB) and uses optimized AJAX calls. It only updates the cart count when necessary.

= Can I use this in my theme header? =

Absolutely! You can use the shortcode in any widget area, menu, or directly in your theme template files using `<?php echo do_shortcode('[wc_cart_button]'); ?>`

= Can I customize the styling? =

Yes. The plugin includes default styles that work with most themes, but you can easily override them with custom CSS or use the pre-built style classes.

= Does it work with caching plugins? =

Yes. The plugin uses AJAX to fetch the current cart count, so it works even when pages are cached.

= Can I change the cart icon? =

Yes! Use any Dashicon by setting the `icon` attribute:
`[wc_cart_button icon="dashicons-products"]`

= Does it support RTL languages? =

Yes. The plugin includes RTL support out of the box.

= Is it compatible with WPML/multilingual sites? =

Yes. The plugin is translation-ready and works with multilingual setups.

== Screenshots ==

1. Cart button with count badge in header
2. Real-time update when adding to cart
3. Shortcode examples in admin settings
4. Different styling options
5. Mobile responsive design
6. Works with Classic and Block carts

== Changelog ==

= 1.0.0 - 2025-01-XX =
* Initial release
* Real-time cart count updates
* Support for Classic WooCommerce Cart
* Support for Block-based WooCommerce Cart
* Shortcode with customizable attributes
* Admin settings page with documentation
* Multiple styling options
* RTL and accessibility support
* Mobile responsive design

== Upgrade Notice ==

= 1.0.0 =
Initial release of WooCommerce Dynamic Cart Counter.

== Support ==

For support, feature requests, or bug reports:
* GitHub: https://github.com/wpshamimsweb/woocommerce-dynamic-cart-counter

== Credits ==

Developed by [Al-Amin Shamim]

== Privacy Policy ==

This plugin does not collect, store, or share any personal data. All cart information is handled by WooCommerce through standard WordPress/WooCommerce sessions.