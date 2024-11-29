<?php

// submenu callback function
function dbdemo_form_data_fn()
{

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        handle_submit_person_data();
    }

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $name = '';
    $email = '';
    $age = '';
    $sex = '';

    if ($id) {
        if(!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'],'dbdemo_edit_person')){
            die(__('Sorry you are not authorized to do this!', 'database-demo'));
        }
        global $wpdb;
        $table_name = $wpdb->prefix . 'persons';
        $person = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));

        $name = $person->name;
        $email = $person->email;
        $age = $person->age;
        $sex = $person->sex;
    }
    $options = [
        'Male' =>  __('Male', 'database-demo'),
        'Female' =>  __('Female', 'database-demo'),
    ];
    // error_log(print_r($id, true));

?>
    <div class="wrap mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="mb-3"><?php _e('Form Data', 'database-demo'); ?></h1>
                <form method="POST" class="p-3 border rounded bg-light">
                    <?php wp_nonce_field('dbdemo_form_nonce', 'dbdemo_form_nonce_field'); ?>
                    <input type="hidden" name="id" value="<?php echo esc_attr($id); ?>">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="name" class="form-label"><?php _e('Name', 'database-demo'); ?></label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" required>
                        </div>
                        <div class="col-6">
                            <label for="exampleInputEmail1" class="form-label"><?php _e('Email address', 'database-demo'); ?></label>
                            <input type="email" class="form-control" id="exampleInputEmail1" name="email" value="<?php echo $email; ?>" aria-describedby="emailHelp" required>
                            <div id="emailHelp" class="form-text"><?php _e("We'll never share your email with anyone else.", 'database-demo'); ?></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="age" class="form-label"><?php _e('Age', 'database-demo'); ?></label>
                            <input type="number" class="form-control" id="age" name="age" value="<?php echo $age; ?>" required>
                        </div>
                        <div class="col-6">
                            <label for="sex" class="form-label"><?php _e('Sex', 'database-demo'); ?></label>
                            <select class="form-select" id="sex" name="sex" required>
                                <option selected disabled><?php _e('Select', 'database-demo'); ?></option>
                                <?php foreach ($options as $key => $option) :

                                    $selected = ($sex === $key) ? 'selected' : '';

                                ?>
                                    <option value="<?php echo esc_attr($key); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_html_e($option, 'database-demo'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mt-2">
                        <?php submit_button(__('Submit', 'database-demo'), 'primary', 'submit_person_data', false); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
}

// person form submission handle
function handle_submit_person_data()
{

    // error_log(print_r($_POST, true));
    if (isset($_POST['submit_person_data']) && isset($_POST['dbdemo_form_nonce_field']) && wp_verify_nonce($_POST['dbdemo_form_nonce_field'], 'dbdemo_form_nonce')) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'persons';

        $id = isset($_POST['id']) ? intval($_POST['id']) : '0';
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $age = isset($_POST['age']) ? intval($_POST['age']) : '';
        $sex = isset($_POST['sex']) ? sanitize_text_field($_POST['sex']) : '';

        if ($email && $id) {
            $wpdb->update(
                $table_name,
                [
                    'name' => $name,
                    'email' => $email,
                    'age' => $age,
                    'sex' => $sex,
                ],
                ['id' => $id]
            );
            echo '<div class="updated"><p>' . __('Form data updated successfully!', 'database-demo') . '</p></div>';
        } else {
            $wpdb->insert($table_name, [
                'name' => $name,
                'email' => $email,
                'age' => $age,
                'sex' => $sex,
            ]);
            echo '<div class="updated"><p>' . __('Form data saved successfully!', 'database-demo') . '</p></div>';
        }
    }
}

// delete person data
function dbdemo_delete_person()
{
    check_ajax_referer('person_delete_nonce', 'security');

    // error_log(print_r($_POST['person_id'], true));

    if (isset($_POST['person_id'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'persons';

        $id = intval($_POST['person_id']);

        $deleted = $wpdb->delete($table_name, ['id' => $id]);

        if ($deleted) {
            wp_send_json_success();
        } else {
            wp_send_json_error(__('Failed to delete record', 'database-demo'));
        }
    }
}
add_action('wp_ajax_delete_person', 'dbdemo_delete_person');
