<?php
// Settings Page: Plugin Options
class pluginoptions_Settings_Page {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wph_create_settings' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_sections' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_fields' ) );
	}

	public function wph_create_settings() {
		$page_title = 'Plugin Options';
		$menu_title = 'Plugin Options';
		$capability = 'manage_options';
		$slug = 'pluginoptions';
		$callback = array($this, 'wph_settings_content');
		add_options_page($page_title, $menu_title, $capability, $slug, $callback);
	}

	public function wph_settings_content() { ?>
		<div class="wrap">
			<h1>Plugin Options</h1>
			<?php settings_errors(); ?>
			<form method="POST" action="options.php">
				<?php
					settings_fields( 'pluginoptions' );
					do_settings_sections( 'pluginoptions' );
					submit_button();
				?>
			</form>
		</div> <?php
	}

	public function wph_setup_sections() {
		add_settings_section( 'pluginoptions_section', 'Demonstration of plugin setting options', array(), 'pluginoptions' );
	}

	public function wph_setup_fields() {
		$fields = array(
			array(
				'label' => 'Lattitude',
				'id' => 'options_lattitude',
				'type' => 'text',
				'section' => 'pluginoptions_section',
				'placeholder' => 'Insert place lattitude',
			),
			array(
				'label' => 'Longitude ',
				'id' => 'options_longitude',
				'type' => 'text',
				'section' => 'pluginoptions_section',
				'placeholder' => 'Insert place longitude ',
			),
			array(
				'label' => 'Zoom Label',
				'id' => 'options_zoom',
				'type' => 'text',
				'section' => 'pluginoptions_section',
				'placeholder' => 'Insert zoom label',
			),
			array(
				'label' => 'API Key',
				'id' => 'options_api_key',
				'type' => 'text',
				'section' => 'pluginoptions_section',
				'placeholder' => 'Insert API key',
			),
			array(
				'label' => 'External CSS',
				'id' => 'options_external_css',
				'type' => 'textarea',
				'section' => 'pluginoptions_section',
			),
			array(
				'label' => 'Expiry Date',
				'id' => 'options_expiry_date',
				'type' => 'date',
				'section' => 'pluginoptions_section',
			),
		);
		foreach( $fields as $field ){
			add_settings_field( $field['id'], $field['label'], array( $this, 'wph_field_callback' ), 'pluginoptions', $field['section'], $field );
			register_setting( 'pluginoptions', $field['id'] );
		}
	}

	public function wph_field_callback( $field ) {
		$value = get_option( $field['id'] );
		$placeholder = '';
		if ( isset($field['placeholder']) ) {
			$placeholder = $field['placeholder'];
		}
		switch ( $field['type'] ) {
				case 'textarea':
				printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>',
					$field['id'],
					$placeholder,
					$value
					);
					break;
			default:
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
					$field['id'],
					$field['type'],
					$placeholder,
					$value
				);
		}
		if( isset($field['desc']) ) {
			if( $desc = $field['desc'] ) {
				printf( '<p class="description">%s </p>', $desc );
			}
		}
	}
}
new pluginoptions_Settings_Page();