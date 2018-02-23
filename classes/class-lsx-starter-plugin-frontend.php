<?php
/**
 * LSX Starter Plugin Frontend Class.
 *
 * @package lsx-starter-plugin
 */
class LSX_Starter_Plugin_Frontend {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ), 999 );
	}

	public function assets() {
		wp_enqueue_script( 'lsx-starter-plugin', LSX_STARTER_PLUGIN_URL . 'assets/js/lsx-starter-plugin.min.js', array( 'jquery' ), LSX_STARTER_PLUGIN_VER, true );

		$params = apply_filters( 'lsx_starter_plugin_js_params', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		));

		wp_localize_script( 'lsx-starter-plugin', 'lsx_customizer_params', $params );

		wp_enqueue_style( 'lsx-starter-plugin', LSX_STARTER_PLUGIN_URL . 'assets/css/lsx-starter-plugin.css', array(), LSX_STARTER_PLUGIN_VER );
		wp_style_add_data( 'lsx-starter-plugin', 'rtl', 'replace' );
	}

}

global $lsx_starter_plugin_frontend;
$lsx_starter_plugin_frontend = new LSX_Starter_Plugin_Frontend();
