<?php
/**
 * Template Name: Page Builder Full-Width
 * Template Post Type: page, post
 *
 * Description: Displays a browser full-width page for use with page builders like Elementor, Beaver Builder,  Divi Builder, and Visual Composer.
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */

get_header();

	if ( have_posts() ) {

		// Start the Loop
		while ( have_posts() ) {
			the_post();
			get_template_part( 'template-parts/content', 'builderfullwidth' );
			comments_template( '', true );
		} // end of the loop

	} // end have_posts()

get_footer();
