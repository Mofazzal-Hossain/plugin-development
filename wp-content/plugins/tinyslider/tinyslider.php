<?php

/*
 * Plugin Name:       Tiny Slider
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a Tiny Slider plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       tinyslider
 * Domain Path:       /languages
 */

//  textdomain
function tinyslider_load_textdomain()
{
    load_plugin_textdomain('tinyslider', false, dirname(__FILE__) . '/languages');
}
add_action('plugin_loaded', 'tinyslider_load_textdomain');


// function tinyslider_plugin_activation()
// {
// }
// register_activation_hook(__FILE__, 'tinyslider_plugin_activation');

// function tinyslider_plugin_deactivation()
// {
// }
// register_deactivation_hook(__FILE__, 'tinyslider_plugin_deactivation');

function tinyslider_assets()
{
    wp_enqueue_style('tinyslider-css', '//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css', null, '2.9.4');
    wp_enqueue_script('tinyslider-js', '//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/min/tiny-slider.js', null, '2.9.4', true);
    wp_enqueue_script('tinyslider-main-js', plugin_dir_url(__FILE__) . 'assets/js/tinyslider-main.js', array('jquery', 'tinyslider-js'), time(), true);
}
add_action('wp_enqueue_scripts', 'tinyslider_assets');

function tinyslider_init()
{
    add_image_size('tinyslide_size', 500, 500, true);
}
add_action('init', 'tinyslider_init');

function tinyslider_tslider($arguments, $content)
{
    $defaults = array(
        'width' => 800,
        'height' => 600,
        'id' => '',
    );
    $attributes = shortcode_atts($defaults, $arguments);
    $contents = do_shortcode($content);

    $output_shortcode = <<<HTML
        <div id="{$attributes['id']}" class="tinyslider_container" style="width:{$attributes['width']}px;height:{$attributes['height']}px">
            <div id="tinySlider">
                $contents
            </div>
        </div>
    HTML;

    return $output_shortcode;
}

add_shortcode('tslider', 'tinyslider_tslider');

function tinyslider_tslide($arguments)
{

    $defaults = array(
        'id' => '',
        'size' => 'tinyslide_size',
        'caption' => '',
    );

    $attributes = shortcode_atts($defaults, $arguments);
    $image_url  = wp_get_attachment_image_src($attributes['id'], $attributes['size']);
    // error_log(print_r($image_url,true));
    $output_shortcode = <<<HTML
        <div class='slide'>
            <img src="{$image_url[0]}" alt="{$attributes['caption']}">
            <p>{$attributes['caption']}</p>
        </div>
    HTML;

    return $output_shortcode;
}

add_shortcode('tslide', 'tinyslider_tslide');
