<?php
/*
 * Plugin Name:       Extend Single Template
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a visual editor MCE quick tags plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       extend-single-template
 * Domain Path:       /languages
*/

// Define a version constant
define('VERSION', '1.0.0');

function exsintemp_plugin_loaded()
{
    load_plugin_textdomain('extend-single-template', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'exsintemp_plugin_loaded');

// file include
require_once plugin_dir_path(__FILE__) . 'includes/recipe-post-type.php';


// extend single template path
function exsintemp_recipe_single_template($file) {
    global $post;
    if('recipe' == $post->post_type){
        $file_path = plugin_dir_path(__FILE__) . 'templates/single-recipe-template.php';
        $file = $file_path;
    }
    return $file;
}
add_filter('single_template', 'exsintemp_recipe_single_template');