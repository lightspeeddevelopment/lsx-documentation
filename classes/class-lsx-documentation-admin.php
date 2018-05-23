<?php
/**
 * LSX Documentation Admin Class
 *
 * @package   LSX Documentation
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2016 LightSpeed
 */
class LSX_Documentation_Admin {
	public function __construct() {
		if ( ! class_exists( 'CMB_Meta_Box' ) ) {
			require_once( LSX_DOCUMENTATION_PATH . '/vendor/Custom-Meta-Boxes/custom-meta-boxes.php' );
		}

		if ( function_exists( 'tour_operator' ) ) {
			$this->options = get_option( '_lsx-to_settings', false );
		} else {
			$this->options = get_option( '_lsx_settings', false );

			if ( false === $this->options ) {
				$this->options = get_option( '_lsx_lsx-settings', false );
			}
		}

		add_action( 'init', array( $this, 'post_type_setup' ) );
		add_action( 'init', array( $this, 'taxonomy_setup' ) );
		add_action( 'init', array( $this, 'product_taxonomy_setup' ) );
		add_filter( 'cmb_meta_boxes', array( $this, 'field_setup' ) );
		add_action( 'cmb_save_custom', array( $this, 'post_relations' ), 3, 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

		add_action( 'init', array( $this, 'create_settings_page' ), 100 );
		add_filter( 'lsx_framework_settings_tabs', array( $this, 'register_tabs' ), 100, 1 );

		add_filter( 'type_url_form_media', array( $this, 'change_attachment_field_button' ), 20, 1 );
		add_filter( 'enter_title_here', array( $this, 'change_title_text' ) );
	}

	/**
	 * Register the Documentation and Product Tag post type
	 */
	public function post_type_setup() {
		$labels = array(
			'name'			   => esc_html_x( 'Documentation', 'post type general name', 'lsx-documentation' ),
			'singular_name'      => esc_html_x( 'Documentation', 'post type singular name', 'lsx-documentation' ),
			'add_new'          => esc_html_x( 'Add New', 'post type general name', 'lsx-documentation' ),
			'add_new_item'       => esc_html__( 'Add New Documentation', 'lsx-documentation' ),
			'edit_item'		  => esc_html__( 'Edit Documentation', 'lsx-documentation' ),
			'new_item'		   => esc_html__( 'New Documentation', 'lsx-documentation' ),
			'all_items'		  => esc_html__( 'All Documentation', 'lsx-documentation' ),
			'view_item'		  => esc_html__( 'View Documentation', 'lsx-documentation' ),
			'search_items'       => esc_html__( 'Search Documentation', 'lsx-documentation' ),
			'not_found'		  => esc_html__( 'No documentation found', 'lsx-documentation' ),
			'not_found_in_trash' => esc_html__( 'No documentation found in Trash', 'lsx-documentation' ),
			'parent_item_colon'  => '',
			'menu_name'		  => esc_html_x( 'Documentation', 'admin menu', 'lsx-documentation' ),
		);

		$args = array(
			'labels'			 => $labels,
			'public'			 => true,
			'publicly_queryable' => true,
			'show_ui'          => true,
			'show_in_menu'       => true,
			'menu_icon'		  => 'dashicons-welcome-write-blog',
			'query_var'		  => true,
			'rewrite'          => array(
				'slug' => 'documentation',
			),
			'capability_type'    => 'post',
			'has_archive'		=> 'documentation',
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'		   => array(
				'title',
				'editor',
				'thumbnail',
				'excerpt',
			),
		);

		register_post_type( 'documentation', $args );
	}

