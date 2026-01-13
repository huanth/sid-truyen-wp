<?php
/**
 * Plugin Name: Sid Truyen Core
 * Plugin URI: https://sidtruyen.com
 * Description: Core functionality for Sid Truyen theme. Manages Novels, Chapters, and Genres.
 * Version: 1.1.0
 * Author: Nauhyuh
 * Author URI: https://sidtruyen.com
 * Text Domain: sid-truyen-core
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define Plugin Constants
define( 'SID_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'SID_CORE_URL', plugin_dir_url( __FILE__ ) );

// Include Files
require_once SID_CORE_PATH . 'inc/post-types.php';
require_once SID_CORE_PATH . 'inc/taxonomies.php';
require_once SID_CORE_PATH . 'inc/meta-boxes.php';

// Activation Hook (Flash flush rules)
register_activation_hook( __FILE__, 'sid_core_activate' );
function sid_core_activate() {
    sid_core_register_post_types();
    sid_core_register_taxonomies();
    flush_rewrite_rules();
}

// Deactivation Hook
register_deactivation_hook( __FILE__, 'sid_core_deactivate' );
function sid_core_deactivate() {
    flush_rewrite_rules();
}
