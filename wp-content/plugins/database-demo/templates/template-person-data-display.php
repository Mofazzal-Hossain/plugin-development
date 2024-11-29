<?php



function dbdemo_display_content()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'persons';
    $person_data = $wpdb->get_results("SELECT * FROM {$table_name}");
    // error_log(print_r($person_data, true));
?>
    <div class="p-5 px-3 person-list-display">
        <div class="container-fluid position-relative">
            <h2><?php echo esc_html_e('Person List', 'database-demo'); ?></h2>
            <div class="add-person">
                <a href="<?php echo esc_url('admin.php?page=form') ?>"><?php echo esc_html_e('Add Person', 'database-demo'); ?></a>
            </div>
            <div id="message"></div>
            <table>
                <tr>
                    <th><?php echo esc_html_e('ID', 'database-demo'); ?></th>
                    <th><?php echo esc_html_e('Name', 'database-demo'); ?></th>
                    <th><?php echo esc_html_e('Email', 'database-demo'); ?></th>
                    <th><?php echo esc_html_e('Age', 'database-demo'); ?></th>
                    <th><?php echo esc_html_e('Sex', 'database-demo'); ?></th>
                    <th><?php echo esc_html_e('Action', 'database-demo'); ?></th>

                </tr>
                <?php
                if (!empty($person_data)) :
                    foreach ($person_data as $key => $data) : ?>

                        <tr id="person-<?php echo esc_attr($data->id); ?>">
                            <td><?php echo esc_html_e($data->id, 'database-demo'); ?></td>
                            <td><?php echo esc_html_e($data->name, 'database-demo'); ?></td>
                            <td><?php echo esc_html_e($data->email, 'database-demo'); ?></td>
                            <td><?php echo esc_html_e($data->age, 'database-demo'); ?></td>
                            <td><?php echo esc_html_e($data->sex, 'database-demo'); ?></td>
                            <td>
                                <a href="<?php echo wp_nonce_url('admin.php?page=form&id=' . $data->id,'dbdemo_edit_person','nonce'); ?>" class="px-2 fs-6 py-0 btn btn-primary" type="button"><?php echo esc_html_e('Edit', 'database-demo'); ?></a>
                                <a href="#" class="px-2 fs-6 py-0 btn btn-danger person-delete" data-id="<?php echo esc_attr($data->id); ?>"><?php echo esc_html_e('Delete', 'database-demo'); ?></a>
                            </td>
                        </tr>


                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center"><?php echo esc_html_e('No data found', 'database-demo'); ?></td>
                    </tr>

                <?php endif; ?>

            </table>
        </div>
    </div>
<?php
}
