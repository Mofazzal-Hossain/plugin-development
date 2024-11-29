<?php

/*
 * Plugin Name:       Post QR Code
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a plugin that counts the number of QR Code in a post.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       post-qrcode
 * Domain Path:       /languages
 */

// plugin activation hook
// function postqrcode_activation_hook()
// {
// }
// register_activation_hook(__FILE__, 'postqrcode_activation_hook');

// // plugin deactivation hook
// function postqrcode_deactivation_hook()
// {
// }
// register_deactivation_hook(__FILE__, 'postqrcode_deactivation_hook');

// plugin textdomain loaded


// qrcode enqueue scripts
function qrcode_enqueue_scripts($screen)
{
    if ('options-general.php' === $screen) {
        wp_enqueue_style('qrcode-minitoggle-css', plugin_dir_url(__FILE__) . '/assets/css/minitoggle.css');
        wp_enqueue_script('qrcode-minitoggle-js', plugin_dir_url(__FILE__) . '/assets/js/minitoggle.js');
        wp_enqueue_script('qrcode-main-js', plugin_dir_url(__FILE__) . '/assets/js/qrcode-main.js', array('jquery'), time(), true);
    }
}

add_action('admin_enqueue_scripts', 'qrcode_enqueue_scripts');


global $qrcode_countries;
$qrcode_countries = array(
    __('none', 'post-qrcode'),
    __('Afghanistan', 'post-qrcode'),
    __('Bangladesh', 'post-qrcode'),
    __('Bhutan', 'post-qrcode'),
    __('India', 'post-qrcode'),
    __('Maldives', 'post-qrcode'),
    __('Nepal', 'post-qrcode'),
    __('Pakistan', 'post-qrcode'),
    __('Sri-Lanka', 'post-qrcode'),

);

// plugin init
function qrcode_init()
{
    global $qrcode_countries;
    $qrcode_countries = apply_filters('qrcode_admin_countries', $qrcode_countries);
}
add_action('init', 'qrcode_init');

// plugin textdomain
function postqrcode_load_textdomain()
{
    load_plugin_textdomain('post-qrcode', false, dirname(__FILE__) . '/languages');
}

add_action('plugins_loaded', 'postqrcode_load_textdomain');

// post qrcode content
function postqrcode_post_qrcode($content)
{
    $current_post_id = get_the_ID();
    $current_post_title = strtolower(get_the_title($current_post_id));
    $current_post_permalink = urlencode(get_the_permalink($current_post_id));
    $current_post_type = get_post_type($current_post_id);
    // error_log(print_r($current_post_type, true));

    $exclude_post_type = apply_filters('postqrcode_exclude_post', array());

    $width = get_option('qrcode_width');
    $height = get_option('qrcode_height');
    $width = $width ? $width : 180;
    $height = $height ? $height : 180;
    $dimension_qrcode_img = apply_filters('postqrcode_dimension', "{$width}x{$height}");


    if (in_array($current_post_type, $exclude_post_type)) {
        return $content;
    }


    $post_qrcode = sprintf('https://api.qrserver.com/v1/create-qr-code/?size=%s&data=%s', $dimension_qrcode_img, $current_post_permalink);
    $content .= sprintf('<img src="%s" alt="%s">', $post_qrcode, $current_post_title);
    return $content;
}
add_filter('the_content', 'postqrcode_post_qrcode');


// register menu page
function postqrcode_register_custom_menu_page()
{

    add_menu_page('QR Code', 'QR Code', 'manage_options',  'qr-code', 'post_qr_code_callback', 'dashicons-admin-network', 10);
}

add_action('admin_menu', 'postqrcode_register_custom_menu_page');
function post_qr_code_callback()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_qrcode_nonce']) && wp_verify_nonce($_POST['post_qrcode_nonce'], 'post_qrcode_nonce_action')) {
        // error_log(print_r($_POST, true));

        $qr_width = isset($_POST['qr-width']) ? intval(sanitize_text_field($_POST['qr-width'])) : '';
        $qr_height = isset($_POST['qr-height']) ? intval(sanitize_text_field($_POST['qr-height'])) : '';

        update_option('qrcode_img_width', $qr_width);
        update_option('qrcode_img_height', $qr_height);
    }

    $width = get_option('qrcode_img_width');
    $height = get_option('qrcode_img_height');
    // error_log('Width: ' . $width . 'Height: ' . $height);
?>
    <form id="qrCodeSize" method="post" action="">
        <?php wp_nonce_field('post_qrcode_nonce_action', 'post_qrcode_nonce') ?>
        <div class="form-group">
            <label for="qr-width"><?php echo esc_html('Width', 'post-qrcode') ?></label>
            <input type="number" id="qr-width" name="qr-width" value="<?php echo esc_attr($width); ?>">
        </div>
        <div class="form-group">
            <label for="qr-height"><?php echo esc_html('Height', 'post-qrcode') ?></label>
            <input type="number" id="qr-height" name="qr-height" value="<?php echo esc_attr($height); ?>">
        </div>
        <button type="submit" id="qrcode-submit"><?php echo esc_html_e('Submit', 'post-qrcode'); ?></button>
    </form>
