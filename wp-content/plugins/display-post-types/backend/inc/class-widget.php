<?php
/**
 * Display post types widget.
 *
 * @package Display_Post_Types
 * @since 1.0.0
 */

namespace Display_Post_Types;

/**
 * Display post types widget.
 *
 * @since 1.0.0
 *
 * @see WP_Widget
 */
class Widget extends \WP_Widget {

	/**
	 * Holds all registered post type objects.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $post_types = array();

	/**
	 * Holds sort orderby options.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $orderby = array();

	/**
	 * Holds image cropping options.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $imagecrop = array();

	/**
	 * Holds image aspect ratio options.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $aspectratio = array();

	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var array
	 */
	protected $defaults = array();

	/**
	 * Holds all display styles.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var array
	 */
	protected $styles = array();

	/**
	 * Holds all display contents.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var array
	 */
	protected $contents = array();

	/**
	 * Holds all display styles supported items.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var array
	 */
	protected $style_supported = array();

	/**
	 * Sets up a new Blank widget instance.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Set widget instance settings default values.
		$this->defaults          = dpt_get_defaults();
		$this->defaults['title'] = '';

		// Set the options for orderby.
		$this->orderby = array(
			'date'          => esc_html__( 'Publish Date', 'display-post-types' ),
			'modified'      => esc_html__( 'Modified Date', 'display-post-types' ),
			'title'         => esc_html__( 'Title', 'display-post-types' ),
			'author'        => esc_html__( 'Author', 'display-post-types' ),
			'comment_count' => esc_html__( 'Comment Count', 'display-post-types' ),
			'rand'          => esc_html__( 'Random', 'display-post-types' ),
		);

		$this->imagecrop = array(
			'topleftcrop'      => esc_html__( 'Top Left Cropping', 'display-post-types' ),
			'topcentercrop'    => esc_html__( 'Top Center Cropping', 'display-post-types' ),
			'centercrop'       => esc_html__( 'Center Cropping', 'display-post-types' ),
			'bottomleftcrop'   => esc_html__( 'Bottom Left Cropping', 'display-post-types' ),
			'bottomcentercrop' => esc_html__( 'Bottom Center Cropping', 'display-post-types' ),
		);

		$this->aspectratio = array(
			''       => esc_html__( 'No Cropping', 'display-post-types' ),
			'land1'  => esc_html__( 'Landscape (4:3)', 'display-post-types' ),
			'land2'  => esc_html__( 'Landscape (3:2)', 'display-post-types' ),
			'port1'  => esc_html__( 'Portrait (3:4)', 'display-post-types' ),
			'port2'  => esc_html__( 'Portrait (2:3)', 'display-post-types' ),
			'wdscrn' => esc_html__( 'Widescreen (16:9)', 'display-post-types' ),
			'squr'   => esc_html__( 'Square (1:1)', 'display-post-types' ),
		);

		// Get list of all registered supported contents.
		$this->contents = array(
			'thumbnail' => esc_html__( 'Thumbnail', 'display-post-types' ),
			'title'     => esc_html__( 'Title', 'display-post-types' ),
			'meta'      => esc_html__( 'Meta info', 'display-post-types' ),
			'category'  => esc_html__( 'Category', 'display-post-types' ),
			'excerpt'   => esc_html__( 'Excerpt', 'display-post-types' ),
			'date'      => esc_html__( 'Date', 'display-post-types' ),
			'ago'       => esc_html__( 'Ago', 'display-post-types' ),
			'author'    => esc_html__( 'Author', 'display-post-types' ),
			'content'   => esc_html__( 'Content', 'display-post-types' ),
		);

		// Set the widget options.
		$widget_ops = array(
			'classname'                   => 'display_posts_types',
			'description'                 => esc_html__( 'Create a display post types widget.', 'display-post-types' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'dpt_display_post_types', esc_html__( 'Display Post Types', 'display-post-types' ), $widget_ops );
	}

	/**
	 * Outputs the content for the current widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {

		$args['widget_id'] = isset( $args['widget_id'] ) ? $args['widget_id'] : $this->id;

		// Merge with defaults.
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		dpt_display_posts( $instance );

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Outputs the settings form for the widget.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		// Merge with defaults.
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		?>
		<p>
			<?php $this->label( 'title', esc_html__( 'Title', 'display-post-types' ) ); ?>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<?php
			$post_type = dpt_get_post_types();
			$post_type = array_merge( array( '' => esc_html__( 'None', 'display-post-types' ) ), $post_type );
			$this->label( 'post_type', esc_html__( 'Select Post Type', 'display-post-types' ) );
			$this->select( 'post_type', $post_type, $instance['post_type'] );
			?>
		</p>

		<a class="dpt-filter dpt-settings-toggle" <?php echo ( ! $instance['post_type'] ) ? ' style="display:none;"' : ''; ?>><?php esc_html_e( 'Get items to be displayed', 'display-post-types' ); ?></a>
		<div class="dpt-filter-content dpt-settings-content">
			<div class="page-panel" <?php echo 'page' !== $instance['post_type'] ? ' style="display:none;"' : ''; ?>>
				<?php $this->pages_checklist( $instance['pages'] ); ?>
			</div><!-- .page-panel -->

			<div class="post-panel" <?php echo ( 'page' === $instance['post_type'] ) ? ' style="display:none;"' : ''; ?>>

				<p class="post-ids">
					<?php $this->label( 'post_ids', esc_html__( 'Get items by Post IDs (optional)', 'display-post-types' ) ); ?>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_ids' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_ids' ) ); ?>" type="text" placeholder="<?php echo esc_attr_x( 'Comma separated ids, i.e. 230,300', 'Placeholder text for post ids', 'display-post-types' ); ?>" value="<?php echo esc_attr( $instance['post_ids'] ); ?>" />
				</p>

				<div class="taxonomies">
					<?php $this->taxonomies_select( $instance['post_type'], $instance['taxonomy'] ); ?>
				</div><!-- .taxonomies -->

				<div class="terms-panel" <?php echo '' === $instance['taxonomy'] ? ' style="display:none;"' : ''; ?>>
					<?php $this->terms_checklist( $instance['taxonomy'], $instance['terms'] ); ?>
				</div><!-- .terms-panel -->

				<p class="terms-relation" <?php echo '' === $instance['taxonomy'] ? ' style="display:none;"' : ''; ?>>
					<?php
					$this->label( 'relation', esc_html__( 'Terms Relationship', 'display-post-types' ) );
					$relation = array(
						'IN'  => esc_html__( 'Show posts belong to any of the terms checked above.', 'display-post-types' ),
						'AND' => esc_html__( 'Show posts belong to all of the terms checked above.', 'display-post-types' ),
					);
					$this->select( 'relation', $relation, $instance['relation'] );
					?>
				</p>

				<p class="number-of-posts">
					<?php $this->label( 'number', esc_html__( 'Number of items to display', 'display-post-types' ) ); ?>
					<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo absint( $instance['number'] ); ?>" size="3" />
				</p>

				<p class="post-offset">
					<?php $this->label( 'offset', esc_html__( 'Offset (number of posts to displace)', 'display-post-types' ) ); ?>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'offset' ) ); ?>" type="number" step="1" min="0" value="<?php echo absint( $instance['offset'] ); ?>" size="3" />
				</p>

				<p class="posts-orderby">
					<?php
					$this->label( 'orderby', esc_html__( 'Order By', 'display-post-types' ) );
					$this->select( 'orderby', $this->orderby, $instance['orderby'] );
					?>
				</p>

				<p class="order">
					<?php
					$this->label( 'order', esc_html__( 'Sort Order', 'display-post-types' ) );
					$order = array(
						'DESC' => esc_html__( 'Descending', 'display-post-types' ),
						'ASC'  => esc_html__( 'Ascending', 'display-post-types' ),
					);
					$this->select( 'order', $order, $instance['order'] );
					?>
				</p>
			</div><!-- .post-panel -->
		</div>

		<a class="dpt-style dpt-settings-toggle" <?php echo ( ! $instance['post_type'] ) ? ' style="display:none;"' : ''; ?>><?php esc_html_e( 'Styling selected items', 'display-post-types' ); ?></a>
		<div class="dpt-style-content dpt-settings-content<?php echo ( 'post' !== $instance['post_type'] ) ? ' not-post' : ''; ?>">
			<div class="style-panel">
				<p class="posts-dstyle">
					<?php
					$this->label( 'styles', esc_html__( 'Display Style', 'display-post-types' ) );
					$styles = $this->get_display_styles();
					$this->select( 'styles', $styles, $instance['styles'] );
					?>
				</p>

				<div class="styles-supported">
					<?php $this->supported_checklist( $instance['styles'], $instance['style_sup'] ); ?>
				</div><!-- .styles-supported -->

				<?php
				$ex_class = in_array( 'excerpt', $instance['style_sup'], true ) ? 'has-ex' : '';
				?>
				<div class="custom-excerpts <?php echo esc_attr( $ex_class ); ?>" <?php echo ( in_array( 'excerpt', $instance['style_sup'], true ) && $this->is_style_support( $instance['styles'], 'excerpt' ) ) ? '' : ' style="display:none;"'; ?>>
					<p class="elength">
						<?php $this->label( 'e_length', esc_html__( 'Excerpt Length (in words)', 'display-post-types' ) ); ?>
						<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'e_length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'e_length' ) ); ?>" type="number" step="1" min="0" value="<?php echo absint( $instance['e_length'] ); ?>" size="3" />
					</p>
					<p class="eteaser">
						<?php $this->label( 'e_teaser', esc_html__( 'Excerpt Teaser Text', 'display-post-types' ) ); ?>
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'e_teaser' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'e_teaser' ) ); ?>" type="text" placeholder="<?php echo esc_attr_x( 'i.e., Continue Reading, Read More', 'Placeholder text for excerpt teaser', 'display-post-types' ); ?>" value="<?php echo esc_attr( $instance['e_teaser'] ); ?>" />
					</p>
				</div>

				<p class="posts-imgaspect">
					<?php
					$this->label( 'img_aspect', esc_html__( 'Image Cropping', 'display-post-types' ) );
					$this->select( 'img_aspect', $this->aspectratio, $instance['img_aspect'] );
					?>
				</p>

				<p class="posts-imgcrop" <?php echo ( '' === $instance['img_aspect'] ) ? ' style="display:none;"' : ''; ?>>
					<?php
					$this->label( 'image_crop', esc_html__( 'Image Cropping Position', 'display-post-types' ) );
					$this->select( 'image_crop', $this->imagecrop, $instance['image_crop'] );
					?>
				</p>

				<p class="posts-imgalign" <?php echo ( ! $this->is_style_support( $instance['styles'], 'ialign' ) ) ? ' style="display:none;"' : ''; ?>>
					<?php
					$ialign = array(
						''      => esc_html__( 'Left Aligned', 'display-post-types' ),
						'right' => esc_html__( 'Right Aligned', 'display-post-types' ),
					);
					$this->label( 'img_align', esc_html__( 'Image Alignment', 'display-post-types' ) );
					$this->select( 'img_align', $ialign, $instance['img_align'] );
					?>
				</p>

				<p class="colnarr" <?php echo ( ! $this->is_style_support( $instance['styles'], 'multicol' ) ) ? ' style="display:none;"' : ''; ?>>
					<?php $this->label( 'col_narr', esc_html__( 'Number of grid columns', 'display-post-types' ) ); ?>
					<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'col_narr' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'col_narr' ) ); ?>" type="number" step="1" min="1" max="8" value="<?php echo absint( $instance['col_narr'] ); ?>" size="1" />
				</p>

				<p class="autotime" <?php echo ( ! $this->is_style_support( $instance['styles'], 'slider' ) ) ? ' style="display:none;"' : ''; ?>>
					<?php $this->label( 'autotime', esc_html__( 'Auto slide timer (delay in ms)', 'display-post-types' ) ); ?>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'autotime' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'autotime' ) ); ?>" type="number" step="500" min="0" value="<?php echo absint( $instance['autotime'] ); ?>" size="4" />
					<span class="dpt-desc" style="font-style: italic;"><?php esc_html_e( 'Setting delay time to 0 will disable auto slide.', 'display-post-type' ); ?>
				</p>
			</div>
		</div>

		<a class="dpt-add-style dpt-settings-toggle" <?php echo ( ! $instance['post_type'] ) ? ' style="display:none;"' : ''; ?>><?php esc_html_e( 'Additional Styling Options', 'display-post-types' ); ?></a>
		<div class="dpt-style-content dpt-settings-content<?php echo ( 'post' !== $instance['post_type'] ) ? ' not-post' : ''; ?>">
			<p>
				<?php
				$text_align = array(
					''       => esc_html__( 'Left Align', 'display-post-types' ),
					'r-text' => esc_html__( 'Right Align', 'display-post-types' ),
					'c-text' => esc_html__( 'Center Align', 'display-post-types' ),
				);
				$this->label( 'text_align', esc_html__( 'Text Align', 'display-post-types' ) );
				$this->select( 'text_align', $text_align, $instance['text_align'] );
				?>
			</p>
			<p class="hgutter">
				<?php $this->label( 'h_gutter', esc_html__( 'Horizontal Gutter (in px)', 'display-post-types' ) ); ?>
				<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'h_gutter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'h_gutter' ) ); ?>" type="number" step="1" min="0" value="<?php echo absint( $instance['h_gutter'] ); ?>" size="3" />
			</p>
			<p class="vgutter">
				<?php $this->label( 'v_gutter', esc_html__( 'Vertical Gutter (in px)', 'display-post-types' ) ); ?>
				<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'v_gutter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'v_gutter' ) ); ?>" type="number" step="1" min="0" value="<?php echo absint( $instance['v_gutter'] ); ?>" size="3" />
			</p>
			<p class="brradius">
				<?php $this->label( 'br_radius', esc_html__( 'Border Radius (in px)', 'display-post-types' ) ); ?>
				<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'br_radius' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'br_radius' ) ); ?>" type="number" step="1" min="0" value="<?php echo absint( $instance['br_radius'] ); ?>" size="3" />
			</p>
			<p class="plholder">
				<?php $this->label( 'pl_holder', esc_html__( 'Thumbnail Placeholder', 'display-post-types' ) ); ?>
				<input id="<?php echo esc_attr( $this->get_field_id( 'pl_holder' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pl_holder' ) ); ?>" type="checkbox" value="yes" <?php checked( $instance['pl_holder'], 'yes' ); ?> />
			</p>
		</div>

		<?php
		do_action( 'dpt_widget_extend', $this, $instance );
	}

	/**
	 * Handles updating the settings for the current widget instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance          = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		$valid_post_types      = array_keys( dpt_get_post_types() );
		$instance['post_type'] = in_array( $new_instance['post_type'], $valid_post_types, true ) ? $new_instance['post_type'] : '';

		if ( 'page' === $instance['post_type'] ) {
			// Get list of all pages.
			$pages       = get_pages( array( 'exclude' => get_option( 'page_for_posts' ) ) );
			$valid_pages = wp_list_pluck( $pages, 'ID' );

			$instance['pages']    = array_intersect( $new_instance['pages'], $valid_pages );
			$instance['taxonomy'] = array();
		} else {
			$instance['pages'] = array();
		}

		if ( $instance['post_type'] && 'page' !== $instance['post_type'] && $new_instance['post_ids'] ) {
			$post_ids             = array_map( 'absint', explode( ',', $new_instance['post_ids'] ) );
			$instance['post_ids'] = implode( ',', $post_ids );
		} else {
			$instance['post_ids'] = '';
		}

		if ( $instance['post_type'] && 'page' !== $instance['post_type'] && $new_instance['taxonomy'] ) {
			// Get list of all taxonomies for a post type.
			$taxonomies = get_object_taxonomies( $instance['post_type'], 'objects' );
			$taxonomies = wp_list_pluck( $taxonomies, 'label', 'name' );

			$instance['taxonomy'] = array_key_exists( $new_instance['taxonomy'], $taxonomies ) ? $new_instance['taxonomy'] : '';
		} else {
			$instance['taxonomy'] = '';
		}

		if ( $instance['taxonomy'] && $new_instance['terms'] ) {
			// Get list of all terms.
			$terms       = get_terms( array( 'taxonomy' => $instance['taxonomy'] ) );
			$terms       = wp_list_pluck( $terms, 'name', 'slug' );
			$valid_terms = array_keys( $terms );

			$instance['terms'] = array_intersect( $new_instance['terms'], $valid_terms );
		} else {
			$instance['terms'] = array();
		}

		$instance['relation'] = ( 'IN' === $new_instance['relation'] ) ? 'IN' : 'AND';
		$instance['number']   = absint( $new_instance['number'] );
		$instance['offset']   = absint( $new_instance['offset'] );
		$instance['orderby']  = ( array_key_exists( $new_instance['orderby'], $this->orderby ) ) ? $new_instance['orderby'] : 'date';

		$instance['order'] = ( 'DESC' === $new_instance['order'] ) ? 'DESC' : 'ASC';

		$instance['image_crop'] = ( array_key_exists( $new_instance['image_crop'], $this->imagecrop ) ) ? $new_instance['image_crop'] : 'centercrop';

		$instance['img_aspect'] = ( array_key_exists( $new_instance['img_aspect'], $this->aspectratio ) ) ? $new_instance['img_aspect'] : 'land1';

		$instance['img_align']  = ( 'right' === $new_instance['img_align'] ) ? 'right' : '';
		$instance['text_align'] = sanitize_text_field( $new_instance['text_align'] );

		$instance['e_length'] = absint( $new_instance['e_length'] );
		$instance['v_gutter'] = absint( $new_instance['v_gutter'] );
		$instance['h_gutter'] = absint( $new_instance['h_gutter'] );
		$instance['e_teaser'] = sanitize_text_field( $new_instance['e_teaser'] );

		$instance['br_radius'] = absint( $new_instance['br_radius'] );
		$instance['col_narr']  = absint( $new_instance['col_narr'] );
		$instance['autotime']  = absint( $new_instance['autotime'] );

		$valid_styles       = $this->get_display_styles();
		$instance['styles'] = array_key_exists( $new_instance['styles'], $valid_styles ) ? $new_instance['styles'] : '';

		if ( $instance['styles'] ) {
			$valid_style_sup       = array_keys( $this->contents );
			$instance['style_sup'] = array_intersect( $new_instance['style_sup'], $valid_style_sup );
		} else {
			$instance['style_sup'] = array();
		}

		$instance['pl_holder'] = 'yes' === $new_instance['pl_holder'] ? 'yes' : '';

		return $instance;
	}

	/**
	 * Prints a checkbox list of all pages.
	 *
	 * @param array $selected_pages Checked pages.
	 * @return void
	 */
	public function pages_checklist( $selected_pages ) {

		// Get list of all pages.
		$pages = get_pages( array( 'exclude' => get_option( 'page_for_posts' ) ) );
		$pages = wp_list_pluck( $pages, 'post_title', 'ID' );

		$this->label( 'pages', esc_html__( 'Select Pages', 'display-post-types' ) );
		$this->mu_checkbox( 'pages', $pages, $selected_pages );
	}

