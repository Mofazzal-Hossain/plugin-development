<?php

/*
 * Plugin Name:       Assets Ninja
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a Assets Ninja plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       assets-ninja
 * Domain Path:       /languages
 */

// textdomain
// function asn_lanaguage_load()
// {
//     load_plugin_textdomain('asn', false, dirname(__FILE__) . '/languages');
// }
// add_action('plugin_loaded', 'asn_lanaguage_load');

/*
// class && method/function
    class calculation{
        // properties
        public $a, $b, $c;

        // method/function
        function sum()
        {
            $this->c = $this->a + $this->b;
            return $this->c;
        }
        // method/function
        function sub()
        {
            $this->c = $this->a - $this->b;
            return $this->c;
        }
    }
    // object
    $c1 = new calculation();
    // another object
    $r2 = new calculation();

    //assign value
    $c1->a = 10;
    $c1->b = 20;

    error_log(print_r($c1->sum(), true));
*/


// class && constructor

/*
    class person{
        public $name,$age,$profession;

        function __construct($n,$a,$p)
        {
            $this->name = $n;
            $this->age = $a;
            $this->profession = $p;
        }

    }

    $class_object = new person('Mofazzal Hossain', 26, 'Developer');
    $class_object2 = new person('Habib Khan', 28, 'Designer');

    error_log(print_r($class_object, true));
    error_log(print_r($class_object2, true));

*/
// plugin directory
define('ASN_PLUGIN_DIR', plugin_dir_url(__FILE__));
define('ASN_PUBLIC_ASSETS_DIR', ASN_PLUGIN_DIR . 'public');
define('ASN_ADMIN_ASSETS_DIR', ASN_PLUGIN_DIR . 'admin');

class assetsNinja
{
    private $version;
    function __construct()
    {
        $this->version = time();
        add_action('init', array($this, 'asn_init'));
        add_action('plugin_loaded', array($this, 'load_textdomain'));
        add_action('wp_enqueue_scripts', array($this, 'load_public_assets'));
        add_action('admin_enqueue_scripts', array($this, 'load_admin_assets'));
        add_shortcode('asnmedia', array($this, 'asn_media_shortcode'));
    }


    function asn_media_shortcode($attributes)
    {

        $id = wp_get_attachment_image_src($attributes['id']);

        $asnmedia_url = $id[0];
        update_option('asn_media_url',$asnmedia_url);
        $output = <<<HTML
            <div id="asn-media"></div>
        HTML;
        return $output;
    }
    // plugin init
    function asn_init()
    {
        wp_deregister_style('fontawesome-css');
        wp_register_style('fontawesome-css', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css');
    }

    // text domain
    function load_textdomain()
    {
        load_plugin_textdomain('assets-ninja', false, ASN_PLUGIN_DIR . '/languages');
    }

    // public assets enqueue
    function load_public_assets()
    {
        $css_files = array(
            'asn-custom-css' => array('path' => ASN_PUBLIC_ASSETS_DIR . '/css/custom.css'),
        );
        // wp_enqueue_style('asn-custom-css', ASN_PUBLIC_ASSETS_DIR . '/css/custom.css', null, '1.0');

        foreach ($css_files as $handle => $file) {
            wp_enqueue_style($handle, $file['path'], null, $this->version);
        }

        $image_url = get_option('asn_media_url');
        $bg_data = 
            <<<EOD
                #asn-media{
                    background-image: url( $image_url);
                }
            EOD;
        wp_add_inline_style('asn-custom-css', $bg_data);
        // asn_media_url($data);
        // $url = asn_media_url();
        // error_log(print_r($url, true));

        // wp_enqueue_script('asn-main-js', ASN_PUBLIC_ASSETS_DIR . '/js/main.js', array('jquery'), $this->version, true);


        $js_files = array(
            'asn-main-js' => array('path' => ASN_PUBLIC_ASSETS_DIR . '/js/main.js', 'dep' => array('jquery')),
            // 'asn-main-js' => array('path' => ASN_PUBLIC_ASSETS_DIR . '/js/main.js', 'dep' => array('jquery', 'asn-another-js')),
            // 'asn-another-js' => array('path' => ASN_PUBLIC_ASSETS_DIR . '/js/another.js', 'dep' => array('jquery', 'asn-more-js')),
            // 'asn-more-js' => array('path' => ASN_PUBLIC_ASSETS_DIR . '/js/more.js', 'dep' => array('jquery')),
        );

        foreach ($js_files as $handle => $jsfile) {
            wp_enqueue_script($handle, $jsfile['path'], $jsfile['dep'], $this->version, true);
        }

        // wp_enqueue_script('asn-main-js', ASN_PUBLIC_ASSETS_DIR . '/js/main.js', array('jquery', 'asn-another-js'), $this->version, true);
        // wp_enqueue_script('asn-another-js', ASN_PUBLIC_ASSETS_DIR . '/js/another.js', array('jquery', 'asn-more-js'), $this->version, true);
        // wp_enqueue_script('asn-more-js', ASN_PUBLIC_ASSETS_DIR . '/js/more.js', array('jquery'), $this->version, true);

        $data = array(
            'name' => 'Mofazzal Hossain',
            'age' => '26',
            'profession' => 'Developer'
        );

        wp_localize_script('asn-main-js', 'mydata', $data);
    }



    // admin assets enqueue
    function load_admin_assets($screen)
    {
        $current_screen = get_current_screen();

        wp_enqueue_style('asn-custom-css', ASN_ADMIN_ASSETS_DIR . '/css/custom.css', null, '1.0');

        if ('edit.php' == $screen && 'page' == $current_screen->post_type) {
            wp_enqueue_script('asn-custom-js', ASN_ADMIN_ASSETS_DIR . '/js/custom.js', array('jquery'), $this->version, true);
        }
    }
}

new assetsNinja();
