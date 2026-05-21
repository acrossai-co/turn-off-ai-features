<?php
/**
 * Tests for the turn-off-ai-features plugin.
 *
 * @package TurnOffAIFeatures
 */

/**
 * Tests core plugin behavior.
 */
class Test_Toaif_Plugin extends WP_UnitTestCase {

	/**
	 * Reset options after each test.
	 */
	public function tear_down() {
		delete_option( 'toaif_disable_ai' );
		delete_option( 'toaif_hide_connectors' );
		parent::tear_down();
	}

	/**
	 * Confirm the main plugin function was loaded.
	 */
	public function test_plugin_loaded_function_exists() {
		$this->assertTrue( function_exists( 'toaif_disable_field_cb' ) );
	}

	/**
	 * When the option is off (unset or '0'), the filter must not interfere.
	 */
	public function test_wp_supports_ai_filter_passthrough_when_option_off() {
		delete_option( 'toaif_disable_ai' );
		$this->assertTrue( apply_filters( 'wp_supports_ai', true ) );

		update_option( 'toaif_disable_ai', '0' );
		$this->assertTrue( apply_filters( 'wp_supports_ai', true ) );
	}

	/**
	 * When the option is '1', the filter must return false.
	 */
	public function test_wp_supports_ai_returns_false_when_option_enabled() {
		update_option( 'toaif_disable_ai', '1' );
		$this->assertFalse( apply_filters( 'wp_supports_ai', true ) );
	}

	/**
	 * WP_AI_SUPPORT constant must not be defined when the option is off at load time.
	 * The constant is set once at plugin load — this test bootstraps with option='0'.
	 */
	public function test_wp_ai_support_constant_not_defined_when_option_off_at_load() {
		$this->assertFalse( defined( 'WP_AI_SUPPORT' ) );
	}

	/**
	 * The wp_supports_ai filter (filter-level fallback) must return false with option on.
	 * Tests the fallback path — covers environments where the constant was not set at load.
	 */
	public function test_wp_supports_ai_filter_returns_false_when_option_on() {
		update_option( 'toaif_disable_ai', '1' );
		$this->assertFalse( (bool) apply_filters( 'wp_supports_ai', true ) );
	}

	/**
	 * The wp_supports_ai filter must pass through when the option is off.
	 */
	public function test_wp_supports_ai_filter_passes_through_when_option_off() {
		update_option( 'toaif_disable_ai', '0' );
		$this->assertTrue( (bool) apply_filters( 'wp_supports_ai', true ) );
	}

	/**
	 * Connectors submenu is removed when both options are on.
	 */
	public function test_connectors_menu_hidden_when_both_options_on() {
		global $submenu;
		$submenu['options-general.php']   = array();
		$submenu['options-general.php'][] = array( 'Connectors', 'manage_options', 'options-connectors.php' );

		update_option( 'toaif_disable_ai', '1' );
		update_option( 'toaif_hide_connectors', '1' );
		do_action( 'admin_menu' );

		$slugs = wp_list_pluck( $submenu['options-general.php'], 2 );
		$this->assertNotContains( 'options-connectors.php', $slugs );
	}

	/**
	 * Connectors submenu stays when only the sub-toggle is on (main is off).
	 */
	public function test_connectors_menu_visible_when_main_toggle_off() {
		global $submenu;
		$submenu['options-general.php']   = array();
		$submenu['options-general.php'][] = array( 'Connectors', 'manage_options', 'options-connectors.php' );

		update_option( 'toaif_disable_ai', '0' );
		update_option( 'toaif_hide_connectors', '1' );
		do_action( 'admin_menu' );

		$slugs = wp_list_pluck( $submenu['options-general.php'], 2 );
		$this->assertContains( 'options-connectors.php', $slugs );
	}

	/**
	 * Connectors submenu stays when only the main toggle is on (sub is off).
	 */
	public function test_connectors_menu_visible_when_sub_toggle_off() {
		global $submenu;
		$submenu['options-general.php']   = array();
		$submenu['options-general.php'][] = array( 'Connectors', 'manage_options', 'options-connectors.php' );

		update_option( 'toaif_disable_ai', '1' );
		update_option( 'toaif_hide_connectors', '0' );
		do_action( 'admin_menu' );

		$slugs = wp_list_pluck( $submenu['options-general.php'], 2 );
		$this->assertContains( 'options-connectors.php', $slugs );
	}
}
