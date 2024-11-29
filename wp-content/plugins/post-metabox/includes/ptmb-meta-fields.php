<?php

// nonce security function
function ptmb_metabox_secured($nonce_field, $nonce_action, $post_id)
{
    $nonce = isset($_POST[$nonce_field]) ? $_POST[$nonce_field] : '';

    if ($nonce == '') {
        return false;
    }
    if (!wp_verify_nonce($nonce, $nonce_action)) {
        return false;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return false;
    }

    if (wp_is_post_autosave($post_id)) {
        return false;
    }

    if (wp_is_post_revision($post_id)) {
        return false;
    }
    return true;
}


// add post metabox
function ptmb_post_metabox()
{
    $post_type = array('post', 'page');
    add_meta_box(
        'ptmb_location',
        __('Location Info', 'post-metabox'),
        'ptmb_display_location_meta',
        $post_type,
        'advanced',
        'high'
    );

    add_meta_box(
        'ptmb_book',
        __('Book Info', 'post-metabox'),
        'ptmb_display_book_meta',
        array('book'),
        'advanced',
        'high'
    );
}

// metabox info
add_action('admin_menu', 'ptmb_post_metabox');


// post metabox location meta
function ptmb_display_location_meta($post)
{
    $post_id = $post->ID;
    $country = get_post_meta($post_id, 'ptmb_country', true);
    $city = get_post_meta($post_id, 'ptmb_city', true);
    $favorite = get_post_meta($post_id, 'ptmb_favorite', true);
    $ptmb_colors = get_post_meta($post_id, 'ptmb_colors', true);
    $ptmb_color_redio = get_post_meta($post_id, 'ptmb_color_redio', true);
    $ptmb_color_select = get_post_meta($post_id, 'ptmb_color_select', true);
  
    $checked = ($favorite == 'yes') ? 'checked' : '';

    wp_nonce_field('ptmb_location_nonce', 'ptmb_location_nonce_field');
    $colors = array('red', 'blue', 'white', 'pink', 'black', 'purple');

?>
    <fieldset>
        <div class="row">
            <div class="col-3">
                <label for="ptmb_country" class="mb-2"><?php echo esc_html_e('Country', 'post-metabox'); ?></label>
                <input type="text" class="form-control" name="ptmb_country" id="ptmb_country" value="<?php echo esc_attr($country); ?>">
            </div>
            <div class="col-3">
                <label for="ptmb_city" class="mb-2"><?php echo esc_html_e('City', 'post-metabox'); ?></label>
                <input type="text" class="form-control" name="ptmb_city" id="ptmb_city" value="<?php echo esc_attr($city); ?>">
            </div>
            <div class="col-2">
                <label class="mb-2"><?php echo esc_html_e('Post favorite', 'post-metabox'); ?></label>
                <div class="form-check d-flex align-items-center">
                    <input class="form-check-input mt-0" type="checkbox" name="ptmb_favorite" value="yes" id="ptmb_favorite" <?php echo esc_attr($checked); ?>>
                    <label class="form-check-label" for="ptmb_favorite"><?php echo esc_html_e('is Favorite?', 'post-meta'); ?></label>
                </div>
            </div>
            <div class="col-2">
                <label class="mb-2"><?php echo esc_html_e('Colors Check', 'post-metabox'); ?></label>
                <?php foreach ($colors as $color) :
                    if(is_array($ptmb_colors)){
                        $color_checked = in_array($color, $ptmb_colors) ? 'checked' : [];
                    }
                    ?>
                    <div class="form-check d-flex align-items-center">
                        <input class="form-check-input mt-0" type="checkbox" name="ptmb_colors[]" value="<?php echo esc_attr($color); ?>" id="<?php echo esc_attr($color); ?>" <?php echo esc_attr($color_checked); ?>>
                        <label class="form-check-label" for="<?php echo esc_attr($color); ?>"><?php echo esc_html_e(strtoupper($color), 'post-meta'); ?></label>

                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-2">
                <label class="mb-2"><?php echo esc_html_e('Colors Radio', 'post-metabox'); ?></label>
                <?php foreach ($colors as $color) :
                    ?>
                    <div class="form-check d-flex align-items-center">
                        <input class="form-check-input mt-0" type="radio" name="ptmb_color_redio" value="<?php echo esc_attr($color); ?>" id="<?php echo esc_attr($color); ?>" <?php echo ($ptmb_color_redio == $color) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="<?php echo esc_attr($color); ?>"><?php echo esc_html_e(strtoupper($color), 'post-meta'); ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-12">
                <label class="mb-2"><?php echo esc_html_e('Select a color', 'post-metabox'); ?></label>
                <select class="form-select" name="ptmb_color_select" id="ptmb_color_select" aria-label="Default select example">
                    <option selected disabled>Open this select menu</option>
                    <?php foreach ($colors as $color) : ?>
                        <option value="<?php echo esc_attr($color); ?>" <?php echo ($color == $ptmb_color_select) ? 'selected' : ''; ?>><?php echo esc_html_e(strtoupper($color), 'post-metabox'); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </fieldset>
<?php

}

