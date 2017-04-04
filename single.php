<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */

get_header(); ?>

<div id="maincontentcontainer">
	<div id="primary" class="grid-container site-content" role="main">

			<div class="grid-70 tablet-grid-70">

				<?php
				// Start the Loop
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content', get_post_format() );

					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() ) {
						comments_template( '', true );
					}

					ephemeris_single_posts_pagination();

				} // end of the loop
				?>

			</div> <!-- /.grid-70 -->
			<?php get_sidebar(); ?>

	</div> <!-- /#primary.grid-container.site-content -->
</div> <!-- /#maincontentcontainer -->

<?php get_footer(); ?>
