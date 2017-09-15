<?php
/**
 * Template Name: Left Sidebar Page
 *
 * Description: Displays a page with a left hand sidebar.
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */

get_header(); ?>

<div id="maincontentcontainer">
	<div id="content" class="grid-container site-content" role="main">

		<?php do_action( 'ephemeris_before_main_grid' ); ?>
		<?php get_sidebar(); ?>
		<div class="grid-70 tablet-grid-70">

			<?php
			do_action( 'ephemeris_before_content' );
			if ( have_posts() ) {

				// Start the Loop
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content', 'page' );
				} // end of the loop

				ephemeris_posts_pagination();

			} else {

				get_template_part( 'template-parts/no', 'results' ); // Include the template that displays a message that posts cannot be found

			} // end have_posts()
			do_action( 'ephemeris_after_content' );
			?>

		</div> <!-- /.grid-70 -->
		<?php do_action( 'ephemeris_after_main_grid' ); ?>

	</div> <!-- /#content.grid-container.site-content -->
</div> <!-- /#maincontentcontainer -->

<?php get_footer(); ?>
