<?php

/*
 * Plugin Name:       Plugin Options
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a plugin options setings plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       plugin-options
 * Domain Path:       /languages
*/

require_once plugin_dir_path(__FILE__) . '/includes/plugin-options-setting-page.php';
require_once plugin_dir_path(__FILE__) . '/includes/admin-menu-options-setting-page.php';

function plnopt_plugin_loaded()
{
    load_plugin_textdomain('post-column', false, plugin_dir_url(__FILE__) . '/languages');
}
add_action('plugin_loaded', 'plnopt_plugin_loaded');

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'plgopt_action_links');


function plgopt_action_links($links)
{
    $newlink = sprintf('<a href="%s">%s</a>', 'options-general.php?page=pluginoptions', __('Settings', 'plugin-options'));
    $links[] = $newlink;
    return $links;
}