	/**
	 * Register the Documentation Category taxonomy
	 */
	public function taxonomy_setup() {
		$labels = array(
			'name'			  => esc_html_x( 'Documentation Categories', 'taxonomy general name', 'lsx-documentation' ),
			'singular_name'     => esc_html_x( 'Doc Category', 'taxonomy singular name', 'lsx-documentation' ),
			'search_items'      => esc_html__( 'Search Doc Categories', 'lsx-documentation' ),
			'all_items'		 => esc_html__( 'All Documentation', 'lsx-documentation' ),
			'parent_item'       => esc_html__( 'Parent Documentation', 'lsx-documentation' ),
			'parent_item_colon' => esc_html__( 'Parent Documentation:', 'lsx-documentation' ),
			'edit_item'		 => esc_html__( 'Edit Documentation', 'lsx-documentation' ),
			'update_item'       => esc_html__( 'Update Documentation', 'lsx-documentation' ),
			'add_new_item'      => esc_html__( 'Add New', 'lsx-documentation' ),
			'new_item_name'     => esc_html__( 'New Documentation Name', 'lsx-documentation' ),
			'menu_name'		 => esc_html__( 'Documentation Category', 'lsx-documentation' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'          => $labels,
			'show_ui'		   => true,
			'show_admin_column' => true,
			'query_var'		 => true,
			'rewrite'		   => array(
				'slug' => 'documentation-category',
			),
		);

		register_taxonomy( 'documentation-category', array( 'documentation' ), $args );
	}

	/**
	 * Register the Product tag taxonomy
	 */
	public function product_taxonomy_setup() {
		$labels = array(
			'name'			  => esc_html_x( 'Product Tags', 'taxonomy general name', 'lsx-documentation' ),
			'singular_name'     => esc_html_x( 'Product Tag', 'taxonomy singular name', 'lsx-documentation' ),
			'search_items'      => esc_html__( 'Search Product Tags', 'lsx-documentation' ),
			'all_items'		 => esc_html__( 'All Product Tags', 'lsx-documentation' ),
			'parent_item'       => esc_html__( 'Parent Product Tags', 'lsx-documentation' ),
			'parent_item_colon' => esc_html__( 'Parent Product Tags:', 'lsx-documentation' ),
			'edit_item'		 => esc_html__( 'Edit Documentation', 'lsx-documentation' ),
			'update_item'       => esc_html__( 'Update Product Tags', 'lsx-documentation' ),
			'add_new_item'      => esc_html__( 'Add New', 'lsx-documentation' ),
			'new_item_name'     => esc_html__( 'New Product Tag Name', 'lsx-documentation' ),
			'menu_name'		 => esc_html__( 'Product Tags', 'lsx-documentation' ),
		);

		$args = array(
			'hierarchical'      => false,
			'labels'          => $labels,
			'show_ui'		   => true,
			'show_admin_column' => true,
			'query_var'		 => true,
			'rewrite'		   => array(
				'slug' => 'product-tags',
			),
		);

		register_taxonomy( 'product-tags', array( 'documentation' ), $args );
	}

	/**
	 * Add metabox with custom fields to the Documentation post type
	 */
	public function field_setup( $meta_boxes ) {
		$prefix = 'lsx_documentation_';

		$fields = array(
			array(
				'name' => esc_html__( 'Featured:', 'lsx-documentation' ),
				'id'   => $prefix . 'featured',
				'type' => 'checkbox',
			),
			array(
				'name' => esc_html__( 'URL for the related Woocommerce Product:', 'lsx-documentation' ),
				'id'   => $prefix . 'url',
				'type' => 'text',
			),
		);

		$group_fields = array(
			array(
				'name' => esc_html__( 'Question:', 'lsx-documentation' ),
				'id'   => 'faqquestion',
				'type' => 'textarea',
			),
			array(
				'name' => esc_html__( 'Answer:', 'lsx-documentation' ),
				'id'   => 'faqanswer',
				'type' => 'textarea',
			),
		);
		// $fields[] = array(
		// 	'name' => esc_html__( 'Documentation:', 'lsx-documentation' ),
		// 	'id' => 'documentation_to_documentation',
		// 	'type' => 'post_select',
		// 	'use_ajax' => false,
		// 	'query' => array(
		// 		'post_type' => 'documentation',
		// 		'nopagin' => true,
		// 		'posts_per_page' => '50',
		// 		'orderby' => 'title',
		// 		'order' => 'ASC',
		// 	),
		// 	'repeatable' => true,
		// 	'allow_none' => true,
		// 	'cols' => 12,
		// );

		if ( class_exists( 'LSX_Services' ) ) {
			$fields[] = array(
				'name' => esc_html__( 'Services related to this documentation:', 'lsx-documentation' ),
				'id' => 'service_to_documentation',
				'type' => 'post_select',
				'use_ajax' => false,
				'query' => array(
					'post_type' => 'service',
					'nopagin' => true,
					'posts_per_page' => '50',
					'orderby' => 'title',
					'order' => 'ASC',
				),
				'repeatable' => true,
				'allow_none' => true,
				'cols' => 12,
			);
		}

		if ( class_exists( 'LSX_Testimonials' ) ) {
			$fields[] = array(
				'name' => esc_html__( 'Testimonials related to this documentation:', 'lsx-documentation' ),
				'id' => 'testimonial_to_documentation',
				'type' => 'post_select',
				'use_ajax' => false,
				'query' => array(
					'post_type' => 'testimonial',
					'nopagin' => true,
					'posts_per_page' => '50',
					'orderby' => 'title',
					'order' => 'ASC',
				),
				'repeatable' => true,
				'allow_none' => true,
				'cols' => 12,
			);
		}

		if ( class_exists( 'LSX_Team' ) ) {
			$fields[] = array(
				'name' => esc_html__( 'Team members involved with this documentation:', 'lsx-documentation' ),
				'id' => 'team_to_documentation',
				'type' => 'post_select',
				'use_ajax' => false,
				'query' => array(
					'post_type' => 'team',
					'nopagin' => true,
					'posts_per_page' => '50',
					'orderby' => 'title',
					'order' => 'ASC',
				),
				'repeatable' => true,
				'allow_none' => true,
				'cols' => 12,
			);
		}


		if ( class_exists( 'woocommerce' ) ) {
			$fields[] = array(
				'name' => esc_html__( 'Products used for this documentation:', 'lsx-documentation' ),
				'id' => 'product_to_documentation',
				'type' => 'post_select',
				'use_ajax' => false,
				'query' => array(
					'post_type' => 'product',
					'nopagin' => true,
					'posts_per_page' => '50',
					'orderby' => 'title',
					'order' => 'ASC',
				),
				'repeatable' => true,
				'allow_none' => true,
				'cols' => 12,
			);
		}

		$fields[] = array(
			'id'            => 'gp',
			'name'          => 'FAQ',
			'type'          => 'group',
			'repeatable'    => true,
			'sortable'      => true,
			'fields'        => $group_fields,
			'desc'          => 'This is the group description.',
		);

		$meta_boxes[] = array(
			'title'  => esc_html__( 'Documentation Details', 'lsx-documentation' ),
			'pages'  => 'documentation',
			'fields' => $fields,
		);

		return $meta_boxes;
	}

	/**
	 * Sets up the "post relations".
	 */
	public function post_relations( $post_id, $field, $value ) {
		$connections = array(
			// 'documentation_to_documentation',
			'documentation_to_service',
			'service_to_documentation',

			'documentation_to_testimonial',
			'testimonial_to_documentation',

			'documentation_to_team',
			'team_to_documentation',
		);

		if ( in_array( $field['id'], $connections ) ) {
			$this->save_related_post( $connections, $post_id, $field, $value );
		}
	}

	/**
	 * Save the reverse post relation.
	 */
	public function save_related_post( $connections, $post_id, $field, $value ) {
		$ids = explode( '_to_', $field['id'] );
		$relation = $ids[1] . '_to_' . $ids[0];

		if ( in_array( $relation, $connections ) ) {
			$previous_values = get_post_meta( $post_id, $field['id'], false );

			if ( ! empty( $previous_values ) ) {
				foreach ( $previous_values as $v ) {
					delete_post_meta( $v, $relation, $post_id );
				}
			}

			if ( is_array( $value ) ) {
				foreach ( $value as $v ) {
					if ( ! empty( $v ) ) {
						add_post_meta( $v, $relation, $post_id );
					}
				}
			}
		}
	}

	public function assets() {
		// wp_enqueue_media();.
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );

		wp_enqueue_script( 'lsx-documentation-admin', LSX_DOCUMENTATION_URL . 'assets/js/lsx-documentation-admin.min.js', array( 'jquery' ), LSX_DOCUMENTATION_VER, true );
		wp_enqueue_style( 'lsx-documentation-admin', LSX_DOCUMENTATION_URL . 'assets/css/lsx-documentation-admin.css', array(), LSX_DOCUMENTATION_VER );
	}