	/**
	 * Prints a checkbox list of all terms for a taxonomy.
	 *
	 * @param str   $taxonomy       Selected Taxonomy.
	 * @param array $selected_terms Selected Terms.
	 * @return void
	 */
	public function terms_checklist( $taxonomy, $selected_terms = array() ) {

		// Get list of all registered terms.
		$terms = get_terms();

		// Get 'checkbox' options as value => label.
		$options = wp_list_pluck( $terms, 'name', 'slug' );

		// Get HTML classes for checkbox options.
		$classes = wp_list_pluck( $terms, 'taxonomy', 'slug' );
		if ( $taxonomy ) {
			foreach ( $classes as $slug => $taxon ) {
				if ( $taxonomy !== $taxon ) {
					$classes[ $slug ] .= ' dpt-hidden';
				}
			}
		}

		// Terms Checkbox markup.
		$this->label( 'terms', esc_html__( 'Select Terms', 'display-post-types' ) );
		$this->mu_checkbox( 'terms', $options, $selected_terms, $classes );
	}

	/**
	 * Prints a checkbox list of all supported content for a style.
	 *
	 * @param str   $style    Selected Style.
	 * @param array $selected Selected content.
	 * @return void
	 */
	public function supported_checklist( $style, $selected = array() ) {

		$classes = array();

		foreach ( $this->contents as $slug => $label ) {
			foreach ( $this->style_supported as $s => $supported ) {
				if ( in_array( $slug, $supported, true ) ) {
					$classes[ $slug ][] = $s;
				} elseif ( $s === $style ) {
					$classes[ $slug ][] = 'dpt-hidden';
				}
			}
			if ( 'category' === $slug ) {
				$classes[ $slug ][] = 'post-only';
			}
			$classes[ $slug ] = join( ' ', array_unique( $classes[ $slug ] ) );
		}

		// Terms Checkbox markup.
		$this->label( 'style_sup', esc_html__( 'Items supported by display style', 'display-post-types' ) );
		$this->mu_checkbox( 'style_sup', $this->contents, $selected, $classes );
	}

