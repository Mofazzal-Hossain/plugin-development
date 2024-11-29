<?php

function plgopt_admin_menu_page()
{
    add_menu_page(
        __('Plugin Option Settings', 'plugin-options'),
        __('Plugin Settings'),
        'manage_options',
        'plugin-setting-option',
        'plugin_option_settings',
        'dashicons-admin-plugins',
    );
}

function plugin_option_settings()
{
    // echo "hello";
    $latitude = get_option('plugin_option_latitude');
    // error_log(print_r($latitude, true));
?>
    <form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
        <?php wp_nonce_field('plugin_options_nonce'); ?>
        <h2><?php echo esc_html_e('Plugin Settings', 'plugin-options'); ?></h2>
        <input type="text" name="plugin_option_latitude" id="plugin_option_latitude" value="<?php echo esc_attr($latitude); ?>">
        <input type="hidden" name="action" value="plugin_options_admin_page">
        <?php submit_button('Submit Now'); ?>
    </form>
<?php
}

add_action('admin_menu', 'plgopt_admin_menu_page');

function update_plugin_options_form()
{
    error_log('echo');
    check_admin_referer('plugin_options_nonce');
    if (isset($_POST['plugin_option_latitude'])) {
        $latitude = isset($_POST['plugin_option_latitude']) ? $_POST['plugin_option_latitude'] : '';
        update_option('plugin_option_latitude', $latitude);
    }
    wp_redirect(admin_url('admin.php?page=plugin-setting-option&update=true'));
    exit;
}
add_action('admin_post_plugin_options_admin_page', 'update_plugin_options_form');
