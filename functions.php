<?php
/**
 * Ephemeris functions and definitions
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_setup' ) ) {
	function ephemeris_setup() {
		$defaults = ephemeris_generate_defaults();
		$contentwidth = 865;

		// Make theme available for translation
		load_theme_textdomain( 'ephemeris' );

		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style( array(
			'editor-style.css',
			'css/font-awesome.min.css'
			)
		);

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );

		// Calculate the optimal featured image size based on the layout width.
		// Default image width will be 865px with a site layout width of 1200px assuming 75/25 grid layout
		// ( ( site width - 20px padding ) * 70% grid container ) - 20px grid padding
		$contentwidth = absint( ( ( get_theme_mod( 'ephemeris_layout_width', $defaults['ephemeris_layout_width'] ) - 20 ) * 0.75 ) - 20 );

		// Create an extra image size for the Post featured image
		add_image_size( 'ephemeris_post_feature_full_width', $contentwidth, 500, true );

		// Set the width of our content when using the default template
		$GLOBALS['content_width'] = $contentwidth;

		// This theme uses wp_nav_menu() in one location
		register_nav_menus( array(
				'primary-menu' => esc_html__( 'Primary Menu', 'ephemeris' )
			)
		);

		// This theme supports a variety of post formats
		add_theme_support( 'post-formats', array(
			'aside',
			'audio',
			'chat',
			'gallery',
			'image',
			'link',
			'quote',
			'status',
			'video'
			)
		);

		// Add theme support for HTML5 markup for the search forms, comment forms, comment lists, gallery, and caption
		add_theme_support( 'html5', array(
			'comment-list',
			'comment-form',
			'gallery',
			'caption'
			)
		);

		// Enable support for widget sidebars selective refresh in the Customizer.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Enable support for Custom Backgrounds
		add_theme_support( 'custom-background', array(
				// Background color default
				'default-color' => 'fff',
				'wp-head-callback' => 'ephemeris_custom_background_cb',
			)
		);

		// Enable support for Custom Headers
		add_theme_support( 'custom-header', array(
				// Header text display default
				'header-text' => false,
				// Header text color default
				'default-text-color' => 'fff',
				// Flexible width
				'flex-width' => true,
				// Header image width (in pixels)
				'width' => 1160,
				// Flexible height
				'flex-height' => true,
				// Header image height (in pixels)
				'height' => 280
			)
		);

		// Enable support for Theme Logos
		add_theme_support( 'custom-logo', array(
			'width'       => 300,
			'height'      => 80,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title', 'site-description' ),
			)
		);

		// Let WordPress manage the document title.
		// By adding theme support, we declare that this theme does not use a hard-coded <title> tag in the document head, and expect WordPress to provide it for us.
		add_theme_support( 'title-tag' );

		// Enable support for WooCommerce & WooCommerce product galleries
		add_theme_support( 'woocommerce', array(
			'product_grid' => array(
				'default_rows' => 3,
				'default_columns' => 4,
			),
		) );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		// Display a handy map of where all the theme hooks reside
		// Only used when WP_EPHEMERIS_HOOKS is defined as true in wp-config.php
		if ( defined( 'WP_EPHEMERIS_HOOKS') && WP_EPHEMERIS_HOOKS ) {
			$ephemeris_hooks = ephemeris_get_hooks();
			foreach ( $ephemeris_hooks as $hook ) {
				add_action( $hook, 'ephemeris_display_hook' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'ephemeris_setup' );

/**
 * Enqueue scripts and styles
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_scripts_styles' ) ) {
	function ephemeris_scripts_styles() {

		/**
		 * Register and enqueue our stylesheets
		 */

		// Start off with a clean base by using normalise.
		wp_enqueue_style( 'normalize', trailingslashit( get_template_directory_uri() ) . 'css/normalize.css', array(), '7.0.0', 'all' );

		// Register and enqueue our icon font
		// We're using the awesome Font Awesome icon font. http://fortawesome.github.io/Font-Awesome
		wp_enqueue_style( 'font-awesome', trailingslashit( get_template_directory_uri() ) . 'css/font-awesome.min.css', array( 'normalize' ), '4.7.0', 'all' );

		// Our styles for setting up the grid. We're using Unsemantic. http://unsemantic.com
		wp_enqueue_style( 'unsemantic-grid', trailingslashit( get_template_directory_uri() ) . 'css/unsemantic.css', array( 'font-awesome' ), '1.0.0', 'all' );

		/*
		 * Load our Google Fonts.
		 *
		 * To disable in a child theme, use wp_dequeue_style()
		 * function mytheme_dequeue_fonts() {
		 *     wp_dequeue_style( 'ephemeris-fonts' );
		 * }
		 * add_action( 'wp_enqueue_scripts', 'mytheme_dequeue_fonts', 11 );
		 */
		$fonts_url = ephemeris_fonts_url();
		if ( !empty( $fonts_url ) ) {
			wp_enqueue_style( 'ephemeris-fonts', $fonts_url, array(), null );
		}

		// If using a child theme, auto-load the parent theme style.
		// Props to Justin Tadlock for this recommendation - http://justintadlock.com/archives/2014/11/03/loading-parent-styles-for-child-themes
		if ( is_child_theme() ) {
			wp_enqueue_style( 'parent-style', trailingslashit( get_template_directory_uri() ) . 'style.css' );
		}

		// Enqueue the default WordPress stylesheet
		wp_enqueue_style( 'ephemeris-style', get_stylesheet_uri() );

		/**
		 * Register and enqueue our scripts
		 */

		// Load Modernizr at the top of the document, which enables HTML5 elements and feature detects
		wp_enqueue_script( 'modernizr', trailingslashit( get_template_directory_uri() ) . 'js/modernizr-min.js', array(), '3.5.0', false );

		// Adds JavaScript to pages with the comment form to support sites with threaded comments (when in use)
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Load jQuery Validation as well as the initialiser to provide client side comment form validation
		// You can change the validation error messages below
		if ( is_singular() && comments_open() ) {
			wp_register_script( 'validate', trailingslashit( get_template_directory_uri() ) . 'js/jquery.validate.min.js', array( 'jquery' ), '1.17.0', true );
			wp_enqueue_script( 'comment-validate', trailingslashit( get_template_directory_uri() ) . 'js/comment-form-validation.js', array( 'jquery', 'validate' ), '1.0.0', true );

			wp_localize_script( 'comment-validate', 'comments_object', array(
				'req' => get_option( 'require_name_email' ),
				'author'  => esc_html__( 'Please enter your name', 'ephemeris' ),
				'email'  => esc_html__( 'Please enter a valid email address', 'ephemeris' ),
				'comment' => esc_html__( 'Please add a comment', 'ephemeris' ) )
			);
		}

		// Enqueue our common scripts
		wp_enqueue_script( 'ephemeris-common-js', trailingslashit( get_template_directory_uri() ) . 'js/common.js', array( 'jquery' ), '0.1.0', true );
	}
}
add_action( 'wp_enqueue_scripts', 'ephemeris_scripts_styles' );

