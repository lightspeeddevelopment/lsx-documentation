<?php
/**
 * LSX Documentation Widget Class
 *
 * @package   LSX Documentation
 * @author	LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2016 LightSpeed
 */
class LSX_Documentation_Widget extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'classname' => 'lsx-documentation',
		);

		parent::__construct( 'LSX_Documentation_Widget', esc_html__( 'LSX Documentation', 'lsx-documentation' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = $instance['title'];
		$title_link = $instance['title_link'];
		$tagline = $instance['tagline'];
		$columns = $instance['columns'];
		$orderby = $instance['orderby'];
		$order = $instance['order'];
		$limit = $instance['limit'];
		$include = $instance['include'];
		$display = $instance['display'];
		$size = $instance['size'];
		$button_text = $instance['button_text'];
		$responsive = $instance['responsive'];
		$show_image = $instance['show_image'];
		$carousel = $instance['carousel'];
		$featured = $instance['featured'];

		// If limit not set, display 99 posts
		if ( empty( $limit ) ) {
			$limit = '99';
		}

		// If specific posts included, display 99 posts
		if ( ! empty( $include ) ) {
			$limit = '99';
		}

		if ( '1' == $responsive ) {
			$responsive = 'true';
		} else {
			$responsive = 'false';
		}

		if ( '1' == $show_image ) {
			$show_image = 'true';
		} else {
			$show_image = 'false';
		}

		if ( '1' == $carousel ) {
			$carousel = 'true';
		} else {
			$carousel = 'false';
		}

		if ( '1' == $featured ) {
			$featured = 'true';
		} else {
			$featured = 'false';
		}

		if ( $title_link ) {
			//$link_open = '<a href="' . $title_link . '">';
			$link_open = '';
			$link_btn_open = '<a href="' . $title_link . '" class="btn border-btn">';
			//$link_close = '</a>';
			$link_close = '';
			$link_btn_close = '</a>';
		} else {
			$link_open = '';
			$link_btn_open = '';
			$link_close = '';
			$link_btn_close = '';
		}

		echo wp_kses_post( $before_widget );

		if ( $title ) {
			echo wp_kses_post( $before_title . $link_open . $title . $link_close . $after_title );
		}

		if ( $tagline ) {
			echo '<p class="tagline text-center">' . esc_html( $tagline ) . '</p>';
		}

		if ( class_exists( 'LSX_Documentation' ) ) {
			lsx_documentation( array(
				'columns' => $columns,
				'orderby' => $orderby,
				'order' => $order,
				'limit' => $limit,
				'include' => $include,
				'display' => $display,
				'size' => $size,
				'responsive' => $responsive,
				'show_image' => $show_image,
				'carousel' => $carousel,
				'featured' => $featured,
			) );
		};

		if ( $button_text && $title_link ) {
			echo wp_kses_post( '<p class="text-center lsx-documentation-archive-link-wrap"><span class="lsx-documentation-archive-link">' . $link_btn_open . $button_text . ' <i class="fa fa-angle-right"></i>' . $link_btn_close . '</span></p>' );
		}

		echo wp_kses_post( $after_widget );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = wp_kses_post( force_balance_tags( $new_instance['title'] ) );
		$instance['title_link'] = strip_tags( $new_instance['title_link'] );
		$instance['tagline'] = wp_kses_post( force_balance_tags( $new_instance['tagline'] ) );
		$instance['columns'] = strip_tags( $new_instance['columns'] );
		$instance['orderby'] = strip_tags( $new_instance['orderby'] );
		$instance['order'] = strip_tags( $new_instance['order'] );
		$instance['limit'] = strip_tags( $new_instance['limit'] );
		$instance['include'] = strip_tags( $new_instance['include'] );
		$instance['display'] = strip_tags( $new_instance['display'] );
		$instance['size'] = strip_tags( $new_instance['size'] );
		$instance['button_text'] = strip_tags( $new_instance['button_text'] );
		$instance['responsive'] = strip_tags( $new_instance['responsive'] );
		$instance['show_image'] = strip_tags( $new_instance['show_image'] );
		$instance['carousel'] = strip_tags( $new_instance['carousel'] );
		$instance['featured'] = strip_tags( $new_instance['featured'] );

		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' => 'Documentation',
			'title_link' => '',
			'tagline' => '',
			'columns' => '3',
			'orderby' => 'name',
			'order' => 'ASC',
			'limit' => '',
			'include' => '',
			'display' => 'excerpt',
			'size' => 'lsx-thumbnail-single',
			'button_text' => '',
			'responsive' => 1,
			'show_image' => 1,
			'carousel' => 1,
			'featured' => 0,
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		$title          = esc_attr( $instance['title'] );
		$title_link     = esc_attr( $instance['title_link'] );
		$tagline        = esc_attr( $instance['tagline'] );
		$columns        = esc_attr( $instance['columns'] );
		$orderby        = esc_attr( $instance['orderby'] );
		$order          = esc_attr( $instance['order'] );
		$limit          = esc_attr( $instance['limit'] );
		$include        = esc_attr( $instance['include'] );
		$display        = esc_attr( $instance['display'] );
		$size           = esc_attr( $instance['size'] );
		$button_text    = esc_attr( $instance['button_text'] );
		$responsive     = esc_attr( $instance['responsive'] );
		$show_image     = esc_attr( $instance['show_image'] );
		$carousel       = esc_attr( $instance['carousel'] );
		$featured       = esc_attr( $instance['featured'] );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'lsx-documentation' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title_link' ) ); ?>"><?php esc_html_e( 'Page Link:', 'lsx-documentation' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title_link' ) ); ?>" type="text" value="<?php echo esc_attr( $title_link ); ?>" />
			<small><?php esc_html_e( 'Link the widget to a page', 'lsx-documentation' ); ?></small>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'tagline' ) ); ?>"><?php esc_html_e( 'Tagline:', 'lsx-documentation' ); ?></label>
			<textarea class="widefat" rows="8" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'tagline' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tagline' ) ); ?>"><?php echo esc_html( $tagline ); ?></textarea>
			<small><?php esc_html_e( 'Tagline to display below the widget title', 'lsx-documentation' ); ?></small>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php esc_html_e( 'Columns:', 'lsx-documentation' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" class="widefat">
			<?php
				$options = array( '1', '2', '3', '4' );

				foreach ( $options as $option ) {
					echo '<option value="' . esc_attr( lcfirst( $option ) ) . '" id="' . esc_attr( $option ) . '"', lcfirst( $option ) == $columns ? ' selected="selected"' : '', '>', esc_html( $option ), '</option>';
				}
			?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order By:', 'lsx-documentation' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" class="widefat">
			<?php
				$options = array(
					esc_html__( 'None', 'lsx-documentation' ) => 'none',
					esc_html__( 'ID', 'lsx-documentation' ) => 'ID',
					esc_html__( 'Name', 'lsx-documentation' ) => 'name',
					esc_html__( 'Date', 'lsx-documentation' ) => 'date',
					esc_html__( 'Modified Date', 'lsx-documentation' ) => 'modified',
					esc_html__( 'Random', 'lsx-documentation' ) => 'rand',
					esc_html__( 'Menu (WP dashboard order)', 'lsx-documentation' ) => 'menu_order',
				);

				foreach ( $options as $name => $value ) {
					echo '<option value="' . esc_attr( $value ) . '" id="' . esc_attr( $value ) . '"', $orderby == $value ? ' selected="selected"' : '', '>', esc_html( $name ), '</option>';
				}
			?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order:', 'lsx-documentation' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" class="widefat">
			<?php
				$options = array(
					esc_html__( 'Ascending', 'lsx-documentation' ) => 'ASC',
					esc_html__( 'Descending', 'lsx-documentation' ) => 'DESC',
				);

				foreach ( $options as $name => $value ) {
					echo '<option value="' . esc_attr( $value ) . '" id="' . esc_attr( $value ) . '"', $order == $value ? ' selected="selected"' : '', '>', esc_html( $name ), '</option>';
				}
			?>
			</select>
		</p>
		<p class="limit">
			<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Maximum amount:', 'lsx-documentation' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" value="<?php echo esc_attr( $limit ); ?>" />
			<small><?php esc_html_e( 'Leave empty to display all', 'lsx-documentation' ); ?></small>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'include' ) ); ?>"><?php esc_html_e( 'Specify Documentation by ID:', 'lsx-documentation' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'include' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'include' ) ); ?>" type="text" value="<?php echo esc_attr( $include ); ?>" />
			<small><?php esc_html_e( 'Comma separated list, overrides limit and order settings', 'lsx-documentation' ); ?></small>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'display' ) ); ?>"><?php esc_html_e( 'Display:', 'lsx-documentation' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'display' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'display' ) ); ?>" class="widefat">
			<?php
				$options = array(
					esc_html__( 'Excerpt', 'lsx-documentation' ) => 'excerpt',
					esc_html__( 'Full Content', 'lsx-documentation' ) => 'full',
				);

				foreach ( $options as $name => $value ) {
					echo '<option value="' . esc_attr( $value ) . '" id="' . esc_attr( $value ) . '"', $display == $value ? ' selected="selected"' : '', '>', esc_html( $name ), '</option>';
				}
			?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Image size:', 'lsx-documentation' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>" type="text" value="<?php echo esc_attr( $size ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button "view all" text:', 'lsx-documentation' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>" />
			<small><?php esc_html_e( 'Leave empty to not display the button', 'lsx-documentation' ); ?></small>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_image' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_image ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>"><?php esc_html_e( 'Display Images', 'lsx-documentation' ); ?></label>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'responsive' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'responsive' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $responsive ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'responsive' ) ); ?>"><?php esc_html_e( 'Responsive Images', 'lsx-documentation' ); ?></label>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'carousel' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'carousel' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $carousel ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'carousel' ) ); ?>"><?php esc_html_e( 'Carousel', 'lsx-documentation' ); ?></label>
		</p>
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'featured' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'featured' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $featured ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'featured' ) ); ?>"><?php esc_html_e( 'Featured posts', 'lsx-documentation' ); ?></label>
		</p>
		<?php
	}

}

add_action( 'widgets_init', create_function( '', 'return register_widget( "LSX_Documentation_Widget" );' ) );