	/**
	 * Prints select list of all taxonomies for a post type.
	 *
	 * @param str   $post_type Selected post type.
	 * @param array $selected  Selected taxonomy in widget form.
	 * @return void
	 */
	public function taxonomies_select( $post_type, $selected = array() ) {

		$options = dpt_get_taxonomies();

		// Get HTML classes for select options.
		$taxonomies = get_taxonomies( array(), 'objects' );
		$classes    = wp_list_pluck( $taxonomies, 'object_type', 'name' );
		if ( $post_type && 'page' !== $post_type ) {
			foreach ( $classes as $name => $type ) {
				$type = (array) $type;
				if ( ! in_array( $post_type, $type, true ) ) {
					$type[]           = 'dpt-hidden';
					$classes[ $name ] = $type;
				}
			}
		}
		$classes[''] = 'always-visible';

		// Taxonomy Select markup.
		$this->label( 'taxonomy', esc_html__( 'Get items by Taxonomy', 'display-post-types' ) );
		$this->select( 'taxonomy', $options, $selected, $classes );
	}

	/**
	 * Markup for 'label' for widget input options.
	 *
	 * @param str  $for  Label for which ID.
	 * @param str  $text Label text.
	 * @param bool $echo Display or Return.
	 * @return void|string
	 */
	public function label( $for, $text, $echo = true ) {
		$label = sprintf( '<label for="%s">%s:</label>', esc_attr( $this->get_field_id( $for ) ), esc_html( $text ) );
		if ( $echo ) {
			echo $label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $label;
		}
	}

