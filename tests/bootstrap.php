<?php
/**
 * PHPUnit bootstrap file for turn-off-ai-features.
 *
 * @package TurnOffAIFeatures
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_tests_dir/includes/functions.php\n"; // phpcs:ignore WordPress.Security.EscapeOutput
	echo "Run: bash bin/install-wp-tests.sh wordpress_test root root 127.0.0.1 latest\n"; // phpcs:ignore WordPress.Security.EscapeOutput
	exit( 1 );
}

require_once $_tests_dir . '/includes/functions.php';

tests_add_filter(
	'muplugins_loaded',
	static function () {
		require dirname( __DIR__ ) . '/turn-off-ai-features.php';
	}
);

require $_tests_dir . '/includes/bootstrap.php';
