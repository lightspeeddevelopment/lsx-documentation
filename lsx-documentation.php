<?php
/*
 * Plugin Name: LSX Documentation
 * Plugin URI:  https://www.lsdev.biz/product/lsx-documentation/
 * Description: The LSX Documentation extension adds the "Documentation" post type.
 * Version:     1.0.0
 * Author:      LightSpeed
 * Author URI:  https://www.lsdev.biz/
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lsx-documentation
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LSX_DOCUMENTATION_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_DOCUMENTATION_CORE', __FILE__ );
define( 'LSX_DOCUMENTATION_URL', plugin_dir_url( __FILE__ ) );
define( 'LSX_DOCUMENTATION_VER', '1.0.0' );

/* ======================= Below is the Plugin Class init ========================= */

// Post Type and Custom Fields
require_once( LSX_DOCUMENTATION_PATH . '/classes/class-lsx-documentation-admin.php' );

// Frontend scripts and styles
require_once( LSX_DOCUMENTATION_PATH . '/classes/class-lsx-documentation-frontend.php' );

// Shortcode and Template Tag
require_once( LSX_DOCUMENTATION_PATH . '/classes/class-lsx-documentation.php' );

// Widget
require_once( LSX_DOCUMENTATION_PATH . '/classes/class-lsx-documentation-widget.php' );

// Template Tag and functions
require_once( LSX_DOCUMENTATION_PATH . '/includes/functions.php' );

// Post reorder
require_once( LSX_DOCUMENTATION_PATH . '/includes/class-lsx-documentation-scpo-engine.php' );
