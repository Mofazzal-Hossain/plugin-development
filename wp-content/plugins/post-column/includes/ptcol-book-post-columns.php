<?php


// manage post columns
function ptcol_book_post_columns($columns)
{

    if ('book' == get_post_type()) {
        unset($columns['date']);
        $columns['id'] = __('Post ID', 'post-column');
        $columns['chapter'] = __('Chapter', 'post-column');
        $columns['thumbnail'] = __('Thumbnail', 'post-column');
        $columns['date'] = __('Date', 'post-column');
    }

    return $columns;
}
add_filter('manage_posts_columns', 'ptcol_book_post_columns');

// posts custom column
function ptcol_update_book_post_column($columns, $post_id)
{
    if ('book' == get_post_type()) {
        if ('id' == $columns) {
            echo $post_id;
        } elseif ('thumbnail' == $columns) {
            $thumbnail = get_the_post_thumbnail($post_id, array(80, 80));
            echo $thumbnail;
        } elseif ('chapter' == $columns) {
            $chapter_cat = get_the_term_list($post_id, 'chapters');
            echo $chapter_cat;
        }
    }
}
add_action('manage_posts_custom_column', 'ptcol_update_book_post_column', 10, 2);

function ptcol_book_post_column_sortable($columns)
{
    $columns['id'] = 'id';
    $columns['chapter'] = 'chapter';
    return $columns;
}
add_filter('manage_edit-book_sortable_columns', 'ptcol_book_post_column_sortable');


function ptcol_pre_book_posts($query)
{
    if (!is_admin()) {
        return false;
    }

    $orderby = $query->get('orderby');

    // book chapter filter
    $get_book_chapter = isset($_GET['book_chapters']) ? $_GET['book_chapters'] : '';
    if ($_GET['post_type'] == 'book' && $get_book_chapter) {
        $chapter = isset($_GET['book_chapters']) ? $_GET['book_chapters'] : '';
        // error_log(print_r($query, true));
        $query->query_vars['tax_query'] = array(
            array(
                'taxonomy' => 'chapters',
                'field' => 'slug',
                'terms' => $chapter,
            )
        );
    }

    if ('id' == $orderby) {
        $query->set('orderby', 'ID');
    }
    // error_log(print_r($orderby, true));
}

add_action('pre_get_posts', 'ptcol_pre_book_posts');


function ptcol_manage_posts_filter()
{

    if ($_GET['post_type'] == 'book') {
        $chapter_cat = get_terms(
            array(
                'taxonomy' => 'chapters',
                'hide_empty' => true,
            ),
        );
        $selected_chapter = isset($_GET['book_chapters']) ? $_GET['book_chapters'] : '';

?>
        <select name="book_chapters" id="book_chapters">
            <option disabled selected><?php echo esc_html_e('Select a chapter', 'post-column'); ?></option>
            <?php foreach ($chapter_cat as $cat) :
                $selected = ($cat->slug == $selected_chapter) ? 'selected' : '';
            ?>
                <option value="<?php echo esc_attr($cat->slug); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_html_e($cat->name); ?></option>
            <?php endforeach; ?>
        </select>
    <?php
    }
}
add_action('restrict_manage_posts', 'ptcol_manage_posts_filter');

function ptcol_book_post_thumbnail_filter()
{
    $select_options = array(
        '1' => 'Has Thumbnail',
        '2' => 'No Thumbnail'
    );
    $current_option = isset($_GET['book_thumbnail']) ? $_GET['book_thumbnail'] : '';
    ?>
    <select name="book_thumbnail" id="book_thumbnail">
        <option selected disabled><?php esc_html_e('Filter by thumbnail', 'post-column'); ?></option>
        <?php foreach ($select_options as $key => $option) :
            $selected = ($current_option == $key) ? 'selected' : '';
        ?>
            <option value="<?php echo esc_attr($key); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_html($option); ?></option>
        <?php endforeach; ?>
    </select>
<?php
}

add_action('restrict_manage_posts', 'ptcol_book_post_thumbnail_filter');

function ptcol_book_post_thumbnail_filter_update($query)
{
    if (is_admin() && $query->is_main_query()) {
        $thumbnail = isset($_GET['book_thumbnail']) ? $_GET['book_thumbnail'] : '';
        if ('1' == $thumbnail) {
            $query->set('meta_query', array(
                array(
                    'key' => '_thumbnail_id',
                    'compare' => 'EXISTS'
                )
            ));
        } elseif ('2' == $thumbnail) {
            $query->set('meta_query', array(
                array(
                    'key' => '_thumbnail_id',
                    'compare' => 'NOT EXISTS'
                )
            ));
        }
    }
}
add_action('pre_get_posts', 'ptcol_book_post_thumbnail_filter_update');
