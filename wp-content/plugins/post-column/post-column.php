<?php

/*
 * Plugin Name:       Post Column
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a Assets Ninja plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       post-column
 * Domain Path:       /languages
*/
define('PLUGIN_DIR', plugin_dir_url(__FILE__));
define('PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
function ptcol_plugin_loaded()
{
    load_plugin_textdomain('post-column', false, PLUGIN_DIR . '/languages');
}
add_action('plugin_loaded', 'ptcol_plugin_loaded');

include_once PLUGIN_DIR_PATH .'/includes/ptcol-default-post-columns.php';
include_once PLUGIN_DIR_PATH .'/includes/ptcol-book-post-columns.php';



