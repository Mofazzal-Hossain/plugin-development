<?php
/*
 * Plugin Name:       Admin Notice Ninja
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a admin notification ninja plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       admin-notice-ninja
 * Domain Path:       /languages
*/

// text domain
function ann_textdomain()
{
    load_plugin_textdomain('admin-notice-ninja', false, plugin_dir_url(__FILE__) . '/languages');
}

add_action('plugins_loaded', 'ann_textdomain');

function ann_activation_hook()
{
    set_transient('ann_admin_notice_transient', true, 5);
}
register_activation_hook(__FILE__, 'ann_activation_hook');


function ann_admin_notice()
{
    global $pagenow;
    global $post_type;
    error_log(print_r($post_type, true));
?>

    <?php if(in_array($pagenow,array('index.php', 'edit.php', 'plugins.php'))):?>
        <div class="notice notice-success is-dismissible">
            <p><?php echo esc_html_e(' Welcome Admin Notice Ninja', 'admin-notice-ninja'); ?></p>
        </div>
    <?php endif;?>
    <?php

    if (get_transient('ann_admin_notice_transient')) : ?>
        <div class="notice notice-success is-dismissible">
            <p><?php echo esc_html_e('Welcome Admin Notice Ninja', 'admin-notice-ninja'); ?></p>
        </div>
    <?php
    endif;

    if (!(isset($_COOKIE['ann_close_notice']) && $_COOKIE['ann_close_notice'] == 1)) : ?>
        <div id="ann_notice" class="notice notice-success is-dismissible">
            <p><?php echo esc_html_e('Welcome Admin Notice Ninja', 'admin-notice-ninja'); ?></p>
        </div>
<?php endif;
}
add_action('admin_notices', 'ann_admin_notice');


// assets
add_action('admin_enqueue_scripts', function () { //closure function
    wp_enqueue_script('ann-admin-main-js', plugin_dir_url(__FILE__) . 'admin/js/admin-main.js', array('jquery'), time(), true);
});
