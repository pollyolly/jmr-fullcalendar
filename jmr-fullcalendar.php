<?php 
/**
 * @package JMR FullCalendar
 * @version 1.0
 */
/*
Plugin Name: JMR Full Calendar
Plugin URI: 
Description: Integrated with google Calendar. To use -> [fcalendar_shortcode]
Author: John Mark
Version: 1.0
Author URI: 
*/
function fullcalendar_style_js(){
    wp_register_style('jmr_cal_css', plugins_url('/assets/style.css', __FILE__));
    wp_register_style('jmr_cal_css1', plugins_url('/assets/fullcalendar/jquery-ui.theme.min.css', __FILE__));
    wp_register_style('jmr_cal_css2', plugins_url('/assets/fullcalendar/fullcalendar.min.css', __FILE__));
    wp_register_style('jmr_cal_css3', plugins_url('/assets/fullcalendar/fullcalendar.print.min.css', __FILE__));
   
    wp_register_script('jmr_cal_js1', plugins_url('/assets/moment.min.js', __FILE__), array('jquery'), 1.0, true);
    wp_register_script('jmr_cal_js2', plugins_url('/assets/fullcalendar/fullcalendar.min.js', __FILE__), array('jquery'), 1.0, true);
    wp_register_script('jmr_cal_js3', plugins_url('/assets/fullcalendar/gcal.min.js', __FILE__), array('jquery'), 1.0, true);
    wp_register_script('jmr_cal_js4', plugins_url('/assets/script.js', __FILE__), array('jquery'),'', true);
   $creadentials = array(
       'api_key' => esc_attr( get_option('apikey') ),
       'gcalendarId' => esc_attr( get_option('googlecalendarid') )
   );
   wp_localize_script('jmr_cal_js4', 'fullcalendar', $creadentials);
   if(!is_admin()){
    wp_enqueue_style('jmr_cal_css');
    wp_enqueue_style('jmr_cal_css1');
    wp_enqueue_style('jmr_cal_css2');
    wp_enqueue_style('jmr_cal_css3');
    wp_enqueue_script('jmr_cal_js1');
    wp_enqueue_script('jmr_cal_js2');
    wp_enqueue_script('jmr_cal_js3');
    wp_enqueue_script('jmr_cal_js4');
   }
}
add_action('wp_enqueue_scripts', 'fullcalendar_style_js');

function fullcalendar_shortcode($atts, $content = null) {
	// $atts = shortcode_atts( array(), $atts, 'fcalendar_shortcode'
	// );
    ob_start();
        echo '<div class="jmrFullcalendar"><div class="jmr-fullcalendar" id="fcalLoading">loading...</div>';
        echo '<div class="jmr-fullcalendar" id="fClendar"></div></div>';
	return ob_get_clean();
}
add_shortcode('fcalendar_shortcode','fullcalendar_shortcode');

function fullcalendar_post_type(){
	$labels = array(
		'name' 				=> 'Event List',
		'singular_name' 	=> 'Event List',
		'menu_name' 		=> 'Event List',
		'name_admin_bar' 	=> 'Event List'
	);
	$args = array(
		'labels' 			=> $labels,
		'show_ui' 			=> true,
		'show_in_menu'		=> true,
		'capability_type'	=> 'post',
		'hierarchical'		=> false,
		'menu_position'		=> 30,
		'menu_icon'			=> 'dashicons-info',
		'supports'			=> array('title','editor')
	);
	register_post_type('event-calendar', $args);
}
add_action('init', 'fullcalendar_post_type');

function fullcalendar_postype_column( $columns ){
    $arrcolumn = array(
        'cb' => '<input type="checkbox"/>',
        'title'=>'Event Title',
        'datetime' => 'Date/Time',
        'description' => 'Description',
        'date' => 'Date' );
    return $arrcolumn;
}
add_filter('manage_event-calendar_posts_columns', 'fullcalendar_postype_column');

function fullcalendar_postype_column_name( $column, $post_id ){
    switch( $column ){
        case 'description':
            echo get_the_excerpt();
         break;
        case 'datetime':
            echo 'setdate';
        break;
    }
}
add_action('manage_event-calendar_posts_custom_column','fullcalendar_postype_column_name', 1, 2);

/* Add submenu page Settings */
function fullcalendar_settings_subpage(){
	add_submenu_page(
		'edit.php?post_type=event-calendar',
		'Settings',
		'Settings',
		'manage_options',
		'fullcalendar_settings_page',
		'fullcalendar_settings_form_callback');
		add_action( 'admin_init', 'fullcalendar_settings');
}
add_action( 'admin_menu', 'fullcalendar_settings_subpage' );

function fullcalendar_settings(){
    register_setting( 'fullcalendar_settings_group', 'apikey');
    register_setting( 'fullcalendar_settings_group', 'googlecalendarid');
    
    add_settings_section( 'fullcalendar_setting_options_section', 'Full Calendar Settings', 'fullcalendar_option_callback', 'fullcalendar_settings_page');

    add_settings_field( 'calendar-apikey', 'API Key', 'apikey_field_callback', 'fullcalendar_settings_page', 'fullcalendar_setting_options_section');
    add_settings_field( 'calendar-googlecalendarid', 'Google Calendar ID', 'googlecalendarid_field_callback', 'fullcalendar_settings_page', 'fullcalendar_setting_options_section');
}
function fullcalendar_option_callback(){
    echo "";
}
function apikey_field_callback(){
	echo '<input type="text" name="apikey" value="'. esc_attr( get_option('apikey') ).'" placeholder="API Key" required style="width: 500px;"/>';
}
function googlecalendarid_field_callback(){
	echo '<input type="text" name="googlecalendarid" value="'. esc_attr( get_option('googlecalendarid') ).'" placeholder="Google Calendar ID" required style="width: 500px;"/>';
}
function fullcalendar_settings_form_callback(){
    include_once (plugin_dir_path(__FILE__)  . 'form/setting-form.php');
}