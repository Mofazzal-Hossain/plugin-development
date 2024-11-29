<?php

// Adds widget: Demo Widget
class Demowidget_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'demowidget_widget',
			esc_html__( 'Demo Widget', 'custom-widgets' ),
			array( 'description' => esc_html__( 'This is demo widget description', 'custom-widgets' ), ) // Args
		);
		add_action( 'admin_footer', array( $this, 'media_fields' ) );
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'media_fields' ) );
	}

	private $widget_fields = array(
		array(
			'label' => 'Book Name',
			'id' => 'bookname_text',
			'type' => 'text',
		),
		array(
			'label' => 'Book Author',
			'id' => 'bookauthor_text',
			'type' => 'text',
		),
		array(
			'label' => 'Book Publish Year',
			'id' => 'bookpublishyear_date',
			'type' => 'date',
		),
		array(
			'label' => 'Book Cover Image',
			'id' => 'bookcoverimage_media',
			'type' => 'media',
		),
		array(
			'label' => 'Favorite Book',
			'id' => 'favoritebook_checkbox',
			'default' => 'No',
			'type' => 'checkbox',
		),
		array(
			'label' => 'Select Chapter',
			'id' => 'selectchapter_select',
			'default' => 'Select a chapter',
			'type' => 'select',
			'options' => array(
				'Chapter 01',
				'Chapter 02',
				'Chapter 03',
				'',
			),
		),
		array(
			'label' => 'Author Email',
			'id' => 'authoremail_email',
			'default' => 'john@gmail.com',
			'type' => 'email',
		),
		array(
			'label' => 'Author URL',
			'id' => 'authorurl_url',
			'type' => 'url',
		),
		array(
			'label' => 'Author Contact Number',
			'id' => 'authorcontactnu_tel',
			'type' => 'tel',
		),
	);

	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		// Output generated fields
		echo '<p>'.$instance['bookname_text'].'</p>';
		echo '<p>'.$instance['bookauthor_text'].'</p>';
		echo '<p>'.$instance['bookpublishyear_date'].'</p>';
		echo '<p>'.$instance['bookcoverimage_media'].'</p>';
		echo '<p>'.$instance['favoritebook_checkbox'].'</p>';
		echo '<p>'.$instance['selectchapter_select'].'</p>';
		echo '<p>'.$instance['authoremail_email'].'</p>';
		echo '<p>'.$instance['authorurl_url'].'</p>';
		echo '<p>'.$instance['authorcontactnu_tel'].'</p>';
		
		echo $args['after_widget'];
	}

	public function media_fields() {
		?><script>
			jQuery(document).ready(function($){
				if ( typeof wp.media !== 'undefined' ) {
					var _custom_media = true,
					_orig_send_attachment = wp.media.editor.send.attachment;
					$(document).on('click','.custommedia',function(e) {
						var send_attachment_bkp = wp.media.editor.send.attachment;
						var button = $(this);
						var id = button.attr('id');
						_custom_media = true;
							wp.media.editor.send.attachment = function(props, attachment){
							if ( _custom_media ) {
								$('input#'+id).val(attachment.id);
								$('span#preview'+id).css('background-image', 'url('+attachment.url+')');
								$('input#'+id).trigger('change');
							} else {
								return _orig_send_attachment.apply( this, [props, attachment] );
							};
						}
						wp.media.editor.open(button);
						return false;
					});
					$('.add_media').on('click', function(){
						_custom_media = false;
					});
					$(document).on('click', '.remove-media', function() {
						var parent = $(this).parents('p');
						parent.find('input[type="media"]').val('').trigger('change');
						parent.find('span').css('background-image', 'url()');
					});
				}
			});
		</script><?php
	}

	public function field_generator( $instance ) {
		$output = '';
		foreach ( $this->widget_fields as $widget_field ) {
			$default = '';
			if ( isset($widget_field['default']) ) {
				$default = $widget_field['default'];
			}
			$widget_value = ! empty( $instance[$widget_field['id']] ) ? $instance[$widget_field['id']] : esc_html__( $default, 'custom-widgets' );
			switch ( $widget_field['type'] ) {
				case 'media':
					$media_url = '';
					if ($widget_value) {
						$media_url = wp_get_attachment_url($widget_value);
					}
					$output .= '<p>';
					$output .= '<label for="'.esc_attr( $this->get_field_id( $widget_field['id'] ) ).'">'.esc_attr( $widget_field['label'], 'custom-widgets' ).':</label> ';
					$output .= '<input style="display:none;" class="widefat" id="'.esc_attr( $this->get_field_id( $widget_field['id'] ) ).'" name="'.esc_attr( $this->get_field_name( $widget_field['id'] ) ).'" type="'.$widget_field['type'].'" value="'.$widget_value.'">';
					$output .= '<span id="preview'.esc_attr( $this->get_field_id( $widget_field['id'] ) ).'" style="margin-right:10px;border:2px solid #eee;display:block;width: 100px;height:100px;background-image:url('.$media_url.');background-size:contain;background-repeat:no-repeat;"></span>';
					$output .= '<button id="'.$this->get_field_id( $widget_field['id'] ).'" class="button select-media custommedia">Add Media</button>';
					$output .= '<input style="width: 19%;" class="button remove-media" id="buttonremove" name="buttonremove" type="button" value="Clear" />';
					$output .= '</p>';
					break;
				case 'checkbox':
					$output .= '<p>';
					$output .= '<input class="checkbox" type="checkbox" '.checked( $widget_value, true, false ).' id="'.esc_attr( $this->get_field_id( $widget_field['id'] ) ).'" name="'.esc_attr( $this->get_field_name( $widget_field['id'] ) ).'" value="1">';
					$output .= '<label for="'.esc_attr( $this->get_field_id( $widget_field['id'] ) ).'">'.esc_attr( $widget_field['label'], 'custom-widgets' ).'</label>';
					$output .= '</p>';
					break;
				case 'select':
					$output .= '<p>';
					$output .= '<label for="'.esc_attr( $this->get_field_id( $widget_field['id'] ) ).'">'.esc_attr( $widget_field['label'], 'custom-widgets' ).':</label> ';
					$output .= '<select id="'.esc_attr( $this->get_field_id( $widget_field['id'] ) ).'" name="'.esc_attr( $this->get_field_name( $widget_field['id'] ) ).'">';
					foreach ($widget_field['options'] as $option) {
						if ($widget_value == $option) {
							$output .= '<option value="'.$option.'" selected>'.$option.'</option>';
						} else {
							$output .= '<option value="'.$option.'">'.$option.'</option>';
						}
					}
					$output .= '</select>';
					$output .= '</p>';
					break;
				default:
					$output .= '<p>';
					$output .= '<label for="'.esc_attr( $this->get_field_id( $widget_field['id'] ) ).'">'.esc_attr( $widget_field['label'], 'custom-widgets' ).':</label> ';
					$output .= '<input class="widefat" id="'.esc_attr( $this->get_field_id( $widget_field['id'] ) ).'" name="'.esc_attr( $this->get_field_name( $widget_field['id'] ) ).'" type="'.$widget_field['type'].'" value="'.esc_attr( $widget_value ).'">';
					$output .= '</p>';
			}
		}
		echo $output;
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'custom-widgets' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'custom-widgets' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
		$this->field_generator( $instance );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		foreach ( $this->widget_fields as $widget_field ) {
			switch ( $widget_field['type'] ) {
				default:
					$instance[$widget_field['id']] = ( ! empty( $new_instance[$widget_field['id']] ) ) ? strip_tags( $new_instance[$widget_field['id']] ) : '';
			}
		}
		return $instance;
	}
}
