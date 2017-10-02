<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query. E.g. it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */

get_header(); ?>

<div id="maincontentcontainer">
	<div id="content" class="grid-container site-content" role="main">

		<?php do_action( 'ephemeris_before_main_grid' ); ?>
		<div <?php ephemeris_main_class(); ?>>

			<?php
			do_action( 'ephemeris_before_content' );
			if ( have_posts() ) {

				// Start the Loop
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content', get_post_format() ); // Include the Post-Format-specific template for the content
				} // end of the loop

				ephemeris_posts_pagination();

			} else {

				get_template_part( 'template-parts/no', 'results' ); // Include the template that displays a message that posts cannot be found

			} // end have_posts()
			do_action( 'ephemeris_after_content' );
			?>

		</div>
		<?php get_sidebar(); ?>
		<?php do_action( 'ephemeris_after_main_grid' ); ?>

	</div> <!-- /#content.grid-container.site-content -->
</div> <!-- /#maincontentcontainer -->

<?php get_footer(); ?>
