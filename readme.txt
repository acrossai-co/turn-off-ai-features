=== Turn Off AI Features ===
Contributors:      raftaar1191
Tags:              ai, turn-off, wp-supports-ai, disable-ai
Requires at least: 7.0
Tested up to:      7.0
Requires PHP:      7.4
Stable tag:        0.0.9
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Adds an option to the General Settings page to turn off AI features in WordPress.

== Description ==

Turn Off AI Features lets you control AI functionality in WordPress without touching code. It hooks into the wp_supports_ai filter at priority 1000 and returns false when the option is enabled.

Features:

* Toggle AI on or off from Settings > General — no deactivation needed.
* WP-CLI support: wp ai disable / wp ai enable / wp ai status.
* Settings link on the Plugins page for quick access.
* Runs at priority 1000, overriding other plugins that may enable AI.

== Installation ==

1. Upload the turn-off-ai-features folder to /wp-content/plugins/.
2. Activate the plugin through the Plugins menu in WordPress.
3. Go to Settings > General and check "Turn off AI features on this site".

== Frequently Asked Questions ==

= How do I turn off AI features? =

After activating the plugin, go to Settings > General and check the
"Turn off AI features on this site" checkbox, then click Save Changes.
You can also use the Settings link on the Plugins page to get there directly.

= How do I turn on AI features again? =

Uncheck the "Turn off AI features on this site" checkbox on the Settings > General
page and save. You do not need to deactivate or delete the plugin.

= Can I toggle AI from the command line? =

Yes. The plugin registers three WP-CLI commands:

  wp toaif disable   — Turns off AI features site-wide.
  wp toaif enable    — Turns on AI features site-wide.
  wp toaif status    — Shows the current AI on/off state.

= Does this affect the WP_AI_SUPPORT constant? =

No. This plugin hooks into the wp_supports_ai filter at priority 1000, which runs
after the constant is evaluated, and forces the return value to false regardless
of the constant.

= Why use this plugin if AI is already off by default without a connector? =

You're right that AI is "off" by default without a connector configured. However,
WordPress 7.0 introduced wp_supports_ai() — a global kill switch that goes beyond
just not configuring connectors.

Here's why it matters:

wp_supports_ai() provides:
* WP_AI_SUPPORT constant in wp-config.php for server-level control
* A filter hook for programmatic control at any point in the stack
* A guarantee that works independently of connector configuration

Real-world use case: An agency managing 50 client sites needs one control point to
ensure AI is architecturally disabled. If a plugin updates and tries to call
wp_ai_client_prompt(), the function returns false immediately — no matter what.

The difference is between hoping AI stays off vs. guaranteeing it stays off.

= Can I disable AI without using this plugin? =

Yes. WordPress 7.0 provides two built-in mechanisms you can use directly:

1. WP_AI_SUPPORT constant in wp-config.php:

   define( 'WP_AI_SUPPORT', false );

   Add this line to your wp-config.php file to disable AI at the server level,
   before any plugin or theme code runs.

2. The wp_supports_ai filter hook:

   add_filter( 'wp_supports_ai', '__return_false' );

   Add this to your theme's functions.php or a custom plugin for programmatic
   control.

This plugin simply provides a UI toggle in Settings > General and WP-CLI commands
so you don't need to touch code or wp-config.php directly.

= What exactly is wp_supports_ai()? =

wp_supports_ai() is a core WordPress function introduced in WordPress 7.0. It acts
as the central gatekeeper for all AI functionality. Any plugin or theme that wants
to use AI — such as wp_ai_client_prompt() — must first check this function.

If it returns false, AI calls are skipped entirely. This plugin hooks into the
wp_supports_ai filter at priority 1000 to force it to return false when disabled.

= Will this plugin break anything on my site? =

No. Disabling AI features only prevents AI-powered functionality from running. All
other plugin and theme features — content editing, publishing, widgets, menus, etc.
— are completely unaffected. Only features that explicitly rely on wp_supports_ai()
or wp_ai_client_prompt() will be turned off.

= What if another plugin also hooks into wp_supports_ai? =

This plugin runs at priority 1000, which is very high. Most plugins hook in at the
default priority of 10. This means if another plugin tries to force AI on, this
plugin will override it. If you need an even higher priority, use the
WP_AI_SUPPORT constant in wp-config.php — it runs before any plugin code.

= Does this work on WordPress Multisite? =

The plugin controls AI on a per-site basis. Each site in a network manages its own
setting via Settings > General. If you need to disable AI across all sites in a
network at once, use the WP_AI_SUPPORT constant in wp-config.php, which applies
network-wide.

= What happens if I deactivate the plugin? =

AI features will return to their previous state — enabled if a connector is
configured, disabled if not. The plugin's database option is preserved on
deactivation (only removed on uninstall), so re-activating will restore your
previous setting.

= Does this plugin have any performance impact? =

Negligible. It adds a single lightweight filter callback. When AI is disabled, it
actually improves performance by short-circuiting AI calls before they execute any
network requests or processing.

= Is this plugin compatible with WordPress.com or managed hosting? =

Yes, the settings UI and WP-CLI commands work on any standard WordPress install.
However, on some managed platforms, wp-config.php may not be directly editable —
in that case, use this plugin's toggle instead of the WP_AI_SUPPORT constant.

= Do I need to keep the plugin active to keep AI turned off? =

Yes, if you're relying on the plugin's UI toggle. The filter only runs while the
plugin is active. If you want a persistent, plugin-independent solution, add
define( 'WP_AI_SUPPORT', false ); to your wp-config.php instead.

= Is this plugin useful for GDPR or data privacy compliance? =

Yes. Disabling AI at the infrastructure level ensures that no user data is sent to
AI connectors or third-party AI providers. For compliance-sensitive environments,
combining this plugin with the WP_AI_SUPPORT constant gives you an auditable,
two-layer guarantee that AI processing is off.

== Changelog ==

= 0.0.9 =
* Fixed: Exclude dev-only files from WordPress.org release (bin/, tests/, composer.json, phpcs.xml.dist, phpunit.xml.dist, DEPLOYMENT_GUIDE.md, README.md) via .distignore

= 0.0.8 =
* Added: PHPUnit test suite with GitHub Actions CI — automated testing across PHP 7.4–8.3
* Added: WordPress Coding Standards (PHPCS) check in CI required before merging PRs
* Added: "Hide Connectors Page" option in Settings > General — removes the Connectors admin page from the menu when AI is turned off
* Changed: Plugin now defines WP_AI_SUPPORT constant at load time in addition to the wp_supports_ai filter, matching the WordPress 7.0 native AI disable mechanism
* Fixed: Updated all documentation to use the correct WP_AI_SUPPORT constant name (previously incorrectly referenced as WP_SUPPORTS_AI)

= 0.0.7 =
* Added WordPress.org plugin banner (772x250) and retina banner (1544x500)
* Added plugin icons (128x128 and 256x256) featuring WordPress W + AI + red OFF toggle

= 0.0.6 =
* Expanded FAQ section with detailed answers about wp_supports_ai(), multisite,
  GDPR/compliance, performance, managed hosting, and native disable alternatives

= 0.0.5 =
* Added WP-CLI command support for AI feature management
* Commands: wp toaif disable, wp toaif enable, wp toaif status

= 0.0.4 =
* Fixed GitHub Actions workflow configuration
* Resolved WordPress.org SVN deployment integration
* Stable tag properly configured

= 0.0.1 =
* Initial release
* Added option to toggle AI features from Settings > General
* Integrated with wp_supports_ai filter at priority 1000
* Added plugin action links for Settings page access