/**
 * Enqueue scripts for our Customizer preview
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_customizer_preview_scripts' ) ) {
	function ephemeris_customizer_preview_scripts() {
		wp_enqueue_script( 'ephemeris-customizer-preview-js', trailingslashit( get_template_directory_uri() ) . 'js/customizer-preview.js', array( 'customize-preview', 'jquery' ), '1.0', true );
		wp_enqueue_script( 'ephemeris-common-js', trailingslashit( get_template_directory_uri() ) . 'js/common.js', array( 'jquery' ), '1.0', true );
	}
}
add_action( 'customize_preview_init', 'ephemeris_customizer_preview_scripts' );

/**
 * Enqueue scripts for our Customizer controls. Allows Customizer preview to update based on panel selected
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_customize_controls_enqueue_scripts' ) ) {
	function ephemeris_customize_controls_enqueue_scripts() {
		if( ephemeris_is_plugin_active( 'woocommerce' ) ) {
			$shop_page_url = wc_get_page_permalink( 'shop' );

			wp_enqueue_script( 'ephemeris-customize-controls-js', trailingslashit( get_template_directory_uri() ) . 'js/customize-controls.js', array( 'customize-controls' ), '1.0', true );

			wp_localize_script( 'ephemeris-customize-controls-js', 'ephemeris_customizer_data',
				array(
					'ephemeris_woocommerce_url' => $shop_page_url
				)
			);
		}
	}
}
add_action( 'customize_controls_enqueue_scripts', 'ephemeris_customize_controls_enqueue_scripts' );

/**
 * Action any theme or plugin specific hooks. Fires before determining which template to load. Will also work in Customizer preview.
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
function ephemeris_template_redirect() {
	$defaults = ephemeris_generate_defaults();
	$template = '';

	// If WooCommerce is running, check if we should be displaying the Breadcrumbs
	if( ephemeris_is_plugin_active( 'woocommerce' ) && !get_theme_mod( 'ephemeris_woocommerce_breadcrumbs', $defaults['ephemeris_woocommerce_breadcrumbs'] ) ) {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
	}

	// Check whether we are replacing the default Header and ensure the builder is actually active
	$template = ephemeris_has_pagebuilder_template( 'header' );
	if ( !empty( $template ) ) {
		add_action( 'ephemeris_before_header', 'ephemeris_display_' . $template . '_header' );
	}
	else {
		add_action( 'ephemeris_announcement_bar_content', 'ephemeris_social_header' );
		add_action( 'ephemeris_header_content', 'ephemeris_logo_grid' );
		add_action( 'ephemeris_header_content', 'ephemeris_nav_grid' );
	}

	// Check whether we are replacing the default footer and ensure the builder is actually active
	$template = ephemeris_has_pagebuilder_template( 'footer' );
	if ( !empty( $template ) ) {
		add_action( 'ephemeris_after_footer', 'ephemeris_display_' . $template . '_footer' );
	}

	if ( has_header_image() ) {
		add_action( 'ephemeris_before_main_content', 'ephemeris_header_image' );
	}
}
add_action( 'template_redirect', 'ephemeris_template_redirect' );

/**
 * Display the social header icons, including its grid container.
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_social_header' ) ) {
	function ephemeris_social_header() {
		$social_header = '';

		$social_header .= '<div class="grid-100 tablet-grid-100 social-header">';
		$social_header .= ephemeris_get_social_media();
		$social_header .= '</div> <!-- /.grid-100.social-header -->';

		echo $social_header;
	}
}

/**
 * Display the site logo or site title, including its grid container.
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_logo_grid' ) ) {
	function ephemeris_logo_grid() {
		$logo_grid = '';

		$logo_grid .= '<div class="grid-40 tablet-grid-40 mobile-grid-100 site-title' . ( is_rtl() ? ' push-60 tablet-push-60' : '' ) . '">';
		$logo_grid .= ephemeris_the_custom_logo();
		$logo_grid .= '</div> <!-- /.grid-40.site-title -->';

		echo $logo_grid;
	}
}

/**
 * Display the main navigation.
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_nav_grid' ) ) {
	function ephemeris_nav_grid() {
		$nav_grid = '';

		$nav_grid .= '<div class="grid-60 tablet-grid-60 mobile-grid-100' . ( is_rtl() ? ' pull-40 tablet-pull-40' : '' ) . '">';
		$nav_grid .= '<nav id="site-navigation" class="main-navigation" role="navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">';
		$nav_grid .= '<div class="assistive-text skip-link"><a href="#content" title="' . esc_attr__( 'Skip to content', 'ephemeris' ) . '">' . esc_html__( 'Skip to content', 'ephemeris' ) . '</a></div>';
		$nav_grid .= wp_nav_menu(
			array(
				'theme_location' => 'primary-menu',
				'menu_class' => 'nav-menu',
				'echo' => false
			) );
		$nav_grid .= '</nav> <!-- /.site-navigation.main-navigation -->';
		$nav_grid .= '</div> <!-- /.grid-60 -->';

		echo $nav_grid;
	}
}

/**
 * Display the elementor header template.
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
function ephemeris_display_elementor_header() {
	$defaults = ephemeris_generate_defaults();

	echo do_shortcode( '[elementor-template id="' . get_theme_mod( 'ephemeris_elementor_header_template', $defaults['ephemeris_elementor_header_template'] ) . '"]' );
}

/**
 * Display the elementor footer template.
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
function ephemeris_display_elementor_footer() {
	$defaults = ephemeris_generate_defaults();

	echo do_shortcode( '[elementor-template id="' . get_theme_mod( 'ephemeris_elementor_footer_template', $defaults['ephemeris_elementor_footer_template'] ) . '"]' );
}

/**
 * Returns the optional custom logo. If no logo is available, it returns the Site Title
 *
 * @since Ephemeris 1.0
 *
 * @return string	The logo or Site Title link
 */
if ( ! function_exists( 'ephemeris_the_custom_logo' ) ) {
	function ephemeris_the_custom_logo() {
		$logo_title = '';

		if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			$logo_title .= get_custom_logo();
		}
		else {
			$logo_title .= '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" rel="home">';
			$logo_title .= get_bloginfo( 'name' );
			$logo_title .= '</a>';
		}

		return $logo_title;
	}
}

/**
 * Display the header image if there is one.
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_header_image' ) ) {
	function ephemeris_header_image() {
		$header_image = "";

		if ( has_header_image() ) {
			$header_image .= '<div id="bannercontainer"><div class="banner grid-container"><div class="header-image grid-100">';
			$header_image .= '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
			$header_image .= sprintf( '<img src="%1$s" srcset="%2$s" width="%3$s" height="%4$s" alt="%5$s">',
				get_header_image(),
				esc_attr( wp_get_attachment_image_srcset( get_custom_header()->attachment_id ) ),
				esc_attr( get_custom_header()->width ),
				esc_attr( get_custom_header()->height ),
				esc_attr( get_bloginfo( 'name', 'display' ) )
		 	);
			$header_image .= '</a>';
			$header_image .= '</div> <!-- .header-image.grid-100 --></div> <!-- /.banner.grid-container --></div> <!-- /#bannercontainer -->';

			echo $header_image;
		}
	}
}

/**
 * Returns the Google font stylesheet URL, if available.
 *
 * The use of Open Sans and Dosis by default is localized. For languages that use characters not supported by the fonts, the fonts can be disabled.
 *
 * @since Ephemeris 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
if ( ! function_exists( 'ephemeris_fonts_url' ) ) {
	function ephemeris_fonts_url() {
		$fonts_url = '';
		$subsets = 'latin';

		/* translators: If there are characters in your language that are not supported by Open Sans, translate this to 'off'.
		 * Do not translate into your own language.
		 */
		$bodyFont = _x( 'on', 'Open Sans font: on or off', 'ephemeris' );

		/* translators: To add an additional Open Sans character subset specific to your language, translate this to 'greek', 'cyrillic' or 'vietnamese'.
		 * Do not translate into your own language.
		 */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (cyrillic)', 'ephemeris' );

		if ( 'cyrillic' == $subset ) {
			$subsets .= ',cyrillic';
		}

		/* translators: If there are characters in your language that are not supported by Dosis, translate this to 'off'.
		 * Do not translate into your own language.
		 */
		$headerFont = _x( 'on', 'Dosis font: on or off', 'ephemeris' );

		if ( 'off' !== $bodyFont || 'off' !== $headerFont ) {
			$font_families = array();

			if ( 'off' !== $bodyFont )
				$font_families[] = 'Open+Sans:400,400i,700,700i';

			if ( 'off' !== $headerFont )
				$font_families[] = 'Dosis:700';

			$query_args = array(
				'family' => implode( '%7C', $font_families ),
				'subset' => $subsets,
			);
			$fonts_url = add_query_arg( $query_args, "https://fonts.googleapis.com/css" );
		}

		return esc_url_raw( $fonts_url );
	}
}

