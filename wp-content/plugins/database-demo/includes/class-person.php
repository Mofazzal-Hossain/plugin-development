<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Person extends WP_List_Table
{

    public function __construct($data)
    {
        parent::__construct();
        $this->items = $data;
    }

    // get columns
    function get_columns()
    {
        return [
            'cb' => '<input type="checkbox" />',
            'name' => __('Name', 'database-demo'),
            'id' => __('ID', 'database-demo'),
            'email' => __('Email', 'database-demo'),
            'age' => __('Age', 'database-demo'),
            'sex' => __('Sex', 'database-demo'),
            'action' => __('Actions', 'database-demo'),
        ];
    }

    // checkbox
    function column_cb($item)
    {
        return '<input type="checkbox" value="' . $item['id'] . '" />';
    }

    // checkbox
    function column_name($item)
    {

        $actions = [
            'edit' => '<a href="' . wp_nonce_url('admin.php?page=form&id=' . $item['id'], 'dbdemo_edit_person', 'nonce') . '">' . __('Edit', 'database-demo') . '</a>',
            'delete' => '<a href="#" class="person-delete" data-id="' . esc_attr($item['id']) . '">' . __('Delete', 'database-demo') . '</a>'
        ];

        return sprintf('%s %s', $item['name'], $this->row_actions($actions));
    }

    // action
    function column_action($item)
    {
        $actions = [
            '<a href="' . wp_nonce_url('admin.php?page=form&id=' . $item['id'], 'dbdemo_edit_person', 'nonce') . '" class="px-2 fs-6 py-0 btn btn-primary">' . __('Edit', 'database-demo') . '</a>',
            '<a href="#" class="px-2 fs-6 py-0 btn btn-danger person-delete" data-id="' . esc_attr($item['id']) . '">' . __('Delete', 'database-demo') . '</a>'
        ];

        return implode(' ', $actions);
    }


    // sortable field
    function get_sortable_columns()
    {
        return [
            'id' => ['id', true],
            'age' => ['age', true]
        ];
    }

    // column default
    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    // prepare items
    function prepare_items()
    {
        $this->_column_headers = array($this->get_columns(), [], $this->get_sortable_columns());

        $total_items = count($this->items);
        $per_page = apply_filters('dbdemo_pagination_per_page', 3);
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

    // extra table nav
    function extra_tablenav($which)
    {
        if ('top' == $which) :
            $gender = isset($_REQUEST['tabledata_gender']) ? sanitize_text_field($_REQUEST['tabledata_gender']) : '';
            // error_log(print_r($gender, true));
            $gender_options = [
                'All' => __('All', 'tabledata'),
                'Male'  => __('Male', 'tabledata'),
                'Female' => __('Female', 'tabledata'),
            ];

?>
            <div class="alignleft actions">
                <select name="tabledata_gender" id="tabledata_gender">
                    <?php foreach ($gender_options as $key => $option) :
                        $selected = ($key === $gender) ? 'selected' : '';
                    ?>
                        <option value="<?php echo esc_attr($key); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_html($option); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php submit_button(__('Filter', 'tabledata'), 'button', 'submit', false); ?>
            </div>
<?php
        endif;
    }
}
