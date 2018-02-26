<?php
/**
 * LSX Documentation Main Class
 *
 * @package   LSX Documentation
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2016 LightSpeed
 */
class LSX_Documentation {

	public $columns, $responsive, $options;

	public function __construct() {
		if ( function_exists( 'tour_operator' ) ) {
			$this->options = get_option( '_lsx-to_settings', false );
		} else {
			$this->options = get_option( '_lsx_settings', false );
			if ( false === $this->options ) {
				$this->options = get_option( '_lsx_lsx-settings', false );
			}
		}

		add_filter( 'lsx_banner_allowed_post_types', array( $this, 'lsx_banner_allowed_post_types' ) );
		add_filter( 'lsx_banner_allowed_taxonomies', array( $this, 'lsx_banner_allowed_taxonomies' ) );
	}

	/**
	 * Enable documentation custom post type on LSX Banners.
	 */
	public function lsx_banner_allowed_post_types( $post_types ) {
		$post_types[] = 'documentation';
		return $post_types;
	}

	/**
	 * Enable documentation custom taxonomies on LSX Banners.
	 */
	public function lsx_banner_allowed_taxonomies( $taxonomies ) {
		$taxonomies[] = 'documentation-category';
		return $taxonomies;
	}

	/**
	 * Returns the shortcode output markup
	 */
	public function output( $atts ) {
		// @codingStandardsIgnoreLine
		extract( shortcode_atts( array(
			'columns' => 3,
			'orderby' => 'name',
			'order' => 'ASC',
			'limit' => '-1',
			'include' => '',
			'display' => 'excerpt',
			'size' => 'lsx-thumbnail-single',
			'responsive' => 'true',
			'show_image' => 'true',
			'carousel' => 'true',
			'featured' => 'false',
		), $atts ) );

		$output = '';

		if ( 'true' === $responsive || true === $responsive ) {
			$responsive = ' img-responsive';
		} else {
			$responsive = '';
		}

		$this->columns = $columns;
		$this->responsive = $responsive;

		if ( ! empty( $include ) ) {
			$include = explode( ',', $include );

			$args = array(
				'post_type' => 'documentation',
				'posts_per_page' => $limit,
				'post__in' => $include,
				'orderby' => 'post__in',
				'order' => $order,
			);
		} else {
			$args = array(
				'post_type' => 'documentation',
				'posts_per_page' => $limit,
				'orderby' => $orderby,
				'order' => $order,
			);

			if ( 'true' === $featured || true === $featured ) {
				$args['meta_key'] = 'lsx_documentation_featured';
				$args['meta_value'] = 1;
			}
		}

		$documentation = new \WP_Query( $args );

		if ( $documentation->have_posts() ) {
			global $post;

			$count = 0;
			$count_global = 0;

			if ( 'true' === $carousel || true === $carousel ) {
				$output .= "<div id='lsx-documentation-slider' class='lsx-documentation-shortcode' data-slick='{\"slidesToShow\": $columns, \"slidesToScroll\": $columns }'>";
			} else {
				$output .= "<div class='lsx-documentation-shortcode'><div class='row'>";
			}

			while ( $documentation->have_posts() ) {
				$documentation->the_post();

				// Count
				$count++;
				$count_global++;

				// Content
				if ( 'full' === $display ) {
					$content = apply_filters( 'the_content', get_the_content() );
					$content = str_replace( ']]>', ']]&gt;', $content );
				} elseif ( 'excerpt' === $display ) {
					$content = apply_filters( 'the_excerpt', get_the_excerpt() );
				}

				// Image
				if ( 'true' === $show_image || true === $show_image ) {
					if ( is_numeric( $size ) ) {
						$thumb_size = array( $size, $size );
					} else {
						$thumb_size = $size;
					}

					if ( ! empty( get_the_post_thumbnail( $post->ID ) ) ) {
						$image = get_the_post_thumbnail( $post->ID, $thumb_size, array(
							'class' => $responsive,
						) );
					} else {
						$image = '';
					}

					if ( empty( $image ) ) {
						if ( $this->options['display'] && ! empty( $this->options['display']['documentation_placeholder'] ) ) {
							$image = '<img class="' . $responsive . '" src="' . $this->options['display']['documentation_placeholder'] . '" width="' . $size . '" alt="placeholder" />';
						} else {
							$image = '';
						}
					}
				} else {
					$image = '';
				}

				// documentation categories
				$doc_categories = '';
				$terms = get_the_terms( $post->ID, 'documentation-category' );

				if ( $terms && ! is_wp_error( $terms ) ) {
					$doc_categories = array();

					foreach ( $terms as $term ) {
						$doc_categories[] = $term->name;
					}

					$doc_categories = join( ', ', $doc_categories );
				}

				$documentation_category = '' !== $doc_categories ? "<p class='lsx-documentation-category'>$doc_categories</p>" : '';

				if ( 'true' === $carousel || true === $carousel ) {
					$output .= "
						<div class='lsx-documentation-slot'>
							" . ( ! empty( $image ) ? "<a href='" . get_permalink() . "'><figure class='lsx-documentation-avatar'>$image</figure></a>" : '' ) . "
							<h5 class='lsx-documentation-title'><a href='" . get_permalink() . "'>" . apply_filters( 'the_title', $post->post_title ) . "</a></h5>
							$documentation_category
							<div class='lsx-documentation-content'><a href='" . get_permalink() . "' class='moretag'>" . esc_html__( 'View more', 'lsx-documentation' ) . '</a></div>
						</div>';
				} elseif ( $columns >= 1 && $columns <= 4 ) {
					$md_col_width = 12 / $columns;

					$output .= "
						<div class='col-xs-12 col-md-" . $md_col_width . "'>
							<div class='lsx-documentation-slot'>
								" . ( ! empty( $image ) ? "<a href='" . get_permalink() . "'><figure class='lsx-documentation-avatar'>$image</figure></a>" : '' ) . "
								<h5 class='lsx-documentation-title'><a href='" . get_permalink() . "'>" . apply_filters( 'the_title', $post->post_title ) . "</a></h5>
								$documentation_category
								<div class='lsx-documentation-content'><a href='" . get_permalink() . "' class='moretag'>" . esc_html__( 'View more', 'lsx-documentation' ) . '</a></div>
							</div>
						</div>';

					if ( $count == $columns && $documentation->post_count > $count_global ) {
						$output .= '</div>';
						$output .= "<div class='row'>";
						$count = 0;
					}
				} else {
					$output .= "
						<p class='bg-warning' style='padding: 20px;'>
							" . esc_html__( 'Invalid number of columns set. LSX Documentation supports 1 to 4 columns.', 'lsx-documentation' ) . '
						</p>';
				}

				wp_reset_postdata();
			}

			if ( 'true' !== $carousel && true !== $carousel ) {
				$output .= '</div>';
			}

			$output .= '</div>';

			return $output;
		}
	}

}

global $lsx_documentation;
$lsx_documentation = new LSX_Documentation();
