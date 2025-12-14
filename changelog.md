# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-12-14

### Added
- Initial release of WooCommerce Dynamic Cart Counter
- Real-time cart count updates via AJAX
- Support for Classic WooCommerce Cart
- Support for Block-based WooCommerce Cart (React)
- Shortcode `[wc_cart_button]` with customizable attributes
- Admin settings page with comprehensive documentation
- Multiple styling options and CSS classes
- MutationObserver for detecting React cart changes
- Event delegation for efficient event handling
- RTL (Right-to-Left) language support
- Accessibility features and ARIA labels
- Mobile responsive design
- Dark mode support
- High contrast mode support
- Translation-ready with text domain
- Security features (nonce verification, capability checks)
- Performance optimization (debouncing, smart updates)

### Features
- **icon** attribute - Choose any Dashicon for cart button
- **text** attribute - Add custom text next to cart icon
- **show_count** attribute - Toggle count badge visibility
- **class** attribute - Add custom CSS classes
- Pre-built style classes: square-badge, large-badge, inline-badge, button-style
- PHP template function support
- Widget area compatibility
- Menu integration support

### Technical
- Automatic cart type detection (Classic vs Block)
- WooCommerce cart fragment system integration
- jQuery-based AJAX implementation
- CSS-only animations for smooth transitions
- No external dependencies
- Lightweight footprint (< 5KB total)
- Compatible with major caching plugins
- Works with popular page builders

### Documentation
- Comprehensive README.md
- WordPress.org compatible readme.txt
- Inline code documentation
- Admin panel usage guide
- Multiple shortcode examples
- Styling customization guide

### Security
- Sanitization of all user inputs
- Escaping of all outputs
- Nonce verification for AJAX requests
- Capability checks for admin functions
- No SQL queries (uses WordPress/WooCommerce APIs)

---

## [Unreleased]

### Planned Features
- Visual shortcode builder in admin
- Additional icon libraries (Font Awesome, etc.)
- Cart dropdown preview on hover
- Customizer integration for live preview
- Multiple cart button styles
- Animation options for count updates
- Sound effects on cart update (optional)
- Mini cart widget
- Recently viewed products integration

### Planned Improvements
- Performance monitoring dashboard
- A/B testing for cart button styles
- Analytics integration
- Compatibility checker
- Automated testing suite

---

## Version History

### Version Numbering
- **Major version** (X.0.0): Breaking changes, major new features
- **Minor version** (1.X.0): New features, backward compatible
- **Patch version** (1.0.X): Bug fixes, minor improvements

---

## Support

For issues, feature requests, or contributions:
- GitHub: https://github.com/wpshamimsweb/woocommerce-dynamic-cart-counter
- Issues: https://github.com/wpshamimsweb/woocommerce-dynamic-cart-counter/issues

---

**Note**: This is the first stable release. Future updates will maintain backward compatibility whenever possible.