<?php
/**
 * LSX Documentation Frontend Class
 *
 * @package   LSX Documentation
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2017 LightSpeed
 */
class LSX_Documentation_Frontend {

	public function __construct() {
		if ( function_exists( 'tour_operator' ) ) {
			$this->options = get_option( '_lsx-to_settings', false );
		} else {
			$this->options = get_option( '_lsx_settings', false );

			if ( false === $this->options ) {
				$this->options = get_option( '_lsx_lsx-settings', false );
			}
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 5 );
		add_filter( 'wp_kses_allowed_html', array( $this, 'wp_kses_allowed_html' ), 10, 2 );
		add_filter( 'template_include', array( $this, 'single_template_include' ), 99 );
		add_filter( 'template_include', array( $this, 'archive_template_include' ), 99 );

		if ( ! empty( $this->options['display'] ) && ! empty( $this->options['display']['documentation_disable_single'] ) ) {
			add_action( 'template_redirect', array( $this, 'disable_single' ) );
		}

		if ( is_admin() ) {
			add_filter( 'lsx_customizer_colour_selectors_body', array( $this, 'customizer_body_colours_handler' ), 15, 2 );
		}

		add_filter( 'lsx_banner_title', array( $this, 'lsx_banner_archive_title' ), 15 );

		add_filter( 'excerpt_more_p', array( $this, 'change_excerpt_more' ) );
		add_filter( 'excerpt_length', array( $this, 'change_excerpt_length' ) );
		add_filter( 'excerpt_strip_tags', array( $this, 'change_excerpt_strip_tags' ) );

		add_filter( 'pre_get_posts', array( $this, 'posts_per_page' ) );
		add_action( 'wp_footer', array( $this, 'add_form_modal' ) );
	}

	public function enqueue_scripts() {
		$has_slick = wp_script_is( 'slick', 'queue' );

		if ( ! $has_slick ) {
			wp_enqueue_style( 'slick', LSX_DOCUMENTATION_URL . 'assets/css/vendor/slick.css', array(), LSX_DOCUMENTATION_VER, null );
			wp_enqueue_script( 'slick', LSX_DOCUMENTATION_URL . 'assets/js/vendor/slick.min.js', array( 'jquery' ), null, LSX_DOCUMENTATION_VER, true );
		}

		$has_scrolltofixed = wp_script_is( 'scrolltofixed', 'queue' );

		if ( ! $has_scrolltofixed ) {
			wp_enqueue_script( 'scrolltofixed', LSX_DOCUMENTATION_URL . 'assets/js/vendor/jquery-scrolltofixed-min.js', array( 'jquery' ), null, LSX_DOCUMENTATION_VER, true );
		}

		$has_isotope = wp_script_is( 'isotope', 'queue' );

		if ( ! $has_isotope ) {
			wp_enqueue_script( 'isotope', LSX_DOCUMENTATION_URL . 'assets/js/vendor/isotope.pkgd.min.js', array( 'jquery' ), null, LSX_DOCUMENTATION_VER, true );
		}

		wp_enqueue_script( 'lsx-documentation', LSX_DOCUMENTATION_URL . 'assets/js/lsx-documentation.min.js', array( 'jquery', 'slick', 'scrolltofixed', 'isotope' ), LSX_DOCUMENTATION_VER, true );

		$params = apply_filters( 'lsx_documentation_js_params', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		));

		wp_localize_script( 'lsx-documentation', 'lsx_customizer_params', $params );