/**
 * Adds additional stylesheets to the TinyMCE editor if needed.
 *
 * @since Ephemeris 1.0
 *
 * @param string $mce_css CSS path to load in TinyMCE.
 * @return string The filtered CSS paths list.
 */
function ephemeris_mce_css( $mce_css ) {
	$fonts_url = ephemeris_fonts_url();

	if ( empty( $fonts_url ) ) {
		return $mce_css;
	}

	if ( !empty( $mce_css ) ) {
		$mce_css .= ',';
	}

	$mce_css .= esc_url_raw( str_replace( ',', '%2C', $fonts_url ) );

	return $mce_css;
}
add_filter( 'mce_css', 'ephemeris_mce_css' );

/**
 * Register widgetized areas
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_widgets_init' ) ) {
	function ephemeris_widgets_init() {
		register_sidebar( array(
				'name' => esc_html__( 'Main Sidebar', 'ephemeris' ),
				'id' => 'sidebar-main',
				'description' => esc_html__( 'Appears in the sidebar on all posts and pages', 'ephemeris' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);

		register_sidebar( array(
				'name' => esc_html__( 'Blog Sidebar', 'ephemeris' ),
				'id' => 'sidebar-blog',
				'description' => esc_html__( 'Appears in the sidebar on the blog and archive pages only', 'ephemeris' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);

		register_sidebar( array(
				'name' => esc_html__( 'Single Post Sidebar', 'ephemeris' ),
				'id' => 'sidebar-single',
				'description' => esc_html__( 'Appears in the sidebar on single posts only', 'ephemeris' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);

		register_sidebar( array(
				'name' => esc_html__( 'Page Sidebar', 'ephemeris' ),
				'id' => 'sidebar-page',
				'description' => esc_html__( 'Appears in the sidebar on pages only', 'ephemeris' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);

		if ( ephemeris_is_plugin_active( 'woocommerce' ) ) {
			register_sidebar( array(
					'name' => esc_html__( 'WooCommerce Sidebar', 'ephemeris' ),
					'id' => 'sidebar-shop',
					'description' => esc_html__( 'Appears in the sidebar on WooCommerce pages only', 'ephemeris' ),
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget' => '</aside>',
					'before_title' => '<h3 class="widget-title">',
					'after_title' => '</h3>'
				)
			);
		}

		register_sidebar( array(
				'name' => esc_html__( 'Footer Widget 1', 'ephemeris' ),
				'id' => 'sidebar-footer1',
				'description' => esc_html__( 'Appears in the footer in column 1', 'ephemeris' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);

		register_sidebar( array(
				'name' => esc_html__( 'Footer Widget 2', 'ephemeris' ),
				'id' => 'sidebar-footer2',
				'description' => esc_html__( 'Appears in the footer in column 2', 'ephemeris' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);

		register_sidebar( array(
				'name' => esc_html__( 'Footer Widget 3', 'ephemeris' ),
				'id' => 'sidebar-footer3',
				'description' => esc_html__( 'Appears in the footer in column 3', 'ephemeris' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);

		register_sidebar( array(
				'name' => esc_html__( 'Footer Widget 4', 'ephemeris' ),
				'id' => 'sidebar-footer4',
				'description' => esc_html__( 'Appears in the footer in column 4', 'ephemeris' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);
	}
}
add_action( 'widgets_init', 'ephemeris_widgets_init' );

/**
 * Custom Background Callback
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_custom_background_cb' ) ) {
	function ephemeris_custom_background_cb() {
		// $background is the saved custom image, or the default image.
		$background = set_url_scheme( get_background_image() );

		// $color is the saved custom color.
		// A default has to be specified in style.css. It will not be printed here.
		$color = get_background_color();

		if ( $color === get_theme_support( 'custom-background', 'default-color' ) ) {
			$color = false;
		}

		if ( ! $background && ! $color ) {
			if ( is_customize_preview() ) {
				echo '<style type="text/css" id="custom-background-css"></style>';
			}
			return;
		}

		$style = $color ? "background-color: #$color;" : '';

		if ( $background ) {
			$image = " background-image: url(" . wp_json_encode( $background ) . ");";

			// Background Position.
			$position_x = get_theme_mod( 'background_position_x', get_theme_support( 'custom-background', 'default-position-x' ) );
			$position_y = get_theme_mod( 'background_position_y', get_theme_support( 'custom-background', 'default-position-y' ) );

			if ( ! in_array( $position_x, array( 'left', 'center', 'right' ), true ) ) {
				$position_x = 'left';
			}

			if ( ! in_array( $position_y, array( 'top', 'center', 'bottom' ), true ) ) {
				$position_y = 'top';
			}

			$position = " background-position: $position_x $position_y;";

			// Background Size.
			$size = get_theme_mod( 'background_size', get_theme_support( 'custom-background', 'default-size' ) );

			if ( ! in_array( $size, array( 'auto', 'contain', 'cover' ), true ) ) {
				$size = 'auto';
			}

			$size = " background-size: $size;";

			// Background Repeat.
			$repeat = get_theme_mod( 'background_repeat', get_theme_support( 'custom-background', 'default-repeat' ) );

			if ( ! in_array( $repeat, array( 'repeat-x', 'repeat-y', 'repeat', 'no-repeat' ), true ) ) {
				$repeat = 'repeat';
			}

			$repeat = " background-repeat: $repeat;";

			// Background Scroll.
			$attachment = get_theme_mod( 'background_attachment', get_theme_support( 'custom-background', 'default-attachment' ) );

			if ( 'fixed' !== $attachment ) {
				$attachment = 'scroll';
			}

			$attachment = " background-attachment: $attachment;";

			$style .= $image . $position . $size . $repeat . $attachment;
		}
	printf( '<style type="text/css" id="custom-background-css">body.custom-background main { %1$s }</style>', trim( $style ) );
	}
}

/**
 * Get the classes for the main content container.
 *
 * @since Ephemeris 1.0
 *
 * @param array Echo on screen or return value
 * @param array Extra classes to append
 * @return string Containing list of classes or empty string if echoed to screen
 */
if ( ! function_exists( 'ephemeris_main_class' ) ) {
	function ephemeris_main_class( $echo=true, $addon_classes='' ) {
		$classes = '';

		if ( is_page_template( 'template-full-width.php' ) || is_404() ) {
			$classes = 'grid-100' . ( !empty( $addon_classes ) ? ' ' . $addon_classes : '' );
		} else {
			$classes = 'grid-75 tablet-grid-75 mobile-grid-100' . ( !empty( $addon_classes ) ? ' ' . $addon_classes : '' );
		}

		return ephemeris_classes( $echo, $classes );
	}
}

/**
 * Get the classes for the sidebar container.
 *
 * @since Ephemeris 1.0
 *
 * @param array Echo on screen or return value
 * @param array Extra classes to append
 * @return string Containing list of classes or empty string if echoed to screen
 */
if ( ! function_exists( 'ephemeris_sidebar_class' ) ) {
	function ephemeris_sidebar_class( $echo=true, $addon_classes='' ) {
		$classes = '';

		$classes = 'grid-25 tablet-grid-25 mobile-grid-100' . ( !empty( $addon_classes ) ? ' ' . $addon_classes : '' );

		return ephemeris_classes( $echo, $classes );
	}
}

