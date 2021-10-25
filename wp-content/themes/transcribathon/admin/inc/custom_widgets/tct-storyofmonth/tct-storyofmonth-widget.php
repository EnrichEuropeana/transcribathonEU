<?php
/*
Widget Name: Story-Of-The-Month
Description: Displays the current top transcribers
Author: Me
Author URI: http://example.com
*/

class _TCT_Storyofmonth_Widget extends SiteOrigin_Widget {

	function __construct() {
		
		parent::__construct(
			'tct-storyofmonth-widget',
			_x('Transcribathon - Story Of The Month', 'storyofmonth-widget (backend)','transcribathon'),
			array(
				'description' => _x('Displays chosen stories as boxes similar to the story overview page', 'storyofmonth-widget (backend)','transcribathon'),
				'panels_groups' => array('transcribathon'),
				'panels_icon' => 'tct-storyboxes-icon',
				'help'        => ''
			),
			
			plugin_dir_path(__FILE__)
		);
		
	}
	
	
	function modify_form( $instance) {
		$url = home_url()."/tp-api/datasets";
		$requestType = "GET";
		include dirname(__FILE__)."/../../custom_scripts/send_api_request.php";
		$datasets = json_decode($result, true);
		foreach ( $datasets  as $dataset ) {
			$dataset_options[$dataset['DatasetId']] = esc_html($dataset['Name']);  
		}

		$url = home_url()."/tp-api/storyPropertyLists/`dc:language`";
		$requestType = "GET";
		include dirname(__FILE__)."/../../custom_scripts/send_api_request.php";
		$languages = json_decode($result, true);

		foreach ( $languages  as $language ) {
			$language_options[$language['Name']] = esc_html($language['Name']);  
		}
		
		// $instance['tct-storyofmonth-headline'] = array(
        // 	'type' => 'text',
		// 	'label' => _x('Headline above the boxes', 'storyofmonth-widget (backend)','transcribathon'),
		// 	'default' => '',
		// 	'optional' => true,
		// );
		$instance['tct-storyofmonth-itemid'] = array(
			'type' => 'number',
			'label' => _x('Get image by entering an item-ID', 'storyofmonth-widget (backend)','transcribathon'),
			'description' => _x('Enter only one item ID - Image will be created.', 'storyofmonth-widget (backend)','transcribathon'),
			'default' => '',
			'rows' => 1,
			'optional' => false,
		);
		
		$instance['tct-storyofmonth-storybunch'] = array(
			'type' => 'number',
			'label' => _x('Get story Title by entering a story-ID', 'storyofmonth-widget (backend)','transcribathon'),
			'description' => _x('Enter only one story ID - Title will be created.', 'storyofmonth-widget (backend)','transcribathon'),
			'default' => '',
			'rows' => 1,
			'optional' => false,
		);
		
		$instance['tct-storyofmonth-subline'] = array(
				'type' => 'textarea',
				'label' => _x('Subline', 'subline-widget (backend)','transcribathon'),
				'default' => '',
				'rows' => 1
		);
		$instance['tct-storyofmonth-description'] = array(
				'type' => 'textarea',
				'label' => _x('Description', 'description-widget (backend)','transcribathon'),
				'default' => '',
				'rows' => 3
		);
		$instance['tct-storyofmonth-lng'] = array(
			'type' => 'text',
			'label' => _x('Language', 'Month-widget (backend)','transcribathon'),
			'default' => '',
			'rows' => 1
		);
		$instance['tct-storyofmonth-month'] = array(
			'type' => 'text',
			'label' => _x('Month', 'Month-widget (backend)','transcribathon'),
			'default' => '',
			'rows' => 1
	);
		
		
		return $instance;
	}
	
	
	
	
	function get_template_name($instance) {
        return 'tct-storyofmonth-widget-template';
    }
	function get_template_dir($instance) {
    	return '';
	}
    function get_style_name($instance) {
        return 'tct-storyofmonth-widget';
    }
	
}

siteorigin_widget_register('tct-storyofmonth-widget', __FILE__, '_TCT_Storyofmonth_Widget');


?>