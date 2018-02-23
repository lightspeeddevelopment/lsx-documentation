<?php
/**
 * LSX Starter Plugin Admin Class.
 *
 * @package lsx-starter-plugin
 */
class LSX_Starter_Plugin_Admin {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
	}

	public function assets() {
		//wp_enqueue_media();
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );

		wp_enqueue_script( 'lsx-starter-plugin-admin', LSX_STARTER_PLUGIN_URL . 'assets/js/lsx-starter-plugin-admin.min.js', array( 'jquery' ), LSX_STARTER_PLUGIN_VER, true );
		wp_enqueue_style( 'lsx-starter-plugin-admin', LSX_STARTER_PLUGIN_URL . 'assets/css/lsx-starter-plugin-admin.css', array(), LSX_STARTER_PLUGIN_VER );
	}

}

global $lsx_starter_plugin_admin;
$lsx_starter_plugin_admin = new LSX_Starter_Plugin_Admin();
