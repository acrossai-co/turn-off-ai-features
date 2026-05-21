<?php
/**
 * Plugin Name: Turn Off AI Features
 * Description: Adds an option to the General Settings page to turn off AI features in WordPress.
 * Version:     0.0.8
 * Requires at least: 7.0
 * Requires PHP:      7.4
 * Author:      raftaar1191
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: turn-off-ai-features
 * Domain Path: /languages
 *
 * @package TurnOffAIFeatures
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hooks into wp_supports_ai to turn off AI when the option is enabled.
 */
add_filter(
	'wp_supports_ai',
	static function ( $supported ) {
		if ( get_option( 'toaif_disable_ai', '0' ) === '1' ) {
			return false;
		}
		return $supported;
	},
	1000
);

/**
 * Registers the setting and adds it to the General Settings page.
 */
add_action(
	'admin_init',
	static function () {
		register_setting(
			'general',
			'toaif_disable_ai',
			array(
				'type'              => 'string',
				'sanitize_callback' => static function ( $value ) {
					return '1' === $value ? '1' : '0';
				},
				'default'           => '0',
			)
		);

		add_settings_field(
			'toaif_disable_ai',
			__( 'AI Features', 'turn-off-ai-features' ),
			'toaif_disable_field_cb',
			'general'
		);
	}
);

/**
 * Renders the AI Features checkbox field.
 */
function toaif_disable_field_cb() {
	$value = get_option( 'toaif_disable_ai', '0' );
	?>
	<label for="toaif_disable_ai">
		<input
			type="checkbox"
			name="toaif_disable_ai"
			id="toaif_disable_ai"
			value="1"
			<?php checked( '1', $value ); ?>
		/>
		<?php esc_html_e( 'Turn off AI features on this site', 'turn-off-ai-features' ); ?>
	</label>
	<?php
}

/**
 * Adds a "Settings" link on the Plugins page pointing to Settings > General.
 */
add_filter(
	'plugin_action_links_' . plugin_basename( __FILE__ ),
	static function ( $links ) {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'options-general.php#toaif_disable_ai' ) ),
			esc_html__( 'Settings', 'turn-off-ai-features' )
		);
		array_unshift( $links, $settings_link );
		return $links;
	}
);

/**
 * Registers the WP-CLI commands for managing AI features.
 *
 * Commands:
 *   wp toaif disable   — Turns off AI features site-wide.
 *   wp toaif enable    — Turns on AI features site-wide.
 *   wp toaif status    — Shows the current AI on/off state.
 */
if ( defined( 'WP_CLI' ) && WP_CLI ) {

	/**
	 * Manages AI features via WP-CLI.
	 */
	class TOAIF_Disable_CLI extends WP_CLI_Command {

		/**
		 * Turns off AI features site-wide.
		 *
		 * ## EXAMPLES
		 *
		 *   wp toaif disable
		 *
		 * @subcommand disable
		 */
		public function disable() {
			update_option( 'toaif_disable_ai', '1' );
			WP_CLI::success( 'AI features have been turned off.' );
		}

		/**
		 * Turns on AI features site-wide.
		 *
		 * ## EXAMPLES
		 *
		 *   wp toaif enable
		 *
		 * @subcommand enable
		 */
		public function enable() {
			update_option( 'toaif_disable_ai', '0' );
			WP_CLI::success( 'AI features have been turned on.' );
		}

		/**
		 * Shows the current AI on/off status.
		 *
		 * ## EXAMPLES
		 *
		 *   wp toaif status
		 *
		 * @subcommand status
		 */
		public function status() {
			$off = get_option( 'toaif_disable_ai', '0' ) === '1';
			if ( $off ) {
				WP_CLI::log( 'AI features are currently: off' );
			} else {
				WP_CLI::log( 'AI features are currently: on' );
			}
		}
	}

	WP_CLI::add_command( 'toaif', 'TOAIF_Disable_CLI' );
}
