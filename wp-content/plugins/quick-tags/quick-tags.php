<?php
/*
 * Plugin Name:       Quick Tags
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a classic editor quick tags plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       quicktags
 * Domain Path:       /languages
*/

define('VERSION', time());


function quicktags_plugin_loaded()
{
    load_plugin_textdomain('quicktags', false, plugin_dir_url(__FILE__) . '/languages');
}
add_action('plugin_loaded', 'quicktags_plugin_loaded');

function quicktags_admin_assets($screen)
{
    if ('post.php' == $screen || 'post-new.php' == $screen) {
        wp_enqueue_style('quicktags-bootstrap-css', '//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', null, '5.0.2');
        wp_enqueue_style('quicktags-fontawesome-css', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css', null, '6.5.2');
        wp_enqueue_script('quicktags-bootstrap-js', '//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array(), '5.0.2', true);
        wp_enqueue_script('quicktags-phonetic-bangla-js', plugin_dir_url(__FILE__) . 'admin/js/phonetic-bangla.js', array(), VERSION, true);
        wp_enqueue_script('quicktags-engine-js', plugin_dir_url(__FILE__) . 'admin/js/engine.js', array('jquery'), VERSION, true);
        wp_enqueue_script('quicktags-main-js', plugin_dir_url(__FILE__) . 'admin/js/admin-main.js', array('jquery', 'quicktags', 'thickbox'), VERSION, true);
        
        wp_localize_script('quicktags-main-js', 'customicons', array('preview' => plugin_dir_url(__FILE__) . 'includes/quicktags-fontawesome.php'));
    }
}
add_action('admin_enqueue_scripts', 'quicktags_admin_assets');

function quicktags_public_assets() {
    wp_enqueue_style('quicktags-fontawesome-css', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css', null, '6.5.2');
}
add_action('wp_enqueue_scripts', 'quicktags_public_assets');