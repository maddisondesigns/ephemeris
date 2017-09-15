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

		// Register our color controls
		add_action( 'customize_register', array( $this, 'ephemeris_register_color_controls' ) );

		// Register our layout controls
		add_action( 'customize_register', array( $this, 'ephemeris_register_layout_controls' ) );

		// Register our social media controls
		add_action( 'customize_register', array( $this, 'ephemeris_register_social_controls' ) );

		// Register our contact controls
		add_action( 'customize_register', array( $this, 'ephemeris_register_contact_controls' ) );

		// Register our search controls
		add_action( 'customize_register', array( $this, 'ephemeris_register_search_controls' ) );

		// Register our search controls
		add_action( 'customize_register', array( $this, 'ephemeris_register_footer_controls' ) );

		// Register our Typography controls
		//add_action( 'customize_register', array( $this, 'ephemeris_register_typography_controls' ) );

		// Register our WooCommerce controls, only if WooCommerce is active
		if( ephemeris_is_woocommerce_active() ) {
			add_action( 'customize_register', array( $this, 'ephemeris_register_woocommerce_controls' ) );
		}
	}

	/**
	 * Register the Customizer panels
	 */
	public function ephemeris_add_customizer_panels( $wp_customize ) {
		/**
		 * Add our Colors Panel
		 */
		 $wp_customize->add_panel( 'colors_panel',
		 	array(
				'title' => __( 'Colors', 'ephemeris' ),
				'description' => esc_html__( 'Set the colors for your site.', 'ephemeris' ),
				'priority' => 39,
			)
		);

		/**
		 * Add our Header & Navigation Panel
		 */
		 $wp_customize->add_panel( 'header_naviation_panel',
		 	array(
				'title' => __( 'Header & Navigation', 'ephemeris' ),
				'description' => esc_html__( 'Adjust your Header and Navigation sections.', 'ephemeris' ),
				'priority' => 165,
			)
		);
	}

	/**
	 * Register the Customizer sections
	 */
	public function ephemeris_add_customizer_sections( $wp_customize ) {
		/**
		 * Rename the default Colors section
		 */
		$wp_customize->get_section( 'colors' )->title = 'Background';

		/**
		 * Move the default Colors section to our new Colors Panel
		 */
		$wp_customize->get_section( 'colors' )->panel = 'colors_panel';

		/**
		 * Change the Priority of the default Colors section so it's at the top of our Panel
		 */
		$wp_customize->get_section( 'colors' )->priority = 10;

		/**
		 * Add our Page & Post Headers Section
		 */
		$wp_customize->add_section( 'color_page_post_headers_section',
			array(
				'title' => __( 'Page &amp; Post Headers', 'ephemeris' ),
				'description' => esc_html__( 'Set the colors for the main Headers that appear at the top of your Pages &amp; Posts.', 'ephemeris' ),
				'panel' => 'colors_panel',
				'priority' => 20,
			)
		);

		/**
		 * Add our Body Headers Section
		 */
		$wp_customize->add_section( 'color_body_headers_section',
			array(
				'title' => __( 'Body Headers', 'ephemeris' ),
				'description' => esc_html__( 'Set the colors for any Headers that appear in the main body of your site.', 'ephemeris' ),
				'panel' => 'colors_panel',
				'priority' => 30,
			)
		);

		/**
		 * Add our Body Headers Section
		 */
		$wp_customize->add_section( 'color_body_text_section',
			array(
				'title' => __( 'Body Text', 'ephemeris' ),
				'description' => esc_html__( 'Select the color for the text that appears in the main body of your site.', 'ephemeris' ),
				'panel' => 'colors_panel',
				'priority' => 40,
			)
		);

		/**
		 * Add our Layout Section
		 */
		$wp_customize->add_section( 'layout_section',
			array(
				'title' => __( 'Site Layout', 'ephemeris' ),
				'description' => esc_html__( 'Adjust the layout of your site. After adjusting the width, the correct Featured Image width will be calculated for all new images that are uploaded. For all your previously uploaded images, you should consider regenerating your thumbnails.', 'ephemeris' ),
				'priority' => 160,
			)
		);

		/**
		 * Add our Social Icons Section
		 */
		$wp_customize->add_section( 'social_icons_section',
			array(
				'title' => __( 'Social Icons', 'ephemeris' ),
				'description' => esc_html__( 'Add your social media links and we\'ll automatically match them with the appropriate icons. Drag and drop the URLs to rearrange their order.', 'ephemeris' ),
				'panel' => 'header_naviation_panel',
			)
		);

		/**
		 * Add our Contact Section
		 */
		$wp_customize->add_section( 'contact_section',
			array(
				'title' => __( 'Contact', 'ephemeris' ),
				'description' => esc_html__( 'Add your phone number to the site header bar.', 'ephemeris' ),
				'panel' => 'header_naviation_panel',
			)
		);

		/**
		 * Add our Search Section
		 */
		$wp_customize->add_section( 'search_section',
			array(
				'title' => __( 'Search', 'ephemeris' ),
				'description' => esc_html__( 'Add a search icon to your primary naigation menu.', 'ephemeris' ),
				'panel' => 'header_naviation_panel',
			)
		);

		/**
		 * Add our Footer Section
		 */
		$wp_customize->add_section( 'footer_section',
			array(
				'title' => __( 'Footer Layout', 'ephemeris' ),
				'description' => esc_html__( 'Update the content and style of the site footer. The Footer Content will be displayed just below the footer widgets.', 'ephemeris' ),
				'priority' => 170,
			)
		);

		/**
		 * Add our WooCommerce Layout Section, only if WooCommerce is active
		 */
		$wp_customize->add_section( 'woocommerce_layout_section',
			array(
				'title' => __( 'WooCommerce Layout', 'ephemeris' ),
				'description' => esc_html__( 'Adjust the layout of your WooCommerce shop.', 'ephemeris' ),
				'active_callback' => 'ephemeris_is_woocommerce_active',
				'priority' => 175,
			)
		);
	}

	/**
	 * Register our color controls
	 */
	public function ephemeris_register_color_controls( $wp_customize ) {
		// Add our Main Page & Post Header colors

		// Add our color setting and control for title header normal color
		$wp_customize->add_setting( 'color_header_title_normal',
			array(
				'default' => $this->defaults['color_header_title_normal'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Customize_Alpha_Color_Control( $wp_customize, 'color_header_title_normal',
			array(
				'label' => __( 'Header Color', 'ephemeris' ),
				'section' => 'color_page_post_headers_section',
			)
		) );

		// Add our color setting and control for title header link color
		$wp_customize->add_setting( 'color_header_title_link',
			array(
				'default' => $this->defaults['color_header_title_link'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Customize_Alpha_Color_Control( $wp_customize, 'color_header_title_link',
			array(
				'label' => __( 'Header Link Color', 'ephemeris' ),
				'section' => 'color_page_post_headers_section',
			)
		) );

		// Add our color setting and control for title header hover color
		$wp_customize->add_setting( 'color_header_title_hover',
			array(
				'default' => $this->defaults['color_header_title_hover'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Customize_Alpha_Color_Control( $wp_customize, 'color_header_title_hover',
			array(
				'label' => __( 'Header Link Hover Color', 'ephemeris' ),
				'section' => 'color_page_post_headers_section',
			)
		) );

		// Add our color setting and control for title header visited color
		$wp_customize->add_setting( 'color_header_title_visited',
			array(
				'default' => $this->defaults['color_header_title_visited'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Customize_Alpha_Color_Control( $wp_customize, 'color_header_title_visited',
			array(
				'label' => __( 'Header Link Visited Color', 'ephemeris' ),
				'section' => 'color_page_post_headers_section',
			)
		) );

		// Add our body Header colors

		// Add our color setting and control for body header normal color
		$wp_customize->add_setting( 'color_header_body_normal',
			array(
				'default' => $this->defaults['color_header_body_normal'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Customize_Alpha_Color_Control( $wp_customize, 'color_header_body_normal',
			array(
				'label' => __( 'Header Color', 'ephemeris' ),
				'section' => 'color_body_headers_section',
			)
		) );

		// Add our color setting and control for body header link color
		$wp_customize->add_setting( 'color_header_body_link',
			array(
				'default' => $this->defaults['color_header_body_link'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Customize_Alpha_Color_Control( $wp_customize, 'color_header_body_link',
			array(
				'label' => __( 'Header Link Color', 'ephemeris' ),
				'section' => 'color_body_headers_section',
			)
		) );

		// Add our color setting and control for body header hover color
		$wp_customize->add_setting( 'color_header_body_hover',
			array(
				'default' => $this->defaults['color_header_body_hover'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Customize_Alpha_Color_Control( $wp_customize, 'color_header_body_hover',
			array(
				'label' => __( 'Header Link Hover Color', 'ephemeris' ),
				'section' => 'color_body_headers_section',
			)
		) );

		// Add our color setting and control for body header visited color
		$wp_customize->add_setting( 'color_header_body_visited',
			array(
				'default' => $this->defaults['color_header_body_visited'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Customize_Alpha_Color_Control( $wp_customize, 'color_header_body_visited',
			array(
				'label' => __( 'Header Link Visited Color', 'ephemeris' ),
				'section' => 'color_body_headers_section',
			)
		) );

		// Add our body Text colors

		// Add our color setting and control for text normal color
		$wp_customize->add_setting( 'color_text_normal',
			array(
				'default' => $this->defaults['color_text_normal'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Customize_Alpha_Color_Control( $wp_customize, 'color_text_normal',
			array(
				'label' => __( 'Text Color ', 'ephemeris' ),
				'section' => 'color_body_text_section',
			)
		) );

		// Add our color setting and control for text link color
		$wp_customize->add_setting( 'color_text_link',
			array(
				'default' => $this->defaults['color_text_link'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Customize_Alpha_Color_Control( $wp_customize, 'color_text_link',
			array(
				'label' => __( 'Text Link Color', 'ephemeris' ),
				'section' => 'color_body_text_section',
			)
		) );

		// Add our color setting and control for text hover color
		$wp_customize->add_setting( 'color_text_hover',
			array(
				'default' => $this->defaults['color_text_hover'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Customize_Alpha_Color_Control( $wp_customize, 'color_text_hover',
			array(
				'label' => __( 'Text Link Hover Color', 'ephemeris' ),
				'section' => 'color_body_text_section',
			)
		) );

		// Add our color setting and control for text visited color
		$wp_customize->add_setting( 'color_text_visited',
			array(
				'default' => $this->defaults['color_text_visited'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Customize_Alpha_Color_Control( $wp_customize, 'color_text_visited',
			array(
				'label' => __( 'Text Link Visited Color', 'ephemeris' ),
				'section' => 'color_body_text_section',
			)
		) );
	}

	/**
	 * Register our layout controls
	 */
	public function ephemeris_register_layout_controls( $wp_customize ) {

		// Add our Slider setting and control for adjusting the width of the main content area
		$wp_customize->add_setting( 'layout_width',
			array(
				'default' => $this->defaults['layout_width'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control( new Skyrocket_Slider_Custom_Control( $wp_customize, 'layout_width',
			array(
				'label' => __( 'Content Area Width (px)', 'ephemeris' ),
				'section' => 'layout_section',
				'input_attrs' => array(
					'min' => 800,
					'max' => 2000,
					'step' => 5,
				),
			)
		) );
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
				'sanitize_callback' => 'skyrocket_switch_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'social_newtab',
			array(
				'label' => __( 'Open in new browser tab', 'ephemeris' ),
				'section' => 'social_icons_section',
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'social_newtab',
			array(
				'selector' => '.social-header',
				'container_inclusive' => false,
				'render_callback' => function() {
					echo ephemeris_get_social_media();
				},
				'fallback_refresh' => true,
			)
		);

		// Add our Text Radio Button setting and Custom Control for controlling alignment of icons
		$wp_customize->add_setting( 'social_alignment',
			array(
				'default' => $this->defaults['social_alignment'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_radio_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Text_Radio_Button_Custom_Control( $wp_customize, 'social_alignment',
			array(
				'label' => __( 'Alignment', 'ephemeris' ),
				'description' => esc_html__( 'Choose the alignment for your social icons', 'ephemeris' ),
				'section' => 'social_icons_section',
				'choices' => array(
					'alignleft' => __( 'Left', 'ephemeris' ),
					'alignright' => __( 'Right', 'ephemeris' ),
				),
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'social_alignment',
			array(
				'selector' => '.social-header',
				'container_inclusive' => false,
				'render_callback' => function() {
					echo ephemeris_get_social_media();
				},
				'fallback_refresh' => true,
			)
		);

		// Add our Sortable Repeater setting and Custom Control for Social media URLs
		$wp_customize->add_setting( 'social_urls',
			array(
				'default' => $this->defaults['social_urls'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_url_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Sortable_Repeater_Custom_Control( $wp_customize, 'social_urls',
			array(
				'label' => __( 'Social URLs', 'ephemeris' ),
				'description' => esc_html__( 'Add your social media links.', 'ephemeris' ),
				'section' => 'social_icons_section',
				'button_labels' => array(
					'add' => __( 'Add Icon', 'ephemeris' ),
				),
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'social_urls',
			array(
				'selector' => '.social-header',
				'container_inclusive' => false,
				'render_callback' => function() {
					echo ephemeris_get_social_media();
				},
				'fallback_refresh' => true,
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
			'YouTube' => __( '<i class="fa fa-youtube"></i>', 'ephemeris' ),
		);
		$wp_customize->add_setting( 'social_url_icons',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_text_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Single_Accordion_Custom_Control( $wp_customize, 'social_url_icons',
			array(
				'label' => __( 'View list of available icons', 'ephemeris' ),
				'description' => $socialIconsList,
				'section' => 'social_icons_section',
			)
		) );

		// Add our Checkbox switch setting and Custom Control for displaying an RSS icon
		$wp_customize->add_setting( 'social_rss',
			array(
				'default' => $this->defaults['social_rss'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_switch_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'social_rss',
			array(
				'label' => __( 'Display RSS icon', 'ephemeris' ),
				'section' => 'social_icons_section',
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'social_rss',
			array(
				'selector' => '.social-header',
				'container_inclusive' => false,
				'render_callback' => function() {
					echo ephemeris_get_social_media();
				},
				'fallback_refresh' => true,
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
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control( 'contact_phone',
			array(
				'label' => __( 'Display phone number', 'ephemeris' ),
				'type' => 'text',
				'section' => 'contact_section',
			)
		);
		$wp_customize->selective_refresh->add_partial( 'contact_phone',
			array(
				'selector' => '.social-header',
				'container_inclusive' => false,
				'render_callback' => function() {
					echo ephemeris_get_social_media();
				},
				'fallback_refresh' => true,
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
				'sanitize_callback' => 'skyrocket_switch_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'search_menu_icon',
			array(
				'label' => __( 'Display Search Icon', 'ephemeris' ),
				'section' => 'search_section',
			)
		) );
	}

	/**
	 * Register our Footer controls
	 */
	public function ephemeris_register_footer_controls( $wp_customize ) {
		// Add our Alpha Color Picker setting & control for the footer background colour
		$wp_customize->add_setting( 'footer_background_color',
			array(
				'default' => $this->defaults['footer_background_color'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Customize_Alpha_Color_Control( $wp_customize, 'footer_background_color',
			array(
				'label' => __( 'Footer Background Color', 'ephemeris' ),
				'description' => __( 'Select the background color for the footer.', 'ephemeris' ),
				'section' => 'footer_section',
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
				),
			)
		) );

		// Add our Alpha Color Picker setting & control for the footer font colour
		$wp_customize->add_setting( 'footer_font_color',
			array(
				'default' => $this->defaults['footer_font_color'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Customize_Alpha_Color_Control( $wp_customize, 'footer_font_color',
			array(
				'label' => __( 'Footer Font Color', 'ephemeris' ),
				'description' => __( 'Select the font color for the footer.', 'ephemeris' ),
				'section' => 'footer_section',
				'show_opacity' => true,
				'palette' => array(
					'#000',
					'#fff',
					'#df312c',
					'#df9a23',
					'#eef000',
					'#7ed934',
					'#1571c1',
					'#8309e7',
				),
			)
		) );

		// Add our TinyMCE Editor setting & control for getting the footer credits
		$wp_customize->add_setting( 'footer_credits',
			array(
				'default' => $this->defaults['footer_credits'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control( new Skyrocket_TinyMCE_Custom_control( $wp_customize, 'footer_credits',
			array(
				'label' => __( 'Footer Content', 'ephemeris' ),
				'description' => __( 'Enter the text you&#8217;d like to display in the footer.', 'ephemeris' ),
				'section' => 'footer_section',
				'input_attrs' => array(
					'toolbar1' => 'bold italic bullist numlist alignleft aligncenter alignright link',
				),
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'footer_credits',
			array(
				'selector' => '.footer-credits',
				'container_inclusive' => false,
				'render_callback' => function() {
					echo ephemeris_get_credits();
				},
				'fallback_refresh' => false,
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
				'sanitize_callback' => 'skyrocket_switch_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'woocommerce_shop_sidebar',
			array(
				'label' => __( 'Shop page sidebar', 'ephemeris' ),
				'section' => 'woocommerce_layout_section',
			)
		) );

		// Add our Checkbox switch setting and control for displaying a sidebar on the single product page
		$wp_customize->add_setting( 'woocommerce_product_sidebar',
			array(
				'default' => $this->defaults['woocommerce_product_sidebar'],
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_switch_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'woocommerce_product_sidebar',
			array(
				'label' => __( 'Single Product page sidebar', 'ephemeris' ),
				'section' => 'woocommerce_layout_section',
			)
		) );

		// Add our Checkbox switch setting and control for displaying a sidebar on the Product Category & Tag page
		$wp_customize->add_setting( 'woocommerce_cattag_sidebar',
			array(
				'default' => $this->defaults['woocommerce_cattag_sidebar'],
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_switch_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'woocommerce_cattag_sidebar',
			array(
				'label' => __( 'Category & Tag sidebar', 'ephemeris' ),
				'section' => 'woocommerce_layout_section',
			)
		) );

		// Add our Simple Notice setting and control for displaying a message about the WooCommerce shop sidebars
		$wp_customize->add_setting( 'woocommerce_other_sidebar',
			array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'skyrocket_text_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Simple_Notice_Custom_control( $wp_customize, 'woocommerce_other_sidebar',
			array(
				'label' => __( 'Cart, Checkout & My Account sidebars', 'ephemeris' ),
				'description' => esc_html__( 'The Cart, Checkout and My Account pages are displayed using shortcodes. To remove the sidebar from these Pages, simply edit each Page and change the Template (in the Page Attributes Panel) to Full-width Page.', 'ephemeris' ),
				'section' => 'woocommerce_layout_section',
			)
		) );

		// Add our Checkbox switch setting and control for displaying the WooCommerce Breadcrumbs
		$wp_customize->add_setting( 'woocommerce_breadcrumbs',
			array(
				'default' => $this->defaults['woocommerce_breadcrumbs'],
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_switch_sanitization',
			)
		);
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'woocommerce_breadcrumbs',
			array(
				'label' => __( 'Display breadcrumbs', 'ephemeris' ),
				'section' => 'woocommerce_layout_section',
			)
		) );

		// Add our Select setting and control for selecting the number of products to display on the shop page
		$wp_customize->add_setting( 'woocommerce_shop_products',
			array(
				'default'=> $this->defaults['woocommerce_shop_products'],
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_radio_sanitization',
			)
		);
		$wp_customize->add_control( 'woocommerce_shop_products',
			array(
				'label' => __( 'Shop Products', 'ephemeris' ),
				'description' => esc_html__( 'Select the number of products to display on the shop page', 'ephemeris' ),
				'section' => 'woocommerce_layout_section',
				'type' => 'select',
				'choices' => array(
					'4' => __( '4 Products', 'ephemeris' ),
					'8' => __( '8 Products', 'ephemeris' ),
					'12' => __( '12 Products', 'ephemeris' ),
					'16' => __( '16 Products', 'ephemeris' ),
					'20' => __( '20 Products', 'ephemeris' ),
					'24' => __( '24 Products', 'ephemeris' ),
					'28' => __( '28 Products', 'ephemeris' ),
				),
			)
		);
	}
}

/**
 * Load all our Customizer Custom Controls
 */
require_once trailingslashit( dirname(__FILE__) ) . 'custom-controls.php';

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	$ephemeris_settings = new ephemeris_initialise_customizer_settings();
}
