<?php


// user contact methods 
function ptmb_user_contact_methods($methods)
{

    $methods['facebook'] = __('Facebook', 'post-metabox');
    $methods['twitter'] = __('Twitter', 'post-metabox');
    $methods['linkedin'] = __('Linkedin', 'post-metabox');
    $methods['skype'] = __('Skype', 'post-metabox');

    return $methods;
}
// user contact methods
add_filter('user_contactmethods', 'ptmb_user_contact_methods');
