<?php
/**
 * LSX Starter Plugin functions.
 *
 * @package lsx-starter-plugin
 */

/**
 * Adds text domain.
 */
function lsx_starter_plugin_load_plugin_textdomain() {
	load_plugin_textdomain( 'lsx-starter-plugin', false, basename( LSX_STARTER_PLUGIN_PATH ) . '/languages' );
}
add_action( 'init', 'lsx_starter_plugin_load_plugin_textdomain' );
