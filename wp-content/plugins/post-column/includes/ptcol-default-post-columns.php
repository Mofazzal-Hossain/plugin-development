<?php




// manage post columns
function ptcol_post_columns($columns)
{

    if (get_post_type() == 'post') {
        unset($columns['date']);

        // error_log(print_r($columns, true));
        unset($columns['comments']);
        unset($columns['author']);
        unset($columns['categories']);
        unset($columns['tags']);

        $columns['id'] = __('Post ID', 'post-column');
        $columns['author'] = __('Author', 'post-column');
        $columns['categories'] = __('Categories', 'post-column');

        $columns['thumbnail'] = __('Thumbnail', 'post-column');
        $columns['wordcount'] = __('Word Count', 'post-column');
        $columns['date'] = __('Date', 'post-column');
    }

    return $columns;
}
add_filter('manage_posts_columns', 'ptcol_post_columns');

// manage posts custom column
function ptcol_update_post_column($columns, $post_id)
{
    if (get_post_type() == 'post') {
        if ('id' == $columns) {
            echo $post_id;
        } elseif ('thumbnail' == $columns) {
            $thumbnail = get_the_post_thumbnail($post_id, array(80, 80));
            echo $thumbnail;
        } elseif ('wordcount' == $columns) {
            // $content = get_the_content($post_id);
            // $count = str_word_count(strip_tags($content));
            // echo $count;
            $wordn = get_post_meta($post_id, 'wordcount', true);
            echo $wordn;
        }
    }
}
add_action('manage_posts_custom_column', 'ptcol_update_post_column', 10, 2);

function ptcol_post_column_sortable($columns)
{
    if (get_post_type() == 'post') {
        $columns['id'] = 'id';

        $columns['wordcount'] = 'wordcount';
    }
    return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'ptcol_post_column_sortable');

// function ptcol_init()
// {
//     $posts = get_posts(
//         [
//             'posts_per_page' => -1,
//             'post_type' => 'post',
//             'post_status' => 'any',
//         ]
//     );

//     foreach($posts as $post){
//         // error_log(print_r($post, true));
//         $post_content = strip_tags($post->post_content);
//         $content_count = str_word_count($post_content);
//         update_post_meta($post->ID, 'wordcount', $content_count);
//     }

// }
// add_action('init', 'ptcol_init');

function ptcol_pre_posts($query)
{
    if (!is_admin()) {
        return false;
    }

    if (get_post_type() == 'post') {
        $orderby = $query->get('orderby');

        if (get_post_type() == 'post') {
            if ('wordcount' == $orderby) {
                $query->set('meta_key', 'wordcount');
                $query->set('orderby', 'meta_value_num');
            }
        }


        if ('id' == $orderby) {
            $query->set('orderby', 'ID');
        }
    }
    // error_log(print_r($orderby, true));
}

add_action('pre_get_posts', 'ptcol_pre_posts');

function ptcol_save_content_count($post_id)
{
    if (get_post_type() == 'post') {
        $_post = get_post($post_id);
        $content = $_post->post_content;
        $wordcount = strip_tags($content);
        $wordcount = str_word_count($wordcount);
        // error_log(print_r($wordcount, true));
        update_post_meta($post_id, 'wordcount', $wordcount);
    }
}
add_action('save_post', 'ptcol_save_content_count');


function ptcol_post_wordcount_selection()
{
    $wordcount_options = array(
        '1' => 'Above 400',
        '2' => '300 to 400',
        '3' => '200 to 300',
        '4' => 'Below 200'
    );
    $current_option = isset($_GET['post_word_count']) ? $_GET['post_word_count'] : '';
?>
    <select name="post_word_count" id="post_word_count">
        <option disabled selected><?php esc_html_e('Filter by wordcount', 'post-column'); ?></option>
        <?php foreach ($wordcount_options as $key => $option) :
            $selected = ($current_option == $key) ? 'selected' : '';
        ?>
            <option value="<?php echo esc_attr($key); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_html($option); ?></option>
        <?php endforeach; ?>
    </select>
<?php
}
add_action('restrict_manage_posts', 'ptcol_post_wordcount_selection');

function ptcol_post_wordcount_filter_update($query)
{
    if (is_admin() && is_main_query()) {
        $selected_option = isset($_GET['post_word_count']) ? $_GET['post_word_count'] : '';
        if ('1' == $selected_option) {
            $query->set('meta_query', array(
                array(
                    'key' => 'wordcount',
                    'value' => 400,
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                ),
            ));
        } elseif ('2' == $selected_option) {
            $query->set('meta_query', array(
                array(
                    'key' => 'wordcount',
                    'value' => array(300,400),
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC'
                ),
            ));
        }
        elseif ('3' == $selected_option) {
            $query->set('meta_query', array(
                array(
                    'key' => 'wordcount',
                    'value' => array(200,300),
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC'
                ),
            ));
        }elseif ('4' == $selected_option) {
            $query->set('meta_query', array(
                array(
                    'key' => 'wordcount',
                    'value' => 200,
                    'compare' => '<',
                    'type' => 'NUMERIC'
                ),
            ));
        }
    }
}
add_action('pre_get_posts', 'ptcol_post_wordcount_filter_update');
