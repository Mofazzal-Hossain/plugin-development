<?php

/*
 * Plugin Name:       Carbon Ninja
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a carbon ninja plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       carbon-ninja
 * Domain Path:       /languages
*/

use Carbon_Fields\Container;
use Carbon_Fields\Field;

require_once('vendor/autoload.php');

// Define a version constant
define('CARBON_VERSION', '1.0.0');

function crbnninja_plugin_loaded()
{
    load_plugin_textdomain('carbon-ninja', false, dirname(plugin_basename(__FILE__)) . '/languages');
    \Carbon_Fields\Carbon_Fields::boot();
}
add_action('plugins_loaded', 'crbnninja_plugin_loaded');

function crbnninja_register_metabox()
{
    Container::make('post_meta', __('Post metabox', 'carbon-ninja'))
        ->where('post_type', '=', 'page')
        // ->set_priority('high') // Optionally set the priority
        ->add_fields(array(
            Field::make('text', 'crbnninja_number_posts', __('Number of posts', 'carbon-ninja')),
            Field::make('text', 'crbnninja_count_characters', __('Number of words', 'carbon-ninja')),
            Field::make( 'checkbox', 'crb_show_content', __( 'Show Content' ) )
        ));
   
}
add_action('carbon_fields_register_fields', 'crbnninja_register_metabox');

