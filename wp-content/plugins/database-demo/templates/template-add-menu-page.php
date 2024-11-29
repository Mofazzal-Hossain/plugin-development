<?php

add_action('admin_menu', 'dbdemo_add_menu_page');

function dbdemo_add_menu_page()
{
    add_menu_page(
        __('Database Demo', 'database-demo'),
        __('Database', 'atabase-demo'),
        'manage_options',
        'database-demo',
        'dbdemo_display_content',
        'dashicons-database',
        50
    );
    // person list
    add_submenu_page(
        'database-demo',
        __('Person List', 'database-demo'),
        __('Person List', 'database-demo'),
        'manage_options',
        'person-list',
        'dbdemo_person_list_display'
    );
    // form
    add_submenu_page(
        'database-demo',
        __('Form', 'database-demo'),
        __('Form', 'database-demo'),
        'manage_options',
        'form',
        'dbdemo_form_data_fn'
    );
}
