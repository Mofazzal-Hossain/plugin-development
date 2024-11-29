<?php
/*
 * Plugin Name:       Redux Demo
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a classic editor Redux Demo plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       reduxdemo
 * Domain Path:       /languages
*/

require_once plugin_dir_path(__FILE__) . 'installer.php';
require_once plugin_dir_path(__FILE__) . 'includes/redux-option-panel.php';
require_once plugin_dir_path(__FILE__) . 'includes/redux-post-metabox.php';

function get_redux_value($option, $key, $default)
{
    if (class_exists('Redux')) {
        return Redux::get_option($option, $key, $default);
    }
}


function rdxdm_admin_menu()
{
    add_menu_page(
        __('Redux Demo', 'reduxdemo'),
        __('Redux Demo', 'reduxdemo'),
        'manage_options',
        'redux-demo',
        'rdxdm_page_content_diplay',
        '',
        50
    );
}
add_action('admin_menu', 'rdxdm_admin_menu');


function rdxdm_page_content_diplay()
{
    echo get_redux_value('rdx_custom_demo', 'opt-text', "text");
    echo ': ' . get_redux_value('rdx_custom_demo', 'redux_date', "text");
    echo '<br>';
    echo get_redux_value('rdx_custom_demo', 'opt-text2', "text");
    echo ': ' . get_redux_value('rdx_custom_demo', 'redux_date2', "text");
}
