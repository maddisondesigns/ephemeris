<?php
/**
 * The template for displaying an archive page for Categories.
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */

get_header(); ?>

<div id="maincontentcontainer">
	<div id="primary" class="grid-container site-content" role="main">

		<div class="grid-70 tablet-grid-70">

			<?php if ( have_posts() ) { ?>

				<header class="archive-header">
					<?php the_archive_title(); ?>

					<?php if ( get_the_author_meta( 'description' ) ) { // If a user has filled out their description, show a bio on their entries.
						get_template_part( 'author-bio' );
					} ?>
				</header><!-- .archive-header -->

				<?php // Start the Loop ?>
				<?php while ( have_posts() ) {
					the_post(); ?>
					<?php get_template_part( 'content', get_post_format() ); ?>
				<?php } ?>

				<?php ephemeris_posts_pagination(); ?>

			<?php } else { ?>

				<?php get_template_part( 'no-results' ); // Include the template that displays a message that posts cannot be found ?>

			<?php } // end have_posts() check ?>

		</div> <!-- /.grid-70 -->
		<?php get_sidebar(); ?>

	</div> <!-- /#primary.grid-container.site-content -->
</div> <!-- /#maincontentcontainer -->

<?php get_footer(); ?>
