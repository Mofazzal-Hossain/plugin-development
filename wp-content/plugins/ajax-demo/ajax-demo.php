<?php

/*
 * Plugin Name:       Ajax Demo
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a Ajax Demo plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       ajax-demo
 * Domain Path:       /languages
*/


add_action('admin_enqueue_scripts', function ($hook) {
    if ('toplevel_page_ajax-demo' == $hook) {
        wp_enqueue_style('pure-grid-css', '//unpkg.com/purecss@1.0.1/build/grids-min.css');
        wp_enqueue_style('ajax-demo-css', plugin_dir_url(__FILE__) . "assets/css/style.css", null, time());
        wp_enqueue_script('ajax-demo-js', plugin_dir_url(__FILE__) . "assets/js/main.js", array('jquery'), time(), true);
        wp_localize_script(
            'ajax-demo-js',
            'plugindata',
            ['ajaxurl' => admin_url('admin-ajax.php')]
        );

        $nonce = wp_create_nonce('ajxdemo_nonce');

        wp_localize_script('ajax-demo-js', 'secureData', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => $nonce,
        ]);

        wp_localize_script('ajax-demo-js', 'personData', [
            'Name' => 'Mofazzal Hossain',
            'Email' => 'mosharofmd74@gmail.com',
            'Age' => 25
        ]);
    }
});

add_action('wp_ajax_display_result', function () {

    if (wp_verify_nonce($_POST['nonce'], 'display_result')) {
        $task = $_POST['task'];
        if ('add-new-record' == $task) {
            echo 'hello';
        } elseif ('replace-or-insert' == $task) {
        }
    }
    die(0);
});

add_action('admin_menu', function () {
    add_menu_page('Ajax Demo', 'Ajax Demo', 'manage_options', 'ajax-demo', 'ajxdemo_admin_page');
});

function ajxdemo_admin_page()
{
?>
    <div class="container" style="padding-top:20px;">
        <h1>Ajax Demo</h1>
        <div class="pure-g">
            <div class="pure-u-1-4" style='height:100vh;'>
                <div class="plugin-side-options">
                    <button class="action-button" data-task='simple_ajax_call'>Simple Ajax Call</button>
                    <button class="action-button" data-task='unprivileged_ajax_call'>Unprivileged Ajax Call</button>
                    <button class="action-button" data-task='wp_localize_script'>Why wp_localize_script</button>
                    <button class="action-button" data-task='wp_secure_ajax_call'>Security with Nonce</button>
                </div>
            </div>
            <div class="pure-u-3-4">
                <div class="plugin-demo-content">
                    <h3 class="plugin-result-title">Result</h3>
                    <div id="plugin-demo-result" class="plugin-result"></div>
                </div>
            </div>
        </div>
    </div>
<?php
}


add_action('wp_ajax_simple_ajax_call', 'ajxdemo_simple_ajax_call');

function ajxdemo_simple_ajax_call()
{
    $simple = isset($_POST['simple']) ? sanitize_text_field($_POST['simple']) : '';

    wp_send_json_success($simple);
    die();
}

function ajxdemo_privileged_ajax_call()
{
    $simple = isset($_POST['simple']) ? sanitize_text_field($_POST['simple']) : '';

    wp_send_json_success($simple);
    die();
}
add_action('wp_ajax_privileged_ajax_call', 'ajxdemo_privileged_ajax_call');
add_action('wp_ajax_nopriv_privileged_ajax_call', 'ajxdemo_privileged_ajax_call');


function ajxdemo_secure_ajax_call()
{
    $simple = isset($_POST['simple']) ? sanitize_text_field($_POST['simple']) : '';
    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
   
    if(!wp_verify_nonce($nonce, 'ajxdemo_nonce')){
        wp_send_json_error('Invalid nonce');
        die();
    }

    wp_send_json_success($simple);


    die();
}
add_action('wp_ajax_secure_ajax_call', 'ajxdemo_secure_ajax_call');
add_action('wp_ajax_nopriv_secure_ajax_call', 'ajxdemo_secure_ajax_call');
