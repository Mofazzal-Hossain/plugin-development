<?php
/*
 * Plugin Name:       Roles Demo
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a roles demo plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       roles-demo
 * Domain Path:       /languages
*/


add_action('admin_enqueue_scripts', function ($hook) {
    if ('toplevel_page_roles-demo' == $hook) {
        wp_enqueue_style('pure-grid-css', '//unpkg.com/purecss@1.0.1/build/grids-min.css');
        wp_enqueue_style('roles-demo-css', plugin_dir_url(__FILE__) . "assets/css/style.css", null, time());
        wp_enqueue_script('roles-demo-js', plugin_dir_url(__FILE__) . "assets/js/main.js", array('jquery'), time(), true);
        $nonce = wp_create_nonce('display_result');
        wp_localize_script(
            'roles-demo-js',
            'plugindata',
            array('ajax_url' => admin_url('admin-ajax.php'), 'nonce' => $nonce)
        );
    }
});

add_action('wp_ajax_display_result', function () {

    if (wp_verify_nonce($_POST['nonce'], 'display_result')) {
        $task = $_POST['task'];
        if ('current-user-details' == $task) {
            $current_user = wp_get_current_user();
            $user_email = $current_user->user_email;

            echo $user_email . '<br>';
            // print_r($current_user);
            if (is_user_logged_in()) {
                echo 'someone is loggin';
            }
        } elseif ('any-user-detail' == $task) {
            $user = new WP_User(1);
            echo $user->user_email . '<br>';
            print_r($user);
        } elseif ('current-role' == $task) {
            $user = new WP_User(1);
            foreach ($user->roles as $role) {
                echo $role;
            }
            // print_r($user);
        } elseif ('all-roles' == $task) {
            global $wp_roles;
            // print_r($wp_roles);
            echo "<h3>use WP Roles global veriable</h3>";
            foreach ($wp_roles->roles as $role) {
                echo $role['name'] . '<br>';
            }

            $roles = get_editable_roles();
            echo '<hr>';
            echo "<h3>use get editable role function</h3>";
            foreach ($roles as $role) {

                echo $role['name'] . '<br>';
            }
        } elseif ('current-capabilities' == $task) {
            $current_user = wp_get_current_user();

            print_r($current_user->allcaps);
        } elseif ('check-user-cap' == $task) {
        } elseif ('create-user' == $task) {
            $create_user = wp_create_user('Mofazzal Hossain', 'rajon123', 'rajon@gmail.com');
            if (is_wp_error($create_user)) {
                echo 'Sorry, that user is already create!';
            } else {
                echo 'User succssfully created: ' . $create_user;
            }
            // print_r($create_user);


        } elseif ('set-role' == $task) {
            $user = new WP_User(2);
            $user->remove_role('subscriber');
            $user->add_role('author');
            print_r($user);
        } elseif ('login' == $task) {
            /*
                $user = wp_authenticate('Mofazzal Hossain', 'rajon123');
                if(is_wp_error($user)){
                    echo 'failed';
                }else{
                    wp_set_current_user($user->ID);
                    wp_set_auth_cookie($user->ID);
                    echo $user->user_email;
                }
                // print_r($user);
            */

            /*
                $user = wp_signon(
                    [
                        'user_login' => 'Mofazzal Hossain',
                        'user_password' => 'rajon123',
                        'remember' => true
                    ],
                );

                if(is_wp_error($user)){
                    echo 'failed';
                }else{
                    wp_set_current_user($user->ID);
                    echo $user->user_email;
                }
            */

            wp_set_auth_cookie(2);
        } elseif ('users-by-role' == $task) {
            $user = get_users(['role' => 'author', 'orderby' => 'user_email', 'order' => 'desc']);
            print_r($user);
        } elseif ('change-role' == $task) {
            $user = new WP_User(2);
            // $user->remove_cap('administration');
            // $user->remove_role('administration');
            $user->set_role('administrator');
            print_r($user);
        } elseif ('create-role' == $task) {
            $role = add_role('super_admin', __('Super Admin', 'roles-demo'), [
                'read' => true,
                'activate_plugins' => true,

            ]);

            $user = new WP_User(2);
            $user->remove_cap('roles-demo');
            $user->remove_cap('Roles Demo');
            print_r($user);

            if ($user) {
                $user->set_role('super_admin');
                $user->add_cap('edit_posts');
                $user->add_cap('edit_pages');
                $user->add_cap('manage_options');
                $user->add_cap('edit_dashboard');
                $user->add_cap('switch_themes');
            }
        }
    }
    die(0);
});

add_action('admin_menu', function () {
    add_menu_page('Roles Demo', 'Roles Demo', 'manage_options', 'roles-demo', 'rolesdemo_admin_page');
});

function rolesdemo_admin_page()
{
?>
    <div class="container" style="padding-top:20px;">
        <h1>Roles Demo</h1>
        <div class="pure-g">
            <div class="pure-u-1-4" style='height:100vh;'>
                <div class="plugin-side-options">
                    <button class="action-button" data-task='current-user-details'>Get Current User Details</button>
                    <button class="action-button" data-task='any-user-detail'>Get Any User Details</button>
                    <button class="action-button" data-task='current-role'>Detect Any User Role</button>
                    <button class="action-button" data-task='all-roles'>Get All Roles List</button>
                    <button class="action-button" data-task='current-capabilities'>Current User Capability</button>
                    <button class="action-button" data-task='check-user-cap'>Check User Capability</button>
                    <button class="action-button" data-task='create-user'>Create A New User</button>
                    <button class="action-button" data-task='set-role'>Assign Role To A New User</button>
                    <button class="action-button" data-task='login'>Login As A User</button>
                    <button class="action-button" data-task='users-by-role'>Find All Users From Role</button>
                    <button class="action-button" data-task='change-role'>Change User Role</button>
                    <button class="action-button" data-task='create-role'>Create New Role</button>
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
