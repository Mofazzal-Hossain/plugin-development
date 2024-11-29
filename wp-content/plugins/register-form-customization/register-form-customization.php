<?php
/*
 * Plugin Name:       Register Form Customization
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a Register Form Customization plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       rfc
 * Domain Path:       /languages
*/

add_action('register_form', 'rfc_register_form');

function rfc_register_form()
{
    $first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
?>
    <p>
        <label for="first_name"><?php echo esc_html_e('First name', 'rfc'); ?></label>
        <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($first_name) ?>" size="25">
    </p>
    <p>
        <label for="last_name"><?php echo esc_html_e('Last name', 'rfc'); ?></label>
        <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($last_name) ?>" size="25">
    </p>
    <p>
        <label for="phone"><?php echo esc_html_e('Phone', 'rfc'); ?></label>
        <input type="text" id="phone" name="phone" value="<?php echo esc_attr($phone) ?>" size="25">
    </p>
<?php
}



add_filter('registration_errors', 'rfc_registration_errors_handle', 10, 3);
function rfc_registration_errors_handle($errors, $sanitized_user_login, $user_email)
{
    $first_name  = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $phone  = $_POST['phone'];

    if (empty($first_name) || !empty($first_name) && trim($first_name) == '') {
        $errors->add('first_name_error', sprintf('<strong>%s</strong>: %s', __("Error", 'rfc'), __('You must include a first name.', 'rfc')));
    }
    if (empty($last_name) || !empty($last_name) && trim($last_name) == '') {
        $errors->add('last_name_error', sprintf('<strong>%s</strong>: %s', __("Error", 'rfc'), __('You must include a last name.', 'rfc')));
    }
    if (empty($phone)) {
        $errors->add('phone_error', sprintf('<strong>%s</strong>: %s', __("Error", 'rfc'), __('You must include a phone number.', 'rfc')));
    }
    return $errors;
}

add_action('user_register', 'rfc_user_register');

function rfc_user_register($user_id)
{
    if (!empty($_POST['first_name'])) {
        update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['first_name']));
    }
    if (!empty($_POST['last_name'])) {
        update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['last_name']));
    }
    if (!empty($_POST['phone'])) {
        update_user_meta($user_id, 'phone', sanitize_text_field($_POST['phone']));
    }
}



function rfc_user_profile_metadata($user)
{
?>
    <h3><?php echo esc_html_e('It is Your Phone Number', 'rfc'); ?></h3>
    <table class="form-table">
        <tr>
            <th>
                <label for="phone"><?php echo esc_html_e('Phone', 'rfc'); ?></label>
            </th>
            <td>
                <input type="text" class="regular-text ltr" id="phone" name="phone" value="<?php echo esc_attr(get_user_meta($user->ID, 'phone', true)) ?>">
                <p class="description"><?php echo esc_html_e('Please enter your phone numbe.', 'rfc'); ?></p>
            </td>
        </tr>
    </table>
<?php
}


function rfc_user_profile_metadata_update($user_id)
{

    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }


    return update_user_meta(
        $user_id,
        'phone',
        $_POST['phone']
    );
}

// Add the field to user's own profile editing screen.
add_action(
    'show_user_profile',
    'rfc_user_profile_metadata'
);

// Add the field to user profile editing screen.
add_action(
    'edit_user_profile',
    'rfc_user_profile_metadata'
);

// Add the save action to user's own profile editing screen update.
add_action(
    'personal_options_update',
    'rfc_user_profile_metadata_update'
);

// Add the save action to user profile editing screen update.
add_action(
    'edit_user_profile_update',
    'rfc_user_profile_metadata_update'
);


add_action('register_form', function () {
?>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            var usernameField = document.getElementById("user_login");
            var userEmailField = document.getElementById("user_email");

            var form = document.getElementById('registerform');

            var firstNameField = document.getElementById("first_name").parentNode;
            var lastNameField = document.getElementById("last_name").parentNode;
            var phoneFiled = document.getElementById("phone").parentNode;

            form.insertBefore(userEmailField.parentNode, firstNameField.nextSibling)
            form.insertBefore(userEmailField.parentNode, lastNameField.nextSibling)
            form.insertBefore(userEmailField.parentNode, phoneFiled.nextSibling)
            

            form.insertBefore(usernameField.parentNode, firstNameField.nextSibling);
            form.insertBefore(usernameField.parentNode, lastNameField.nextSibling);
            form.insertBefore(usernameField.parentNode, phoneFiled.nextSibling);

        });
    </script>
<?php
});
