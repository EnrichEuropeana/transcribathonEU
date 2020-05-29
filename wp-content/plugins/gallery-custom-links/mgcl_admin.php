<?php

include "common/admin.php";

class Meow_Gallery_Custom_Links_Admin extends MeowApps_Admin { // extends MeowApps_Admin

	public $core;

	public function __construct( $prefix, $mainfile, $domain ) {
		parent::__construct( $prefix, $mainfile, $domain );
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'app_menu' ) );
			add_filter( 'attachment_fields_to_edit', array( $this, 'attachment_fields_to_edit' ), 10, 2 );
			add_filter( 'attachment_fields_to_save', array( $this, 'apply_filter_attachment_fields_to_save' ), 10 , 2 );
		}
	}

	function common_url( $file ) {
		return trailingslashit( plugin_dir_url( __FILE__ ) ) . 'common/' . $file;
	}

	function attachment_fields_to_edit( $fields, $post ) {
		$fields['gallery_link_url'] = array(
			'label' => __( 'Link URL', 'gallery-custom-links' ),
			'input' => 'text',
			'value' => get_post_meta( $post->ID, '_gallery_link_url', true )
		);
		$target_value = get_post_meta( $post->ID, '_gallery_link_target', true );
		$fields['gallery_link_target'] = array(
			'label' => __( 'Link Target', 'gallery-custom-links' ),
			'input' => 'html',
			'html'  => '
				<select class="widefat" name="attachments[' . $post->ID . '][gallery_link_target]" id="attachments[' . $post->ID . '][gallery_link_target]">
					<option value="_self"' . ( $target_value == '_self' ? ' selected="selected"' : '' ) . '>' .
						__( 'Same page', 'gallery-custom-links' ) .
					'</option>
					<option value="_blank"' . ( $target_value == '_blank' ? ' selected="selected"' : '' ) . '>' .
						__( 'New page', 'gallery-custom-links' ) .
					'</option>
				</select>'
			);
		// XXXX: Custom modification to add "noopener noreferrer" als REL-option, Christoph Letmaier, 14.01.2020
		$rel_value = get_post_meta( $post->ID, '_gallery_link_rel', true );
		$fields['gallery_link_rel'] = array(
			'label' => __( 'Link Rel', 'gallery-custom-links' ),
			'input' => 'text',
			'value' => get_post_meta( $post->ID, '_gallery_link_rel', true )
		);
		// XXXX: Custom code for new aria-label field, Christoph Letmaier, 14.01.2020
		$fields['gallery_link_aria'] = array(
			'label' => __( 'Arial Label', 'gallery-custom-links' ),
			'input' => 'text',
			'value' => get_post_meta( $post->ID, '_gallery_link_aria', true )
		);
		
		return $fields;
	}

	function apply_filter_attachment_fields_to_save( $post, $attachment ) {
		if ( isset( $attachment['gallery_link_url'] ) )
			update_post_meta( $post['ID'], '_gallery_link_url', $attachment['gallery_link_url'] );
		if ( isset( $attachment['gallery_link_target'] ) )
			update_post_meta( $post['ID'], '_gallery_link_target', $attachment['gallery_link_target'] );
		if ( isset( $attachment['gallery_link_rel'] ) )
			update_post_meta( $post['ID'], '_gallery_link_rel', $attachment['gallery_link_rel'] );
		// XXXX: Custom code for saving _gallery_link_aria, Christoph Letmaier, 14.01.2020
		if ( isset( $attachment['gallery_link_aria'] ) )
			update_post_meta( $post['ID'], '_gallery_link_aria', $attachment['gallery_link_aria'] );
		return $post;
	}

	function app_menu() {

		$method = apply_filters( 'mgcl_method', 'media_title' );

		// SUBMENU > Settings
		add_submenu_page( 'meowapps-main-menu', 'Gallery Custom Links', 'Custom Links', 'manage_options',
			'mgcl_settings-menu', array( $this, 'admin_settings' ) );

			// SUBMENU > Settings > Basic Settings
			add_settings_section( 'mgcl_settings', null, null, 'mgcl_settings-menu' );
			add_settings_field( 'mgcl_obmode', "OB Mode", array( $this, 'admin_obmode_callback' ),
				'mgcl_settings-menu', 'mgcl_settings' );
			add_settings_field( 'mgcl_parsing_engine', "Parsing Engine", array( $this, 'admin_parsing_engine_callback' ),
				'mgcl_settings-menu', 'mgcl_settings' );
			add_settings_field( 'mgcl_log', "Logs", array( $this, 'admin_log_callback' ),
				'mgcl_settings-menu', 'mgcl_settings' );

			// SUBMENU > Settings > Button
			$button_enabled = get_option( 'mgcl_button_enabled', false );
			add_settings_section( 'mgcl_settings_button', null, null, 'mgcl_settings_button-menu' );
			add_settings_field( 'mgcl_button_enabled', "Use Buttons", array( $this, 'admin_button_enabled_callback' ),
				'mgcl_settings_button-menu', 'mgcl_settings_button' );
			if ( $button_enabled ) {
				add_settings_field( 'mgcl_button_label', "Label", array( $this, 'admin_button_label_callback' ),
				'mgcl_settings_button-menu', 'mgcl_settings_button' );
				// add_settings_field( 'mgcl_button_style', "Style (CSS)", array( $this, 'admin_map_style_callback' ),
				// 'mgcl_settings_button-menu', 'mgcl_settings_button' );
			}
			

			// SETTINGS
			register_setting( 'mgcl_settings', 'mgcl_obmode' );
			register_setting( 'mgcl_settings', 'mgcl_parsing_engine' );
			register_setting( 'mgcl_settings', 'mgcl_log' );

			register_setting( 'mgcl_settings_button', 'mgcl_button_enabled' );
			register_setting( 'mgcl_settings_button', 'mgcl_button_label' );
			//register_setting( 'mgcl_settings_button', 'mgcl_button_style' );
	}

	function admin_settings() {
		?>
		<div class="wrap">
			<?php echo $this->display_title( "Gallery Custom Links" );  ?>

			<div class="meow-row">
				<div class="meow-box meow-col meow-span_2_of_2">
					<h3>How to use</h3>
					<div class="inside">
						<?php
						printf(
							/* Translators: %s: link to tutorial */
							esc_html__( 'This plugin works out of the box, the default settings are the best for most installs. If your photos are only in the content, then you should switch the OB Mode off, that will make the process much faster. Don\'t hesitate to take a look at the %s.', 'gallery-custom-links' ),
							'<a target="_blank" href="https://meowapps.com/plugin/gallery-custom-links/">' . esc_html__( 'official page', 'gallery-custom-links' ) . '</a>'
						);
						?>
					</div>
				</div>
			</div>


			<div class="meow-row">

					<div class="meow-col meow-span_1_of_2">

						<div class="meow-box">
							<h3>Settings</h3>
							<div class="inside">
								<form method="post" action="options.php">
									<?php settings_fields( 'mgcl_settings' ); ?>
							    <?php do_settings_sections( 'mgcl_settings-menu' ); ?>
							    <?php submit_button(); ?>
								</form>
							</div>
						</div>

						<div class="meow-box">
							<h3>CTA Buttons</h3>
							<div class="inside">
								<p><b>Currently works with the <a href='https://wordpress.org/plugins/meow-gallery/' target='_blank'>Meow Gallery</a> and Gutenberg Galleries</b>. In a future Pro version, support for additional galleries might be added (please contact <a href='https://meowapps.com/contact/' target='_blank'>Meow Apps</a> for this).</p>
								<form method="post" action="options.php">
									<?php settings_fields( 'mgcl_settings_button' ); ?>
							    <?php do_settings_sections( 'mgcl_settings_button-menu' ); ?>
							    <?php submit_button(); ?>
								</form>
							</div>
						</div>

					</div>

			</div>

		</div>
		<?php
	}

	function admin_obmode_callback( $args ) {
		$html = '<input type="checkbox" id="mgcl_obmode" name="mgcl_obmode" value="1" ' .
			checked( 1, get_option( 'mgcl_obmode', true ), false ) . '/>';
		$html .= '<label>' . __( 'Enabled', 'gallery-custom-links' ) . '</label>';
		echo $html;
	}

	function admin_parsing_engine_callback( $args ) {
		$layouts = array(
			//'None' => array( 'name' => __( 'None', 'gallery-custom-links' ), 'desc' => "" ),
			'HtmlDomParser' => array( 'name' => __( 'HtmlDomParser', 'gallery-custom-links' ), 'desc' => "" ),
			'DiDom' => array( 'name' => __( 'DiDom', 'gallery-custom-links' ), 'desc' => "" )
		);
		$html = '';
		$image_size = get_option( 'mgcl_parsing_engine', 'HtmlDomParser' );
		foreach ( $layouts as $key => $arg )
			$html .= '<input type="radio" class="radio" id="mgcl_parsing_engine" name="mgcl_parsing_engine" value="' . $key . '"' .
				checked( $key, $image_size, false ) . ' > ' . $arg['name'] . '<br />';
		echo $html;
	}

	function admin_log_callback( $args ) {
		$html = '<input type="checkbox" id="mgcl_log" name="mgcl_log" value="1" ' .
			checked( 1, get_option( 'mgcl_log', false ), false ) . '/>';
		$html .= '<label>' . __( 'Enabled', 'gallery-custom-links' ) . '</label>';
		echo $html;
	}

	function admin_button_enabled_callback( $args ) {
		$html = '<input type="checkbox" id="mgcl_button_enabled" name="mgcl_button_enabled" value="1" ' .
			checked( 1, get_option( 'mgcl_button_enabled', false ), false ) . '/>';
		$html .= '<label>' . __( 'Enabled', 'gallery-custom-links' ) . '</label>';
		$html .= '<br /><span class="description">A call-to-action button will be used of a clickable image.</label>';
		echo $html;
	}

	function admin_button_label_callback( $args ) {
		$value = get_option( 'mgcl_button_label', "Click here" );
		$html = '<input type="text" id="mgcl_button_label" name="mgcl_button_label" value="' . $value . '" />';
		$html .= '<br /><span class="description">A call-to-action button will be used of a clickable image.</label>';
		echo $html;
	}

}

?>