		wp_enqueue_style( 'lsx-documentation', LSX_DOCUMENTATION_URL . 'assets/css/lsx-documentation.css', array(), LSX_DOCUMENTATION_VER );
		wp_style_add_data( 'lsx-documentation', 'rtl', 'replace' );
	}

	/**
	 * Allow data params for Slick slider addon.
	 */
	public function wp_kses_allowed_html( $allowedtags, $context ) {
		$allowedtags['div']['data-slick'] = true;
		return $allowedtags;
	}

	/**
	 * Single template.
	 */
	public function single_template_include( $template ) {
		if ( is_main_query() && is_singular( 'documentation' ) ) {
			if ( empty( locate_template( array( 'single-documentation.php' ) ) ) && file_exists( LSX_DOCUMENTATION_PATH . 'templates/single-documentation.php' ) ) {
				$template = LSX_DOCUMENTATION_PATH . 'templates/single-documentation.php';
			}
		}

		return $template;
	}

	/**
	 * Archive template.
	 */
	public function archive_template_include( $template ) {
		if ( is_main_query() && ( is_post_type_archive( 'documentation' ) || is_tax( 'documentation-category' ) ) ) {
			if ( empty( locate_template( array( 'archive-documentation.php' ) ) ) && file_exists( LSX_DOCUMENTATION_PATH . 'templates/archive-documentation.php' ) ) {
				$template = LSX_DOCUMENTATION_PATH . 'templates/archive-documentation.php';
			}
		}

		return $template;
	}

	/**
	 * Removes access to single documentation member posts.
	 */
	public function disable_single() {
		$queried_post_type = get_query_var( 'post_type' );

		if ( is_single() && 'documentation' === $queried_post_type ) {
			wp_redirect( home_url(), 301 );
			exit;
		}
	}

	/**
	 * Handle body colours that might be change by LSX Customiser
	 */
	public function customizer_body_colours_handler( $css, $colors ) {
		$css .= '
			@import "' . LSX_DOCUMENTATION_PATH . '/assets/css/scss/customizer-documentation-body-colours";

			/**
			 * LSX Customizer - Body (LSX Documentation)
			 */
			@include customizer-documentation-body-colours (
				$bg: 		' . $colors['background_color'] . ',
				$breaker: 	' . $colors['body_line_color'] . ',
				$color:    	' . $colors['body_text_color'] . ',
				$link:    	' . $colors['body_link_color'] . ',
				$hover:    	' . $colors['body_link_hover_color'] . ',
				$small:    	' . $colors['body_text_small_color'] . '
			);
		';

		return $css;
	}

	/**
	 * Change the LSX Banners title for documentation archive.
	 */
	public function lsx_banner_archive_title( $title ) {
		if ( is_main_query() && is_post_type_archive( 'documentation' ) ) {
			$title = '<h1 class="page-title">' . esc_html__( 'Documentation', 'lsx-documentation' ) . '</h1>';
		}

		if ( is_main_query() && is_tax( 'documentation-category' ) ) {
			$tax = get_queried_object();
			$title = '<h1 class="page-title">' . esc_html__( 'Documentation Type', 'lsx-documentation' ) . ': ' . apply_filters( 'the_title', $tax->name ) . '</h1>';
		}

		return $title;
	}

	/**
	 * Remove the "continue reading" when the single is disabled.
	 */
	public function change_excerpt_more( $excerpt_more ) {
		global $post;

		if ( 'documentation' === $post->post_type ) {
			if ( ! empty( $this->options['display'] ) && ! empty( $this->options['display']['documentation_disable_single'] ) ) {
				$excerpt_more = '';
			}
		}

		return $excerpt_more;
	}

	/**
	 * Change the word count when crop the content to excerpt (homepage widget).
	 */
	public function change_excerpt_length( $excerpt_word_count ) {
		global $post;

		if ( is_front_page() && 'documentation' === $post->post_type ) {
			$excerpt_word_count = 20;
		}

		if ( is_singular( 'documentation' ) && ( 'team' === $post->post_type || 'testimonial' === $post->post_type ) ) {
			$excerpt_word_count = 20;
		}

		return $excerpt_word_count;
	}

	/**
	 * Change the allowed tags crop the content to excerpt (homepage widget).
	 */
	public function change_excerpt_strip_tags( $allowed_tags ) {
		global $post;

		if ( is_front_page() && 'documentation' === $post->post_type ) {
			$allowed_tags = '<p>,<br>,<b>,<strong>,<i>,<u>,<ul>,<ol>,<li>,<span>';
		}

		if ( is_singular( 'documentation' ) && ( 'team' === $post->post_type || 'testimonial' === $post->post_type ) ) {
			$allowed_tags = '<p>,<br>,<b>,<strong>,<i>,<u>,<ul>,<ol>,<li>,<span>';
		}

		return $allowed_tags;
	}

	/**
	 * Change posts per page counter for archive.
	 */
	public function posts_per_page( $query ) {
		if ( ! is_admin() && $query->is_main_query() ) {
			if ( $query->is_post_type_archive( 'documentation' ) || $query->is_tax( 'documentation-category' ) ) {
				$query->set( 'posts_per_page', -1 );
			}
		}

		return $query;
	}

	/**
	 * Add form modal
	 */
	public function add_form_modal() {
		global $lsx_documentation;

		if ( empty( $lsx_documentation->options['display'] ) || empty( $lsx_documentation->options['display']['documentation_modal_enable'] ) ) {
			return '';
		}

		if ( empty( $lsx_documentation->options['display']['documentation_modal_cta_label'] ) || empty( $lsx_documentation->options['display']['documentation_modal_form_id'] ) ) {
			return '';
		}
		?>
		<div class="lsx-modal modal fade" id="lsx-documentation-contact" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<button type="button" class="close" data-dismiss="modal">&times;</button>

					<div class="modal-header">
						<h4 class="modal-title"><?php echo esc_html( $lsx_documentation->options['display']['documentation_modal_cta_label'] ); ?></h4>
					</div>

					<div class="modal-body">
						<?php echo do_shortcode( '[caldera_form id="' . $lsx_documentation->options['display']['documentation_modal_form_id'] . '"]' ); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

}

global $lsx_documentation_frontend;
$lsx_documentation_frontend = new LSX_Documentation_Frontend();
