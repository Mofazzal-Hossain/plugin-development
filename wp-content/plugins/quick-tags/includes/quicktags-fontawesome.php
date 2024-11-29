<?php

//avoid directly accessing this file 
if (!defined('ABSPATH')) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
}

?>

<div class="fontawesome-icons mt-3">
    <h2><?php echo esc_html_e('Add a icon', 'quicktags'); ?></h2>
    <ul class="p-0 mt-3 d-flex align-center gap-2">
        <li>
            <button class="btn button-primary d-flex gap-2 align-items-center" onclick="insertIcon('fa-facebook-f')">
                <i class="fa-brands fa-facebook-f"></i>
                <?php esc_html_e('Facebook', 'quicktags'); ?>
            </button>
        </li>
        <li>
            <button class="btn button-primary d-flex gap-2 align-items-center" onclick="insertIcon('fa-x-twitter')">
                <i class="fa-brands fa-x-twitter"></i>
                <?php esc_html_e('Twitter', 'quicktags'); ?>
            </button>
        </li>
        <li>
            <button class="btn button-primary d-flex gap-2 align-items-center" onclick="insertIcon('fa-linkedin-in')">
                <i class="fa-brands fa-linkedin-in"></i>
                <?php esc_html_e('Linkedin', 'quicktags'); ?>
            </button>
        </li>
        <li>
            <button class="btn button-primary d-flex gap-2 align-items-center" onclick="insertIcon('fa-instagram')">
                <i class="fa-brands fa-instagram"></i>
                <?php esc_html_e('Instagram', 'quicktags'); ?>
            </button>
        </li>
    </ul>
</div>

