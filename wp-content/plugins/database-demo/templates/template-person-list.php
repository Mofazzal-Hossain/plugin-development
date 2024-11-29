<?php

function dbdemo_person_list_display()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'persons';
    $data = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

    // $search_name = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    $search_gender = isset($_GET['tabledata_gender']) ? sanitize_text_field($_GET['tabledata_gender']) : '';

    global $orderby;
    $orderby = $_REQUEST['orderby'] ?? '';
    $order = $_REQUEST['order'] ?? '';

    // if ($search_name) {
    //     $data = array_filter($data, 'dbdemo_search_filter_fn');
    // }
    if ($search_gender) {
        $data = array_filter($data, 'dbdemo_gender_filter_fn');
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


    $person = new Person($data);
    $person->prepare_items();
?>
    <div class="p-5 px-3">
        <div class="d-flex justify-content-between align-items-end">
            <h3><?php echo esc_html_e('Person List', 'database-demo'); ?></h3>
           
            <?php echo $person->search_box('search','search_id'); ?>
        </div>
        <div id="message"></div>

        <div class="person-list">
            <form method="get">
                <?php
                    $person->get_bulk_actions();
                    $person->display();
                ?>
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
            </form>
        </div>
    </div>
<?php
}

// array filter callback function
// function dbdemo_search_filter_fn($item)
// {
//     $name = strtolower($item['name']);
//     $email = strtolower($item['email']);
//     $age = strtolower($item['age']);
//     $sex = strtolower($item['sex']);
//     $search_str = $name . $email . $age . $sex;

//     $search_name = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

//     if (strpos($search_str, $search_name) !== false) {
//         return true;
//     }
//     return false;
// }

// filter by gender
function dbdemo_gender_filter_fn($item)
{
    $gender = $item['sex'];
    error_log('Gender' . print_r($gender, true));
    $search_gender = isset($_GET['tabledata_gender']) ? sanitize_text_field($_GET['tabledata_gender']) : '';

    error_log('search value: ' . print_r($search_gender, true));

    if ('All' == $search_gender) {
        return true;
    } else if ($gender === $search_gender) {
        return true;
    }
    return false;
}
