<?php

/*
 * Plugin Name:       Database Demo
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a database plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       database-demo
 * Domain Path:       /languages
*/


// Define a version constant
define('DBDEMO_VERSION', '1.0');

// incldue files
require_once plugin_dir_path(__FILE__) . 'includes/class-person.php';
require_once plugin_dir_path(__FILE__) . 'templates/template-add-menu-page.php';
require_once plugin_dir_path(__FILE__) . 'templates/template-person-data-display.php';
require_once plugin_dir_path(__FILE__) . 'templates/template-person-list.php';
require_once plugin_dir_path(__FILE__) . 'templates/template-person-form.php';


class AdminAssets
{
    function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'dbdemo_admin_assets'));
    }

    function dbdemo_admin_assets()
    {
        // css files
        $css_files = [
            'tabledata-admin-css' => ['path' => plugin_dir_url(__FILE__) .  'admin/css/admin-style.css'],
            'dbdemo-bootstrap-css' => ['path' => '//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css'],
        ];

        foreach ($css_files as $handle => $file) {
            wp_enqueue_style($handle, $file['path']);
        }

        // js files
        $js_files = [
            'dbdemo-bootstrap-js' => ['path' => '//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', 'dep' => array()],
            'dbdemo-admin-main-js' => ['path' =>  plugin_dir_url(__FILE__) . 'admin/js/admin-main.js', 'dep' => array('jquery')],
        ];

        foreach ($js_files as $handle => $file) {
            wp_enqueue_script($handle, $file['path'], $file['dep'], DBDEMO_VERSION, true);
        }

        wp_localize_script('dbdemo-admin-main-js', 'dbdemo_vars', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('person_delete_nonce'),
            'confirm_message' => __('Are you sure you want to delete this record?', 'database-demo'),
        ]);
    }
}

new AdminAssets();
// text domain load
function dbdemo_plugin_loaded()
{
    load_plugin_textdomain('database-demo', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'dbdemo_plugin_loaded');


// register activation hook create table
function dbdemo_person_table_create()
{
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    global $wpdb;
    $table_name = $wpdb->prefix . 'persons';
    $charset_collate = $wpdb->get_charset_collate();

    $create_table = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(250),
        email VARCHAR(250),
        age VARCHAR(250),
        sex VARCHAR(250),
        PRIMARY KEY (id)
    ) $charset_collate;";

    // error_log($create_table);

    dbDelta($create_table);
}
register_activation_hook(__FILE__, 'dbdemo_person_table_create');


// activation hook insert data
function dbdemo_person_table_data_insert()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'persons';
    include('includes/dataset.php');
    $table_data = $data;
    if (isset($table_data) && is_array($table_data)) {
        foreach ($table_data as $entry) {
            $exits = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE name=%s AND email=%s AND age=%s AND sex=%s",
                $entry['name'],
                $entry['email'],
                $entry['age'],
                $entry['sex']
            ));
            if ($exits == 0) {
                $wpdb->insert($table_name, [
                    'name' => $entry['name'],
                    'email' => $entry['email'],
                    'age' => $entry['age'],
                    'sex' => $entry['sex'],
                ]);
            }
        }
    }
}
register_activation_hook(__FILE__, 'dbdemo_person_table_data_insert');

// deactivation hook truncate data
function dbdemo_person_data_flush()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'persons';

    $truncate_data = "TRUNCATE TABLE {$table_name}";
    $wpdb->query($truncate_data);
}
register_deactivation_hook(__FILE__, 'dbdemo_person_data_flush');


/*

function dbdemo_plugin_load()
{
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    global $wpdb;
    $table_name = $wpdb->prefix . 'persons';
    $charset_collate = $wpdb->get_charset_collate();

    $create_table = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        Name VARCHAR(250) NOT NULL,
        Email VARCHAR(250) NOT NULL,
        Gender VARCHAR(250) NOT NULL,
        Phone VARCHAR(250) NOT NULL,
        Country VARCHAR(250) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    dbDelta($create_table);
}
add_action('plugins_loaded', 'dbdemo_plugin_load');


*/
