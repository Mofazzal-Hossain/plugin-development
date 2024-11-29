<?php

/*
 * Plugin Name:       Options Demo
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a options demo plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       options-demo
 * Domain Path:       /languages
*/
function opdemo_init()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'employee';
    $sql = "CREATE TABLE {$table_name} (
			id INT NOT NULL AUTO_INCREMENT,
			`name` VARCHAR(250),
			email VARCHAR(250),
            age INT,
			PRIMARY KEY (id)
	);";
    require_once ABSPATH . "wp-admin/includes/upgrade.php";
    dbDelta($sql);
}

register_activation_hook(__FILE__, "opdemo_init");

add_action('admin_enqueue_scripts', function ($hook) {
    if ('toplevel_page_options-demo' == $hook) {
        wp_enqueue_style('pure-grid-css', '//unpkg.com/purecss@1.0.1/build/grids-min.css');
        wp_enqueue_style('opdemo-demo-css', plugin_dir_url(__FILE__) . "assets/css/style.css", null, time());
        wp_enqueue_script('opdemo-demo-js', plugin_dir_url(__FILE__) . "assets/js/main.js", array('jquery'), time(), true);
        $nonce = wp_create_nonce('display_result');
        wp_localize_script(
            'opdemo-demo-js',
            'plugindata',
            array('ajax_url' => admin_url('admin-ajax.php'), 'nonce' => $nonce)
        );
    }
});

add_action('wp_ajax_display_result', function () {

    if (wp_verify_nonce($_POST['nonce'], 'display_result')) {
        $task = $_POST['task'];
        if ('add-option' == $task) {
            add_option('opdemo_country', 'Bangladesh');
            add_option('opdemo_country2', 'India');
            echo "New option added: " . get_option('opdemo_country') . '<br>';
            echo "New option added: " . get_option('opdemo_country2');
        } elseif ('add-array-option' == $task) {
            $key = 'opdemo_country_list';
            $value = [
                'India',
                'Bangladesh',
                'Pakistan',
                'China'
            ];

            $key2 = 'opdemo_country_list2';
            $value2 = [
                'Peru',
                'Oman'
            ];

            add_option($key, $value);
            add_option($key2, $value2);
            print_r(get_option('opdemo_country_list'));
            print_r(get_option('opdemo_country_list2'));
        } elseif ('add-json-option' == $task) {
            $json_key = 'opdemo_option_json';
            $json_data = [
                '1' => 'Bangladesh',
                '2' => 'India'
            ];
            $json = json_encode($json_data);
            add_option($json_key, $json);
            print_r(get_option('opdemo_option_json'));
            $json_key2 = 'opdemo_option_json2';
            $json_data2 = [
                '1' => 'South Africa',
                '2' => 'Oman'
            ];
            $json2 = json_encode($json_data2);
            add_option($json_key2, $json2);
            print_r(get_option('opdemo_option_json2'));
        } elseif ('get-option' == $task) {
            $result = get_option('opdemo_country');
            echo "Result: " . $result;
        } elseif ('get-option-array' == $task) {
            $results  = get_option('opdemo_country_list');
            foreach ($results as $result) {
                echo $result . '<br>';
            }
        } elseif ('update-option' == $task) {
            $result = update_option('opdemo_country', 'Srilanka');
            echo 'Update country option: ' .  get_option('opdemo_country');
        } elseif ('update-option-array' == $task) {
            $key = 'opdemo_country_list';
            $value = [
                'Australia',
                'Bangladesh',
                'Pakistan',
                'China',
                'india'
            ];
            $result = update_option($key, $value);

            echo 'Update Country list array: <br>';
            print_r(get_option($key));
        } elseif ('option-filter-hook' == $task) {
            echo get_option('opdemo_country');
        } elseif ('delete-option' == $task) {
            $result = delete_option('opdemo_country');
            echo 'Delete a option: ' . $result;
        } elseif ('export-opitons' == $task) {
            $option_string_key = ['opdemo_country', 'opdemo_country2'];
            $option_array_key = ['opdemo_country_list', 'opdemo_country_list2'];
            $option_json_key = ['opdemo_option_json', 'opdemo_option_json2'];

            $exported_data = [];
            foreach ($option_string_key as $key) {
                $value = get_option($key);
                $exported_data[$key] = $value;
            }

            foreach ($option_array_key as $key) {
                $value = get_option($key);
                $exported_data[$key] = $value;
            }
            foreach ($option_json_key as $key) {
                $value = json_decode(get_option($key));
                $exported_data[$key] = $value;
            }

            $export =  json_encode($exported_data);
            add_option('export-options', $export);
            print_r($export);
        } elseif ('import-options' == $task) {
            $import = get_option('export-options');
            $import_decode = json_decode($import, true);

            print_r($import_decode);
        }
    }
    die(0);
});

// option filter hook

add_filter('option_opdemo_country', function ($value) {
    return strtoupper($value) . ' My Country';
});
// add_filter('option_opdemo_country_list', function($value){
//     return json_decode($value, true);
// });


add_action('admin_menu', function () {
    add_menu_page('Options Demo', 'Options Demo', 'manage_options', 'options-demo', 'opdemo_admin_page');
});

function opdemo_admin_page()
{
?>
    <div class="container" style="padding-top:20px;">
        <h1>Options Demo</h1>
        <div class="pure-g">
            <div class="pure-u-1-4" style='height:100vh;'>
                <div class="plugin-side-options">
                    <button class="action-button" data-task='add-option'>Add new option</button>
                    <button class="action-button" data-task='add-array-option'>Add array options</button>
                    <button class="action-button" data-task='add-json-option'>Add Json options</button>
                    <button class="action-button" data-task='get-option'>Display save option</button>
                    <button class="action-button" data-task='get-option-array'>Display save option array</button>
                    <button class="action-button" data-task='update-option'>Update option</button>
                    <button class="action-button" data-task='update-option-array'>Update option array</button>
                    <button class="action-button" data-task='option-filter-hook'>Option filter hook</button>
                    <button class="action-button" data-task='delete-option'>Delete option</button>
                    <button class="action-button" data-task='export-opitons'>Export Options</button>
                    <button class="action-button" data-task='import-options'>Import Options</button>
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
