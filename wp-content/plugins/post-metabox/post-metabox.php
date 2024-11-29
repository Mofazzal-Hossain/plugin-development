<?php
/*
 * Plugin Name:       Post Metabox
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a Post Metabox plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       post-metabox
 * Domain Path:       /languages
 */

//  define
define('PTMB_DIR', plugin_dir_url(__FILE__));
define('PTMB_DIR_PATH', plugin_dir_path(__FILE__));
define('PTMB_PUBLIC_DIR', PTMB_DIR . 'public');
define('PTMB_ADMIN_DIR', PTMB_DIR . 'admin');

// file include
include PTMB_DIR_PATH . '/includes/ptmb-category-metabox.php';
include PTMB_DIR_PATH . '/includes/ptmb-user-field-meta.php';
include PTMB_DIR_PATH . '/includes/ptmb-meta-fields.php';

class postMetabox
{
    private $version;
    // construct function
    function __construct()
    {
        $this->version = time();
        add_action('plugin_loaded', array($this, 'ptmb_loaded'));
        add_action('wp_enqueue_scripts', array($this, 'ptmb_public_assets'));
        add_action('admin_enqueue_scripts', array($this, 'ptmb_admin_assets'));
    }
  
    // post metabox plugin loaded
    function ptmb_loaded(){
        load_plugin_textdomain('metabox', false, PTMB_DIR . '/languages');
    }

    // post metabox public assets
    function ptmb_public_assets(){

        // public css files in array
        $public_css_files = array(
            'ptmb-public-custom-css' => array('path' => PTMB_PUBLIC_DIR . '/css/public-custom.css'),
        );

        // loop enqueue public css files
        foreach ($public_css_files as $handle => $public_css) {
            wp_enqueue_style($handle, $public_css['path'], null, $this->version);
        }

        // public js files in array
        $public_js_files = array(
            'ptmb-public-main-js' => array('path' => PTMB_PUBLIC_DIR . '/js/public-main.js', 'dep' => 'jquery'),
        );

        // loop enqueue public js files
        foreach ($public_js_files as $handle => $public_js) {
            wp_enqueue_script($handle, $public_js['path'], $public_js['dep'], $this->version, true);
        }
    }

    // post metabox admin assets
    function ptmb_admin_assets(){

        // admin css files in array
        $admin_css_files = array(
            'ptmb-admin-bootstrap-css' => array('path' => '//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css'),
            'ptmb-admin-ui-css' => array('path' => '//code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css'),
            'ptmb-admin-select2-css' => array('path' => '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'),
            'ptmb-admin-custom-css' => array('path' => PTMB_ADMIN_DIR . '/css/admin-custom.css'),
        );
        // loop enqueue admin css files
        foreach ($admin_css_files as $handle => $admin_css) {
            wp_enqueue_style($handle, $admin_css['path'], null, $this->version);
        }

        // admin js files in array
        $admin_js_files = array(
            'ptmb-admin-bootstrap-js' => array('path' => '//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', 'dep' => null),
            'ptmb-admin-select2-js' => array('path' => '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', 'dep' => null),
            'ptmb-admin-main-js' => array('path' => PTMB_ADMIN_DIR . '/js/admin-main.js', 'dep' => array('jquery', 'jquery-ui-datepicker')),
        );

        // loop enqueue admin js files
        foreach ($admin_js_files as $handle => $admin_js) {
            wp_enqueue_script($handle, $admin_js['path'], $admin_js['dep'], $this->version, true);
        }
    }
}

new postMetabox();
