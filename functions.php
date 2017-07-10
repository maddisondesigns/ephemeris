<?php
/**
 * Ephemeris functions and definitions
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since Ephemeris 1.0
 */
function ephemeris_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'ephemeris_content_width', 806 );
}
add_action( 'after_setup_theme', 'ephemeris_content_width', 0 );

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

		/**
		 * Make theme available for translation
		 * Translations can be filed in the /languages/ directory
		 * If you're building a theme based on Ephemeris, use a find and replace
		 * to change 'ephemeris' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'ephemeris', trailingslashit( get_template_directory() ) . 'languages' );

		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );

		// Create an extra image size for the Post featured image
		add_image_size( 'ephemeris_post_feature_full_width', 806, 300, true );

		// This theme uses wp_nav_menu() in one location
		register_nav_menus( array(
				'primary' => esc_html__( 'Primary Menu', 'ephemeris' )
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
			'search-form',
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

		// Enable support for Custom Headers (or in our case, a custom logo)
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

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		// Enable support for WooCommerce & WooCommerce product galleries
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
}
add_action( 'after_setup_theme', 'ephemeris_setup' );

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
				'family' => implode( '|', $font_families ),
				'subset' => $subsets,
			);
			$fonts_url = add_query_arg( $query_args, "https://fonts.googleapis.com/css" );
		}

		return $fonts_url;
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

		register_sidebar( array(
				'name' => esc_html__( 'First Footer Widget Area', 'ephemeris' ),
				'id' => 'sidebar-footer1',
				'description' => esc_html__( 'Appears in the footer sidebar', 'ephemeris' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);

		register_sidebar( array(
				'name' => esc_html__( 'Second Footer Widget Area', 'ephemeris' ),
				'id' => 'sidebar-footer2',
				'description' => esc_html__( 'Appears in the footer sidebar', 'ephemeris' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);

		register_sidebar( array(
				'name' => esc_html__( 'Third Footer Widget Area', 'ephemeris' ),
				'id' => 'sidebar-footer3',
				'description' => esc_html__( 'Appears in the footer sidebar', 'ephemeris' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);

		register_sidebar( array(
				'name' => esc_html__( 'Fourth Footer Widget Area', 'ephemeris' ),
				'id' => 'sidebar-footer4',
				'description' => esc_html__( 'Appears in the footer sidebar', 'ephemeris' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);

		if ( ephemeris_is_woocommerce_active() ) {
			register_sidebar( array(
					'name' => esc_html__( 'WooCommerce Widget Area', 'ephemeris' ),
					'id' => 'sidebar-shop',
					'description' => esc_html__( 'Appears in the sidebar on WooCommerce pages', 'ephemeris' ),
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget' => '</aside>',
					'before_title' => '<h3 class="widget-title">',
					'after_title' => '</h3>'
				)
			);
		}
	}
}
add_action( 'widgets_init', 'ephemeris_widgets_init' );

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
		wp_enqueue_style( 'normalize', trailingslashit( get_template_directory_uri() ) . 'css/normalize.css', array(), '4.1.1', 'all' );

		// Register and enqueue our icon font
		// We're using the awesome Font Awesome icon font. http://fortawesome.github.io/Font-Awesome
		wp_enqueue_style( 'fontawesome', trailingslashit( get_template_directory_uri() ) . 'css/font-awesome.min.css', array( 'normalize' ), '4.7', 'all' );

		// Our styles for setting up the grid. We're using Unsemantic. http://unsemantic.com
		wp_enqueue_style( 'unsemanticgrid', trailingslashit( get_template_directory_uri() ) . 'css/unsemantic.css', array( 'fontawesome' ), '1.0.0', 'all' );

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
			wp_enqueue_style( 'ephemeris-fonts', esc_url_raw( $fonts_url ), array(), null );
		}

		// If using a child theme, auto-load the parent theme style.
		// Props to Justin Tadlock for this recommendation - http://justintadlock.com/archives/2014/11/03/loading-parent-styles-for-child-themes
		if ( is_child_theme() ) {
			wp_enqueue_style( 'parent-style', trailingslashit( get_template_directory_uri() ) . 'style.css' );
		}

		// Enqueue the default WordPress stylesheet
		wp_enqueue_style( 'style', get_stylesheet_uri() );

		/**
		 * Register and enqueue our scripts
		 */

		// Load Modernizr at the top of the document, which enables HTML5 elements and feature detects
		wp_enqueue_script( 'modernizr', trailingslashit( get_template_directory_uri() ) . 'js/modernizr-min.js', array(), '3.3.1', false );

		// Adds JavaScript to pages with the comment form to support sites with threaded comments (when in use)
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Load jQuery Validation as well as the initialiser to provide client side comment form validation
		// You can change the validation error messages below
		if ( is_singular() && comments_open() ) {
			wp_register_script( 'validate', trailingslashit( get_template_directory_uri() ) . 'js/jquery.validate.min.js', array( 'jquery' ), '1.16.0', true );
			wp_enqueue_script( 'commentvalidate', trailingslashit( get_template_directory_uri() ) . 'js/comment-form-validation.js', array( 'jquery', 'validate' ), '1.0.0', true );

			wp_localize_script( 'commentvalidate', 'comments_object', array(
				'req' => get_option( 'require_name_email' ),
				'author'  => esc_html__( 'Please enter your name', 'ephemeris' ),
				'email'  => esc_html__( 'Please enter a valid email address', 'ephemeris' ),
				'comment' => esc_html__( 'Please add a comment', 'ephemeris' ) )
			);
		}

		// Enqueue our common scripts
		wp_register_script( 'ephemeriscommonjs', trailingslashit( get_template_directory_uri() ) . 'js/common.js', array( 'jquery' ), '0.1.0', true );
		wp_enqueue_script( 'ephemeriscommonjs' );

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
		wp_enqueue_script( 'ephemeris-customizer-preview', trailingslashit( get_template_directory_uri() ) . 'js/customizer-preview.js', array( 'customize-preview', 'jquery' ), true );
		wp_enqueue_script( 'ephemeriscommonjs', trailingslashit( get_template_directory_uri() ) . 'js/common.js', array( 'jquery' ), '0.1.0', true );
	}
}
add_action( 'customize_preview_init', 'ephemeris_customizer_preview_scripts' );

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
	?>
	<style type="text/css" id="custom-background-css">
	body.custom-background main { <?php echo trim( $style ); ?> }
	</style>
	<?php
	}
}

