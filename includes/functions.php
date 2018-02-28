<?php
/**
 * Functions
 *
 * @package   LSX Documentation
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2016 LightSpeed
 */

/**
 * Add our action to init to set up our vars first.
 */
function lsx_documentation_load_plugin_textdomain() {
	load_plugin_textdomain( 'lsx-documentation', false, basename( LSX_DOCUMENTATION_PATH ) . '/languages' );
}
add_action( 'init', 'lsx_documentation_load_plugin_textdomain' );

/**
 * Wraps the output class in a function to be called in templates
 */
function lsx_documentation( $args ) {
	$lsx_documentation = new LSX_Documentation;
	echo wp_kses_post( $lsx_documentation->output( $args ) );
}

/**
 * Shortcode
 */
function lsx_documentation_shortcode( $atts ) {
	$lsx_documentation = new LSX_Documentation;
	return $lsx_documentation->output( $atts );
}
add_shortcode( 'lsx_documentation', 'lsx_documentation_shortcode' );

/**
 * Wraps the output class in a function to be called in templates
 */
function lsx_documentation_category_list() {
	do_action( 'lsx_documentation_category_list' );
}

function lsx_child_documentation_category_list() {
	do_action( 'lsx_child_documentation_category_list' );
}

function lsx_documentation_list() {
	do_action( 'lsx_documentation_list' );
}

function lsx_documentation_sidebar() {
	do_action( 'lsx_documentation_sidebar' );
}

function lsx_documentation_single_tag() {
	do_action( 'lsx_documentation_single_tag' );
}




add_action( 'dashboard_glance_items', 'cpad_at_glance_content_table_end' );
function cpad_at_glance_content_table_end() {
    $args = array(
        'public' => true,
        '_builtin' => false
    );
    $output = 'object';
    $operator = 'and';

    $post_types = get_post_types( $args, $output, $operator );
    foreach ( $post_types as $post_type ) {
        $num_posts = wp_count_posts( $post_type->name );
        $num = number_format_i18n( $num_posts->publish );
        $text = _n( $post_type->labels->singular_name, $post_type->labels->name, intval( $num_posts->publish ) );
        if ( current_user_can( 'edit_posts' ) ) {
            $output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $num . ' ' . $text . '</a>';
            echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
        }
    }
}