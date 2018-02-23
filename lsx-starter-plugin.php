<?php
/*
 * Plugin Name:	LSX Starter Plugin
 * Plugin URI:	https://github.com/lightspeeddevelopment/lsx-starter-plugin
 * Description:	LSX Starter Plugin for building LSX theme extensions.
 * Author:		LightSpeed
 * Version: 	1.0.0
 * Author URI: 	https://www.lsdev.biz/
 * License: 	GPL3
 * Text Domain: lsx-starter-plugin
 * Domain Path: /languages/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LSX_STARTER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_STARTER_PLUGIN_CORE', __FILE__ );
define( 'LSX_STARTER_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );
define( 'LSX_STARTER_PLUGIN_VER',  '1.0.0' );

/* ======================= The API Classes ========================= */

if ( ! class_exists( 'LSX_API_Manager' ) ) {
	require_once( 'classes/class-lsx-api-manager.php' );
}

/**
 * Run when the plugin is active, and generate a unique password for the site instance.
 */
function lsx_starter_plugin_activate_plugin() {
	$lsx_to_password = get_option( 'lsx_api_instance', false );

	if ( false === $lsx_to_password ) {
		update_option( 'lsx_api_instance', LSX_API_Manager::generatePassword() );
	}
}
register_activation_hook( __FILE__, 'lsx_starter_plugin_activate_plugin' );

/**
 * Grabs the email and api key from the LSX Currency Settings.
 */
function lsx_starter_plugin_options_pages_filter( $pages ) {
	$pages[] = 'lsx-settings';
	$pages[] = 'lsx-to-settings';
	return $pages;
}
add_filter( 'lsx_api_manager_options_pages', 'lsx_starter_plugin_options_pages_filter', 10, 1 );

function lsx_starter_plugin_api_admin_init() {
	global $lsx_starter_plugin_api_manager;

	if ( function_exists( 'tour_operator' ) ) {
		$options = get_option( '_lsx-to_settings', false );
	} else {
		$options = get_option( '_lsx_settings', false );

		if ( false === $options ) {
			$options = get_option( '_lsx_lsx-settings', false );
		}
	}

	$data = array(
		'api_key' => '',
		'email'   => '',
	);

	if ( false !== $options && isset( $options['api'] ) ) {
		if ( isset( $options['api']['lsx-starter-plugin_api_key'] ) && '' !== $options['api']['lsx-starter-plugin_api_key'] ) {
			$data['api_key'] = $options['api']['lsx-starter-plugin_api_key'];
		}

		if ( isset( $options['api']['lsx-starter-plugin_email'] ) && '' !== $options['api']['lsx-starter-plugin_email'] ) {
			$data['email'] = $options['api']['lsx-starter-plugin_email'];
		}
	}

	$instance = get_option( 'lsx_api_instance', false );

	if ( false === $instance ) {
		$instance = LSX_API_Manager::generatePassword();
	}

	$api_array = array(
		'product_id' => 'LSX Starter Plugin',
		'version'    => '1.0.0',
		'instance'   => $instance,
		'email'      => $data['email'],
		'api_key'    => $data['api_key'],
		'file'       => 'lsx-starter-plugin.php',
	);

	$lsx_starter_plugin_api_manager = new LSX_API_Manager( $api_array );
}
add_action( 'admin_init', 'lsx_starter_plugin_api_admin_init' );

/* ======================= Below is the Plugin Class init ========================= */

require_once( LSX_STARTER_PLUGIN_PATH . '/classes/class-lsx-starter-plugin.php' );
require_once( LSX_STARTER_PLUGIN_PATH . '/classes/class-lsx-starter-plugin-admin.php' );
require_once( LSX_STARTER_PLUGIN_PATH . '/classes/class-lsx-starter-plugin-frontend.php' );
require_once( LSX_STARTER_PLUGIN_PATH . '/includes/functions.php' );
