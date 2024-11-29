<?php 

/*
 * Plugin Name:       Word Count
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a plugin that counts the number of words in a post.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       word-count
 * Domain Path:       /languages
 */

// plugin activation hook
function wordcount_activation_hook() {}
register_activation_hook(__FILE__, 'wordcount_activation_hook');

// plugin deactivation hook
function wordcount_deactivation_hook() {}
register_deactivation_hook(__FILE__, 'wordcount_deactivation_hook');

// plugin textdomain loaded

function wordcount_load_textdomain() {
    load_plugin_textdomain('word-count', false, dirname(__FILE__) .'/languages');
}

add_action('plugins_loaded', 'wordcount_load_textdomain');

// word count a post
function wordcount_content_count($content) {
    if(is_singular('post')){
        $stripped_content = strip_tags($content);

        // total number of words
        $word_label = __('Total number of words', 'word-count');
        $word_count = str_word_count($stripped_content);
        $word_label = apply_filters('wordcount_word_label',$word_label);
        $word_tag   = apply_filters('wordcount_word_tag', 'h2');

        // total number of strings
        $string_label = __('Total number of strings: ', 'word-count');
        $string_count = strlen($stripped_content);
        $string_label = apply_filters('wordcount_string_label',$string_label);
        $string_tag   = apply_filters('wordcount_string_tag', 'h2');

        $content .= sprintf('<%s> %s:  %s</%s>',$word_tag,$word_label,$word_count,$word_tag );
        $content .= sprintf('<%s> %s:  %s</%s>',$string_tag,$string_label,$string_count,$string_tag );


        return $content;
    }
}
add_filter('the_content', 'wordcount_content_count');

function wordcount_word_reading_time($content) {
    $stripped_content = strip_tags($content);
    $total_word = str_word_count($stripped_content);
    // $total_word =900;
    $reading_min = floor($total_word / 238);
    $reading_sec = floor($total_word % 238 /(238/60));

    $label = __('Total word reading time: ', 'word-count');
    $label = apply_filters('wordcount_reading_label', $label);

    $content.= sprintf('<h3>%s %s Minutes %s Seconds</h3>',$label, $reading_min, $reading_sec);

    // error_log(print_r($reading_min, true));
    // error_log(print_r($reading_sec, true));

    return $content;
}
add_filter('the_content', 'wordcount_word_reading_time');