<?php
/**
 * Ephemeris Customizer Setup and Custom Controls
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class ephemeris_initialise_customizer_settings {
	// Get our default values
	private $defaults;

	public function __construct() {
		// Get our Customizer defaults
		$this->defaults = ephemeris_generate_defaults();

		// Register our sections
		add_action( 'customize_register', array( $this, 'ephemeris_add_customizer_sections' ) );

		// Register our controls
		add_action( 'customize_register', array( $this, 'ephemeris_register_social_controls' ) );

		// Register our Typography controls
		//add_action( 'customize_register', array( $this, 'ephemeris_register_typography_controls' ) );

		// Register our Layout controls
		//add_action( 'customize_register', array( $this, 'ephemeris_register_layout_controls' ) );

	}

	public function ephemeris_add_customizer_sections( $wp_customize ) {
		/**
		 * Add our Social Icons Section
		 */
		$wp_customize->add_section( 'social_icons_section',
			array(
				'title' => esc_html__(  'Social Icons' ),
				'description' => esc_html__(  'Add your social media lnks and we’ll automatically match them with the appropriate icons. Drag and drop the URLs to rearrange their order.' )
			)
		);
	}

	public function ephemeris_register_social_controls( $wp_customize ) {

		// Add our Checkbox switch setting and control for opening URLs in a new tab
		$wp_customize->add_setting( 'social_newtab',
			array(
				'default' => $this->defaults['social_newtab'],
				'sanitize_callback' => 'ephemeris_switch_sanitization'
			)
		);
		$wp_customize->add_control( new Ephemeris_Toggle_Switch_Custom_control( $wp_customize, 'social_newtab',
			array(
				'label' => esc_html__( 'Open in new browser tab', 'ephemeris' ),
				'type' => 'checkbox',
				'settings' => 'social_newtab',
				'section' => 'social_icons_section'
			)
		) );

		// Test of Standard Checkbox Control
		$wp_customize->add_setting( 'sample_checkbox',
			array(
			'default' => '1'
			)
		);
		$wp_customize->add_control( 'sample_checkbox',
			array(
				'label' => esc_html__( 'Standard Checkbox Control', 'ephemeris' ),
				'settings' => 'sample_checkbox',
				'section'  => 'social_icons_section',
				'type'=> 'checkbox',
				'std' => '1'
			)
		);

		// Test of Sortable Repeater Custom Control
		$wp_customize->add_setting( 'social_urls',
			array(
				'default' => '',
				'sanitize_callback' => 'ephemeris_url_sanitization'
			)
		);
		$wp_customize->add_control( new Ephemeris_Sortable_Repeater_Custom_Control( $wp_customize, 'social_urls',
			array(
				'label' => esc_html__( 'Sortable Repeater', 'ephemeris' ),
				'description' => esc_html__( 'This is the field description, if needed', 'ephemeris' ),
				'settings' => 'social_urls',
				'section' => 'social_icons_section'
			)
		) );

		// Test of Single Accordion Custom Control
		$socialIconsList = array(
			'Facebook' => esc_html__( 'fa-facebook', 'ephemeris' ),
			'Twitter' => esc_html__( 'fa-twitter', 'ephemeris' ),
			'Google+' => esc_html__( 'fa-google-plus', 'ephemeris' ),
			'LinkedIn' => esc_html__( 'fa-linkedin', 'ephemeris' ),
			'SlideShare' => esc_html__( 'fa-slideshare', 'ephemeris' ),
			'Slack' => esc_html__( 'fa-slack', 'ephemeris' ),
			'Dribbble' => esc_html__( 'fa-dribbble', 'ephemeris' ),
			'Tumblr' => esc_html__( 'fa-tumblr', 'ephemeris' ),
			'Reddit' => esc_html__( 'fa-reddit', 'ephemeris' ),
			'Twitch' => esc_html__( 'fa-twitch', 'ephemeris' ),
			'Github' => esc_html__( 'fa-github', 'ephemeris' ),
			'Bitbucket' => esc_html__( 'fa-bitbucket', 'ephemeris' ),
			'Stack Overflow' => esc_html__( 'fa-stack-overflow', 'ephemeris' ),
			'CodePen' => esc_html__( 'fa-codepen', 'ephemeris' ),
			'Foursquare' => esc_html__( 'fa-foursquare', 'ephemeris' ),
			'YouTube' => esc_html__( 'fa-youtube', 'ephemeris' ),
			'Vimeo' => esc_html__( 'fa-vimeo', 'ephemeris' ),
			'Instagram' => esc_html__( 'fa-instagram', 'ephemeris' ),
			'Snapchat' => esc_html__( 'fa-snapchat', 'ephemeris' ),
			'Flickr' => esc_html__( 'fa-flickr', 'ephemeris' ),
			'Pinterest' => esc_html__( 'fa-pinterest', 'ephemeris' )
		);
		$wp_customize->add_setting( 'social_url_icons',
			array(
				'default' => '',
				'sanitize_callback' => 'ephemeris_text_sanitization'
			)
		);
		$wp_customize->add_control( new Ephemeris_Single_Accordion_Custom_Control( $wp_customize, 'social_url_icons',
			array(
				'label' => esc_html__( 'View list of available icons', 'ephemeris' ),
				'description' => $socialIconsList,
				'settings' => 'social_url_icons',
				'section' => 'social_icons_section'
			)
		) );

		// Test of Slider Custom Control
		$wp_customize->add_setting( 'header-font-size',
			array(
				'default' => '18',
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_sanitize_integer'
			)
		);
		$wp_customize->add_control( new Ephemeris_Slider_Custom_Control( $wp_customize, 'header-font-size',
			array(
				'label' => esc_html__( 'Header size (px)', 'ephemeris' ),
				'settings' => 'header-font-size',
				'section' => 'social_icons_section',
				'input_attrs' => array(
					'min' => 10,
					'max' => 50,
					'step' => 2,
				),
			)
		) );

		// Test of Image Radio Button Custom Control
		$wp_customize->add_setting( 'image_options',
			array(
				'default' => 'sidebarright',
				'sanitize_callback' => 'ephemeris_text_sanitization'
			)
		);
		$wp_customize->add_control( new Ephemeris_Image_Radio_Button_Custom_Control( $wp_customize, 'image_options',
			array(
				'label' => esc_attr__( 'Layout', 'ephemeris' ),
				'description' => esc_attr__( 'Choose the layout for your blog', 'ephemeris' ),
				'settings' => 'image_options',
				'section' => 'social_icons_section',
				'choices' => array(
					'sidebarleft' => array(
						'image' => trailingslashit( get_template_directory_uri() ) . 'images/sidebar-left.png',
						'name' => esc_html__( 'Left Sidebar' )
					),
					'sidebarnone' => array(
						'image' => trailingslashit( get_template_directory_uri() ) . 'images/sidebar-none.png',
						'name' => esc_html__( 'No Sidebar' )
					),
					'sidebarright' => array(
						'image' => trailingslashit( get_template_directory_uri() ) . 'images/sidebar-right.png',
						'name' => esc_html__( 'Right Sidebar' )
					)
				)
			)
		) );

		// Test of Text Radio Button Custom Control
		$wp_customize->add_setting( 'font_style',
			array(
				'default' => 'bold',
				'sanitize_callback' => 'ephemeris_text_sanitization'
			)
		);
		$wp_customize->add_control( new Ephemeris_Text_Radio_Button_Custom_Control( $wp_customize, 'font_style',
			array(
				'label' => esc_attr__( 'Font Style', 'ephemeris' ),
				'description' => esc_attr__( 'Choose the layout for your blog', 'ephemeris' ),
				'settings' => 'font_style',
				'section' => 'social_icons_section',
				'choices' => array(
					'bold' => esc_html__( 'Bold' ),
					'italic' => esc_html__( 'Italic' ),
					'underline' => esc_html__( 'Underline' )
				)
			)
		) );

		$wp_customize->add_setting( 'text_layout',
			array(
				'default' => 'right',
				'sanitize_callback' => 'ephemeris_text_sanitization'
			)
		);
		$wp_customize->add_control( new Ephemeris_Text_Radio_Button_Custom_Control( $wp_customize, 'text_layout',
			array(
				'label' => esc_attr__( 'Text layout', 'ephemeris' ),
				'description' => esc_attr__( 'Choose the layout for your text', 'ephemeris' ),
				'settings' => 'text_layout',
				'section' => 'social_icons_section',
				'choices' => array(
					'left' => esc_html__( 'Left' ),
					'centered' => esc_html__( 'Centered' ),
					'right' => esc_html__( 'Right' )
				)
			)
		) );

		// Test of Image Checkbox Custom Control
		$wp_customize->add_setting( 'header_font_style',
			array(
				'default' => 'stylebold',
				'sanitize_callback' => 'ephemeris_text_sanitization'
			)
		);
		$wp_customize->add_control( new Ephemeris_Image_checkbox_Custom_Control( $wp_customize, 'header_font_style',
			array(
				'label' => esc_attr__( 'H1', 'ephemeris' ),
				'description' => esc_attr__( 'Select the font styles for your main Header', 'ephemeris' ),
				'settings' => 'header_font_style',
				'section' => 'social_icons_section',
				'choices' => array(
					'stylebold' => array(
						'image' => trailingslashit( get_template_directory_uri() ) . 'images/Bold.png',
						'name' => esc_html__( 'Bold' )
					),
					'styleitalic' => array(
						'image' => trailingslashit( get_template_directory_uri() ) . 'images/Italic.png',
						'name' => esc_html__( 'Italic' )
					),
					'styleallcaps' => array(
						'image' => trailingslashit( get_template_directory_uri() ) . 'images/AllCaps.png',
						'name' => esc_html__( 'All Caps' )
					),
					'styleunderline' => array(
						'image' => trailingslashit( get_template_directory_uri() ) . 'images/Underline.png',
						'name' => esc_html__( 'Underline' )
					)
				)
			)
		) );

		// Test of Color Control
      $wp_customize->add_setting( 'body_font_color',
			array(
				'default' => '#333'
			)
      );
      $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'body_font_color',
			array(
				'label' => 'Main Body Font Color',
				'section' => 'social_icons_section',
				'settings' => 'body_font_color'
			)
		) );

		// Test of Alpha Color Picker Control
		$wp_customize->add_setting( 'body_font_alpha_color',
			array(
				'default' => 'rgba(209,0,55,0.7)',
				'transport' => 'postMessage'
			)
		);
		$wp_customize->add_control( new Ephemeris_Customize_Alpha_Color_Control( $wp_customize, 'body_font_alpha_color',
			array(
				'label' => esc_attr__( 'Alpha Color Picker', 'ephemeris' ),
				'section' => 'social_icons_section',
				'settings' => 'body_font_alpha_color',
				'show_opacity' => true,
				'palette' => array(
					'#000',
					'#fff',
					'#df312c',
					'#df9a23',
					'#eef000',
					'#7ed934',
					'#1571c1',
					'#8309e7'
				)
			)
		) );

		// Test of Google Font Select Control
		$wp_customize->add_setting( 'body_font',
			array(
			 'default' => '{"font":"Open Sans","regularweight":"regular","italicweight":"italic","boldweight":"700","category":"sans-serif"}'
			)
		);
		$wp_customize->add_control( new Google_Font_Select_Custom_Control( $wp_customize, 'body_font',
			array(
				'label'   => esc_attr__( 'Main Body Font', 'ephemeris' ),
				'section' => 'social_icons_section',
				'settings'   => 'body_font'
			)
		) );

		// Test of Standard Dropdown Select Control
		$wp_customize->add_setting( 'std_dropdown',
			array(
				'default'=>'jet-fuel'
			)
		);
		$wp_customize->add_control( 'std_dropdown',
			array(
				'type' => 'select',
				'label' => 'Standard Dropdown Select Control',
				'section' => 'social_icons_section',
				'setting' => 'std_dropdown',
				'choices' => array(
	            'wordpress' => 'WordPress',
	            'hamsters' => 'Hamsters',
	            'jet-fuel' => 'Jet Fuel',
	            'nuclear-energy' => 'Nuclear Energy'
				)
			)
		);

	}
}

/**
 * Load all our Customizer Custom Controls
 */
require_once trailingslashit( dirname(__FILE__) ) . 'custom-controls.php';

/**
 * Initialise our Customizer settings
 */
new ephemeris_initialise_customizer_settings();