/**
 * Echo the classes to the screen or return as a string.
 *
 * @since Ephemeris 1.0
 *
 * @param array Echo on screen or return value
 * @param array Classes to echo or return
 * @return string Containing list of classes or empty string if echoed to screen
 */
function ephemeris_classes( $echo, $classes ) {
	$return_val = '';

	if ( $echo ) {
		echo 'class="' . $classes . '"';
	} else {
		$return_val = 'class="' . $classes . '"';
	}
	return $return_val;
}

/**
 * Displays post navigation on single pages.
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_single_posts_pagination' ) ) {
	function ephemeris_single_posts_pagination() {
		printf( '<nav role="navigation" class="navigation pagination nav-single">' );
			printf( '<h2 class="screen-reader-text">' . esc_html__( 'Posts navigation', 'ephemeris' ) . '</h2>' );

			$previous_post_icon = sprintf( '<i class="%1$s" aria-hidden="true"></i>', ( is_rtl() ? _x( 'fa fa-angle-right', 'Previous post link icon classes', 'ephemeris' ) : _x( 'fa fa-angle-left', 'Previous post link icon classes', 'ephemeris' ) ) );
			$next_post_icon = sprintf( '<i class="%1$s" aria-hidden="true"></i>', ( is_rtl() ? _x( 'fa fa-angle-left', 'Next post link icon classes', 'ephemeris' ) : _x( 'fa fa-angle-right', 'Next post link icon classes', 'ephemeris' ) ) );

			previous_post_link(
				'<div class="nav-previous">%link</div>',
				'<span class="meta-nav">' . $previous_post_icon . '</span> %title' );
			next_post_link(
				'<div class="nav-next">%link</div>',
				'%title <span class="meta-nav">' . $next_post_icon . '</span>' );

		printf( '</nav><!-- .navigation pagination nav-single -->' );
	}
}

/**
 * Displays post navigation on archive pages.
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_posts_pagination' ) ) {
	function ephemeris_posts_pagination() {
		$previous_post_icon = sprintf( '<i class="%1$s" aria-hidden="true"></i>', ( is_rtl() ? _x( 'fa fa-angle-right', 'Previous page link icon classes', 'ephemeris' ) : _x( 'fa fa-angle-left', 'Previous page link icon classes', 'ephemeris' ) ) );
		$next_post_icon = sprintf( '<i class="%1$s" aria-hidden="true"></i>', ( is_rtl() ? _x( 'fa fa-angle-left', 'Next page link icon classes', 'ephemeris' ) : _x( 'fa fa-angle-right', 'Next page link icon classes', 'ephemeris' ) ) );

		the_posts_pagination(
			array(
				'mid_size' => 2,
				'prev_text' => $previous_post_icon . ' <span>' . __( 'Previous', 'ephemeris' ) . '</span>',
				'next_text' => '<span>' . __( 'Next', 'ephemeris' ) . '</span> ' . $next_post_icon
			)
		);
	}
}

/**
 * Update the Comments form so that the 'required' span is contained within the form label.
 *
 * @since Ephemeris 1.0
 *
 * @param string Comment form fields html
 * @return string The updated comment form fields html
 */
if ( ! function_exists( 'ephemeris_comment_form_default_fields' ) ) {
	function ephemeris_comment_form_default_fields( $fields ) {

		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? ' aria-required="true"' : "" );

		$fields['author'] = '<p class="comment-form-author">' . '<label for="author">' . esc_html__( 'Name', 'ephemeris' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' . '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';

		$fields['email'] =  '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'ephemeris' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' . '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';

		$fields['url'] =  '<p class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'ephemeris' ) . '</label>' . '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>';

		return $fields;

	}
}
add_action( 'comment_form_default_fields', 'ephemeris_comment_form_default_fields' );

/**
 * Update the Comments form to add a 'required' span to the Comment textarea within the form label, because it's pointless
 * submitting a comment that doesn't actually have any text in the comment field!
 *
 * @since Ephemeris 1.0
 *
 * @param string Comment form textarea html
 * @return string The updated comment form textarea html
 */
if ( ! function_exists( 'ephemeris_comment_form_field_comment' ) ) {
	function ephemeris_comment_form_field_comment( $field ) {
		if ( !ephemeris_is_plugin_active( 'woocommerce' ) || ( ephemeris_is_plugin_active( 'woocommerce' ) && !is_product() ) ) {
			$field = '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun', 'ephemeris' ) . ' <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';
		}
		return $field;

	}
}
add_action( 'comment_form_field_comment', 'ephemeris_comment_form_field_comment' );

/**
 * Prints HTML with meta information for current post: author and date
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
function ephemeris_posted_on() {
	$post_icon = '';
	switch ( get_post_format() ) {
		case 'aside':
			$post_icon = 'fa-file-o';
			break;
		case 'audio':
			$post_icon = 'fa-volume-up';
			break;
		case 'chat':
			$post_icon = 'fa-comment';
			break;
		case 'gallery':
			$post_icon = 'fa-camera';
			break;
		case 'image':
			$post_icon = 'fa-picture-o';
			break;
		case 'link':
			$post_icon = 'fa-link';
			break;
		case 'quote':
			$post_icon = 'fa-quote-left';
			break;
		case 'status':
			$post_icon = 'fa-user';
			break;
		case 'video':
			$post_icon = 'fa-video-camera';
			break;
		default:
			$post_icon = 'fa-calendar';
			break;
	}

	// Translators: 1: Icon 2: Permalink 3: Post date and time 4: Publish date in ISO format 5: Post date
	$date = sprintf( '<span class="publish-date"><i class="fa %1$s" aria-hidden="true"></i> <a href="%2$s" title="%3$s" rel="bookmark"><time class="entry-date" datetime="%4$s" itemprop="datePublished">%5$s</time></a></span>',
		$post_icon,
		esc_url( get_permalink() ),
		sprintf( esc_html__( 'Posted %1$s @ %2$s', 'ephemeris' ), esc_html( get_the_date() ), esc_attr( get_the_time() ) ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	// Translators: 1: Date link 2: Author title 3: Author
	$author = sprintf( '<address class="publish-author"><i class="fa fa-pencil" aria-hidden="true"></i> <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author" itemprop="author">%3$s</a></span></address>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( esc_html__( 'View all posts by %s', 'ephemeris' ), get_the_author() ) ),
		get_the_author()
	);

	// Return the Categories as a list
	$categories_list = get_the_category_list( esc_html__( ' ', 'ephemeris' ) );

	// Translators: 1: Permalink 2: Title 3: No. of Comments
	$comments = sprintf( '<span class="comments-link"><i class="fa fa-comment" aria-hidden="true"></i> <a href="%1$s" title="%2$s">%3$s</a></span>',
		esc_url( get_comments_link() ),
		esc_attr( esc_html__( 'Comment on ' , 'ephemeris' ) . the_title_attribute( 'echo=0' ) ),
		( get_comments_number() > 0 ? sprintf( _n( '%1$s Comment', '%1$s Comments', get_comments_number(), 'ephemeris' ), get_comments_number() ) : esc_html__( 'No Comments', 'ephemeris' ) )
	);

	// Translators: 1: Date 2: Author 3: Categories 4: Comments
	printf( wp_kses( __( '<div class="header-meta">%1$s%2$s<span class="post-categories">%3$s</span>%4$s</div>', 'ephemeris' ), array(
		'div' => array (
			'class' => array() ),
		'span' => array(
			'class' => array() ) ) ),
		$date,
		$author,
		$categories_list,
		( is_search() ? '' : $comments )
	);
}
add_action( 'ephemeris_after_entry_title', 'ephemeris_posted_on' );

/**
 * Prints HTML with meta information for current post: categories, tags, permalink
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_entry_meta' ) ) {
	function ephemeris_entry_meta() {
		// Return the Tags as a list
		$tag_list = "";
		if ( get_the_tag_list() ) {
			$tag_list = get_the_tag_list( '<span class="post-tags">', esc_html__( ' ', 'ephemeris' ), '</span>' );
		}

		// Translators: 1: Tag list
		if ( $tag_list ) {
			printf( wp_kses( __( '<i class="fa fa-tag" aria-hidden="true"></i> %1$s', 'ephemeris' ), array( 'i' => array( 'class' => array() ) ) ), $tag_list );
		}
	}
}

/**
 * Adjusts content_width value for full-width templates and attachments
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_content_width' ) ) {
	function ephemeris_content_width() {
		$defaults = ephemeris_generate_defaults();
		global $content_width;

		if ( is_page_template( 'template-full-width.php' ) || is_attachment() ) {
			// Calculate the optimal width based on the layout width.
			// Default width will be 1160px with a site layout width of 1200px when using the full-width template
			// site width - 40px padding
			$content_width = absint( get_theme_mod( 'ephemeris_layout_width', $defaults['ephemeris_layout_width'] ) - 40 );
		}
	}
}
add_action( 'template_redirect', 'ephemeris_content_width' );

/**
 * Change the "read more..." link so it links to the top of the page rather than part way down
 *
 * @since Ephemeris 1.0
 *
 * @param string The 'Read more' link
 * @return string The link to the post url without the more tag appended on the end
 */