<?php
}

// admin setting init
function qrcode_settings_init()
{
    // add settings section
    add_settings_section('qrcode_section', __('Post to QR Code', 'post-qrcode'), 'qrcode_section_callback', 'general');

    // add settings field
    add_settings_field('qrcode_width', __('QR Code Width', 'post-qrcode'), 'qrcode_post_field', 'general', 'qrcode_section', array('qrcode_width'));
    add_settings_field('qrcode_height', __('QR Code Height', 'post-qrcode'), 'qrcode_post_field', 'general', 'qrcode_section', array('qrcode_height'));
    // add_settings_field('qrcode_extra', __('QR Code extra', 'post-qrcode'), 'qrcode_post_field', 'general', 'qrcode_section', array('qrcode_extra'));
    add_settings_field('qrcode_country', __('QR Code Country Select', 'post-qrcode'), 'qrcode_country_select', 'general', 'qrcode_section');
    add_settings_field('qrcode_checkbox', __('QR Code Country Checkbox', 'post-qrcode'), 'qrcode_country_checkbox', 'general', 'qrcode_section');
    add_settings_field('qrcode_toggle', __('QR Code Toggle', 'post-qrcode'), 'qrcode_toggle_callback', 'general', 'qrcode_section');

    // register settings field
    register_setting('general', 'qrcode_width', array('sanitize_callback' =>  'esc_attr'));
    register_setting('general', 'qrcode_height', array('sanitize_callback' => 'esc_attr'));
    // register_setting('general', 'qrcode_extra', array('sanitize_callback' => 'esc_attr'));
    register_setting('general', 'qrcode_country', array('sanitize_callback' => 'esc_attr'));
    register_setting('general', 'qrcode_checkbox');
    register_setting('general', 'qrcode_toggle');
}
add_action('admin_init', 'qrcode_settings_init');

// settings section callback function
function qrcode_section_callback()
{
    echo '<p>' . __('Settings for post to qrcode plugin', 'post-qrcode') . '</p>';
}


// common callback function with arguments pass
function qrcode_post_field($args)
{
    $option = get_option($args[0]);
    // error_log(print_r($option, true));
    printf('<input type="text" id="%s" name="%s" value="%s"/>', esc_attr($args[0]), esc_attr($args[0]), esc_attr($option));
}

// qrcode country select
function qrcode_country_select()
{
    // error_log(print_r($country_selected, true));
    $country_selected = get_option('qrcode_country');

    printf('<select name="%s" id="%s">', esc_attr('qrcode_country'), esc_attr('qrcode_country'));
    global $qrcode_countries;

    // error_log(print_r($qrcode_countries, true));
    foreach ($qrcode_countries as $country) {
        // error_log(print_r($country, true));
        $selected = ($country_selected == $country) ? 'selected' : '';
        printf('<option value="%s" %s>%s</option>', esc_attr($country), esc_attr($selected), esc_html($country));
    }
    echo '</select>';
}

// qrcode country checkbox
function qrcode_country_checkbox()
{
    $country_checked = get_option('qrcode_checkbox');
    // error_log(print_r($country_checked, true));
    global $qrcode_countries;

    foreach ($qrcode_countries as $country) {
        $selected = in_array($country, $country_checked) ? 'checked' : '';
        // printf('<input type="checkbox" name="qrcode_checkbox[]" id="%s" value="%s" %s %s/>%s', esc_attr('qrcode_checkbox'), esc_attr($country), esc_attr($selected), ($index == 0) ? '' : 'style="margin-left:10px;"', esc_html($country));
        printf('<input type="checkbox" name="qrcode_checkbox[]" id="%s" value="%s" %s/>%s<br>', esc_attr('qrcode_checkbox'), esc_attr($country), esc_attr($selected), esc_html($country));
    }
}

function qrcode_toggle_callback() {
    $toggle_value = get_option('qrcode_toggle');
    // error_log(print_r($toggle_value, true));
    echo '<div id="qrcodeToggle" class="toggle"></div>';
    printf('<input type="hidden" id="qrcode_toggle" name="qrcode_toggle" value="%s" />', $toggle_value);
}

// function qrcode_width_callback()
// {
//     $qrcode_image_width = get_option('qrcode_image_width');
//     // error_log($qrcode_image_width);
//     $input_field = sprintf('<input type="%s" id="%s" name="%s" value="%s"/>', 'number', 'qrcode_image_width', 'qrcode_image_width', $qrcode_image_width);
//     echo $input_field;
// }

// function qrcode_height_callback()
// {
//     $qrcode_image_height = get_option('qrcode_image_height');
//     $height_input = sprintf('<input type="%s" id="%s" name="%s" value="%s"/>', 'number', 'qrcode_image_height', 'qrcode_image_height', $qrcode_image_height);
//     echo $height_input;
// }