	/**
	 * Returns the array of settings to the UIX Class
	 */
	public function create_settings_page() {
		if ( is_admin() ) {
			if ( ! class_exists( '\lsx\ui\uix' ) && ! function_exists( 'tour_operator' ) ) {
				include_once LSX_DOCUMENTATION_PATH . 'vendor/uix/uix.php';
				$pages = $this->settings_page_array();
				$uix = \lsx\ui\uix::get_instance( 'lsx' );
				$uix->register_pages( $pages );
			}

			if ( function_exists( 'tour_operator' ) ) {
				add_action( 'lsx_to_framework_display_tab_content', array( $this, 'display_settings' ), 11 );
			} else {
				add_action( 'lsx_framework_display_tab_content', array( $this, 'display_settings' ), 11 );
			}
		}
	}

	/**
	 * Returns the array of settings to the UIX Class
	 */
	public function settings_page_array() {
		$tabs = apply_filters( 'lsx_framework_settings_tabs', array() );

		return array(
			'settings'  => array(
				'page_title'  => esc_html__( 'Theme Options', 'lsx-documentation' ),
				'menu_title'  => esc_html__( 'Theme Options', 'lsx-documentation' ),
				'capability'  => 'manage_options',
				'icon'        => 'dashicons-book-alt',
				'parent'      => 'themes.php',
				'save_button' => esc_html__( 'Save Changes', 'lsx-documentation' ),
				'tabs'        => $tabs,
			),
		);
	}

