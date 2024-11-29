<?php

/*
 * Plugin Name:       User Role Blocker Demo
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a User Role Blocker Demo plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       urbd
 * Domain Path:       /languages
*/

// plugin activation
register_activation_hook(__FILE__, 'urbd_file_activation');

function urbd_file_activation()
{
    add_role('urbd_user_blocked', __('Blocked', 'urbd'), ['blocked' => true]);
};


// plugin deactivation
register_deactivation_hook(__FILE__, 'urbd_file_deactivation');

function urbd_file_deactivation()
{
    remove_role('urbd_user_blocked');
    remove_role('urb_user_blocker');
    add_role('subscriber', 'Subscriber');
}

// plugin init function
add_action('init', function () {
    add_rewrite_rule('blocked/?$', 'index.php?blocked=1', 'top');

    if (current_user_can('blocked') && is_admin()) {
        wp_redirect(home_url('/blocked'));
        exit;
    }
});

// add block into query vars
add_filter('query_vars', function ($query_vars) {

    $query_vars[] = 'blocked';
    // error_log(print_r($query_vars, true));
    return $query_vars;
});


add_action('template_redirect', function () {
    $is_blocked = intval(get_query_var('blocked'));
    if ($is_blocked) {
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Blocked</title>
            <?php wp_head() ?>
        </head>

        <body>
            <h3 style="text-align: center;"><?php echo esc_html_e('You are not authorized person!!', 'urbd'); ?></h3>
            <?php wp_footer() ?>
        </body>

        </html>
<?php
        die();
    }
});
