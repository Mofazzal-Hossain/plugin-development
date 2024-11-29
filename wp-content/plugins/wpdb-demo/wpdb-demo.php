<?php

/*
 * Plugin Name:       WPDB Demo
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a wpdb demo plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       wpdb-demo
 * Domain Path:       /languages
*/
function wpdbdemo_init()
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

register_activation_hook(__FILE__, "wpdbdemo_init");

add_action('admin_enqueue_scripts', function ($hook) {
    if ('toplevel_page_wpdb-demo' == $hook) {
        wp_enqueue_style('pure-grid-css', '//unpkg.com/purecss@1.0.1/build/grids-min.css');
        wp_enqueue_style('wpdb-demo-css', plugin_dir_url(__FILE__) . "assets/css/style.css", null, time());
        wp_enqueue_script('wpdb-demo-js', plugin_dir_url(__FILE__) . "assets/js/main.js", array('jquery'), time(), true);
        $nonce = wp_create_nonce('display_result');
        wp_localize_script(
            'wpdb-demo-js',
            'plugindata',
            array('ajax_url' => admin_url('admin-ajax.php'), 'nonce' => $nonce)
        );
    }
});

add_action('wp_ajax_display_result', function () {
    global $wpdb;
    $table_name = $wpdb->prefix . 'employee';
    if (wp_verify_nonce($_POST['nonce'], 'display_result')) {
        $task = $_POST['task'];
        if ('add-new-record' == $task) {
            $employee = [
                'name' => 'Mofazzal',
                'email' => 'admin@gmail.com',
                'age' => 25,
            ];
            $wpdb->insert($table_name, $employee);

            echo '<h3>New record added</h3>';
            echo 'ID: ' . $wpdb->insert_id;
        } elseif ('replace-or-insert' == $task) {
            $employee = [
                'id' => 2,
                'name' => 'Mofazzal Hossain',
                'email' => 'mofazzal@gmail.com',
                'age' => 25,
            ];
            $wpdb->replace($table_name, $employee);
            echo '<h3>Operation Done</h3>';
            echo 'ID: ' . $wpdb->insert_id;
        } elseif ('update-data' == $task) {
            $employee = [
                'id' => 2,
                'name' => 'Mosharaf Hossain',
                'email' => 'mofazzal@gmail.com',
                'age' => 29,
            ];
            $result = $wpdb->update($table_name, $employee, ['id' => $employee['id']]);
            echo '<h3>Existing Record Updated</h3>';
            echo 'ID: ' . $result;
        } elseif ('load-single-row' == $task) {
            $row = $wpdb->get_row("SELECT * FROM $table_name WHERE id=3", ARRAY_A);
            print_r($row);
            echo "ID: " . $row['id'];
            echo "<br>Name: " . $row['name'];
            echo "<br>Email: " . $row['email'];
            echo "<br>Age: " . $row['age'];
        } elseif ('load-multiple-row' == $task) {
            $result = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
            print_r($result);

            $result = $wpdb->get_results("SELECT age,id,email,age FROM $table_name", OBJECT_K);
            print_r($result);
        } elseif ('add-multiple' == $task) {
            $employee = [
                [
                    'name' => 'Khan',
                    'email' => 'khan@gmail.com',
                    'age' => 22,
                ],
                [
                    'name' => 'Ety',
                    'email' => 'ety@gmail.com',
                    'age' => 22,
                ],
            ];
            foreach ($employee as $data) {
                $wpdb->insert($table_name, $data);
                print_r($data);
            }
        } elseif ('prepared-statement' == $task) {
            $id = 3;
            $email = 'mofazzal@gmail.com';
            // $prepare_statement = $wpdb->prepare("SELECT * FROM {$table_name} WHERE id > %d", $id);
            $prepare_statement = $wpdb->prepare("SELECT * FROM {$table_name} WHERE email = %s", $email);
            $result = $wpdb->get_results($prepare_statement);
            print_r($result);
        } elseif ('single-column' == $task) {
            $email = "SELECT email FROM {$table_name}";
            $email_data = $wpdb->get_col($email);
            print_r($email_data);
            $name = "SELECT name FROM {$table_name}";
            $name_data = $wpdb->get_col($name);
            print_r($name_data);
        } elseif ('single-var' == $task) {
            $result = $wpdb->get_var("SELECT COUNT(*) FROM {$table_name}");
            echo "Total User: " . $result;

            $result = $wpdb->get_var("SELECT name, email FROM {$table_name}", 0,0);
            echo "<br>First User Name: " . $result;

            
            $result = $wpdb->get_var("SELECT name, email FROM {$table_name}", 1,2);
            echo "<br>First User Email: " . $result;

        } elseif ('delete-data' == $task) {

           $delete =  $wpdb->delete($table_name, ['id' => 2]);

            echo "Delete employee id: ". $delete;
        }
    }
    die(0);
});

add_action('admin_menu', function () {
    add_menu_page('WPDB Demo', 'WPDB Demo', 'manage_options', 'wpdb-demo', 'wpdbdemo_admin_page');
});

function wpdbdemo_admin_page()
{
?>
    <div class="container" style="padding-top:20px;">
        <h1>WPDB Demo</h1>
        <div class="pure-g">
            <div class="pure-u-1-4" style='height:100vh;'>
                <div class="plugin-side-options">
                    <button class="action-button" data-task='add-new-record'>Add New Data</button>
                    <button class="action-button" data-task='replace-or-insert'>Replace or Insert</button>
                    <button class="action-button" data-task='update-data'>Update Data</button>
                    <button class="action-button" data-task='load-single-row'>Load Single Row</button>
                    <button class="action-button" data-task='load-multiple-row'>Load Multiple Row</button>
                    <button class="action-button" data-task='add-multiple'>Add Multiple Row</button>
                    <button class="action-button" data-task='prepared-statement'>Prepared Statement</button>
                    <button class="action-button" data-task='single-column'>Display Single Column</button>
                    <button class="action-button" data-task='single-var'>Display Variable</button>
                    <button class="action-button" data-task='delete-data'>Delete Data</button>
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
