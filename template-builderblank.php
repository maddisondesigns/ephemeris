<?php
/**
 * Template Name: Page Builder Blank
 *
 * Description: Displays a browser full-width blank page for use with page builders like Visual Composer, Beaver Builder and the Divi Builder. This template also removes the header and footer section of the page
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */

get_header( 'blank' ); ?>

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

<?php get_footer( 'blank' ); ?>