// post metabox book meta
function ptmb_display_book_meta($post)
{
    $post_id = $post->ID;

    $book_author = get_post_meta($post_id, 'ptmb_book_author', true);
    $book_isbn = get_post_meta($post_id, 'ptmb_book_isbn', true);
    $book_year = get_post_meta($post_id, 'ptmb_book_year', true);
    $book_chapters = get_post_meta($post_id, 'ptmb_book_chapter', true);
    $book_image_id = get_post_meta($post_id, 'ptmb_book_image_id', true);
    $book_image_url = get_post_meta($post_id, 'ptmb_book_image_url', true);
    $book_post_select = get_post_meta($post_id, 'ptmb_post_select', true);
    $book_post_category = get_post_meta($post_id, 'ptmb_post_category', true);

    $chapters = array('Title Page', 'Dedication Page', 'Table of Contents', 'Epigraph', 'Foreword', 'Acknowledgments Page', 'Prologue', 'Epilogue', 'Bibliography', 'Author Bio');

    wp_nonce_field('ptmb_book_nonce', 'ptmb_book_nonce_field');

    ?>
    <fieldset>
        <div class="row">
            <div class="col-4 mb-3">
                <label for="ptmb_book_author" class="mb-2"><?php echo esc_html_e('Book Author', 'post-metabox'); ?></label>
                <input type="text" class="form-control" name="ptmb_book_author" id="ptmb_book_author" value="<?php echo esc_attr($book_author); ?>">
            </div>
            <div class="col-4 mb-3">
                <label for="ptmb_book_isbn" class="mb-2"><?php echo esc_html_e('Book ISBN', 'post-metabox'); ?></label>
                <input type="text" class="form-control" name="ptmb_book_isbn" id="ptmb_book_isbn" value="<?php echo esc_attr($book_isbn); ?>">
            </div>
            <div class="col-4 mb-3">
                <label for="ptmb_book_year" class="mb-2"><?php echo esc_html_e('Publish Year', 'post-metabox'); ?></label>
                <input type="text" class="form-control" name="ptmb_book_year" id="ptmb_book_year" value="<?php echo esc_attr($book_year); ?>">
            </div>
            <div class="col-6">
                <label for="ptmb_book_chapter" class="mb-2 d-block"><?php echo esc_html_e('Book Chapter', 'post-metabox'); ?></label>
                <select name="ptmb_book_chapter[]" id="ptmb_book_chapter" multiple="multiple">
                    <?php foreach ($chapters as $chapter) :
                        if (is_array($book_chapters)) {
                            $selected_chapter = in_array($chapter, $book_chapters) ? 'selected' : '';
                        }
                    ?>
                        <option value="<?php echo esc_attr(strtolower($chapter)); ?>" <?php echo esc_attr($selected_chapter); ?>><?php echo esc_html_e($chapter, 'post-metabox'); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6">
                <label for="ptmb_book_image" class="mb-2 d-block"><?php echo esc_html_e('Book Cover Image', 'post-metabox'); ?></label>
                <button type="button" id="uploadBookImage" class="btn button-primary"><?php echo esc_html_e('Add image', 'post-metabox'); ?></button>
                <input type="hidden" name="ptmb_book_image_id" id="ptmbBookCoverId" value="<?php echo esc_attr($book_image_id); ?>">
                <input type="hidden" name="ptmb_book_image_url" id="ptmbBookCoverUrl" value="<?php echo esc_attr($book_image_url); ?>">
                <div class="image-container mt-3"></div>
                <!-- </?php if(!empty($book_image_url)):?>
                      <img src="</?php echo esc_url($book_image_url); ?>" width="250" height="250" class="mt-3 d-block" id="</?php echo esc_attr($book_image_id);?>" alt="Book cover">
                  </?php endif;?> -->
            </div>
            <div class="col-6 mt-4">
                <?php
                $args = array(
                    'post_type' => 'post',
                    'hide_empty' => true
                );
                $posts = get_posts($args);

                ?>
                <label for="ptmb_post_select" class="mb-2 d-block"><?php echo esc_html_e('All posts', 'post-metabox'); ?></label>
                <select name="ptmb_post_select" class="form-control" id="ptmb_post_select">
                    <option selected disabled><?php echo esc_html_e('Select a post', 'post-metabox'); ?></option>
                    <?php foreach ($posts as $post) :
                        $option_value = str_replace(' ', '-', strtolower($post->post_title));
                        $selected = ($option_value == $book_post_select) ? 'selected' : '';
                    ?>
                        <option value="<?php echo esc_attr($option_value); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_html_e($post->post_title, 'post-metabox'); ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-6 mt-4">
                <?php
                $categories = get_terms([
                    'taxonomy' => 'category',
                    'hide_empty' => false,
                ]);
                ?>
                <label for="ptmb_post_select" class="mb-2 d-block"><?php echo esc_html_e('All Category', 'post-metabox'); ?></label>
                <select name="ptmb_post_category" class="form-control" id="ptmb_post_category">
                    <option selected disabled><?php echo esc_html_e('Select a category', 'post-metabox'); ?></option>
                    <?php foreach ($categories as $cat) :
                        $selected = ($cat->name == $book_post_category) ? 'selected' : '';
                    ?>
                        <option value="<?php echo esc_attr($cat->name); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_html_e($cat->name, 'post-metabox'); ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
    </fieldset>
<?php

}

