<?php
require_once plugin_dir_path(__FILE__) . 'libs/class-tgm-plugin-activation.php';
// TGM plugin installer
function acfd_tgm_plugin_activation()
{
    $plugins = array(
        array(
            'name'        => 'Redux Framework',
            'slug'        => 'redux-framework',
            'require'     => true

        ),
    );
    $config = array(
        'id'           => 'plugin-development',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug'  => 'themes.php',            // Parent menu slug.
        'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',

    );
    tgmpa($plugins, $config);
}
add_action('tgmpa_register', 'acfd_tgm_plugin_activation');
