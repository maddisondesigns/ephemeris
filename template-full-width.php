<?php
/**
 * Template Name: Full-width Page
 *
 * Description: Displays a full-width page, with no sidebar. This template is great for pages containing large amounts of content.
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
					get_template_part( 'template-parts/content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() ) {
						comments_template( '', true );
					}

				} // end of the loop

			} // end have_posts()
			do_action( 'ephemeris_after_content' );
			?>

		</div>
		<?php do_action( 'ephemeris_after_main_grid' ); ?>

	</div><!-- /#content.grid-container.site-content -->
</div> <!-- /#maincontentcontainer -->

<?php get_footer(); ?>
