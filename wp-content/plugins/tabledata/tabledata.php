<?php

/*
 * Plugin Name:       Table Data
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a table data plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       tabledata
 * Domain Path:       /languages
*/

require_once plugin_dir_path(__FILE__) . 'dataset.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-persons-table.php';

// Define a version constant
define('VERSION', '1.0.0');


if (!class_exists("WP_List_Table")) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

// admin assets
function tabledata_admin_assets()
{
    wp_enqueue_style('tabledata-bootstrap-css', '//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', null, VERSION);
    wp_enqueue_script('tabledata-bootstrap-js', '//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array(), VERSION, true);
}
add_action('admin_enqueue_scripts', 'tabledata_admin_assets');

// text domain load
function tabledata_plugin_loaded()
{
    load_plugin_textdomain('tabledata', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'tabledata_plugin_loaded');

// register activation hook
function tabledata_person_table_create()
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
register_activation_hook(__FILE__, 'tabledata_person_table_create');


// add menu page
function tabledata_add_menu_page()
{
    add_menu_page(
        __('Table Data', 'tabledata'),
        __('Table Data', 'tabledata'),
        'manage_options',
        'table-data',
        'tabledata_display_content',
        'dashicons-table-col-after',
        50
    );
}

// array filter callback function
function search_filter_fn($item)
{
    $name = strtolower($item['name']);
    $email = strtolower($item['email']);
    $age = strtolower($item['age']);
    $sex = strtolower($item['sex']);
    $search_str = $name . $email . $age . $sex;

    $search_name = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

    if (strpos($search_str, $search_name) !== false) {
        return true;
    }
    return false;
}

// filter by gender
function search_gender_filter_fn($item)
{
    $gender = $item['sex'];
    $search_gender = isset($_GET['tabledata_gender']) ? sanitize_text_field($_GET['tabledata_gender']) : '';

    if ('All' == $search_gender) {
        return true;
    } else if ($gender === $search_gender) {
        return true;
    }
    return false;
}

// display tabledata
function tabledata_display_content()
{
    // include('dataset.php');

    global $wpdb;
    $table_name = $wpdb->prefix . 'persons';
    $data = $wpdb->get_results("SELECT * FROM {$table_name}", ARRAY_A);

    // convert object array 
    // $data = array_map(function ($object) {
    //     return (array) $object;
    // }, $data);

    // error_log(print_r($data, true));

    $search_name = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    $search_gender = isset($_GET['tabledata_gender']) ? sanitize_text_field($_GET['tabledata_gender']) : '';

    global $orderby;
    $orderby = $_REQUEST['orderby'] ?? '';
    $order = $_REQUEST['order'] ?? '';

    if ($search_name) {
        $data = array_filter($data, 'search_filter_fn');
    }
    if ($search_gender) {
        $data = array_filter($data, 'search_gender_filter_fn');
    }
    if ('age' == $orderby || 'id' == $orderby) {
        $sort_fn = ('asc' == $order) ?  function ($item1, $item2) {
            global $orderby;
            return $item1[$orderby] <=> $item2[$orderby];
        } : function ($item1, $item2) {
            global $orderby;
            return $item2[$orderby] <=> $item1[$orderby];
        };

        usort($data, $sort_fn);
    }

    $table = new PersonTable(); // PersonTable class init
    $table->set_data($data); // dataset pass set_data function
    $table->prepare_items(); // call prepare_items
?>
    <div class="wrap p-4 px-3">
        <div class="container-fluid">
            <form method="get">
                <div class="d-flex justify-content-between align-items-end">
                    <h3><?php echo esc_html_e('Person Table Data', 'tabledata'); ?></h3>
                    <?php $table->search_box('Search', 'search_id'); // call search filter 
                    ?>
                </div>
                <?php

                $table->get_bulk_actions();
                $table->display(); // display content
                ?>
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
            </form>
        </div>
    </div>
<?php


}

add_action('admin_menu', 'tabledata_add_menu_page');
