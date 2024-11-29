<?php

/**
 * 
 * Chapter category add extra fields
 * 
 */



// category [chapter] form field 
function ptmb_chapters_form_field($term_id){
    $extra = get_term_meta($term_id, 'extra', true);
    
    ?>
    <div class="form-field term-slug-wrap">
        <label for="extra"><?php echo esc_html_e('Extra', 'post-metabox'); ?></label>
        <input name="extra" id="extra" type="text" value="<?php echo esc_attr($extra); ?>" size="40" aria-describedby="extra-description">
        <p id="slug-description"><?php echo esc_html_e('This is a extra option', 'post-metabox'); ?></p>
    </div><?php
}

add_action("chapters_add_form_fields", 'ptmb_chapters_form_field');

// save category field
function ptmb_save_chapter_field($term_id){
   
    if (wp_verify_nonce($_POST['_wpnonce_add-tag'], 'add-tag')) {
        $extra = isset($_POST['extra']) ? sanitize_text_field($_POST['extra']) : '';
        update_term_meta($term_id, 'extra', $extra);
    }
}
add_action('created_chapters', 'ptmb_save_chapter_field');

// category [chapter] edit form field 
function ptmb_edit_form_field($term) {
    $term_id = $term->term_id;
    $extra = get_term_meta($term_id, 'extra', true);
    
    ?>
        <tr class="form-field term-slug-wrap">
            <th scope="row">
                <label for="extra"><?php echo esc_html_e('Extra', 'post-metabox'); ?></label>
            </th>
            <td>
                <input name="extra" id="extra" type="text" value="<?php echo esc_attr($extra); ?>" size="40" aria-describedby="extra-description">
                <p id="slug-description"><?php echo esc_html_e('This is a extra option', 'post-metabox'); ?></p>
            </td>
        </tr>
    <?php
}

add_action("chapters_edit_form_fields", 'ptmb_edit_form_field');

// update edited category field
function ptmb_save_chapter_edit_field($term_id){
   
    if (wp_verify_nonce($_POST['_wpnonce'], "update-tag_{$term_id}")) {
        $extra = isset($_POST['extra']) ? sanitize_text_field($_POST['extra']) : '';
        update_term_meta($term_id, 'extra', $extra);
    }
}

add_action('edited_chapters', 'ptmb_save_chapter_edit_field');