	/**
	 * Register tabs
	 */
	public function register_tabs( $tabs ) {
		$default = true;

		if ( false !== $tabs && is_array( $tabs ) && count( $tabs ) > 0 ) {
			$default = false;
		}

		if ( ! function_exists( 'tour_operator' ) ) {
			if ( ! array_key_exists( 'display', $tabs ) ) {
				$tabs['display'] = array(
					'page_title'		=> '',
					'page_description'  => '',
					'menu_title'		=> esc_html__( 'Display', 'lsx-documentation' ),
					'template'		  => LSX_DOCUMENTATION_PATH . 'includes/settings/display.php',
					'default'		   => $default,
				);

				$default = false;
			}
		}

		return $tabs;
	}

	/**
	 * Outputs the display tabs settings
	 *
	 * @param $tab string
	 * @return null
	 */
	public function display_settings( $tab = 'general' ) {
		if ( 'documentation' === $tab ) {
			$this->disable_single_post_field();
			$this->placeholder_field();
			$this->contact_modal_fields();
		}
	}

	/**
	 * Outputs the Display flags checkbox
	 */
	public function disable_single_post_field() {
		?>
		<tr class="form-field">
			<th scope="row">
				<label for="documentation_disable_single"><?php esc_html_e( 'Disable Single Posts', 'lsx-documentation' ); ?></label>
			</th>
			<td>
				<input type="checkbox" {{#if documentation_disable_single}} checked="checked" {{/if}} name="documentation_disable_single" />
				<small><?php esc_html_e( 'Disable Single Posts.', 'lsx-documentation' ); ?></small>
			</td>
		</tr>
		<?php
	}

	/**
	 * Outputs the flag position field
	 */
	public function placeholder_field() {
		?>
		<tr class="form-field">
			<th scope="row">
				<label for="banner"> <?php esc_html_e( 'Placeholder', 'lsx-documentation' ); ?></label>
			</th>
			<td>
				<input class="input_image_id" type="hidden" {{#if documentation_placeholder_id}} value="{{documentation_placeholder_id}}" {{/if}} name="documentation_placeholder_id" />
				<input class="input_image" type="hidden" {{#if documentation_placeholder}} value="{{documentation_placeholder}}" {{/if}} name="documentation_placeholder" />
				<div class="thumbnail-preview">
					{{#if documentation_placeholder}}<img src="{{documentation_placeholder}}" width="150" />{{/if}}
				</div>
				<a {{#if documentation_placeholder}}style="display:none;"{{/if}} class="button-secondary lsx-thumbnail-image-add" data-slug="documentation_placeholder"><?php esc_html_e( 'Choose Image', 'lsx-documentation' ); ?></a>
				<a {{#unless documentation_placeholder}}style="display:none;"{{/unless}} class="button-secondary lsx-thumbnail-image-delete" data-slug="documentation_placeholder"><?php esc_html_e( 'Delete', 'lsx-documentation' ); ?></a>
			</td>
		</tr>
		<?php
	}

	/**
	 * Outputs the contact modal fields.
	 */
	public function contact_modal_fields() {
		?>
		<tr class="form-field">
			<th scope="row" colspan="2">
				<h2><?php esc_html_e( 'Contact modal', 'lsx-documentation' ); ?></h2>
			</th>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="documentation_modal_enable"><?php esc_html_e( 'Enable contact modal', 'lsx-documentation' ); ?></label>
			</th>
			<td>
				<input type="checkbox" {{#if documentation_modal_enable}} checked="checked" {{/if}} name="documentation_modal_enable" />
				<small><?php esc_html_e( 'Displays contact modal on documentation single.', 'lsx-documentation' ); ?></small>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="documentation_modal_cta_label"><?php esc_html_e( 'Button label', 'lsx-documentation' ); ?></label>
			</th>
			<td>
				<input type="text" {{#if documentation_modal_cta_label}} value="{{documentation_modal_cta_label}}" {{/if}} name="documentation_modal_cta_label" />
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="documentation_modal_form_id"><?php esc_html_e( 'Caldera Form ID', 'lsx-documentation' ); ?></label>
			</th>
			<td>
				<input type="text" {{#if documentation_modal_form_id}} value="{{documentation_modal_form_id}}" {{/if}} name="documentation_modal_form_id" />
			</td>
		</tr>
		<?php
	}

	/**
	 * Change the "Insert into Post" button text when media modal is used for feature images
	 */
	public function change_attachment_field_button( $html ) {
		if ( isset( $_GET['feature_image_text_button'] ) ) {
			$html = str_replace( 'value="Insert into Post"', sprintf( 'value="%s"', esc_html__( 'Select featured image', 'lsx-documentation' ) ), $html );
		}

		return $html;
	}

	public function change_title_text( $title ) {
		$screen = get_current_screen();

		if ( 'documentation' === $screen->post_type ) {
			$title = esc_attr__( 'Enter documentation title', 'lsx-documentation' );
		}

		return $title;
	}
}

$lsx_documentation_admin = new LSX_Documentation_Admin();
