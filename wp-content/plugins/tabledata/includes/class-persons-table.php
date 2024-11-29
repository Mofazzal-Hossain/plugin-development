<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class PersonTable extends WP_List_Table
{

    function set_data($data)
    {
        $this->items = $data;
    }

    // column header name
    function get_columns()
    {
        return [
            'cb' => '<input type="checkbox" id="cb-select-all">',
            'id' => __('ID', 'tabledata'),
            'name' => __('Name', 'tabledata'),
            'email' => __('Email', 'tabledata'),
            'age' => __('Age', 'tabledata'),
            'sex' => __('Sex', 'tabledata'),
        ];
    }

    // sortable column
    function get_sortable_columns()
    {
        return [
            'age' => ['age', true],
            'id' => ['id', true],
        ];
    }

    // read/write column cb
    function column_cb($item)
    {
        return '<input type="checkbox" class="cb-select-' . $item['id'] . '" name="bulk-select[]" id="' . $item['id'] . '" value="' . $item['id'] . '" />';
    }

    // read/write column name
    function column_name($item)
    {
        return "<strong>{$item['name']}</strong>";
    }

    // column hearder
    function prepare_items()
    {
        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());

        $total_items = count($this->items);
        $per_page = apply_filters('tabledata_pagination_per_page', 10);
        $current_page = $this->get_pagenum();
        $total_pages = ceil($total_items / $per_page);

        $data_chunks = array_chunk($this->items, $per_page);
        $this->items = $data_chunks[$current_page - 1] ?? [];

        $this->set_pagination_args(
            [
                'total_items' => $total_items,
                'per_page' => $per_page,
                'total_pages' => $total_pages,
            ],
        );
    }

    // display data value
    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    // get bulk actions
    function get_bulk_actions()
    {
        return [
            'delete' => __('Delete', 'tabledata'),
        ];
    }

    // process bulk action
    function process_bulk_action()
    {
    }

    // Override the display method to add bulk actions
    // function display()
    // {
    // }


    function extra_tablenav($which)
    {
        if ('top' == $which) :
            $gender = isset($_REQUEST['tabledata_gender']) ? sanitize_text_field($_REQUEST['tabledata_gender']) : '';
            // error_log(print_r($gender, true));
            $gender_options = [
                'All' => __('All', 'tabledata'),
                'M'  => __('Male', 'tabledata'),
                'F' => __('Female', 'tabledata'),
            ];

            ?>
            <div class="alignleft actions">
                <select name="tabledata_gender" id="tabledata_gender">
                    <?php foreach ($gender_options as $key => $option) : 
                        $selected = ($key === $gender) ? 'selected' : '';
                        ?>
                        <option value="<?php echo esc_attr($key);?>" <?php echo esc_attr($selected); ?>><?php echo esc_html($option); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php submit_button(__('Filter', 'tabledata'), 'button', 'submit', false); ?>
            </div>
            <?php
        endif;
    }
}
