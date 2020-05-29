<?php
/*
Widget Name: Story-Teaser
Description: Displays the current top transcribers
Author: Me
Author URI: http://example.com
*/

class _TCT_Storyboxes_Widget extends SiteOrigin_Widget {

	function __construct() {
		
		parent::__construct(
			'tct-storyboxes-widget',
			_x('Transcribathon - Story Teaser Boxes', 'storyboxes-widget (backend)','transcribathon'),
			array(
				'description' => _x('Displays chosen stories as boxes similar to the story overview page', 'storyboxes-widget (backend)','transcribathon'),
				'panels_groups' => array('transcribathon'),
				'panels_icon' => 'tct-storyboxes-icon',
				'help'        => ''
			),
			
			plugin_dir_path(__FILE__)
		);
		
	}
	
	
	function modify_form( $instance) {
		$utags = get_terms(array('taxonomy' => 'tct_usertags', 'hide_empty' => false,'orderby'=>'name','order'=>'ASC'));
		$utag_options = array();
		foreach ( $utags  as $utag ) {
			$utag_options[$utag->term_id] = esc_html($utag->name);  
		}
		$ltags = get_terms(array('taxonomy' => 'tct_languages', 'hide_empty' => false,'orderby'=>'name','order'=>'ASC'));
		$ltag_options = array();
		$ltag_options['-'] = '-';
		foreach ( $ltags  as $ltag ) {
			$ltag_options[$ltag->term_id] = esc_html($ltag->name);  
		}
		
		$instance['tct-storyboxes-headline'] = array(
        	'type' => 'text',
			'label' => _x('Headline above the boxes', 'storyboxes-widget (backend)','transcribathon'),
			'default' => '',
			'optional' => true,
    	);
		$instance['tct-storyboxes-storybunch'] = array(
			'type' => 'textarea',
			'label' => _x('EITHER show stories by story-ID', 'storyboxes-widget (backend)','transcribathon'),
			'description' => _x('If you enter story-IDs here - seperated by commas - these will be used.', 'storyboxes-widget (backend)','transcribathon'),
			'default' => '',
			'rows' => 4,
			'optional' => true,
		);
		
		$instance['tct-storyboxes-utags'] = array(
			'type' => 'select',
			'label' => _x('OR select user-tags, the story (or it\'s items) should contain', 'storyboxes-widget (backend)','transcribathon'),
			'description' => _x('You can select multiple entries by holding STRG or Apple-Key while selecting. Attention - selecting more will probably cause more output: it displays all stories which contain at least one of the tags - either themselves or their items', 'storyboxes-widget (backend)','transcribathon'),
			'multiple' => true,
			'options' => $utag_options,
			'optional' => true,
		);
		$instance['tct-storyboxes-ltags'] = array(
			'type' => 'select',
			'label' => _x('Restrict output by language', 'storyboxes-widget (backend)','transcribathon'),
			'description' => _x('You may want to restrict the output by language. To do so, please select a language from the dropdown.', 'storyboxes-widget (backend)','transcribathon'),
			'multiple' => false,
			'options' => $ltag_options,
			'optional' => true,
		);
		
		
		$instance['tct-storyboxes-cols'] = array(
			'type' => 'slider',
			'label' => _x('Number of columns', 'storyboxes-widget (backend)','transcribathon'),
			'description' => _x('Depending on how much space is available for this widget, you might want to adjust the number story-boxes displayed beside each other', 'storyboxes-widget (backend)','transcribathon'),
			'default' => 4,
			'min' => 1,
			'max' => 4,
			'integer' => true
		);
		
		
		return $instance;
	}
	
	
	
	
	function get_template_name($instance) {
        return 'tct-storyboxes-widget-template';
    }
	function get_template_dir($instance) {
    	return '';
	}
    function get_style_name($instance) {
        return 'tct-storyboxes-widget';
    }
	
}

siteorigin_widget_register('tct-storyboxes-widget', __FILE__, '_TCT_Storyboxes_Widget');


?>