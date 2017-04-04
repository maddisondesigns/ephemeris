<?php
/**
 * Template Name: Full-width Page
 *
 * Description: Displays a full-width page, with no sidebar. This template is great for pages
 * containing large amounts of content.
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */

get_header(); ?>

<div id="maincontentcontainer">
	<div id="primary" class="grid-container site-content" role="main">
		<div class="grid-100">

			<?php
			if ( have_posts() ) {

				// Start the Loop
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content', 'page' );
					comments_template( '', true );
				} // end of the loop

			} // end have_posts()
			?>

		</div> <!-- /.grid-100 -->
	</div><!-- /#primary.grid-container.site-content -->
</div> <!-- /#maincontentcontainer -->

<?php get_footer(); ?>