	/**
	 * Markup for Select dropdown lists for widget options.
	 *
	 * @param str   $for      Select for which ID.
	 * @param array $options  Select options as 'value => label' pair.
	 * @param str   $selected selected option.
	 * @param array $classes  Options HTML classes.
	 * @param bool  $echo     Display or return.
	 * @return void|string
	 */
	public function select( $for, $options, $selected, $classes = array(), $echo = true ) {
		$select      = '';
		$final_class = '';
		foreach ( $options as $value => $label ) {
			if ( isset( $classes[ $value ] ) ) {
				$option_classes = (array) $classes[ $value ];
				$option_classes = array_map( 'esc_attr', $option_classes );
				$final_class    = 'class="' . join( ' ', $option_classes ) . '"';
			}
			$select .= sprintf( '<option value="%1$s" %2$s %3$s>%4$s</option>', esc_attr( $value ), $final_class, selected( $value, $selected, false ), esc_html( $label ) );
		}

		$select = sprintf(
			'<select id="%1$s" name="%2$s" class="dpt-%3$s widefat">%4$s</select>',
			esc_attr( $this->get_field_id( $for ) ),
			esc_attr( $this->get_field_name( $for ) ),
			esc_attr( str_replace( '_', '-', $for ) ),
			$select
		);

		if ( $echo ) {
			echo $select; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $select;
		}
	}

