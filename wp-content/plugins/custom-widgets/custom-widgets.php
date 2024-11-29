<?php
/*
 * Plugin Name:       Custom Widgets
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a Custom Widgets plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       custom-widgets
 * Domain Path:       /languages
 */

 // text domain
function cstwid_plugin_load()
{
    load_plugin_textdomain('custom-widgets', false, plugin_dir_url(__FILE__) . '/languages');
}
add_action('plugins_loaded', 'cstwid_plugin_load');



// register sidebar
function cstwid_register_widgets() {
    register_widget('CustomWidgets');
    register_widget('MapWidget');
    register_widget('AdvertisementWidget');
    register_widget('Demowidget_Widget');
}
add_action('widgets_init', 'cstwid_register_widgets');

// files include
require_once plugin_dir_path(__FILE__) . 'widgets/class-custom-widget.php';
require_once plugin_dir_path(__FILE__) . 'widgets/class-map-widget.php';
require_once plugin_dir_path(__FILE__) . 'widgets/class-advertisement-widget.php';
require_once plugin_dir_path(__FILE__) . 'widgets/class-hasty-book-widget.php';

function cstwid_admin_assets(){
    wp_enqueue_script('cstwid-gallery-media-js', plugin_dir_url(__FILE__) . 'admin/js/gallery-media.js', array('jquery'), time(), true);
}
add_action('admin_enqueue_scripts', 'cstwid_admin_assets');