function ephemeris_remove_more_jump_link( $link ) {
	$offset = strpos( $link, '#more-' );
	if ( $offset ) {
		$end = strpos( $link, '"', $offset );
	}
	if ( $end ) {
		$link = substr_replace( $link, '', $offset, $end-$offset );
	}
	return $link;
}
add_filter( 'the_content_more_link', 'ephemeris_remove_more_jump_link' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Ephemeris 1.0
 *
 * @return string The 'Continue reading' link
 */
if ( ! function_exists( 'ephemeris_continue_reading_link' ) ) {
	function ephemeris_continue_reading_link() {
		return '&hellip;<p><a class="more-link" href="'. esc_url( get_permalink() ) . '" title="' . esc_html__( 'Continue reading', 'ephemeris' ) . ' &lsquo;' . get_the_title() . '&rsquo;">' . wp_kses( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'ephemeris' ), array( 'span' => array(
				'class' => array() ) ) ) . '</a></p>';
	}
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with the ephemeris_continue_reading_link().
 *
 * @since Ephemeris 1.0
 *
 * @param string Auto generated excerpt
 * @return string The filtered excerpt
 */
if ( ! function_exists( 'ephemeris_auto_excerpt_more' ) ) {
	function ephemeris_auto_excerpt_more( $more ) {
		if ( is_admin() ) {
			return $more;
		}

		return ephemeris_continue_reading_link();
	}
}
add_filter( 'excerpt_more', 'ephemeris_auto_excerpt_more' );

/**
 * Return a string containing the default footer credits & link
 *
 * @since Ephemeris 1.0
 *
 * @return string Footer credits & link
 */
if ( ! function_exists( 'ephemeris_get_credits_default' ) ) {
	function ephemeris_get_credits_default() {
		$output = '';
		$output = sprintf( '<p style="text-align: center;">%1$s <a href="%2$s" title="%3$s">%4$s</a> &amp; <a href="%5$s" title="%6$s">%7$s</a></p>',
			esc_html__( 'Proudly powered by', 'ephemeris' ),
			esc_url( esc_html__( 'http://wordpress.org', 'ephemeris' ) ),
			esc_attr( esc_html__( 'Semantic Personal Publishing Platform', 'ephemeris' ) ),
			esc_html__( 'WordPress', 'ephemeris' ),
			esc_url( esc_html__( 'http://skyrocketthemes.com', 'ephemeris' ) ),
			esc_attr( esc_html__( 'Skyrocket Themes', 'ephemeris' ) ),
			esc_html__( 'Skyrocket Themes', 'ephemeris' )
		);

		return $output;
	}
}

/**
 * Return a string containing the footer credits theme option
 *
 * @since Ephemeris 1.0
 *
 * @return string footer credits theme option
 */
if ( ! function_exists( 'ephemeris_get_credits' ) ) {
	function ephemeris_get_credits() {
		$defaults = ephemeris_generate_defaults();

		// wpautop this so that it acts like a the new visual text widget, since we're using the same TinyMCE control
		return wpautop( apply_filters( 'ephemeris_footer_credits', get_theme_mod( 'ephemeris_footer_credits', $defaults['ephemeris_footer_credits'] ) ) );
	}
}

/**
 * Filter the Footer Credits to insert the Current Year and Copyright, Registered & Trademark symbols
 *
 * @since Ephemeris 1.0
 *
 * @return string Filtered footer credits string
 */
function ephemeris_filter_footer_credits( $credits ) {
	$credits = str_ireplace ( '%currentyear%' , date( 'Y' ) , $credits );
	$credits = str_ireplace ( '%copy%' , '&copy;' , $credits );
	$credits = str_ireplace ( '%reg%' , '&reg;' , $credits );
	$credits = str_ireplace ( '%trade%' , '&trade;' , $credits );

	return $credits;
}
add_filter( 'ephemeris_footer_credits', 'ephemeris_filter_footer_credits' );

/**
 * Append a search icon to the primary menu
 *
 * @since Ephemeris 1.0
 *
 * @return string Menu list items
 */
if ( ! function_exists( 'ephemeris_add_search_menu_item' ) ) {
	function ephemeris_add_search_menu_item( $items, $args ) {
		$defaults = ephemeris_generate_defaults();

		if( get_theme_mod( 'ephemeris_search_menu_icon', $defaults['ephemeris_search_menu_icon'] ) ) {
			if( $args->theme_location == 'primary-menu' ) {
				$items .= '<li class="menu-item menu-item-search"><a href="#" class="nav-search"><i class="fa fa-search"></i></a></li>';
			}
		}
		return $items;
	}
}
add_filter( 'wp_nav_menu_items', 'ephemeris_add_search_menu_item', 10, 2 );

/**
 * Check if certain plugins are active
 *
 * @since Ephemeris 1.0
 *
 * @param string Plugin name to check
 * @return boolean
 */
function ephemeris_is_plugin_active( $plugin ) {
	$return_val = false;

	switch ( strtolower( $plugin ) ) {
		case 'woocommerce':
			if ( class_exists( 'WooCommerce' ) ) {
				$return_val = true;
			}
			break;

		case 'elementor':
			if ( class_exists( 'Elementor\Plugin' ) ) {
				$return_val = true;
			}
			break;

		case 'beaverbuilder':
			if ( class_exists( 'FLBuilderLoader' ) ) {
				$return_val = true;
			}
			break;

		case 'divibuilder':
			if ( class_exists( 'ET_Builder_Plugin' ) ) {
				$return_val = true;
			}
			break;

		case 'visualcomposer':
			if ( class_exists( 'Vc_Manager' ) ) {
				$return_val = true;
			}
			break;

		default:
			$return_val = false;
	}

	return $return_val;
}

/**
 * Check if any Page Builders are active and if so, whether they should replace the default theme header and/or footer
 *
 * @since Ephemeris 1.0
 *
 * @param string The theme section to check. Either 'header' or 'footer'
 * @return boolean Returns false if no Page Builder templates are to be used
 * @return string Returns name of Page Builder if one is being used to replace the default header and/or footer
 */
if ( ! function_exists( 'ephemeris_has_pagebuilder_template' ) ) {
	function ephemeris_has_pagebuilder_template( $section ) {
		$defaults = ephemeris_generate_defaults();
		$option = '';
		$template = '';
		$pagebuilders = array(
			'elementor',
		);

		foreach ( $pagebuilders as $builder ) {
			$option = 'ephemeris_' . $builder . '_' . strtolower( $section ) . '_template';
			if ( ephemeris_is_plugin_active( strtolower( $builder ) ) && get_theme_mod( $option, $defaults[$option] ) ) {
				$template = $builder;
				break;
			}
		}

		return ( !empty( $template ) ? $template : false );
	}
}

/**
 * Unhook the WooCommerce Wrappers
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Outputs the opening wrapper for the WooCommerce content
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_woocommerce_before_main_content' ) ) {
	function ephemeris_woocommerce_before_main_content() {
		echo '<div id="maincontentcontainer">';
		echo '<div id="content" class="grid-container site-content" role="main">';
		do_action( 'ephemeris_before_main_grid' );

		if ( ephemeris_display_woocommerce_sidebar() ) {
			echo '<div ' . ephemeris_main_class( false ) . '>';
		}
		else {
			echo '<div class="grid-100">';
		}
		do_action( 'ephemeris_before_content' );
	}
}
add_action( 'woocommerce_before_main_content', 'ephemeris_woocommerce_before_main_content', 10 );

/**
 * Outputs the closing wrapper for the WooCommerce content
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_woocommerce_after_main_content' ) ) {
	function ephemeris_woocommerce_after_main_content() {
		do_action( 'ephemeris_after_content' );
		echo '</div>';

		if ( ephemeris_display_woocommerce_sidebar() ) {
			get_sidebar();
		}
		do_action( 'ephemeris_after_main_grid' );
		echo '</div> <!-- /#content.grid-container.site-content -->';
		echo '</div> <!-- /#maincontentcontainer -->';
	}
}
add_action( 'woocommerce_after_main_content', 'ephemeris_woocommerce_after_main_content', 10 );

/**
 * Work out whether the sidebar should be displayed on various WooCommerce pages based on the Customizer setting
 *
 * @since Ephemeris 1.0
 *
 * @return boolean
 */
function ephemeris_display_woocommerce_sidebar() {
	$defaults = ephemeris_generate_defaults();
	$returnVal = false;
	$wcsidebar = '';

	if ( is_shop() ) {
		$wcsidebar = 'ephemeris_woocommerce_shop_sidebar';
	}
	elseif ( is_product() ) {
		$wcsidebar = 'ephemeris_woocommerce_product_sidebar';
	}
	elseif ( is_product_category() || is_product_tag() ) {
		$wcsidebar = 'ephemeris_woocommerce_cattag_sidebar';
	}

	if ( !empty( $wcsidebar ) ) {
		if ( get_theme_mod( $wcsidebar, $defaults[$wcsidebar] ) ) {
			$returnVal = true;
		}
	}

	return $returnVal;
}

/**
 * Set the number of products to display on the WooCommerce shop page
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_shop_product_count' ) ) {
	function ephemeris_shop_product_count( $numprods ) {
		$defaults = ephemeris_generate_defaults();

		return get_theme_mod( 'ephemeris_woocommerce_shop_products', $defaults['ephemeris_woocommerce_shop_products'] );
	}
}
if ( ephemeris_is_plugin_active( 'woocommerce' ) && !ephemeris_woocommerce_version_check( '3.3' ) ) {
	// Only use the loop_shop_per_page filter if WooCommerce is active and it's less than v3.3.
	// WooCommerce v3.3 now has it's own Customizer option for changing the number of products on display
	add_filter( 'loop_shop_per_page', 'ephemeris_shop_product_count', 20 );
}

/**
 * Filter the WooCommerce pagination so that it matches the theme pagination
 *
 * @since Ephemeris 1.0
 *
 * @return array Pagination arguments
 */
if ( ! function_exists( 'ephemeris_woocommerce_pagination_args' ) ) {
	function ephemeris_woocommerce_pagination_args( $paginationargs ) {
		$previous_post_icon = sprintf( '<i class="%1$s" aria-hidden="true"></i>', ( is_rtl() ? _x( 'fa fa-angle-right', 'WooCommerce previous page link icon classes', 'ephemeris' ) : _x( 'fa fa-angle-left', 'WooCommerce previous page link icon classes', 'ephemeris' ) ) );
		$next_post_icon = sprintf( '<i class="%1$s" aria-hidden="true"></i>', ( is_rtl() ? _x( 'fa fa-angle-left', 'WooCommerce next page link icon classes', 'ephemeris' ) : _x( 'fa fa-angle-right', 'WooCommerce next page link icon classes', 'ephemeris' ) ) );

		$paginationargs['prev_text'] = $previous_post_icon . ' ' . __( 'Previous', 'ephemeris' );
		$paginationargs['next_text'] = __( 'Next', 'ephemeris' ) . ' ' . $next_post_icon;
		return $paginationargs;
	}
}
add_filter( 'woocommerce_pagination_args', 'ephemeris_woocommerce_pagination_args', 10 );

/**
 * Check the version of WooCommerce that is current activated
 *
 * @since Ephemeris 1.3.2
 *
 * @return boolean
 */
function ephemeris_woocommerce_version_check( $version = '3.3' ) {
	global $woocommerce;

	if ( ephemeris_is_plugin_active( 'woocommerce' ) ) {
		if ( version_compare( $woocommerce->version, $version, ">=" ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Show all the registered Sidebars in the Customizer Widgets Panel all the time
 *
 * @since Ephemeris 1.0
 *
 * @return array	Customizer arguments
 */
function ephemeris_show_all_sidebars_in_customizer( $args ) {
	$args['active_callback'] = '__return_true';
	return $args;
}
add_filter( 'customizer_widgets_section_args', 'ephemeris_show_all_sidebars_in_customizer' );

/**
 * Set our Social Icons URLs
 *
 * @since Ephemeris 1.0
 *
 * @return array	Social media site urls
 */
if ( ! function_exists( 'ephemeris_generate_ephemeris_social_urls' ) ) {
	function ephemeris_generate_social_urls() {
		$social_icons = array(
			array( 'url' => 'behance.net', 'icon' => 'fa-behance', 'title' => esc_html__( 'Follow me on Behance', 'ephemeris' ), 'class' => 'behance' ),
			array( 'url' => 'bitbucket.org', 'icon' => 'fa-bitbucket', 'title' => esc_html__( 'Fork me on Bitbucket', 'ephemeris' ), 'class' => 'bitbucket' ),
			array( 'url' => 'codepen.io', 'icon' => 'fa-codepen', 'title' => esc_html__( 'Follow me on CodePen', 'ephemeris' ), 'class' => 'codepen' ),
			array( 'url' => 'deviantart.com', 'icon' => 'fa-deviantart', 'title' => esc_html__( 'Watch me on DeviantArt', 'ephemeris' ), 'class' => 'deviantart' ),
			array( 'url' => 'dribbble.com', 'icon' => 'fa-dribbble', 'title' => esc_html__( 'Follow me on Dribbble', 'ephemeris' ), 'class' => 'dribbble' ),
			array( 'url' => 'etsy.com', 'icon' => 'fa-etsy', 'title' => esc_html__( 'favorite me on Etsy', 'ephemeris' ), 'class' => 'etsy' ),
			array( 'url' => 'facebook.com', 'icon' => 'fa-facebook', 'title' => esc_html__( 'Like me on Facebook', 'ephemeris' ), 'class' => 'facebook' ),
			array( 'url' => 'flickr.com', 'icon' => 'fa-flickr', 'title' => esc_html__( 'Connect with me on Flickr', 'ephemeris' ), 'class' => 'flickr' ),
			array( 'url' => 'foursquare.com', 'icon' => 'fa-foursquare', 'title' => esc_html__( 'Follow me on Foursquare', 'ephemeris' ), 'class' => 'foursquare' ),
			array( 'url' => 'github.com', 'icon' => 'fa-github', 'title' => esc_html__( 'Fork me on GitHub', 'ephemeris' ), 'class' => 'github' ),
			array( 'url' => 'instagram.com', 'icon' => 'fa-instagram', 'title' => esc_html__( 'Follow me on Instagram', 'ephemeris' ), 'class' => 'instagram' ),
			array( 'url' => 'last.fm', 'icon' => 'fa-lastfm', 'title' => esc_html__( 'Follow me on Last.fm', 'ephemeris' ), 'class' => 'lastfm' ),
			array( 'url' => 'linkedin.com', 'icon' => 'fa-linkedin', 'title' => esc_html__( 'Connect with me on LinkedIn', 'ephemeris' ), 'class' => 'linkedin' ),
			array( 'url' => 'medium.com', 'icon' => 'fa-medium', 'title' => esc_html__( 'Follow me on Medium', 'ephemeris' ), 'class' => 'medium' ),
			array( 'url' => 'pinterest.com', 'icon' => 'fa-pinterest', 'title' => esc_html__( 'Follow me on Pinterest', 'ephemeris' ), 'class' => 'pinterest' ),
			array( 'url' => 'plus.google.com', 'icon' => 'fa-google-plus', 'title' => esc_html__( 'Connect with me on Google+', 'ephemeris' ), 'class' => 'googleplus' ),
			array( 'url' => 'reddit.com', 'icon' => 'fa-reddit', 'title' => esc_html__( 'Join me on Reddit', 'ephemeris' ), 'class' => 'reddit' ),
			array( 'url' => 'slack.com', 'icon' => 'fa-slack', 'title' => esc_html__( 'Join me on Slack', 'ephemeris' ), 'class' => 'slack.' ),
			array( 'url' => 'slideshare.net', 'icon' => 'fa-slideshare', 'title' => esc_html__( 'Follow me on SlideShare', 'ephemeris' ), 'class' => 'slideshare' ),
			array( 'url' => 'snapchat.com', 'icon' => 'fa-snapchat', 'title' => esc_html__( 'Add me on Snapchat', 'ephemeris' ), 'class' => 'snapchat' ),
			array( 'url' => 'soundcloud.com', 'icon' => 'fa-soundcloud', 'title' => esc_html__( 'Follow me on SoundCloud', 'ephemeris' ), 'class' => 'soundcloud' ),
			array( 'url' => 'spotify.com', 'icon' => 'fa-spotify', 'title' => esc_html__( 'Follow me on Spotify', 'ephemeris' ), 'class' => 'spotify' ),
			array( 'url' => 'stackoverflow.com', 'icon' => 'fa-stack-overflow', 'title' => esc_html__( 'Join me on Stack Overflow', 'ephemeris' ), 'class' => 'stackoverflow' ),
			array( 'url' => 'tumblr.com', 'icon' => 'fa-tumblr', 'title' => esc_html__( 'Follow me on Tumblr', 'ephemeris' ), 'class' => 'tumblr' ),
			array( 'url' => 'twitch.tv', 'icon' => 'fa-twitch', 'title' => esc_html__( 'Follow me on Twitch', 'ephemeris' ), 'class' => 'twitch' ),
			array( 'url' => 'twitter.com', 'icon' => 'fa-twitter', 'title' => esc_html__( 'Follow me on Twitter', 'ephemeris' ), 'class' => 'twitter' ),
			array( 'url' => 'vimeo.com', 'icon' => 'fa-vimeo', 'title' => esc_html__( 'Follow me on Vimeo', 'ephemeris' ), 'class' => 'vimeo' ),
			array( 'url' => 'youtube.com', 'icon' => 'fa-youtube', 'title' => esc_html__( 'Subscribe to me on YouTube', 'ephemeris' ), 'class' => 'youtube' ),
		);

		return apply_filters( 'ephemeris_social_icons', $social_icons );
	}
}

/**
 * Return the URL element from the passed array
 *
 * @since Ephemeris 1.0
 *
 * @return string Social icon URL
 */
function ephemeris_get_social_urls( $element ) {
	return $element['url'];
}

/**
 * Return an unordered list of linked social media icons, based on the urls provided in the Customizer
 *
 * @since Ephemeris 1.0
 *
 * @return string Unordered list of linked social media icons
 */
if ( ! function_exists( 'ephemeris_get_social_media' ) ) {
	function ephemeris_get_social_media() {
		$defaults = ephemeris_generate_defaults();
		$output = '';
		$social_icons = ephemeris_generate_social_urls();
		$social_service_urls = array_map( 'ephemeris_get_social_urls', $social_icons );
		$social_urls = explode( ',', get_theme_mod( 'ephemeris_social_urls', $defaults['ephemeris_social_urls'] ) );
		$ephemeris_social_newtab = get_theme_mod( 'ephemeris_social_newtab', $defaults['ephemeris_social_newtab'] );
		$social_alignment = get_theme_mod( 'ephemeris_social_alignment', $defaults['ephemeris_social_alignment'] );
		$contact_phone = get_theme_mod( 'ephemeris_contact_phone', $defaults['ephemeris_contact_phone'] );

		if( !empty( $contact_phone ) ) {
			$output .= sprintf( '<li class="%1$s"><i class="fa %2$s"></i>%3$s</li>',
				'phone',
				'fa-phone',
				$contact_phone
			);
		}

		foreach( $social_urls as $key => $value ) {
			if ( !empty( $value ) ) {
				$domain = str_ireplace( 'www.', '', parse_url( $value, PHP_URL_HOST ) );
				$index = array_search( $domain, $social_service_urls );
				if( false !== $index ) {
					$output .= sprintf( '<li class="%1$s"><a href="%2$s" title="%3$s"%4$s><i class="fa %5$s"></i></a></li>',
						$social_icons[$index]['class'],
						esc_url( $value ),
						$social_icons[$index]['title'],
						( !$ephemeris_social_newtab ? '' : ' target="_blank"' ),
						$social_icons[$index]['icon']
					);
				}
				else {
					$output .= sprintf( '<li class="nosocial"><a href="%2$s"%3$s><i class="fa %4$s"></i></a></li>',
						$social_icons[$index]['class'],
						esc_url( $value ),
						( !$ephemeris_social_newtab ? '' : ' target="_blank"' ),
						'fa-globe'
					);
				}
			}
		}

		if( get_theme_mod( 'ephemeris_social_rss', $defaults['ephemeris_social_rss'] ) ) {
			$output .= sprintf( '<li class="%1$s"><a href="%2$s" title="%3$s"%4$s><i class="fa %5$s"></i></a></li>',
				'rss',
				esc_url( home_url( '/feed' ) ),
				__( 'Subscribe to my RSS feed', 'ephemeris' ),
				( !$ephemeris_social_newtab ? '' : ' target="_blank"' ),
				'fa-rss'
			);
		}

		if ( !empty( $output ) ) {
			$output = '<ul class="social-icons ' . $social_alignment . '">' . $output . '</ul>';
		}

		return $output;
	}
}

/**
 * Output our Customizer styles in the site header
 *
 * @since Ephemeris 1.0
 *
 * @return string	css styles
 */
function ephemeris_customizer_css_styles() {
	$defaults = ephemeris_generate_defaults();
	$styles = '';

	// Layout styles
	$styles .= '.grid-container { max-width: ' . esc_attr( get_theme_mod( 'ephemeris_layout_width', $defaults['ephemeris_layout_width'] ) ) . 'px; }';

	// Page & Post Header color styles
	$styles .= '.entry-header h1 { color: ' . esc_attr( get_theme_mod( 'ephemeris_color_header_title_normal', $defaults['ephemeris_color_header_title_normal'] ) ) . '; }';
	$styles .= '.entry-header h1 a { color: ' . esc_attr( get_theme_mod( 'ephemeris_color_header_title_link', $defaults['ephemeris_color_header_title_link'] ) ) . '; }';
	$styles .= '.entry-header h1 a:visited { color: ' . esc_attr( get_theme_mod( 'ephemeris_color_header_title_visited', $defaults['ephemeris_color_header_title_visited'] ) ) . '; }';
	$styles .= '.entry-header h1 a:hover, .entry-header h1 a:active { color: ' . esc_attr( get_theme_mod( 'ephemeris_color_header_title_hover', $defaults['ephemeris_color_header_title_hover'] ) ) . '; }';

	// Body Header color styles
	$styles .= 'h1, h2, h3, h4, h5, h6 { color: ' . esc_attr( get_theme_mod( 'ephemeris_color_header_body_normal', $defaults['ephemeris_color_header_body_normal'] ) ) . '; }';
	$styles .= 'h1 a, h2 a, h3 a, h4 a, h5 a, h6 a { color: ' . esc_attr( get_theme_mod( 'ephemeris_color_header_body_link', $defaults['ephemeris_color_header_body_link'] ) ) . '; }';
	$styles .= 'h1 a:visited, h2 a:visited, h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited { color: ' . esc_attr( get_theme_mod( 'ephemeris_color_header_body_visited', $defaults['ephemeris_color_header_body_visited'] ) ) . '; }';
	$styles .= 'h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover, h1 a:active, h2 a:active, h3 a:active, h4 a:active, h5 a:active, h6 a:active { color: ' . esc_attr( get_theme_mod( 'ephemeris_color_header_body_hover', $defaults['ephemeris_color_header_body_hover'] ) ) . '; }';

	// Body Text color styles
	$styles .= '.site-content, .more-link { color: ' . esc_attr( get_theme_mod( 'ephemeris_color_text_normal', $defaults['ephemeris_color_text_normal'] ) ) . '; }';
	$styles .= 'a, .more-link { color: ' . esc_attr( get_theme_mod( 'ephemeris_color_text_link', $defaults['ephemeris_color_text_link'] ) ) . '; }';
	$styles .= 'a:visited, .more-link:visited { color: ' . esc_attr( get_theme_mod( 'ephemeris_color_text_visited', $defaults['ephemeris_color_text_visited'] ) ) . '; }';
	$styles .= 'a:hover, a:active, .more-link:hover, .more-link:active { color: ' . esc_attr( get_theme_mod( 'ephemeris_color_text_hover', $defaults['ephemeris_color_text_hover'] ) ) . '; }';

	// Footer styles
	$styles .= '#footercontainer { background-color: ' . esc_attr( get_theme_mod( 'ephemeris_footer_background_color', $defaults['ephemeris_footer_background_color'] ) ) . '; }';
	$styles .= '#footercreditscontainer { background-color: ' . esc_attr( get_theme_mod( 'ephemeris_footer_background_color', $defaults['ephemeris_footer_background_color'] ) ) . '; }';
	$styles .= '.site-credits { color: ' . esc_attr( get_theme_mod( 'ephemeris_footer_credits_font_color', $defaults['ephemeris_footer_credits_font_color'] ) ) . '; }';

	echo '<style type="text/css">' . $styles . '</style>';
}
add_action( 'wp_head', 'ephemeris_customizer_css_styles' );

/**
 * Get a complete list of Ephemeris hooks
 *
 * @since Ephemeris 1.0
 *
 * @return array	List of Ephemeris theme hooks
 */
function ephemeris_get_hooks() {
	$ephemeris_hooks = array(
		'ephemeris_before_header',
		'ephemeris_announcement_bar_content',
		'ephemeris_before_header_content',
		'ephemeris_after_header_content',
		'ephemeris_header_content',
		'ephemeris_before_main_content',
		'ephemeris_after_main_content',
		'ephemeris_before_content',
		'ephemeris_after_content',
		'ephemeris_before_entry_title',
		'ephemeris_after_entry_title',
		'ephemeris_before_sidebar',
		'ephemeris_after_sidebar',
		'ephemeris_before_main_grid',
		'ephemeris_after_main_grid',
		'ephemeris_before_entry_header',
		'ephemeris_after_entry_header',
		'ephemeris_after_entry_content',
		'ephemeris_before_footer_content',
		'ephemeris_after_footer_content',
		'ephemeris_before_credits_content',
		'ephemeris_after_credits_content',
		'ephemeris_after_footer',
		);

	return $ephemeris_hooks;
}

/**
 * Will display a container with the specified hook name. Used for providing and handy map of where all the theme hooks reside.
 * Only used when WP_EPHEMERIS_HOOKS is defined as true in wp-config.php.
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
function ephemeris_display_hook() {
	$current_filter = current_filter();

	printf( '<div class="ephemeris_hook %1$s">%2$s</div>',
		$current_filter,
		$current_filter
	);
}

/**
 * Set our Customizer default options
 *
 * @since Ephemeris 1.0
 *
 * @return array	Customizer defaults
 */
if ( ! function_exists( 'ephemeris_generate_defaults' ) ) {
	function ephemeris_generate_defaults() {
		$customizer_defaults = array(
			'ephemeris_layout_width' => 1200,
			'ephemeris_color_header_title_normal' => '#3a3a3a',
			'ephemeris_color_header_title_link' => '#3a3a3a',
			'ephemeris_color_header_title_hover' => '#2c7dbe',
			'ephemeris_color_header_title_visited' => '#3a3a3a',
			'ephemeris_color_header_body_normal' => '#3a3a3a',
			'ephemeris_color_header_body_link' => '#2c7dbe',
			'ephemeris_color_header_body_hover' => '#344860',
			'ephemeris_color_header_body_visited' => '#2c7dbe',
			'ephemeris_color_text_normal' => '#3a3a3a',
			'ephemeris_color_text_link' => '#2c7dbe',
			'ephemeris_color_text_hover' => '#344860',
			'ephemeris_color_text_visited' => '#2c7dbe',
			'ephemeris_social_newtab' => 0,
			'ephemeris_social_urls' => '',
			'ephemeris_social_alignment' => 'alignright',
			'ephemeris_social_rss' => 0,
			'ephemeris_contact_phone' => '',
			'ephemeris_search_menu_icon' => 0,
			'ephemeris_footer_background_color' => '#f9f9f9',
			'ephemeris_footer_font_color' => '#9a9a9a',
			'ephemeris_footer_credits_background_color' => '#f9f9f9',
			'ephemeris_footer_credits_font_color' => '#9a9a9a',
			'ephemeris_footer_credits' => ephemeris_get_credits_default(),
			'ephemeris_woocommerce_shop_sidebar' => 1,
			'ephemeris_woocommerce_cattag_sidebar' => 1,
			'ephemeris_woocommerce_product_sidebar' => 0,
			'ephemeris_woocommerce_breadcrumbs' => 1,
			'ephemeris_woocommerce_shop_products' => 12,
			'ephemeris_elementor_header_template' => 0,
			'ephemeris_elementor_footer_template' => 0,
		);

		return apply_filters( 'ephemeris_customizer_defaults', $customizer_defaults );
	}
}

/**
* Load all our Customizer options
*/
include_once trailingslashit( get_template_directory() ) . 'inc/customizer.php';