/**
 * Displays the optional custom logo. If no logo is available, it displays the Site Title
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_the_custom_logo' ) ) {
	function ephemeris_the_custom_logo() {
		$siteTitleStr = "";

		if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			the_custom_logo();
		}
		else {
			$siteTitleStr .= '<h1><a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" rel="home">';
			$siteTitleStr .= get_bloginfo( 'name' );
			$siteTitleStr .= '</a></h1>';
			echo $siteTitleStr;
		}
	}
}

/**
 * Displays post navigation on single pages.
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_single_posts_pagination' ) ) {
	function ephemeris_single_posts_pagination() { ?>

		<nav role="navigation" class="navigation pagination nav-single">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'ephemeris' ); ?></h2>

			<?php
			previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '<i class="fa fa-angle-left" aria-hidden="true"></i>', 'Previous post link', 'ephemeris' ) . '</span> %title' ); next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '<i class="fa fa-angle-right" aria-hidden="true"></i>', 'Next post link', 'ephemeris' ) . '</span>' );
			?>

		</nav><!-- .navigation pagination nav-single -->
		<?php
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
		the_posts_pagination( array(
			'mid_size' => 2,
			'prev_text' => wp_kses( __( '<i class="fa fa-angle-left" aria-hidden="true"></i> <span>Previous</span>', 'ephemeris' ),
				array(
					'i' => array(
						'class' => array(),
						'aria-hidden' => array()
					),
					'span' => array()
				)
			),
			'next_text' => wp_kses( __( '<span>Next</span> <i class="fa fa-angle-right" aria-hidden="true"></i>', 'ephemeris' ),
				array(
					'i' => array(
						'class' => array(),
						'aria-hidden' => array()
					),
					'span' => array()
				)
			)
		) );
	}
}

/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own ephemeris_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 * (Note the lack of a trailing </li>. WordPress will add it itself once it's done listing any children and whatnot)
 *
 * @since Ephemeris 1.0
 *
 * @param array Comment
 * @param array Arguments
 * @param integer Comment depth
 * @return void
 */
