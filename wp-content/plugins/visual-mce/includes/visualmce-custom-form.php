<?php

if(!defined('ABSPATH')){
    include_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
}

?>

<form action="">
    <label for="name"><?php echo esc_html_e('name', 'visualmce');?></label>
    <input type="text" name="name", id="name">
</form>