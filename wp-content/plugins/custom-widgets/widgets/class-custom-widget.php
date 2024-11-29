<?php

class CustomWidgets extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'customwidget',
            __('Custom Widget', 'custom-widget'),
            __('Our custom widget Description', 'custom-widget'),
        );
    }

    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : __('Custom Title', 'custom-widgets');
        $latitude = isset($instance['latitude']) ? $instance['latitude'] : __('Latitude', 'custom-widgets');
        $longtitude = isset($instance['longtitude']) ? $instance['longtitude'] : __('Longtitude', 'custom-widgets');
?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')) ?>"><?php esc_html_e('Title', 'custom-widgets'); ?></label>
            <input type="text" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('latitude')) ?>"><?php esc_html_e('Latitude', 'custom-widgets'); ?></label>
            <input type="text" id="<?php echo esc_attr($this->get_field_id('latitude')); ?>" name="<?php echo esc_attr($this->get_field_name('latitude')); ?>" value="<?php echo esc_attr($latitude); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('longtitude')) ?>"><?php esc_html_e('Longtitude', 'custom-widgets'); ?></label>
            <input type="text" id="<?php echo esc_attr($this->get_field_id('longtitude')); ?>" name="<?php echo esc_attr($this->get_field_name('longtitude')); ?>" value="<?php echo esc_attr($longtitude); ?>">
        </p>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        error_log(print_r($new_instance, true));
        $old_instance['title'] = isset($new_instance['title']) ? strip_tags($new_instance['title']) : '';
        $old_instance['latitude'] = isset($new_instance['latitude']) ? strip_tags($new_instance['latitude']) : '';
        $old_instance['longtitude'] = isset($new_instance['longtitude']) ? strip_tags($new_instance['longtitude']) : '';
        return $old_instance;
    }

    public function widget($args, $instance)
    {
        // error_log(print_r($instance, true));
        echo $args['before_widget'];
        if (isset($instance['title']) && $instance['title'] != '') {
            echo $args['before_title'];
            echo apply_filters('widget_title', $instance['title']);
            echo $args['after_title'];
        }
        if(!empty($instance['longtitude'])){
            printf('%s: %s <br>', __('Latitude', 'custom-widgets'), esc_html($instance['latitude']));
        }
        if(!empty($instance['longtitude'])){
            printf('%s: %s', __('Longtitude', 'custom-widgets'), esc_html($instance['longtitude']));
        }
    }
   
}