if ( ! function_exists( 'ephemeris_comment' ) ) {
	function ephemeris_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) {
		case 'pingback' :
		case 'trackback' :
			// Display trackbacks differently than normal comments ?>
			<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
				<article id="comment-<?php comment_ID(); ?>" class="pingback">
					<p><?php esc_html_e( 'Pingback:', 'ephemeris' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( '(Edit)', 'ephemeris' ), '<span class="edit-link">', '</span>' ); ?></p>
				</article> <!-- #comment-##.pingback -->
			<?php
			break;
		default :
			// Proceed with normal comments.
			global $post; ?>
			<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
				<article id="comment-<?php comment_ID(); ?>" class="comment">
					<header class="comment-meta comment-author vcard">
						<?php
						echo get_avatar( $comment, 44 );
						printf( '<cite class="fn">%1$s %2$s</cite>',
							get_comment_author_link(),
							// If current post author is also comment author, make it known visually.
							( $comment->user_id === $post->post_author ) ? '<span> ' . esc_html__( 'Post author', 'ephemeris' ) . '</span>' : '' );
						printf( '<a href="%1$s" title="Posted %2$s"><time itemprop="datePublished" datetime="%3$s">%4$s</time></a>',
							esc_url( get_comment_link( $comment->comment_ID ) ),
							sprintf( esc_html__( '%1$s @ %2$s', 'ephemeris' ), esc_html( get_comment_date() ), esc_attr( get_comment_time() ) ),
							get_comment_time( 'c' ),
							/* Translators: 1: date, 2: time */
							sprintf( esc_html__( '%1$s at %2$s', 'ephemeris' ), get_comment_date(), get_comment_time() )
						);
						?>
					</header> <!-- .comment-meta -->

					<?php if ( '0' == $comment->comment_approved ) { ?>
						<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'ephemeris' ); ?></p>
					<?php } ?>

					<section class="comment-content comment">
						<?php comment_text(); ?>
						<?php edit_comment_link( esc_html__( 'Edit', 'ephemeris' ), '<p class="edit-link">', '</p>' ); ?>
					</section> <!-- .comment-content -->

					<div class="reply">
						<?php comment_reply_link( array_merge( $args, array( 'reply_text' => wp_kses( __( 'Reply <span>&darr;</span>', 'ephemeris' ), array( 'span' => array() ) ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</div> <!-- .reply -->
				</article> <!-- #comment-## -->
			<?php
			break;
		} // end comment_type check
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

		$fields[ 'author' ] = '<p class="comment-form-author">' . '<label for="author">' . esc_html__( 'Name', 'ephemeris' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' . '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';

		$fields[ 'email' ] =  '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'ephemeris' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' . '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';

		$fields[ 'url' ] =  '<p class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'ephemeris' ) . '</label>' . '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>';

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
		if ( !ephemeris_is_woocommerce_active() || ( ephemeris_is_woocommerce_active() && !is_product() ) ) {
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
if ( ! function_exists( 'ephemeris_posted_on' ) ) {
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
		$date = sprintf( '<span class="publish-date"><i class="fa %1$s" aria-hidden="true"></i> <a href="%2$s" title="Posted %3$s" rel="bookmark"><time class="entry-date" datetime="%4$s" itemprop="datePublished">%5$s</time></a></span>',
			$post_icon,
			esc_url( get_permalink() ),
			sprintf( esc_html__( '%1$s @ %2$s', 'ephemeris' ), esc_html( get_the_date() ), esc_attr( get_the_time() ) ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);

		// Translators: 1: Date link 2: Author link 3: Categories 4: No. of Comments
		$author = sprintf( '<span class="publish-author"><i class="fa fa-pencil" aria-hidden="true"></i> <address class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></address></span>',
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
}

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

		// Translators: 1 is tag
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
		if ( is_page_template( 'full-width.php' ) || is_attachment() ) {
			global $content_width;
			$content_width = 1160;
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
		return ephemeris_continue_reading_link();
	}
}
add_filter( 'excerpt_more', 'ephemeris_auto_excerpt_more' );

/**
 * Add Filter to allow Shortcodes to work in the Sidebar
 *
 * @since Ephemeris 1.0
 */
add_filter( 'widget_text', 'do_shortcode' );

/**
 * Provide an extra layer of security by changing the login error message so it's not specific as to whether the Username or Password was incorrect
 *
 * @since Ephemeris 1.0
 */
if ( ! function_exists( 'ephemeris_failed_login' ) ) {
	function ephemeris_failed_login() {
		return '<strong>ERROR:</strong> The login information you have entered is incorrect.';
	}
	add_filter( 'login_errors', 'ephemeris_failed_login' );
}

/**
 * Return a string containing the footer credits & link
 *
 * @since Ephemeris 1.0
 *
 * @return string Footer credits & link
 */
if ( ! function_exists( 'ephemeris_get_credits' ) ) {
	function ephemeris_get_credits() {
		$output = '';
		$output = sprintf( '<p>%1$s <a href="%2$s" title="%3$s">%4$s</a></p>',
			esc_html__( 'Proudly powered by', 'ephemeris' ),
			esc_url( esc_html__( 'http://wordpress.org/', 'ephemeris' ) ),
			esc_attr( esc_html__( 'Semantic Personal Publishing Platform', 'ephemeris' ) ),
			esc_html__( 'WordPress', 'ephemeris' )
		);

		return $output;
	}
}

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

		if( get_theme_mod( 'search_menu_icon', $defaults['search_menu_icon'] ) ) {
			if( $args->theme_location == 'primary' ) {
				$items .= '<li class="menu-item menu-item-search"><a href="#" class="nav-search"><i class="fa fa-search"></i></a></li>';
			}
		}
		return $items;
	}
}
add_filter( 'wp_nav_menu_items', 'ephemeris_add_search_menu_item', 10, 2 );

/**
 * Unhook the WooCommerce Wrappers
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Outputs the opening container div for WooCommerce
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_before_woocommerce_wrapper' ) ) {
	function ephemeris_before_woocommerce_wrapper() {
		echo '<div id="maincontentcontainer">';
		echo '<div id="primary" class="grid-container site-content" role="main">';
	}
}

/**
 * Outputs the closing container div for WooCommerce
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_after_woocommerce_wrapper' ) ) {
	function ephemeris_after_woocommerce_wrapper() {
		echo '</div> <!-- /#primary.grid-container.site-content -->';
		echo '</div> <!-- /#maincontentcontainer -->';
	}
}

/**
 * Check if WooCommerce is active
 *
 * @since Ephemeris 1.0
 *
 * @return boolean
 */
function ephemeris_is_woocommerce_active() {
	return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
}

/**
 * Check if WooCommerce is active and a WooCommerce template is in use and output the containing div
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_setup_woocommerce_wrappers' ) ) {
	function ephemeris_setup_woocommerce_wrappers() {
		if ( ephemeris_is_woocommerce_active() && is_woocommerce() ) {
				add_action( 'ephemeris_before_woocommerce', 'ephemeris_before_woocommerce_wrapper', 10, 0 );
				add_action( 'ephemeris_after_woocommerce', 'ephemeris_after_woocommerce_wrapper', 10, 0 );
		}
	}
}
add_action( 'template_redirect', 'ephemeris_setup_woocommerce_wrappers', 9 );

/**
 * Outputs the opening wrapper for the WooCommerce content
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_woocommerce_before_main_content' ) ) {
	function ephemeris_woocommerce_before_main_content() {
		if ( !ephemeris_display_woocommerce_sidebar( 'woocommerce_shop_sidebar' ) || !ephemeris_display_woocommerce_sidebar( 'woocommerce_cattag_sidebar' ) || !ephemeris_display_woocommerce_sidebar( 'woocommerce_product_sidebar' ) ) {
			echo '<div class="grid-100">';
		}
		else {
			echo '<div class="grid-70">';
		}
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
		echo '</div>';
	}
}
add_action( 'woocommerce_after_main_content', 'ephemeris_woocommerce_after_main_content', 10 );

/**
 * Remove the sidebar from the WooCommerce product page
 *
 * @since Ephemeris 1.0
 *
 * @return void
 */
if ( ! function_exists( 'ephemeris_remove_woocommerce_sidebar' ) ) {
	function ephemeris_remove_woocommerce_sidebar() {
		if ( !ephemeris_display_woocommerce_sidebar( 'woocommerce_shop_sidebar' ) || !ephemeris_display_woocommerce_sidebar( 'woocommerce_cattag_sidebar' ) || !ephemeris_display_woocommerce_sidebar( 'woocommerce_product_sidebar' ) ) {
			remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
		}
	}
}
add_action( 'woocommerce_before_main_content', 'ephemeris_remove_woocommerce_sidebar' );


/**
 * Work out whether the sidebar should be displayed on various WooCommerce pages based on the Customizer setting
 *
 * @since Ephemeris 1.0
 *
 * @return boolean
 */
function ephemeris_display_woocommerce_sidebar( $wcsidebar ) {
	$defaults = ephemeris_generate_defaults();
	$returnVal = true;

	switch ( $wcsidebar ) {
		case 'woocommerce_shop_sidebar':
			if ( is_shop() && !get_theme_mod( 'woocommerce_shop_sidebar', $defaults['woocommerce_shop_sidebar'] ) ) {
				$returnVal = false;
			}
			break;
		case 'woocommerce_product_sidebar':
			if ( is_product() && !get_theme_mod( 'woocommerce_product_sidebar', $defaults['woocommerce_product_sidebar'] ) ) {
				$returnVal = false;
			}
			break;
		case 'woocommerce_cattag_sidebar':
			if ( ( is_product_category() || is_product_tag() ) && !get_theme_mod( 'woocommerce_cattag_sidebar', $defaults['woocommerce_cattag_sidebar'] ) ) {
				$returnVal = false;
			}
			break;
		default:
			$returnVal = true;
			break;
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
		return 12;
	}
}
add_filter( 'loop_shop_per_page', 'ephemeris_shop_product_count', 20 );

/**
 * Set the number of WooCommerce products to display per row
 *
 * @since Ephemeris 1.0
 *
 * @return integer
 */
// if ( !function_exists( 'ephemeris_loop_columns' ) ) {
// 	function ephemeris_loop_columns() {
// 		return get_theme_mod( 'woocommerce_products_per_row', $defaults['woocommerce_products_per_row'] );
// 	}
// }
// add_filter( 'loop_shop_columns', 'ephemeris_loop_columns' );

/**
 * Filter the WooCommerce pagination so that it matches the theme pagination
 *
 * @since Ephemeris 1.0
 *
 * @return array Pagination arguments
 */
if ( ! function_exists( 'ephemeris_woocommerce_pagination_args' ) ) {
	function ephemeris_woocommerce_pagination_args( $paginationargs ) {
		$paginationargs[ 'prev_text'] = wp_kses( __( '<i class="fa fa-angle-left"></i> Previous', 'ephemeris' ), array( 'i' => array(
			'class' => array() ) ) );
		$paginationargs[ 'next_text'] = wp_kses( __( 'Next <i class="fa fa-angle-right"></i>', 'ephemeris' ), array( 'i' => array(
			'class' => array() ) ) );
		return $paginationargs;
	}
}
add_filter( 'woocommerce_pagination_args', 'ephemeris_woocommerce_pagination_args', 10 );

/**
 * Show all the registered Sidebars in the Customizer Widgets Panel all the time
 */
function ephemeris_show_all_sidebars_in_customizer( $args ) {
	$args['active_callback'] = '__return_true';
	return $args;
}
add_filter( 'customizer_widgets_section_args', 'ephemeris_show_all_sidebars_in_customizer' );

/**
 * Set our Social Icons URLs
 */
if ( ! function_exists( 'ephemeris_generate_social_urls' ) ) {
	function ephemeris_generate_social_urls() {
		$social_icons = array(
			array( 'url' => 'behance.net', 'icon' => 'fa-behance', 'title' => esc_html__( 'Follow me on Behance', 'ephemeris' ), 'class' => 'behance' ),
			array( 'url' => 'bitbucket.org', 'icon' => 'fa-bitbucket', 'title' => esc_html__( 'Fork me on Bitbucket', 'ephemeris' ), 'class' => 'bitbucket' ),
			array( 'url' => 'codepen.io', 'icon' => 'fa-codepen', 'title' => esc_html__( 'Follow me on CodePen', 'ephemeris' ), 'class' => 'codepen' ),
			array( 'url' => 'deviantart.com', 'icon' => 'fa-deviantart', 'title' => esc_html__( 'Watch me on DeviantArt', 'ephemeris' ), 'class' => 'deviantart' ),
			array( 'url' => 'dribbble.com', 'icon' => 'fa-dribbble', 'title' => esc_html__( 'Follow me on Dribbble', 'ephemeris' ), 'class' => 'dribbble' ),
			array( 'url' => 'etsy.com', 'icon' => 'fa-etsy', 'title' => esc_html__( 'favourite me on Etsy', 'ephemeris' ), 'class' => 'etsy' ),
			array( 'url' => 'facebook.com', 'icon' => 'fa-facebook', 'title' => esc_html__( 'Like me on Facebook', 'ephemeris' ), 'class' => 'facebook' ),
			array( 'url' => 'flickr.com', 'icon' => 'fa-flickr', 'title' => esc_html__( 'Connect with me on Flickr', 'ephemeris' ), 'class' => 'flickr' ),
			array( 'url' => 'foursquare.com', 'icon' => 'fa-foursquare', 'title' => esc_html__( 'Follow me on Foursquare', 'ephemeris' ), 'class' => 'foursquare' ),
			array( 'url' => 'github.com', 'icon' => 'fa-github', 'title' => esc_html__( 'Fork me on GitHub', 'ephemeris' ), 'class' => 'github' ),
			array( 'url' => 'instagram.com', 'icon' => 'fa-instagram', 'title' => esc_html__( 'Follow me on Instagram', 'ephemeris' ), 'class' => 'instagram' ),
			array( 'url' => 'last.fm', 'icon' => 'fa-lastfm', 'title' => esc_html__( 'Follow me on Last.fm', 'ephemeris' ), 'class' => 'lastfm' ),
			array( 'url' => 'linkedin.com', 'icon' => 'fa-linkedin', 'title' => esc_html__( 'Connect with me on LinkedIn', 'ephemeris' ), 'class' => 'linkedin' ),
			array( 'url' => 'medium.com', 'icon' => 'fa-medium', 'title' => esc_html__( 'Folllow me on Medium', 'ephemeris' ), 'class' => 'medium' ),
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
		$social_urls = [];
		$social_newtab = 0;
		$social_alignment = '';
		$contact_phone = '';

		$social_urls = explode( ',', get_theme_mod( 'social_urls', $defaults['social_urls'] ) );
		$social_newtab = get_theme_mod( 'social_newtab', $defaults['social_newtab'] );
		$social_alignment = get_theme_mod( 'social_alignment', $defaults['social_alignment'] );
		$contact_phone = get_theme_mod( 'contact_phone', $defaults['contact_phone'] );

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
				$index = array_search( $domain, array_column( $social_icons, 'url' ) );
				if( false !== $index ) {
					$output .= sprintf( '<li class="%1$s"><a href="%2$s" title="%3$s"%4$s><i class="fa %5$s"></i></a></li>',
						$social_icons[$index]['class'],
						esc_url( $value ),
						$social_icons[$index]['title'],
						( !$social_newtab ? '' : ' target="_blank"' ),
						$social_icons[$index]['icon']
					);
				}
				else {
					$output .= sprintf( '<li class="nosocial"><a href="%2$s"%3$s><i class="fa %4$s"></i></a></li>',
						$social_icons[$index]['class'],
						esc_url( $value ),
						( !$social_newtab ? '' : ' target="_blank"' ),
						'fa-globe'
					);
				}
			}
		}

		if( get_theme_mod( 'social_rss', $defaults['social_rss'] ) ) {
			$output .= sprintf( '<li class="%1$s"><a href="%2$s" title="%3$s"%4$s><i class="fa %5$s"></i></a></li>',
				'rss',
				home_url( '/feed' ),
				'Subscribe to my RSS feed',
				( !$social_newtab ? '' : ' target="_blank"' ),
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
* Set our Customizer default options
*/
if ( ! function_exists( 'ephemeris_generate_defaults' ) ) {
	function ephemeris_generate_defaults() {
		$customizer_defaults = array(
			'social_newtab' => 0,
			'social_urls' => '',
			'social_alignment' => 'alignright',
			'social_rss' => 0,
			'contact_phone' => '',
			'search_menu_icon' => 0,
			'woocommerce_shop_sidebar' => 1,
			'woocommerce_cattag_sidebar' => 1,
			'woocommerce_product_sidebar' => 0,
			'woocommerce_breadcrumbs' => 1,
		);

		return apply_filters( 'ephemeris_customizer_defaults', $customizer_defaults );
	}
}

/**
* Load all our Customizer options
*/
include_once trailingslashit( dirname(__FILE__) ) . 'inc/customizer.php';
