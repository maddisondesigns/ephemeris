<?php
/**
 * The template for displaying the sidebar content
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */
?>

<div id="secondary" class="widget-area" role="complementary">
	<?php
	do_action( 'ephemeris_before_sidebar' );

	if ( ephemeris_is_plugin_active( 'woocommerce' ) && is_active_sidebar( 'sidebar-shop' ) && ( is_woocommerce() || is_cart() || is_checkout() ) ) {
		dynamic_sidebar( 'sidebar-shop' );
	}
	else {
		if ( is_active_sidebar( 'sidebar-main' ) ) {
			dynamic_sidebar( 'sidebar-main' );
		}

		if ( ( is_home() || is_archive() ) && is_active_sidebar( 'sidebar-blog' ) ) {
			dynamic_sidebar( 'sidebar-blog' );
		}

		if ( is_single() && is_active_sidebar( 'sidebar-single' ) ) {
			dynamic_sidebar( 'sidebar-single' );
		}

		if ( is_page() && is_active_sidebar( 'sidebar-page' ) ) {
			dynamic_sidebar( 'sidebar-page' );
		}
	}
	do_action( 'ephemeris_after_sidebar' );
	?>

</div> <!-- /#secondary.widget-area -->
