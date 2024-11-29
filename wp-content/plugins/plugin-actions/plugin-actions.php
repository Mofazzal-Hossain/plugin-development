<?php
/*
 * Plugin Name:       Plugin Actions
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a plugin actions plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       plugin-actions
 * Domain Path:       /languages
*/

// text domain
function plgact_textdomain()
{
    load_plugin_textdomain('plugin-actions', false, plugin_dir_url(__FILE__) . '/languages');
}

add_action('plugins_loaded', 'plgact_textdomain');


function plgact_add_menu_page()
{
    add_menu_page(
        __('Plugin Actions', 'plugin-actions'),
        __('Plugin Actions', 'plugin-actions'),
        'manage_options',
        'plugin-actions',
        'plugin_actions_content_display'
    );
}
function plugin_actions_content_display()
{
    echo "hello";
}

add_action('admin_menu', 'plgact_add_menu_page');

// redirect plugin page after activated
add_action('activated_plugin', function ($plugin) {
    if (plugin_basename(__FILE__) == $plugin) {
        wp_redirect(admin_url('admin.php?page=plugin-actions'));
        die();
    }
});

// add new plugin action links
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
    // error_log(print_r($links, true));
    $link = sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=plugin-actions'), __('Settings', 'plugin-actions'));
    array_push($links, $link);
    return $links;
});

// add new plugin row meta

add_filter('plugin_row_meta', 'plgact_add_plugin_row_links', 10, 2);
function plgact_add_plugin_row_links($links, $plugin)
{
    if(plugin_basename(__FILE__) == $plugin){
        $link = sprintf('<a href="%s">%s</a>', esc_url('http://www.google.com'), __('Github', 'plugin-actions'));
        array_push($links, $link);
    }
    return $links;
}
