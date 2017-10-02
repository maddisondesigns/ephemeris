<?php
/**
 * The template for displaying Search Results.
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */

get_header(); ?>

<div id="maincontentcontainer">
	<div id="content" class="grid-container site-content" role="main">

		<div <?php ephemeris_main_class(); ?>>

			<?php if ( have_posts() ) { ?>

				<header class="page-header">
					<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'ephemeris' ), '<span>&ldquo;' . get_search_query() . '&rdquo;</span>' ); ?></h1>
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

		</div>
		<?php get_sidebar(); ?>

	</div> <!-- /#content.grid-container.site-content -->
</div> <!-- /#maincontentcontainer -->

<?php get_footer(); ?>
