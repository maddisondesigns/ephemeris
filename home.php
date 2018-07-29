<?php
/**
 * The Template for displaying the blog posts archive.
 *
 * @package Ephemeris
 * @since Ephemeris 1.4
 */

get_header();
// Get our default settings 
$defaults = ephemeris_generate_defaults();
// Check the Customizer setting for sidebar placement
$post_archive_sidebar_layout = strtolower( get_theme_mod( 'ephemeris_post_archive_template_default', $defaults['ephemeris_post_archive_template_default'] ) );
?>

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
		<?php
		if ( $post_archive_sidebar_layout !== 'none' ) {
			get_sidebar( ( $post_archive_sidebar_layout === 'left' ? 'left' : '' ) );
		}
		?>
		<?php do_action( 'ephemeris_after_main_grid' ); ?>

	</div> <!-- /#content.grid-container.site-content -->
</div> <!-- /#maincontentcontainer -->

<?php get_footer(); ?>
