<?php
/**
 * Template Name: Page Builder Boxed
 *
 * Description: Displays a boxed full-width page for use with page builders like Visual Composer, Beaver Builder and the Divi Builder.
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
					get_template_part( 'template-parts/content', 'builderboxed' );
					comments_template( '', true );
				} // end of the loop

			} // end have_posts()
			?>

		</div> <!-- /.grid-100 -->
	</div><!-- /#primary.grid-container.site-content -->
</div> <!-- /#maincontentcontainer -->

<?php get_footer(); ?>
