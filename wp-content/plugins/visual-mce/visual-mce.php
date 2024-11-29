<?php
/*
 * Plugin Name:       Visual MCE
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a visual editor MCE quick tags plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       visual-mce
 * Domain Path:       /languages
*/

// Define a version constant
define('VERSION', '1.0.0');

function visualmce_plugin_loaded()
{
    load_plugin_textdomain('visual-mce', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'visualmce_plugin_loaded');

// admin init
function visualmce_admin_init()
{

    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }
    if (!user_can_richedit()) {
        return;
    }

    add_filter('mce_external_plugins', 'visualmce_mce_plugins');
    add_filter('mce_buttons', 'visualmce_mce_buttons');
}
add_action('admin_head', 'visualmce_admin_init');

function visualmce_mce_buttons($buttons)
{
    array_push($buttons, 'visualmce_custom_button');
    array_push($buttons, 'visualmce_custom_button2');
    array_push($buttons, 'visualmce_select_country');
    array_push($buttons, 'visualmce_menu_list');
    array_push($buttons, 'visualmce_custom_form');
    return $buttons;
}

function visualmce_mce_plugins($plugins)
{
    $plugins['visualmce_plugins'] = plugin_dir_url(__FILE__) . 'admin/js/admin-main.js';
    return $plugins;
}


// admin enqueue scripts
function visualmce_enqueue_admin_scripts()
{
    wp_enqueue_style('visualmce-admin-css', plugin_dir_url(__FILE__) . 'admin/css/admin-style.css', null, VERSION);
    wp_enqueue_script('visualmce-admin-js', plugin_dir_url(__FILE__) . 'admin/js/admin-main.js', array('jquery', 'thickbox'), VERSION, true);

    // wp_localize_script('visualmce-admin-js', 'customform', array('preview'=>plugin_dir_url(__FILE__) . 'includes/visualmce-custom-form.php'));
}
add_action('admin_enqueue_scripts', 'visualmce_enqueue_admin_scripts');
