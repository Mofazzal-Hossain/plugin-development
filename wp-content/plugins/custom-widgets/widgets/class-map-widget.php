<?php

// extend wp widget properties
class MapWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'mapwidget',
            __('Map Widget', 'custom-widgets'),
            __('Map Widget descripte', 'custom-widgets'),
        );
    }

    // form input 
    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : __('Map title', 'custom-widgets');
        $latitude = isset($instance['latitude']) ? $instance['latitude'] : __('Map latitude', 'custom-widgets');
        $longtitude = isset($instance['longtitude']) ? $instance['longtitude'] : __('Map longtitude', 'custom-widgets');
        $email = isset($instance['email']) ? $instance['email'] : __('admin@gmail.com', 'custom-widgets');

?>
        <div class="form-group">
            <label for="<?php echo esc_attr($this->get_field_id('title')) ?>"><?php echo esc_html_e('Title', 'custom-widgets'); ?></label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name('title')); ?>" id="<?php echo esc_attr($this->get_field_id('title')) ?>" value="<?php echo esc_attr($title) ?>">
        </div>

        <div class="form-group">
            <label for="<?php echo esc_attr($this->get_field_id('latitude')) ?>"><?php echo esc_html_e('Latitude', 'custom-widgets'); ?></label>
            <input type="number" name="<?php echo esc_attr($this->get_field_name('latitude')); ?>" id="<?php echo esc_attr($this->get_field_id('latitude')) ?>" value="<?php echo esc_attr($latitude) ?>">
        </div>

        <div class="form-group">
            <label for="<?php echo esc_attr($this->get_field_id('longtitude')) ?>"><?php echo esc_html_e('Longtitude', 'custom-widgets'); ?></label>
            <input type="number" name="<?php echo esc_attr($this->get_field_name('longtitude')); ?>" id="<?php echo esc_attr($this->get_field_id('longtitude')) ?>" value="<?php echo esc_attr($longtitude) ?>">
        </div>
        <div class="form-group">
            <label for="<?php echo esc_attr($this->get_field_id('email')) ?>"><?php echo esc_html_e('Email', 'custom-widgets'); ?></label>
            <input type="email" name="<?php echo esc_attr($this->get_field_name('email')); ?>" id="<?php echo esc_attr($this->get_field_id('email')) ?>" value="<?php echo esc_attr($email) ?>">
        </div>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = isset($new_instance['title']) ? strip_tags($new_instance['title']) : '';
        $latitude = isset($new_instance['latitude']) ? strip_tags($new_instance['latitude']) : '';
        $longitude = isset($new_instance['longitude']) ? strip_tags($new_instance['longitude']) : '';
        $email = isset($new_instance['email']) ? strip_tags($new_instance['email']) : '';

        // Validate latitude
        if (!is_numeric($latitude)) {
            $instance['latitude'] = $old_instance['latitude'];
        } else {
            $instance['latitude'] = $latitude;
        }

        // Validate longitude
        if (!is_numeric($longitude)) {
            $instance['longitude'] = $old_instance['longitude'];
        } else {
            $instance['longitude'] = $longitude;
        }

        // Validate email
        if (!is_email($email)) {
            $instance['email'] = $old_instance['email'];
        } else {
            $instance['email'] = $email;
        }

        return $instance;
    }

    // display update/save input values
    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        if (isset($instance['title']) && !empty($instance['title'])) {
            echo $args['before_title'];
            echo apply_filters('widget_title', $instance['title']);
            echo $args['after_title'];
        }

        if (isset($instance['latitude']) && !empty($instance['latitude'])) {
            printf('<b>%s</b>: %s <br>', __('Latitude', 'custom-widgets'), esc_html($instance['latitude']));
        }

        if (isset($instance['longtitude']) && !empty($instance['longtitude'])) {
            printf('<b>%s</b>: %s <br>', __('Longtitude', 'custom-widgets'), esc_html($instance['longtitude']));
        }

        if (isset($instance['email']) && !empty($instance['email'])) {
            printf('<b>%s</b>: %s <br>', __('Email', 'custom-widgets'), $instance['email']);
        }

        echo $args['before_widget'];
    }
}
