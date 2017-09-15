<?php
/**
 * Template Name: Page Builder Full-Width
 *
 * Description: Displays a browser full-width page for use with page builders like Elementor, Beaver Builder,  Divi Builder, and Visual Composer.
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */

get_header(); ?>

	<?php
	if ( have_posts() ) {

		// Start the Loop
		while ( have_posts() ) {
			the_post();
			get_template_part( 'template-parts/content', 'builderfullwidth' );
			comments_template( '', true );
		} // end of the loop

	} // end have_posts()
	?>

<?php get_footer(); ?>
