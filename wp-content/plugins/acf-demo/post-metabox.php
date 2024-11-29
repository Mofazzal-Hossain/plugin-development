<?php
/*
 * Plugin Name:       ACF Demo
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a Post Metabox plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       acf-demo
 * Domain Path:       /languages
 */

//  define
define('PTMB_DIR', plugin_dir_url(__FILE__));
define('PTMB_DIR_PATH', plugin_dir_path(__FILE__));
define('PTMB_PUBLIC_DIR', PTMB_DIR . 'public');
define('PTMB_ADMIN_DIR', PTMB_DIR . 'admin');

// file include

require_once(PTMB_DIR_PATH . '/libs/class-tgm-plugin-activation.php');
require_once(PTMB_DIR_PATH . '/includes/acf-metabox.php');

// text domain
function acfd_plugin_load()
{
    load_plugin_textdomain('acf-demo', false, plugin_dir_url(__FILE__) . '/languages');
}
add_action('plugins_loaded', 'acfd_plugin_load');


// TGM plugin installer
function acfd_tgm_plugin_activation()
{
    $plugins = array(
        array(
            'name'        => 'Advanced Custom Fields Pro',
            'slug'        => 'advanced-custom-fields-pro',
            'source'      => plugin_dir_path(__FILE__) . '/plugin/advanced-custom-fields-pro.zip',
            'required'    => true,
        ),
    );
    $config = array(
        'id'           => 'acf-demo',
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins',
        'parent_slug'  => 'plugins.php',
        'capability'   => 'manage_options',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => true,
        'message'      => '',
        'strings'      => array(
            'notice_can_install_required' => _n_noop(
                'ACF Demo plugin requires the following plugin: %1$s.',
                'ACF Demo plugin requires the following plugins: %1$s.',
                'acf-demo'
            ),
        ),

    );

    tgmpa($plugins, $config);
}
add_action('tgmpa_register', 'acfd_tgm_plugin_activation');


add_filter('acf/settings/show_admin', '__return_false');