// post metabox value save
function ptmb_post_meta_save($post_id){

    // post location metabox
    if (isset($_POST['ptmb_location_nonce_field'])) {

        $location = isset($_POST['ptmb_country']) ? $_POST['ptmb_country'] : '';
        $city = isset($_POST['ptmb_city']) ? $_POST['ptmb_city'] : '';
        $favorite = isset($_POST['ptmb_favorite']) ? $_POST['ptmb_favorite'] : '';
        $ptmb_colors = isset($_POST['ptmb_colors']) ? $_POST['ptmb_colors'] : array();
        $ptmb_color_redio = isset($_POST['ptmb_color_redio']) ? $_POST['ptmb_color_redio'] : '';
        $ptmb_color_select = isset($_POST['ptmb_color_select']) ? $_POST['ptmb_color_select'] : array();

        if (!ptmb_metabox_secured('ptmb_location_nonce_field', 'ptmb_location_nonce', $post_id)) {
            return $post_id;
        }

        if ('' == $location || '' == $city) {
            return $post_id;
        }

        // post meta value update
        update_post_meta($post_id, 'ptmb_country', $location);
        update_post_meta($post_id, 'ptmb_city', $city);
        update_post_meta($post_id, 'ptmb_favorite', $favorite);
        update_post_meta($post_id, 'ptmb_colors', $ptmb_colors);
        update_post_meta($post_id, 'ptmb_color_redio', $ptmb_color_redio);
        update_post_meta($post_id, 'ptmb_color_select', $ptmb_color_select);
    }

    // book post type metabox
    if (isset($_POST['ptmb_book_nonce_field'])) {

        // book meta field value
        $book_author = isset($_POST['ptmb_book_author']) ? $_POST['ptmb_book_author'] : '';
        $book_isbn = isset($_POST['ptmb_book_isbn']) ? $_POST['ptmb_book_isbn'] : '';
        $book_year = isset($_POST['ptmb_book_year']) ? $_POST['ptmb_book_year'] : '';
        $book_chapters = isset($_POST['ptmb_book_chapter']) ? $_POST['ptmb_book_chapter'] : array();
        $book_image_id = isset($_POST['ptmb_book_image_id']) ? $_POST['ptmb_book_image_id'] : '';
        $book_image_url = isset($_POST['ptmb_book_image_url']) ? $_POST['ptmb_book_image_url'] : '';
        $ptmb_post_select = isset($_POST['ptmb_post_select']) ? sanitize_text_field($_POST['ptmb_post_select']) : '';
        $ptmb_post_category = isset($_POST['ptmb_post_category']) ? sanitize_text_field($_POST['ptmb_post_category']) : '';

        if (!ptmb_metabox_secured('ptmb_book_nonce_field', 'ptmb_book_nonce', $post_id)) {
            return $post_id;
        }

        // book meta value update
        update_post_meta($post_id, 'ptmb_book_author', $book_author);
        update_post_meta($post_id, 'ptmb_book_isbn', $book_isbn);
        update_post_meta($post_id, 'ptmb_book_year', $book_year);
        update_post_meta($post_id, 'ptmb_book_chapter', $book_chapters);
        update_post_meta($post_id, 'ptmb_book_image_id', $book_image_id);
        update_post_meta($post_id, 'ptmb_book_image_url', $book_image_url);
        update_post_meta($post_id, 'ptmb_post_select', $ptmb_post_select);
        update_post_meta($post_id, 'ptmb_post_category', $ptmb_post_category);
    }
}

add_action('save_post', 'ptmb_post_meta_save');
