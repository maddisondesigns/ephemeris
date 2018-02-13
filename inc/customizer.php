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

		// Add Edit Shortcut to primary Navigation
		add_action( 'customize_register', array( $this, 'ephemeris_add_partial_to_controls' ) );

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

		// Register our footer controls
		add_action( 'customize_register', array( $this, 'ephemeris_register_footer_controls' ) );

		// Register our WooCommerce controls. Callback will only display these controls if WooCommerce is active
		add_action( 'customize_register', array( $this, 'ephemeris_register_woocommerce_controls' ) );

		// Register our Elementor controls. Callback will only display these controls if Elementor is active
		add_action( 'customize_register', array( $this, 'ephemeris_register_elementor_controls' ) );
	}

	/**
	 * Register the Customizer panels
	 */
	public function ephemeris_add_customizer_panels( $wp_customize ) {
		// Add our Colors Panel
		$wp_customize->add_panel( 'colors_panel',
		 	array(
				'title' => __( 'Colors', 'ephemeris' ),
				'description' => esc_html__( 'Set the colors for your site.', 'ephemeris' ),
				'priority' => 40,
			)
		);

		// Add our Header & Navigation Panel
		$wp_customize->add_panel( 'header_naviation_panel',
		 	array(
				'title' => __( 'Header & Navigation', 'ephemeris' ),
				'description' => esc_html__( 'Adjust your Header and Navigation sections.', 'ephemeris' ),
				'priority' => 50,
			)
		);
	}

	/**
	 * Register the Customizer sections
	 */
	public function ephemeris_add_customizer_sections( $wp_customize ) {
		// Rename the default Colors section
		$wp_customize->get_section( 'colors' )->title = 'Background';

		// Move the default Colors section to our new Colors Panel
		$wp_customize->get_section( 'colors' )->panel = 'colors_panel';

		// Change the Priority of the default Colors section so it's at the top of our Panel
		$wp_customize->get_section( 'colors' )->priority = 10;

		// Add our Page & Post Headers Section
		$wp_customize->add_section( 'color_page_post_headers_section',
			array(
				'title' => __( 'Page &amp; Post Headers', 'ephemeris' ),
				'description' => esc_html__( 'Set the colors for the main Headers that appear at the top of your Pages &amp; Posts.', 'ephemeris' ),
				'panel' => 'colors_panel',
				'priority' => 20,
			)
		);

		// Add our Body Headers Section
		$wp_customize->add_section( 'color_body_headers_section',
			array(
				'title' => __( 'Body Headers', 'ephemeris' ),
				'description' => esc_html__( 'Set the colors for any Headers that appear in the main body of your site.', 'ephemeris' ),
				'panel' => 'colors_panel',
				'priority' => 30,
			)
		);

		// Add our Body Text Section
		$wp_customize->add_section( 'color_body_text_section',
			array(
				'title' => __( 'Body Text', 'ephemeris' ),
				'description' => esc_html__( 'Select the color for the text that appears in the main body of your site.', 'ephemeris' ),
				'panel' => 'colors_panel',
				'priority' => 40,
			)
		);

		// Add our Layout Section
		$wp_customize->add_section( 'layout_section',
			array(
				'title' => __( 'Site Layout', 'ephemeris' ),
				'description' => esc_html__( 'Adjust the layout of your site. After adjusting the width, the correct Featured Image width will be calculated for all new images that are uploaded. For all your previously uploaded images, you should consider regenerating your thumbnails.', 'ephemeris' ),
				'priority' => 30,
			)
		);

		// Add our Social Icons Section
		$wp_customize->add_section( 'social_icons_section',
			array(
				'title' => __( 'Social Icons', 'ephemeris' ),
				'description' => esc_html__( 'Add your social media links and we\'ll automatically match them with the appropriate icons. Drag and drop the URLs to rearrange their order.', 'ephemeris' ),
				'panel' => 'header_naviation_panel',
			)
		);

		// Add our Contact Section
		$wp_customize->add_section( 'contact_section',
			array(
				'title' => __( 'Contact', 'ephemeris' ),
				'description' => esc_html__( 'Add your phone number to the site header bar.', 'ephemeris' ),
				'panel' => 'header_naviation_panel',
			)
		);

		// Add our Search Section
		$wp_customize->add_section( 'search_section',
			array(
				'title' => __( 'Search', 'ephemeris' ),
				'description' => esc_html__( 'Add a search icon to your primary navigation menu.', 'ephemeris' ),
				'panel' => 'header_naviation_panel',
			)
		);

		// Add our Footer Section
		$wp_customize->add_section( 'footer_section',
			array(
				'title' => __( 'Footer Layout', 'ephemeris' ),
				'description' => esc_html__( 'Update the content and style of the site footer. The Footer Content will be displayed just below the footer widgets.', 'ephemeris' ),
				'priority' => 95,
			)
		);

		// Add our WooCommerce Layout Section, only if WooCommerce is active
		$wp_customize->add_section( 'woocommerce_layout_section',
			array(
				'title' => __( 'WooCommerce Layout', 'ephemeris' ),
				'description' => esc_html__( 'Adjust the layout of your WooCommerce shop.', 'ephemeris' ),
				'active_callback' => 'ephemeris_is_woocommerce_plugin_active_active_callback',
				'priority' => 160,
			)
		);

		// Add our Elementor Section, only if Elementor is active
		$wp_customize->add_section( 'elementor_section',
			array(
				'title' => __( 'Elementor', 'ephemeris' ),
				'description' => esc_html__( 'If you wish to replace the default theme Header &amp; Footer with your own custom Elementor templates, select them below. You have the option to replace just one, or you can replace both.', 'ephemeris' ),
				'active_callback' => 'ephemeris_is_elementor_plugin_active_active_callback',
				'priority' => 165,
			)
		);
	}

	/**
	 * Manually add the Edit Shortcut to the main navigation as it's not included when wp_nav_menu uses 'echo' => false
	 */
	public function ephemeris_add_partial_to_controls( $wp_customize ) {
		$wp_customize->selective_refresh->add_partial( 'nav_menu_locations[primary-menu]',
			array(
				'selector' => '.main-navigation',
				'container_inclusive' => false,
				'render_callback' => '__return_false',
				'fallback_refresh' => true,
			)
		);
	}

	/**
	 * Register our color controls
	 */
	public function ephemeris_register_color_controls( $wp_customize ) {
		// Add our Main Page & Post Header colors

		// Add our color setting and control for title header normal color
		$wp_customize->add_setting( 'ephemeris_color_header_title_normal',
			array(
				'default' => $this->defaults['ephemeris_color_header_title_normal'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Customize_Alpha_Color_Control( $wp_customize, 'ephemeris_color_header_title_normal',
			array(
				'label' => __( 'Header Color', 'ephemeris' ),
				'section' => 'color_page_post_headers_section',
			)
		) );

		// Add our color setting and control for title header link color
		$wp_customize->add_setting( 'ephemeris_color_header_title_link',
			array(
				'default' => $this->defaults['ephemeris_color_header_title_link'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Customize_Alpha_Color_Control( $wp_customize, 'ephemeris_color_header_title_link',
			array(
				'label' => __( 'Header Link Color', 'ephemeris' ),
				'section' => 'color_page_post_headers_section',
			)
		) );

		// Add our color setting and control for title header hover color
		$wp_customize->add_setting( 'ephemeris_color_header_title_hover',
			array(
				'default' => $this->defaults['ephemeris_color_header_title_hover'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Customize_Alpha_Color_Control( $wp_customize, 'ephemeris_color_header_title_hover',
			array(
				'label' => __( 'Header Link Hover Color', 'ephemeris' ),
				'section' => 'color_page_post_headers_section',
			)
		) );

		// Add our color setting and control for title header visited color
		$wp_customize->add_setting( 'ephemeris_color_header_title_visited',
			array(
				'default' => $this->defaults['ephemeris_color_header_title_visited'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Customize_Alpha_Color_Control( $wp_customize, 'ephemeris_color_header_title_visited',
			array(
				'label' => __( 'Header Link Visited Color', 'ephemeris' ),
				'section' => 'color_page_post_headers_section',
			)
		) );

		// Add our body Header colors

		// Add our color setting and control for body header normal color
		$wp_customize->add_setting( 'ephemeris_color_header_body_normal',
			array(
				'default' => $this->defaults['ephemeris_color_header_body_normal'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Customize_Alpha_Color_Control( $wp_customize, 'ephemeris_color_header_body_normal',
			array(
				'label' => __( 'Header Color', 'ephemeris' ),
				'section' => 'color_body_headers_section',
			)
		) );

		// Add our color setting and control for body header link color
		$wp_customize->add_setting( 'ephemeris_color_header_body_link',
			array(
				'default' => $this->defaults['ephemeris_color_header_body_link'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Customize_Alpha_Color_Control( $wp_customize, 'ephemeris_color_header_body_link',
			array(
				'label' => __( 'Header Link Color', 'ephemeris' ),
				'section' => 'color_body_headers_section',
			)
		) );

		// Add our color setting and control for body header hover color
		$wp_customize->add_setting( 'ephemeris_color_header_body_hover',
			array(
				'default' => $this->defaults['ephemeris_color_header_body_hover'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Customize_Alpha_Color_Control( $wp_customize, 'ephemeris_color_header_body_hover',
			array(
				'label' => __( 'Header Link Hover Color', 'ephemeris' ),
				'section' => 'color_body_headers_section',
			)
		) );

		// Add our color setting and control for body header visited color
		$wp_customize->add_setting( 'ephemeris_color_header_body_visited',
			array(
				'default' => $this->defaults['ephemeris_color_header_body_visited'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Customize_Alpha_Color_Control( $wp_customize, 'ephemeris_color_header_body_visited',
			array(
				'label' => __( 'Header Link Visited Color', 'ephemeris' ),
				'section' => 'color_body_headers_section',
			)
		) );

		// Add our body Text colors

		// Add our color setting and control for text normal color
		$wp_customize->add_setting( 'ephemeris_color_text_normal',
			array(
				'default' => $this->defaults['ephemeris_color_text_normal'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Customize_Alpha_Color_Control( $wp_customize, 'ephemeris_color_text_normal',
			array(
				'label' => __( 'Text Color ', 'ephemeris' ),
				'section' => 'color_body_text_section',
			)
		) );

		// Add our color setting and control for text link color
		$wp_customize->add_setting( 'ephemeris_color_text_link',
			array(
				'default' => $this->defaults['ephemeris_color_text_link'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Customize_Alpha_Color_Control( $wp_customize, 'ephemeris_color_text_link',
			array(
				'label' => __( 'Text Link Color', 'ephemeris' ),
				'section' => 'color_body_text_section',
			)
		) );

		// Add our color setting and control for text hover color
		$wp_customize->add_setting( 'ephemeris_color_text_hover',
			array(
				'default' => $this->defaults['ephemeris_color_text_hover'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Customize_Alpha_Color_Control( $wp_customize, 'ephemeris_color_text_hover',
			array(
				'label' => __( 'Text Link Hover Color', 'ephemeris' ),
				'section' => 'color_body_text_section',
			)
		) );

		// Add our color setting and control for text visited color
		$wp_customize->add_setting( 'ephemeris_color_text_visited',
			array(
				'default' => $this->defaults['ephemeris_color_text_visited'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Customize_Alpha_Color_Control( $wp_customize, 'ephemeris_color_text_visited',
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
		$wp_customize->add_setting( 'ephemeris_layout_width',
			array(
				'default' => $this->defaults['ephemeris_layout_width'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control( new Ephemeris_Slider_Custom_Control( $wp_customize, 'ephemeris_layout_width',
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
		$wp_customize->add_setting( 'ephemeris_social_newtab',
			array(
				'default' => $this->defaults['ephemeris_social_newtab'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_switch_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Toggle_Switch_Custom_control( $wp_customize, 'ephemeris_social_newtab',
			array(
				'label' => __( 'Open in new browser tab', 'ephemeris' ),
				'section' => 'social_icons_section',
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'ephemeris_social_newtab',
			array(
				'selector' => '.social-icons',
				'container_inclusive' => true,
				'render_callback' => 'ephemeris_get_social_media_render_callback',
				'fallback_refresh' => true,
			)
		);

		// Add our Text Radio Button setting and Custom Control for controlling alignment of icons
		$wp_customize->add_setting( 'ephemeris_social_alignment',
			array(
				'default' => $this->defaults['ephemeris_social_alignment'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_radio_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Text_Radio_Button_Custom_Control( $wp_customize, 'ephemeris_social_alignment',
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
		$wp_customize->selective_refresh->add_partial( 'ephemeris_social_alignment',
			array(
				'selector' => '.social-icons',
				'container_inclusive' => true,
				'render_callback' => 'ephemeris_get_social_media_render_callback',
				'fallback_refresh' => true,
			)
		);

		// Add our Sortable Repeater setting and Custom Control for Social media URLs
		$wp_customize->add_setting( 'ephemeris_social_urls',
			array(
				'default' => $this->defaults['ephemeris_social_urls'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_url_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Sortable_Repeater_Custom_Control( $wp_customize, 'ephemeris_social_urls',
			array(
				'label' => __( 'Social URLs', 'ephemeris' ),
				'description' => esc_html__( 'Add your social media links.', 'ephemeris' ),
				'section' => 'social_icons_section',
				'button_labels' => array(
					'add' => __( 'Add Icon', 'ephemeris' ),
				),
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'ephemeris_social_urls',
			array(
				'selector' => '.social-icons',
				'container_inclusive' => true,
				'render_callback' => 'ephemeris_get_social_media_render_callback',
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
				'sanitize_callback' => 'ephemeris_text_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Single_Accordion_Custom_Control( $wp_customize, 'social_url_icons',
			array(
				'label' => __( 'View list of available icons', 'ephemeris' ),
				'description' => $socialIconsList,
				'section' => 'social_icons_section',
			)
		) );

		// Add our Checkbox switch setting and Custom Control for displaying an RSS icon
		$wp_customize->add_setting( 'ephemeris_social_rss',
			array(
				'default' => $this->defaults['ephemeris_social_rss'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_switch_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Toggle_Switch_Custom_control( $wp_customize, 'ephemeris_social_rss',
			array(
				'label' => __( 'Display RSS icon', 'ephemeris' ),
				'section' => 'social_icons_section',
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'ephemeris_social_rss',
			array(
				'selector' => '.social-icons',
				'container_inclusive' => true,
				'render_callback' => 'ephemeris_get_social_media_render_callback',
				'fallback_refresh' => true,
			)
		);

	}

	/**
	 * Register our Contact controls
	 */
	public function ephemeris_register_contact_controls( $wp_customize ) {
		// Add our Text field setting and Control for displaying the phone number
		$wp_customize->add_setting( 'ephemeris_contact_phone',
			array(
				'default' => $this->defaults['ephemeris_contact_phone'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			)
		);
		$wp_customize->add_control( 'ephemeris_contact_phone',
			array(
				'label' => __( 'Display phone number', 'ephemeris' ),
				'type' => 'text',
				'section' => 'contact_section',
			)
		);
		$wp_customize->selective_refresh->add_partial( 'ephemeris_contact_phone',
			array(
				'selector' => '.social-icons',
				'container_inclusive' => true,
				'render_callback' => 'ephemeris_get_social_media_render_callback',
				'fallback_refresh' => true,
			)
		);

	}

	/**
	 * Register our Search controls
	 */
	public function ephemeris_register_search_controls( $wp_customize ) {
		// Add our Checkbox switch setting and control for opening URLs in a new tab
		$wp_customize->add_setting( 'ephemeris_search_menu_icon',
			array(
				'default' => $this->defaults['ephemeris_search_menu_icon'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_switch_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Toggle_Switch_Custom_control( $wp_customize, 'ephemeris_search_menu_icon',
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
		$wp_customize->add_setting( 'ephemeris_footer_background_color',
			array(
				'default' => $this->defaults['ephemeris_footer_background_color'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Customize_Alpha_Color_Control( $wp_customize, 'ephemeris_footer_background_color',
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
		$wp_customize->add_setting( 'ephemeris_footer_credits_font_color',
			array(
				'default' => $this->defaults['ephemeris_footer_credits_font_color'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_hex_rgba_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Customize_Alpha_Color_Control( $wp_customize, 'ephemeris_footer_credits_font_color',
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
		$wp_customize->add_setting( 'ephemeris_footer_credits',
			array(
				'default' => $this->defaults['ephemeris_footer_credits'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control( new Ephemeris_TinyMCE_Custom_control( $wp_customize, 'ephemeris_footer_credits',
			array(
				'label' => __( 'Footer Content', 'ephemeris' ),
				'description' => __( 'Enter the text you&#8217;d like to display in the footer.', 'ephemeris' ),
				'section' => 'footer_section',
				'input_attrs' => array(
					'toolbar1' => 'bold italic bullist numlist alignleft aligncenter alignright link',
				),
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'ephemeris_footer_credits',
			array(
				'selector' => '.footer-credits',
				'container_inclusive' => false,
				'render_callback' => 'ephemeris_get_credits_render_callback',
				'fallback_refresh' => false,
			)
		);

		// Add our Simple Notice setting and control for displaying the credit filters
		$wp_customize->add_setting( 'footer_filters',
			array(
				'default' => '',
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_text_sanitization'
			)
		);
		$wp_customize->add_control( new Ephemeris_Simple_Notice_Custom_control( $wp_customize, 'footer_filters',
			array(
				'label' => '',
				'description' => __( '<code>&#37;currentyear&#37;</code> to insert the current year (auto updates)<br /><code>&#37;copy&#37;</code> to insert the Copyright symbol<br /><code>&#37;reg&#37;</code> to insert the Registered symbol<br /><code>&#37;trade&#37;</code> to insert the Trademark symbol', 'ephemeris' ),
				'section' => 'footer_section'
			)
		) );
	}

	/**
	 * Register our WooCommerce Layout controls
	 */
	public function ephemeris_register_woocommerce_controls( $wp_customize ) {

		// Add our Checkbox switch setting and control for displaying a sidebar on the shop page
		$wp_customize->add_setting( 'ephemeris_woocommerce_shop_sidebar',
			array(
				'default' => $this->defaults['ephemeris_woocommerce_shop_sidebar'],
				'transport' => 'refresh',
				'sanitize_callback' => 'ephemeris_switch_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Toggle_Switch_Custom_control( $wp_customize, 'ephemeris_woocommerce_shop_sidebar',
			array(
				'label' => __( 'Shop page sidebar', 'ephemeris' ),
				'section' => 'woocommerce_layout_section',
			)
		) );

		// Add our Checkbox switch setting and control for displaying a sidebar on the single product page
		$wp_customize->add_setting( 'ephemeris_woocommerce_product_sidebar',
			array(
				'default' => $this->defaults['ephemeris_woocommerce_product_sidebar'],
				'transport' => 'refresh',
				'sanitize_callback' => 'ephemeris_switch_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Toggle_Switch_Custom_control( $wp_customize, 'ephemeris_woocommerce_product_sidebar',
			array(
				'label' => __( 'Single Product page sidebar', 'ephemeris' ),
				'section' => 'woocommerce_layout_section',
			)
		) );

		// Add our Checkbox switch setting and control for displaying a sidebar on the Product Category & Tag page
		$wp_customize->add_setting( 'ephemeris_woocommerce_cattag_sidebar',
			array(
				'default' => $this->defaults['ephemeris_woocommerce_cattag_sidebar'],
				'transport' => 'refresh',
				'sanitize_callback' => 'ephemeris_switch_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Toggle_Switch_Custom_control( $wp_customize, 'ephemeris_woocommerce_cattag_sidebar',
			array(
				'label' => __( 'Category & Tag sidebar', 'ephemeris' ),
				'section' => 'woocommerce_layout_section',
			)
		) );

		// Add our Simple Notice setting and control for displaying a message about the WooCommerce shop sidebars
		$wp_customize->add_setting( 'woocommerce_other_sidebar',
			array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'ephemeris_text_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Simple_Notice_Custom_control( $wp_customize, 'woocommerce_other_sidebar',
			array(
				'label' => __( 'Cart, Checkout & My Account sidebars', 'ephemeris' ),
				'description' => esc_html__( 'The Cart, Checkout and My Account pages are displayed using shortcodes. To remove the sidebar from these Pages, simply edit each Page and change the Template (in the Page Attributes Panel) to Full-width Page.', 'ephemeris' ),
				'section' => 'woocommerce_layout_section',
			)
		) );

		// Add our Checkbox switch setting and control for displaying the WooCommerce Breadcrumbs
		$wp_customize->add_setting( 'ephemeris_woocommerce_breadcrumbs',
			array(
				'default' => $this->defaults['ephemeris_woocommerce_breadcrumbs'],
				'transport' => 'refresh',
				'sanitize_callback' => 'ephemeris_switch_sanitization',
			)
		);
		$wp_customize->add_control( new Ephemeris_Toggle_Switch_Custom_control( $wp_customize, 'ephemeris_woocommerce_breadcrumbs',
			array(
				'label' => __( 'Display breadcrumbs', 'ephemeris' ),
				'section' => 'woocommerce_layout_section',
			)
		) );

		// Only add a Setting and Control for the number of WooCommerce products if WooCommerce is less than v3.3
		if ( !ephemeris_woocommerce_version_check( '3.3' ) ) {
			// Add our Select setting and control for selecting the number of products to display on the shop page
			$wp_customize->add_setting( 'ephemeris_woocommerce_shop_products',
				array(
					'default'=> $this->defaults['ephemeris_woocommerce_shop_products'],
					'transport' => 'refresh',
					'sanitize_callback' => 'ephemeris_radio_sanitization',
				)
			);
			$wp_customize->add_control( 'ephemeris_woocommerce_shop_products',
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
	 * Register our Elementor controls
	 */
	public function ephemeris_register_elementor_controls( $wp_customize ) {
		$template_library_posts = '';
		$template = '';
		$elementor_templates = array();
		$elementor_choices = array();

		// Retrieve the list of Elementor library templates
		$template_library_posts = get_posts(
			array(
				'sort_order' => 'DESC',
				'post_type' => 'elementor_library',
			)
		);
		wp_reset_postdata();

		if ( !empty( $template_library_posts ) ) {

			// Add our Simple Notice setting and control for when there are no templates to select
			$wp_customize->add_setting( 'elementor_templates_notice',
				array(
					'default' => '',
					'transport' => 'postMessage',
					'sanitize_callback' => 'ephemeris_text_sanitization'
				)
			);
			$wp_customize->add_control( new Ephemeris_Simple_Notice_Custom_control( $wp_customize, 'elementor_templates_notice',
				array(
					'label' => __( 'Please Note:', 'ephemeris' ),
					'description' => __( 'By replacing the default theme header, you will also disable the themes default mobile menu so please ensure your template includes its own mobile navigation.', 'ephemeris' ),
					'section' => 'elementor_section'
				)
			) );

			foreach ($template_library_posts as $template ) {
				$elementor_templates[$template->ID] = $template->post_title;
			}

			// Add our default header selection to our list of choices
			$elementor_choices[$this->defaults['ephemeris_elementor_header_template']] = __( 'Use default theme header', 'ephemeris' );
			// Add our Elementor templates to our list of choices
			foreach ($elementor_templates as $key => $value) {
				$elementor_choices[$key] = $value;
			}

			// Add our Select setting and control for selecting the header template to use
			$wp_customize->add_setting( 'ephemeris_elementor_header_template',
				array(
					'default' => $this->defaults['ephemeris_elementor_header_template'],
					'transport' => 'refresh',
					'sanitize_callback' => 'ephemeris_radio_sanitization'
				)
			);
			$wp_customize->add_control( 'ephemeris_elementor_header_template',
				array(
					'label' => __( 'Header Template', 'ephemeris' ),
					'section' => 'elementor_section',
					'type' => 'select',
					'choices' => $elementor_choices,
				)
			);

			$elementor_choices = array();
			// Add our default footer selection to our list of choices
			$elementor_choices[$this->defaults['ephemeris_elementor_footer_template']] = __( 'Use default theme footer', 'ephemeris' );
			// Add our Elementor templates to our list of choices
			foreach ($elementor_templates as $key => $value) {
				$elementor_choices[$key] = $value;
			}

			// Add our Select setting and control for selecting the footer template to use
			$wp_customize->add_setting( 'ephemeris_elementor_footer_template',
				array(
					'default' => $this->defaults['ephemeris_elementor_footer_template'],
					'transport' => 'refresh',
					'sanitize_callback' => 'ephemeris_radio_sanitization'
				)
			);
			$wp_customize->add_control( 'ephemeris_elementor_footer_template',
				array(
					'label' => __( 'Footer Template', 'ephemeris' ),
					'section' => 'elementor_section',
					'type' => 'select',
					'choices' => $elementor_choices,
				)
			);
		}
		else {
			// Add our Simple Notice setting and control for when there are no templates to select
			$wp_customize->add_setting( 'elementor_templates_notice',
				array(
					'default' => '',
					'transport' => 'postMessage',
					'sanitize_callback' => 'ephemeris_text_sanitization'
				)
			);
			$wp_customize->add_control( new Ephemeris_Simple_Notice_Custom_control( $wp_customize, 'elementor_templates_notice',
				array(
					'label' => __( 'Templates unavailable!', 'ephemeris' ),
					'description' => __( 'You haven\'t ceated any Elementor Templates yet. You need to add a template to your Elementor Template Library if you wish to replace the default theme Header &amp; Footer.', 'ephemeris' ),
					'section' => 'elementor_section'
				)
			) );
		}
	}
}

/**
 * Active Callback for checking if the WooCommerce plugin is active
 *
 * @return string	boolean
 */
function ephemeris_is_woocommerce_plugin_active_active_callback() {
	return ephemeris_is_plugin_active( 'woocommerce' );
}

/**
 * Active Callback for checking if the Elementor plugin is active
 *
 * @return string	boolean
 */
function ephemeris_is_elementor_plugin_active_active_callback() {
	return ephemeris_is_plugin_active( 'elementor' );
}

/**
 * Render Callback for displaying the social media icons (which also includes the phone number and rss icons)
 */
function ephemeris_get_social_media_render_callback() {
	echo ephemeris_get_social_media();
}

/**
 * Render Callback for displaying the footer credits
 */
function ephemeris_get_credits_render_callback() {
	echo ephemeris_get_credits();
}

/**
 * Load all our Customizer Custom Controls
 */
require_once trailingslashit( get_template_directory() ) . 'inc/custom-controls.php';

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	$ephemeris_settings = new ephemeris_initialise_customizer_settings();
}
