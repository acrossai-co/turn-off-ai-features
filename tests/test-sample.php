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
	 * Reset option after each test.
	 */
	public function tear_down() {
		delete_option( 'toaif_disable_ai' );
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
}
