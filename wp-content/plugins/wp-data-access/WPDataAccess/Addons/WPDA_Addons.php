<?php

/**
 * Suppress "error - 0 - No summary was found for this file" on phpdoc generation
 *
 * @package WPDataAccess\Addons
 */

namespace WPDataAccess\Addons {

	class WPDA_Addons {

		protected $addons = [];

		protected $is_installed_full_text_search = false;
		protected $is_installed_inline_editing   = false;
		protected $is_installed_code_manager     = false;
		protected $is_installed_style_manager    = false;
		protected $is_installed_report_builder   = false;
		protected $is_installed_grid_editor      = false;

		public function __construct() {
			$this->is_installed_full_text_search = class_exists( 'WPDAPRO_Full_Text_Search\WPDAPRO_Search' );
			$this->is_installed_inline_editing   = class_exists( 'WPDAPRO_Inline_Editing\WPDAPRO_Inline_Editing' );
			$this->is_installed_code_manager     = class_exists( 'WPDAPRO_Code_Manager\WPDAPRO_Code_Manager' );
			$this->is_installed_style_manager    = class_exists( 'WPDAPRO_Style_Manager\WPDAPRO_Style_Manager' );
			$this->is_installed_report_builder   = class_exists( 'WPDAPRO_Reports\WPDAPRO_Reports_Model' );
			$this->is_installed_grid_editor      = class_exists( 'WPDAPRO_Grid_Editor\WPDAPRO_Grid_Manager' );

			$this->addons = [
				[
					'id'           => 'FS',
					'addon'        => 'Full Text Search',
					'purpose'      => 'Allows to add full text support to MyISAM and InnoDB tables. Easy and flexible configuration from Data Explorer table settings. Support global as well as individual column search and listboxes for individual column search.',
					'installed'    => $this->is_installed_full_text_search ? 'Yes' : 'No',
					'price'        => '&euro; 19',
					'is_installed' => $this->is_installed_full_text_search,
					'pp_url'       => '',
				],
				[
					'id'           => 'IE',
					'addon'        => 'Inline Editing',
					'purpose'      => 'Add inline editing to specific list table columns. Use the Data Explorer table settings for easy configuration or define columns as inline editable from the Code Manager or your own PHP file. Supports text fields, checkboxes and (multi selectable) listtable.',
					'installed'    => $this->is_installed_inline_editing ? 'Yes' : 'No',
					'price'        => '&euro; 29',
					'is_installed' => $this->is_installed_inline_editing,
					'pp_url'       => '',
				],
				[
					'id'           => 'CM',
					'addon'        => 'Code Manager',
					'purpose'      => 'Write and manage WordPress hooks, filters and shortcodes from your WordPress dashboard.',
					'installed'    => $this->is_installed_code_manager ? 'Yes' : 'No',
					'price'        => '&euro; 9',
					'is_installed' => $this->is_installed_code_manager,
					'pp_url'       => '',
				],
				[
					'id'           => 'SM',
					'addon'        => 'Style Manager',
					'purpose'      => 'Helps to style list tables and data entry forms. Works in the back-end (WP_List_Table and WPDA_Simple_Form) and the front-end (jQuery DataTables). Contains pre-configured styles for list tables and data entry forms. Allows custom styling for any back-end and/or front-end page. Supports shortcode usage.',
					'installed'    => $this->is_installed_style_manager ? 'Yes' : 'No',
					'price'        => '&euro; 9',
					'is_installed' => $this->is_installed_style_manager,
					'pp_url'       => '',
				],
				[
					'id'           => 'RB',
					'addon'        => 'Report Builder',
					'purpose'      => 'Easy and flexible report builder. Under construction...',
					'installed'    => $this->is_installed_report_builder ? 'Yes' : 'No',
					'price'        => '&euro; 29',
					'is_installed' => $this->is_installed_report_builder,
					'pp_url'       => '',
				],
				[
					'id'           => 'GE',
					'addon'        => 'Grid Editor',
					'purpose'      => 'Supports spreadsheet like table editing. Under construction...',
					'installed'    => $this->is_installed_grid_editor ? 'Yes' : 'No',
					'price'        => '&euro; 29',
					'is_installed' => $this->is_installed_grid_editor,
					'pp_url'       => '',
				],
			];
		}

		public function show() {
			?>
			<div class="wrap">
				<h1 class="wp-heading-inline">
					<span><?php echo __( 'Addons', 'wp-data-access' ); ?></span>
					<a href="https://wpdataaccess.com/addons/" target="_blank"
					   title="Plugin Help - open a new tab or window">
					<span class="dashicons dashicons-editor-help"
						  style="text-decoration:none;vertical-align:top;font-size:30px;">
					</span></a>
				</h1>
				<br/><br/>
				<table class="wp-list-table widefat striped rows">
					<tr>
						<th></th>
						<th><?php echo __( 'Addon', 'wp-data-access' ); ?></th>
						<th><?php echo __( 'Purpose', 'wp-data-access' ); ?></th>
						<th><?php echo __( 'Installed', 'wp-data-access' ); ?></th>
						<th><?php echo __( 'Price', 'wp-data-access' ); ?></th>
					</tr>
					<?php foreach ( $this->addons as $addon ) {
						if ( $addon['is_installed'] ) {
							$already_installed = 'checked disabled="true"';
						} else {
							$already_installed = '';
						}
						?>
						<tr>
							<th class="check-column"><input type="checkbox" name="<?php echo $addon['id']; ?>" <?php echo $already_installed; ?> /></th>
							<td style="white-space: nowrap;"><?php echo $addon['addon']; ?></td>
							<td><?php echo $addon['purpose']; ?></td>
							<td style="white-space: nowrap; text-align: center;"><?php echo $addon['installed']; ?></td>
							<td style="white-space: nowrap; text-align: right;"><?php echo $addon['price']; ?></td>
						</tr>
						<?php
					}
					?>
				</table>
			</div>
			<?php
		}

	}

}