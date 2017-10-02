<?php
/**
 * The template for displaying an archive page for Categories.
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */

get_header(); ?>

<div id="maincontentcontainer">
	<div id="content" class="grid-container site-content" role="main">

		<?php do_action( 'ephemeris_before_main_grid' ); ?>
		<div <?php ephemeris_main_class(); ?>>

			<?php do_action( 'ephemeris_before_content' ); ?>
			<?php if ( have_posts() ) { ?>

				<header class="archive-header">
					<?php the_archive_title(); ?>

					<?php if ( category_description() ) { // Show an optional category description ?>
						<div class="archive-meta"><?php the_archive_description(); ?></div>
					<?php } ?>
				</header>

				<?php
				// Start the Loop
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content', get_post_format() );
				} // end of the loop
				?>

				<?php ephemeris_posts_pagination(); ?>

			<?php } else { ?>

				<?php get_template_part( 'template-parts/no', 'results' ); // Include the template that displays a message that posts cannot be found ?>

			<?php } // end have_posts() ?>
			<?php do_action( 'ephemeris_after_content' ); ?>

		</div>
		<?php get_sidebar(); ?>
		<?php do_action( 'ephemeris_after_main_grid' ); ?>

	</div> <!-- /#content.grid-container.site-content -->
</div> <!-- /#maincontentcontainer -->

<?php get_footer(); ?>
