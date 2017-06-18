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

		// Register our Panels
		add_action( 'customize_register', array( $this, 'ephemeris_add_customizer_panels' ) );

		// Register our sections
		add_action( 'customize_register', array( $this, 'ephemeris_add_customizer_sections' ) );

		// Register our social media controls
		add_action( 'customize_register', array( $this, 'ephemeris_register_social_controls' ) );

		// Register our contact controls
		add_action( 'customize_register', array( $this, 'ephemeris_register_contact_controls' ) );

		// Register our search controls
		add_action( 'customize_register', array( $this, 'ephemeris_register_search_controls' ) );

		// Register our Typography controls
		//add_action( 'customize_register', array( $this, 'ephemeris_register_typography_controls' ) );

		// Register our Layout controls
		//add_action( 'customize_register', array( $this, 'ephemeris_register_layout_controls' ) );

		// Register our WooCommerce controls, only if WooCommerce is active
		if( ephemeris_is_woocommerce_active() ) {
			add_action( 'customize_register', array( $this, 'ephemeris_register_woocommerce_controls' ) );
		}

		// Register our sample Custom Control controls
		add_action( 'customize_register', array( $this, 'ephemeris_register_sample_custom_controls' ) );

		// Register our sample default controls
		add_action( 'customize_register', array( $this, 'ephemeris_register_sample_default_controls' ) );

	}

	/**
	 * Register the Customizer panels
	 */
	public function ephemeris_add_customizer_panels( $wp_customize ) {
		/**
		 * Add our Header & Navigation Panel
		 */
		 $wp_customize->add_panel( 'header_naviation_panel',
		 	array(
				'title' => __( 'Header & Navigation', 'ephemeris' ),
				'description' => esc_html__( 'Adjust your Header and Navigation sections.', 'ephemeris' )
			)
		);
	}

	/**
	 * Register the Customizer sections
	 */
	public function ephemeris_add_customizer_sections( $wp_customize ) {
		/**
		 * Add our Social Icons Section
		 */
		$wp_customize->add_section( 'social_icons_section',
			array(
				'title' => __( 'Social Icons', 'ephemeris' ),
				'description' => esc_html__( 'Add your social media links and we\'ll automatically match them with the appropriate icons. Drag and drop the URLs to rearrange their order.', 'ephemeris' ),
				'panel' => 'header_naviation_panel'
			)
		);

		/**
		 * Add our Contact Section
		 */
		$wp_customize->add_section( 'contact_section',
			array(
				'title' => __( 'Contact', 'ephemeris' ),
				'description' => esc_html__( 'Add your phone number to the site header bar.', 'ephemeris' ),
				'panel' => 'header_naviation_panel'
			)
		);

		/**
		 * Add our Search Section
		 */
		$wp_customize->add_section( 'search_section',
			array(
				'title' => __( 'Search', 'ephemeris' ),
				'description' => esc_html__( 'Add a search icon to your primary naigation menu.', 'ephemeris' ),
				'panel' => 'header_naviation_panel'
			)
		);

		/**
		 * Add our WooCommerce Layout Section, only if WooCommerce is active
		 */
		$wp_customize->add_section( 'woocommerce_layout_section',
			array(
				'title' => __( 'WooCommerce Layout', 'ephemeris' ),
				'description' => esc_html__( 'Adjust the layout of your WooCommerce shop.', 'ephemeris' ),
				'active_callback' => 'skyrocket_is_woocommerce_active'
			)
		);

		$wp_customize->add_section( 'sample_custom_controls_section',
			array(
				'title' => __( 'Sample Custom Controls', 'ephemeris' ),
				'description' => esc_html__( 'These are an example of Customizer Custom Controls.', 'ephemeris' )
			)
		);

		$wp_customize->add_section( 'default_controls_section',
			array(
				'title' => __( 'Default Controls', 'ephemeris' ),
				'description' => esc_html__( 'These are an example of the default Customizer Controls.', 'ephemeris' )
			)
		);

	}

	/**
	 * Register our social media controls
	 */
	public function ephemeris_register_social_controls( $wp_customize ) {

		// Add our Checkbox switch setting and control for opening URLs in a new tab
		$wp_customize->add_setting( 'social_newtab',
			array(
				'default' => $this->defaults['social_newtab'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_switch_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'social_newtab',
			array(
				'label' => __( 'Open in new browser tab', 'ephemeris' ),
				'section' => 'social_icons_section'
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'social_newtab',
			array(
				'selector' => '.social-header',
				'container_inclusive' => false,
				'render_callback' => function() {
					echo ephemeris_get_social_media();
				},
				'fallback_refresh' => true
			)
		);

		// Add our Text Radio Button setting and Custom Control for controlling alignment of icons
		$wp_customize->add_setting( 'social_alignment',
			array(
				'default' => $this->defaults['social_alignment'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_radio_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Text_Radio_Button_Custom_Control( $wp_customize, 'social_alignment',
			array(
				'label' => __( 'Alignment', 'ephemeris' ),
				'description' => esc_html__( 'Choose the alignment for your social icons', 'ephemeris' ),
				'section' => 'social_icons_section',
				'choices' => array(
					'alignleft' => __( 'Left', 'ephemeris' ),
					'alignright' => __( 'Right', 'ephemeris' )
				)
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'social_alignment',
			array(
				'selector' => '.social-header',
				'container_inclusive' => false,
				'render_callback' => function() {
					echo ephemeris_get_social_media();
				},
				'fallback_refresh' => true
			)
		);

		// Add our Sortable Repeater setting and Custom Control for Social media URLs
		$wp_customize->add_setting( 'social_urls',
			array(
				'default' => $this->defaults['social_urls'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_url_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Sortable_Repeater_Custom_Control( $wp_customize, 'social_urls',
			array(
				'label' => __( 'Social URLs', 'ephemeris' ),
				'description' => esc_html__( 'Add your social media links.', 'ephemeris' ),
				'section' => 'social_icons_section',
				'button_labels' => array(
					'add' => __( 'Add Icon', 'ephemeris' ),
				)
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'social_urls',
			array(
				'selector' => '.social-header',
				'container_inclusive' => false,
				'render_callback' => function() {
					echo ephemeris_get_social_media();
				},
				'fallback_refresh' => true
			)
		);

		// Add our Single Accordion setting and Custom Control to list the available Social Media icons
		$socialIconsList = array(
			'Behance' => __( '<i class="fa fa-behance"></i>', 'ephemeris' ),
			'Bitbucket' => __( '<i class="fa fa-bitbucket"></i>', 'ephemeris' ),
			'CodePen' => __( '<i class="fa fa-codepen"></i>', 'ephemeris' ),
			'DeviantArt' => __( '<i class="fa fa-deviantart"></i>', 'ephemeris' ),
			'Dribbble' => __( '<i class="fa fa-dribbble"></i>', 'ephemeris' ),
			'Etsy' => __( '<i class="fa fa-etsy"></i>', 'ephemeris' ),
			'Facebook' => __( '<i class="fa fa-facebook"></i>', 'ephemeris' ),
			'Flickr' => __( '<i class="fa fa-flickr"></i>', 'ephemeris' ),
			'Foursquare' => __( '<i class="fa fa-foursquare"></i>', 'ephemeris' ),
			'GitHub' => __( '<i class="fa fa-github"></i>', 'ephemeris' ),
			'Instagram' => __( '<i class="fa fa-instagram"></i>', 'ephemeris' ),
			'Last.fm' => __( '<i class="fa fa-lastfm"></i>', 'ephemeris' ),
			'LinkedIn' => __( '<i class="fa fa-linkedin"></i>', 'ephemeris' ),
			'Medium' => __( '<i class="fa fa-medium"></i>', 'ephemeris' ),
			'Pinterest' => __( '<i class="fa fa-pinterest"></i>', 'ephemeris' ),
			'Google+' => __( '<i class="fa fa-google-plus"></i>', 'ephemeris' ),
			'Reddit' => __( '<i class="fa fa-reddit"></i>', 'ephemeris' ),
			'Slack' => __( '<i class="fa fa-slack"></i>', 'ephemeris' ),
			'SlideShare' => __( '<i class="fa fa-slideshare"></i>', 'ephemeris' ),
			'Snapchat' => __( '<i class="fa fa-snapchat"></i>', 'ephemeris' ),
			'SoundCloud' => __( '<i class="fa fa-soundcloud"></i>', 'ephemeris' ),
			'Spotify' => __( '<i class="fa fa-spotify"></i>', 'ephemeris' ),
			'Stack Overflow' => __( '<i class="fa fa-stack-overflow"></i>', 'ephemeris' ),
			'Tumblr' => __( '<i class="fa fa-tumblr"></i>', 'ephemeris' ),
			'Twitch' => __( '<i class="fa fa-twitch"></i>', 'ephemeris' ),
			'Twitter' => __( '<i class="fa fa-twitter"></i>', 'ephemeris' ),
			'Vimeo' => __( '<i class="fa fa-vimeo"></i>', 'ephemeris' ),
			'YouTube' => __( '<i class="fa fa-youtube"></i>', 'ephemeris' )
		);
		$wp_customize->add_setting( 'social_url_icons',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_text_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Single_Accordion_Custom_Control( $wp_customize, 'social_url_icons',
			array(
				'label' => __( 'View list of available icons', 'ephemeris' ),
				'description' => $socialIconsList,
				'section' => 'social_icons_section'
			)
		) );

		// Add our Checkbox switch setting and Custom Control for displaying an RSS icon
		$wp_customize->add_setting( 'social_rss',
			array(
				'default' => $this->defaults['social_rss'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_switch_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'social_rss',
			array(
				'label' => __( 'Display RSS icon', 'ephemeris' ),
				'section' => 'social_icons_section'
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'social_rss',
			array(
				'selector' => '.social-header',
				'container_inclusive' => false,
				'render_callback' => function() {
					echo ephemeris_get_social_media();
				},
				'fallback_refresh' => true
			)
		);

	}

	/**
	 * Register our Contact controls
	 */
	public function ephemeris_register_contact_controls( $wp_customize ) {
		// Add our Text field setting and Control for displaying the phone number
		$wp_customize->add_setting( 'contact_phone',
			array(
				'default' => $this->defaults['contact_phone'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses'
			)
		);
		$wp_customize->add_control( 'contact_phone',
			array(
				'label' => __( 'Display phone number', 'ephemeris' ),
				'type' => 'text',
				'section' => 'contact_section'
			)
		);
		$wp_customize->selective_refresh->add_partial( 'contact_phone',
			array(
				'selector' => '.social-header',
				'container_inclusive' => false,
				'render_callback' => function() {
					echo ephemeris_get_social_media();
				},
				'fallback_refresh' => true
			)
		);

	}

	/**
	 * Register our Search controls
	 */
	public function ephemeris_register_search_controls( $wp_customize ) {
		// Add our Checkbox switch setting and control for opening URLs in a new tab
		$wp_customize->add_setting( 'search_menu_icon',
			array(
				'default' => $this->defaults['search_menu_icon'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_switch_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'search_menu_icon',
			array(
				'label' => __( 'Display Search Icon', 'ephemeris' ),
				'section' => 'search_section'
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'search_menu_icon',
			array(
				'selector' => '.menu-item-search',
				'container_inclusive' => false,
				'fallback_refresh' => false
			)
		);
	}

	/**
	 * Register our WooCommerce Layout controls
	 */
	public function ephemeris_register_woocommerce_controls( $wp_customize ) {

		// Add our Checkbox switch setting and control for displaying a sidebar on the shop page
		$wp_customize->add_setting( 'woocommerce_shop_sidebar',
			array(
				'default' => $this->defaults['woocommerce_shop_sidebar'],
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_switch_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'woocommerce_shop_sidebar',
			array(
				'label' => __( 'Shop page sidebar', 'ephemeris' ),
				'section' => 'woocommerce_layout_section'
			)
		) );

		// Add our Checkbox switch setting and control for displaying a sidebar on the single product page
		$wp_customize->add_setting( 'woocommerce_product_sidebar',
			array(
				'default' => $this->defaults['woocommerce_product_sidebar'],
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_switch_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'woocommerce_product_sidebar',
			array(
				'label' => __( 'Single Product page sidebar', 'ephemeris' ),
				'section' => 'woocommerce_layout_section'
			)
		) );

		// Add our Simple Notice setting and control for displaying a message about the WooCommerce shop sidebars
		$wp_customize->add_setting( 'woocommerce_other_sidebar',
			array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_text_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Simple_Notice_Custom_control( $wp_customize, 'woocommerce_other_sidebar',
			array(
				'label' => __( 'Cart, Checkout & My Account sidebars', 'ephemeris' ),
				'description' => esc_html__( 'The Cart, Checkout and My Account pages are displayed using shortcodes. To remove the sidebar from these Pages, simply edit each Page and change the Template (in the Page Attributes Panel) to Full-width Page.', 'ephemeris' ),
				'section' => 'woocommerce_layout_section'
			)
		) );

	}

	/**
	 * Register our sample custom controls
	 */
	public function ephemeris_register_sample_custom_controls( $wp_customize ) {

		// Test of Toggle Switch Custom Control
		$wp_customize->add_setting( 'sample_toggle_switch',
			array(
				'default' => 0,
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_switch_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'sample_toggle_switch',
			array(
				'label' => __( 'Toggle switch', 'ephemeris' ),
				'section' => 'sample_custom_controls_section'
			)
		) );

		// Test of Slider Custom Control
		$wp_customize->add_setting( 'sample_slider_control',
			array(
				'default' => '48',
				'transport' => 'postMessage',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( new Skyrocket_Slider_Custom_Control( $wp_customize, 'sample_slider_control',
			array(
				'label' => __( 'Slider Control (px)', 'ephemeris' ),
				'section' => 'sample_custom_controls_section',
				'input_attrs' => array(
					'min' => 10,
					'max' => 90,
					'step' => 1,
				),
			)
		) );

		// Add our Sortable Repeater setting and Custom Control for Social media URLs
		$wp_customize->add_setting( 'sample_sortable_repeater_control',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_url_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Sortable_Repeater_Custom_Control( $wp_customize, 'sample_sortable_repeater_control',
			array(
				'label' => __( 'Sortable Repeater', 'ephemeris' ),
				'description' => esc_html__( 'This is the control description.', 'ephemeris' ),
				'section' => 'sample_custom_controls_section',
				'button_labels' => array(
					'add' => __( 'Add Row', 'ephemeris' ),
				)
			)
		) );

		// Test of Image Radio Button Custom Control
		$wp_customize->add_setting( 'sample_image_radio_button',
			array(
				'default' => 'sidebarright',
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_radio_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Image_Radio_Button_Custom_Control( $wp_customize, 'sample_image_radio_button',
			array(
				'label' => __( 'Image Radio Button Control', 'ephemeris' ),
				'description' => esc_html__( 'Sample custom control description', 'ephemeris' ),
				'section' => 'sample_custom_controls_section',
				'choices' => array(
					'sidebarleft' => array(
						'image' => trailingslashit( get_template_directory_uri() ) . 'images/sidebar-left.png',
						'name' => __( 'Left Sidebar', 'ephemeris' )
					),
					'sidebarnone' => array(
						'image' => trailingslashit( get_template_directory_uri() ) . 'images/sidebar-none.png',
						'name' => __( 'No Sidebar', 'ephemeris' )
					),
					'sidebarright' => array(
						'image' => trailingslashit( get_template_directory_uri() ) . 'images/sidebar-right.png',
						'name' => __( 'Right Sidebar', 'ephemeris' )
					)
				)
			)
		) );

		// Test of Text Radio Button Custom Control
		$wp_customize->add_setting( 'sample_text_radio_button',
			array(
				'default' => 'right',
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_radio_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Text_Radio_Button_Custom_Control( $wp_customize, 'sample_text_radio_button',
			array(
				'label' => __( 'Text Radio Button Control', 'ephemeris' ),
				'description' => esc_html__( 'Sample custom control description', 'ephemeris' ),
				'section' => 'sample_custom_controls_section',
				'choices' => array(
					'left' => __( 'Left', 'ephemeris' ),
					'centered' => __( 'Centered', 'ephemeris' ),
					'right' => __( 'Right', 'ephemeris' )
				)
			)
		) );

		// Test of Image Checkbox Custom Control
		$wp_customize->add_setting( 'sample_image_checkbox',
			array(
				'default' => 'stylebold',
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_text_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Image_checkbox_Custom_Control( $wp_customize, 'sample_image_checkbox',
			array(
				'label' => __( 'Image Checkbox Control', 'ephemeris' ),
				'description' => esc_html__( 'Sample custom control description', 'ephemeris' ),
				'section' => 'sample_custom_controls_section',
				'choices' => array(
					'stylebold' => array(
						'image' => trailingslashit( get_template_directory_uri() ) . 'images/bold.png',
						'name' => __( 'Bold', 'ephemeris' )
					),
					'styleitalic' => array(
						'image' => trailingslashit( get_template_directory_uri() ) . 'images/italic.png',
						'name' => __( 'Italic', 'ephemeris' )
					),
					'styleallcaps' => array(
						'image' => trailingslashit( get_template_directory_uri() ) . 'images/allcaps.png',
						'name' => __( 'All Caps', 'ephemeris' )
					),
					'styleunderline' => array(
						'image' => trailingslashit( get_template_directory_uri() ) . 'images/underline.png',
						'name' => __( 'Underline', 'ephemeris' )
					)
				)
			)
		) );

		// Test of Single Accordion Control
		$sampleIconsList = array(
			'Behance' => __( '<i class="fa fa-behance"></i>', 'ephemeris' ),
			'Bitbucket' => __( '<i class="fa fa-bitbucket"></i>', 'ephemeris' ),
			'CodePen' => __( '<i class="fa fa-codepen"></i>', 'ephemeris' ),
			'DeviantArt' => __( '<i class="fa fa-deviantart"></i>', 'ephemeris' ),
			'Dribbble' => __( '<i class="fa fa-dribbble"></i>', 'ephemeris' ),
			'Etsy' => __( '<i class="fa fa-etsy"></i>', 'ephemeris' ),
			'Facebook' => __( '<i class="fa fa-facebook"></i>', 'ephemeris' ),
			'Flickr' => __( '<i class="fa fa-flickr"></i>', 'ephemeris' ),
			'Foursquare' => __( '<i class="fa fa-foursquare"></i>', 'ephemeris' ),
			'GitHub' => __( '<i class="fa fa-github"></i>', 'ephemeris' ),
		);
		$wp_customize->add_setting( 'sample_single_accordion',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_text_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Single_Accordion_Custom_Control( $wp_customize, 'sample_single_accordion',
			array(
				'label' => __( 'Single Accordion Control', 'ephemeris' ),
				'description' => $sampleIconsList,
				'section' => 'sample_custom_controls_section'
			)
		) );

		// Test of Alpha Color Picker Control
		$wp_customize->add_setting( 'sample_alpha_color_picker',
			array(
				'default' => 'rgba(209,0,55,0.7)',
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_hex_rgba_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Customize_Alpha_Color_Control( $wp_customize, 'sample_alpha_color_picker',
			array(
				'label' => __( 'Alpha Color Picker Control', 'ephemeris' ),
				'section' => 'sample_custom_controls_section',
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

		// Test of Simple Notice control
		$wp_customize->add_setting( 'sample_simple_notice',
			array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_text_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Simple_Notice_Custom_control( $wp_customize, 'sample_simple_notice',
			array(
				'label' => __( 'Simple Notice Control', 'ephemeris' ),
				'description' => __( 'This Custom Control allows you to display a simple title and description to your users. You can even include <a href="http://google.com" target="_blank">basic html</a>.', 'ephemeris' ),
				'section' => 'sample_custom_controls_section'
			)
		) );

		// Test of Google Font Select Control
		$wp_customize->add_setting( 'sample_google_font_select',
			array(
				'default' => json_encode(
					array(
						'font' => 'Open Sans',
						'regularweight' => 'regular',
						'italicweight' => 'italic',
						'boldweight' => '700',
						'category' => 'sans-serif'
					)
				),
				'sanitize_callback' => 'skyrocket_google_font_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Google_Font_Select_Custom_Control( $wp_customize, 'sample_google_font_select',
			array(
				'label' => __( 'Google Font Control', 'ephemeris' ),
				'description' => esc_html__( 'Sample custom control description', 'ephemeris' ),
				'section' => 'sample_custom_controls_section',
			)
		) );
	}

	/**
	 * Register our sample default controls
	 */
	public function ephemeris_register_sample_default_controls( $wp_customize ) {

		// Test of Text Control
		$wp_customize->add_setting( 'sample_default_text',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_text_sanitization'
			)
		);
		$wp_customize->add_control( 'sample_default_text',
			array(
				'label' => __( 'Default Text Control', 'ephemeris' ),
				'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', 'ephemeris' ),
				'section' => 'default_controls_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'my-custom-class',
					'style' => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Enter name...', 'ephemeris' ),
				),
			)
		);

		// Test of Email Control
		$wp_customize->add_setting( 'sample_email_text',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_email'
			)
		);
		$wp_customize->add_control( 'sample_email_text',
			array(
				'label' => __( 'Default Email Control', 'ephemeris' ),
				'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', 'ephemeris' ),
				'section' => 'default_controls_section',
				'type' => 'email'
			)
		);

		// Test of URL Control
		$wp_customize->add_setting( 'sample_url_text',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'esc_url_raw'
			)
		);
		$wp_customize->add_control( 'sample_url_text',
			array(
				'label' => __( 'Default URL Control', 'ephemeris' ),
				'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', 'ephemeris' ),
				'section' => 'default_controls_section',
				'type' => 'url'
			)
		);

		// Test of Number Control
		$wp_customize->add_setting( 'sample_number_text',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_sanitize_integer'
			)
		);
		$wp_customize->add_control( 'sample_number_text',
			array(
				'label' => __( 'Default Number Control', 'ephemeris' ),
				'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', 'ephemeris' ),
				'section' => 'default_controls_section',
				'type' => 'number'
			)
		);

		// Test of Hidden Control
		$wp_customize->add_setting( 'sample_hidden_text',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_text_sanitization'
			)
		);
		$wp_customize->add_control( 'sample_hidden_text',
			array(
				'label' => __( 'Default Hidden Control', 'ephemeris' ),
				'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', 'ephemeris' ),
				'section' => 'default_controls_section',
				'type' => 'hidden'
			)
		);

		// Test of Date Control
		$wp_customize->add_setting( 'sample_date_text',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_text_sanitization'
			)
		);
		$wp_customize->add_control( 'sample_date_text',
			array(
				'label' => __( 'Default Date Control', 'ephemeris' ),
				'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', 'ephemeris' ),
				'section' => 'default_controls_section',
				'type' => 'text'
			)
		);

		 // Test of Standard Checkbox Control
		$wp_customize->add_setting( 'sample_default_checkbox',
			array(
				'default' => 0,
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_switch_sanitization'
			)
		);
		$wp_customize->add_control( 'sample_default_checkbox',
			array(
				'label' => __( 'Default Checkbox Control', 'ephemeris' ),
				'description' => esc_html__( 'Sample Checkbox description', 'ephemeris' ),
				'section' => 'default_controls_section',
				'type' => 'checkbox'
			)
		);

 		// Test of Standard Select Control
		$wp_customize->add_setting( 'sample_default_select',
			array(
				'default'=>'jet-fuel',
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_radio_sanitization'
			)
		);
		$wp_customize->add_control( 'sample_default_select',
			array(
				'label' => __( 'Standard Select Control', 'ephemeris' ),
				'section' => 'default_controls_section',
				'type' => 'select',
				'choices' => array(
					'wordpress' => __( 'WordPress', 'ephemeris' ),
					'hamsters' => __( 'Hamsters', 'ephemeris' ),
					'jet-fuel' => __( 'Jet Fuel', 'ephemeris' ),
					'nuclear-energy' => __( 'Nuclear Energy', 'ephemeris' )
				)
			)
		);

		// Test of Standard Radio Control
		$wp_customize->add_setting( 'sample_default_radio',
			array(
				'default' => __( 'spider-man', 'ephemeris' ),
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_radio_sanitization'
			)
		);
		$wp_customize->add_control( 'sample_default_radio',
			array(
				'label' => __( 'Standard Radio Control', 'ephemeris' ),
				'section' => 'default_controls_section',
				'type' => 'radio',
				'choices' => array(
					'captain-america' => __( 'Captain America', 'ephemeris' ),
					'iron-man' => __( 'Iron Man', 'ephemeris' ),
					'spider-man' => __( 'Spider-Man', 'ephemeris' ),
					'thor' => __( 'Thor', 'ephemeris' )
				)
			)
		);

		// Test of Dropdown Pages Control
		$wp_customize->add_setting( 'sample_default_dropdownpages',
			array(
				'default' => '0',
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( 'sample_default_dropdownpages',
			array(
				'label' => __( 'Default Dropdown Pages Control', 'ephemeris' ),
				'section' => 'default_controls_section',
				'type' => 'dropdown-pages'
			)
		);

		// Test of Textarea Control
		$wp_customize->add_setting( 'sample_default_textarea',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'wp_filter_nohtml_kses'
			)
		);
		$wp_customize->add_control( 'sample_default_textarea',
			array(
				'label' => __( 'Default Textarea Control', 'ephemeris' ),
				'section' => 'default_controls_section',
				'type' => 'textarea',
				'input_attrs' => array(
					'class' => 'my-custom-class',
					'style' => 'border: 1px solid #999',
					'placeholder' => __( 'Enter message...', 'ephemeris' ),
				),
			)
		);

		// Test of Color Control
		$wp_customize->add_setting( 'sample_default_color',
			array(
				'default' => '#333',
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
			)
		);
		$wp_customize->add_control( 'sample_default_color',
			array(
				'label' => __( 'Default Color Control', 'ephemeris' ),
				'section' => 'default_controls_section',
				'type' => 'color'
			)
		);

		// Test of Media Control
		$wp_customize->add_setting( 'sample_default_media',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'sample_default_media',
			array(
				'label' => __( 'Default Media Control', 'ephemeris' ),
				'description' => esc_html__( 'This is the description for the Media Control', 'ephemeris' ),
				'section' => 'default_controls_section',
				'mime_type' => 'image',
				'button_labels' => array(
					'select' => __( 'Select File', 'ephemeris' ),
					'change' => __( 'Change File', 'ephemeris' ),
					'default' => __( 'Default', 'ephemeris' ),
					'remove' => __( 'Remove', 'ephemeris' ),
					'placeholder' => __( 'No file selected', 'ephemeris' ),
					'frame_title' => __( 'Select File', 'ephemeris' ),
					'frame_button' => __( 'Choose File', 'ephemeris' ),
				)
			)
		) );

		// Test of Image Control
		$wp_customize->add_setting( 'sample_default_image',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'sample_default_image',
			array(
				'label' => __( 'Default Image Control', 'ephemeris' ),
				'description' => esc_html__( 'This is the description for the Image Control', 'ephemeris' ),
				'section' => 'default_controls_section',
				'button_labels' => array(
					'select' => __( 'Select Image', 'ephemeris' ),
					'change' => __( 'Change Image', 'ephemeris' ),
					'remove' => __( 'Remove', 'ephemeris' ),
					'default' => __( 'Default', 'ephemeris' ),
					'placeholder' => __( 'No image selected', 'ephemeris' ),
					'frame_title' => __( 'Select Image', 'ephemeris' ),
					'frame_button' => __( 'Choose Image', 'ephemeris' ),
				)
			)
		) );

		// Test of Cropped Image Control
		$wp_customize->add_setting( 'sample_default_cropped_image',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'sample_default_cropped_image',
			array(
				'label' => __( 'Default Cropped Image Control', 'ephemeris' ),
				'description' => esc_html__( 'This is the description for the Cropped Image Control', 'ephemeris' ),
				'section' => 'default_controls_section',
				'flex_width' => false,
				'flex_height' => false,
				'width' => 800,
				'height' => 400
			)
		) );
	}
}

/**
 * Load all our Customizer Custom Controls
 */
require_once trailingslashit( dirname(__FILE__) ) . 'custom-controls.php';

/**
 * Initialise our Customizer settings
 */
$ephemeris_settings = new ephemeris_initialise_customizer_settings();