	/**
	 * Markup for multiple checkbox for widget options.
	 *
	 * @param str   $for      Select for which ID.
	 * @param array $options  Select options as 'value => label' pair.
	 * @param str   $selected selected option.
	 * @param array $classes  Checkbox input HTML classes.
	 * @param bool  $echo     Display or return.
	 * @return void|string
	 */
	public function mu_checkbox( $for, $options, $selected, $classes = array(), $echo = true ) {

		$final_class = '';

		$mu_checkbox = '<div class="' . esc_attr( $for ) . '-checklist"><ul id="' . esc_attr( $this->get_field_id( $for ) ) . '">';

		$selected    = array_map( 'strval', $selected );
		$rev_options = $options;

		// Moving selected items on top of the array.
		foreach ( $options as $id => $label ) {
			if ( in_array( strval( $id ), $selected, true ) ) {
				$rev_options = array( $id => $label ) + $rev_options;
			}
		}

		foreach ( $rev_options as $id => $label ) {
			if ( isset( $classes[ $id ] ) ) {
				$final_class = ' class="' . esc_attr( $classes[ $id ] ) . '"';
			}
			$mu_checkbox .= "\n<li$final_class>" . '<label class="selectit"><input value="' . esc_attr( $id ) . '" type="checkbox" name="' . esc_attr( $this->get_field_name( $for ) ) . '[]"' . checked( in_array( strval( $id ), $selected, true ), true, false ) . ' /> ' . esc_html( $label ) . "</label></li>\n";
		}
		$mu_checkbox .= "</ul></div>\n";

		if ( $echo ) {
			echo $mu_checkbox; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $mu_checkbox;
		}
	}

	/**
	 * Get display styles.
	 *
	 * @return array
	 */
	public function get_display_styles() {
		if ( ! empty( $this->styles ) ) {
			return $this->styles;
		}

		$styles = apply_filters( 'dpt_styles', array() );
		foreach ( $styles as $style => $args ) {
			$this->styles[ $style ]          = $args['label'];
			$this->style_supported[ $style ] = $args['support'];
		}

		return $this->styles;
	}

	/**
	 * Check if item is supported by the style.
	 *
	 * @param string $style Current display style.
	 * @param string $item  item to be checked for support.
	 * @return bool
	 */
	public function is_style_support( $style, $item ) {
		if ( ! $style ) {
			return false;
		}

		$sup_arr = $this->style_supported[ $style ];
		return in_array( $item, $sup_arr, true );
	}
}
