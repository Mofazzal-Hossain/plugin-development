<?php

/*
 * Plugin Name:       Dashboard Widget
 * Plugin URI:        https://example.com/my-plugin/
 * Description:       This is a Dashboard Widget plugin.
 * Version:           1.0.0
 * Author:            Mofazzal Hossain
 * Author URI:        https://github.com/Mofazzal-Hossain
 * License:           GPL v2 or later
 * Text Domain:       dashboard-widget
 * Domain Path:       /languages
 */

function dbwd_plugins_load()
{
    load_plugin_textdomain('dashboard-widget', false, plugin_dir_path(__FILE__) . 'languages');
}
add_action('plugins_loaded', 'dbwd_plugins_load');


function dbwd_dashboard_custom_widget()
{
    if (current_user_can('edit_dashboard')) {
        wp_add_dashboard_widget(
            'demodashboardwidget',
            __('Fullstro Recent Posts', 'dashboard-widget'),
            'demo_dashboard_widget_callback',
            'demo_dashboard_widget_configure'
        );
    } else {
        wp_add_dashboard_widget(
            'demodashboardwidget',
            __('Fullstro Recent Posts', 'dashboard-widget'),
            'demo_dashboard_widget_callback'
        );
    }
}

add_action('wp_dashboard_setup', 'dbwd_dashboard_custom_widget');

// Fullstro recent post fetch
function demo_dashboard_widget_callback()
{
    $post_feed_url = get_option('post-feed-url', 'https://fullstro.com/feed/');
    $posts_number = get_option('number-of-posts', 4);
    $post_author_val = get_option('post-author', 0);
    $post_summary_val = get_option('post-summary', 0);
    $post_date_val = get_option('post-date', 0);

    $fullstro_feed = array(
        array(
            'url' => $post_feed_url,
            'items' => $posts_number,
            'show_author' => $post_author_val,
            'show_summary' => $post_summary_val,
            'show_date' => $post_date_val
        )
    );

    wp_dashboard_primary_output('demodashboardwidget', $fullstro_feed);
}

function demo_dashboard_widget_configure()
{
    $post_feed_url = get_option('post-feed-url', 'https://fullstro.com/feed/');
    $posts_number = get_option('number-of-posts', 4);
    $post_author_val = get_option('post-author', 0);
    $post_summary_val = get_option('post-summary', 0);
    $post_date_val = get_option('post-date', 0);

    if (isset($_POST['dashboard-widget-nonce']) && wp_verify_nonce($_POST['dashboard-widget-nonce'], 'edit-dashboard-widget_demodashboardwidget')) {

        $feed_url = isset($_POST['feed_url']) ? esc_url($_POST['feed_url']) : 4;
        $numberOfPosts = isset($_POST['numberOfPosts']) ? intval($_POST['numberOfPosts']) : 4;
        $post_author = isset($_POST['post_author']) ? intval($_POST['post_author']) : 0;
        $post_summary = isset($_POST['post_summary']) ? intval($_POST['post_summary']) : 0;
        $post_date = isset($_POST['post_date']) ? intval($_POST['post_date']) : 0;

        update_option('post-feed-url', $feed_url);
        update_option('number-of-posts', $numberOfPosts);
        update_option('post-author', $post_author);
        update_option('post-summary', $post_summary);
        update_option('post-date', $post_date);
    }
?>
    <p class="form-group">
        <label for="feed_url"><?php echo esc_html('Feed URL: ', 'dashboard-widget'); ?></label>
        <input class="widefat" type="url" name="feed_url" id="feed_url" value="<?php echo esc_attr($post_feed_url); ?>">
    </p>
    <p class="form-group">
        <label for="numberOfPosts"><?php echo esc_html('Number of posts', 'dashboard-widget'); ?></label>
        <input class="widefat" type="number" name="numberOfPosts" id="numberOfPosts" value="<?php echo esc_attr($posts_number); ?>">
    </p>
    <p class="form-group">
        <label for="post_author"><?php echo esc_html('Do you want to show post author?', 'dashboard-widget'); ?></label>
        <input type="checkbox" name="post_author" id="post_author" value="1" <?php echo (1 == $post_author_val) ? 'checked' : '' ?>>
    </p>
    <p class="form-group">
        <label for="post_summary"><?php echo esc_html('Do you want to show post summary?', 'dashboard-widget'); ?></label>
        <input type="checkbox" name="post_summary" id="post_summary" value="1" <?php echo (1 == $post_summary_val) ? 'checked' : '' ?>>
    </p>
    <p class="form-group">
        <label for="post_date"><?php echo esc_html('Do you want to show post date?', 'dashboard-widget'); ?></label>
        <input type="checkbox" name="post_date" id="post_date" value="1" <?php echo (1 == $post_date_val) ? 'checked' : '' ?>>
    </p>
<?php
}
