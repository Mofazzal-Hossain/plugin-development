<?php
/*
 * Plugin Name:       Login Form Customization
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a Login Form Customization plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       lfc
 * Domain Path:       /languages
*/

function lfc_login_form_logo()
{ ?>
    <style type="text/css">
        #login h1 a,
        .login h1 a {
            background-image: url(<?php echo plugin_dir_url(__FILE__) . 'assets/images/wplogo.png' ?>);
            height: 65px;
            width: 320px;
            background-repeat: no-repeat;
            padding-bottom: 30px;
        }
    </style>
<?php }
add_action('login_enqueue_scripts', 'lfc_login_form_logo');

function my_login_logo_url()
{
    return home_url();
}
add_filter('login_headerurl', 'my_login_logo_url');

function lfc_login_logo_url_title()
{
    return get_bloginfo('name');
}
add_filter('login_headertext', 'lfc_login_logo_url_title');


add_action('login_head', function () {
    add_filter('gettext', function ($translate_text, $text_to_translate, $textdomain) {
        if('Username or Email Address' == $text_to_translate){
            $translate_text = 'Your Username Key';
        }elseif('Password' == $text_to_translate){
            $translate_text = 'Your Pass Key';
        }elseif('Lost your password?' == $text_to_translate){
            $translate_text = 'Could not login? lost/forget your password?';
        }elseif('Log In' == $text_to_translate){
            $translate_text = 'Sign In';
        }
        return $translate_text;
    }, 10, 3);
});

function lfc_login_stylesheet() {
    wp_enqueue_style( 'lfc-login-css', plugin_dir_url(__FILE__) . 'assets/css/style-login.css' );
    wp_enqueue_script( 'lfc-login-js', plugin_dir_url(__FILE__) . 'assets/js/style-login.js' );
}
add_action( 'login_enqueue_scripts', 'lfc_login_stylesheet' );