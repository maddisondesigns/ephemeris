<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */

get_header();
// Get our default settings 
$defaults = ephemeris_generate_defaults();
// Check the Customizer setting for sidebar placement
$post_sidebar_layout = strtolower( get_theme_mod( 'ephemeris_post_template_default', $defaults['ephemeris_post_template_default'] ) );
?>


<div id="maincontentcontainer">
	<div id="content" class="grid-container site-content" role="main">

			<?php do_action( 'ephemeris_before_main_grid' ); ?>
			<div <?php ephemeris_main_class(); ?>>

				<?php
				do_action( 'ephemeris_before_content' );
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
				do_action( 'ephemeris_after_content' );
				?>

			</div>
			<?php
			if ( $post_sidebar_layout !== 'none' ) {
				get_sidebar( ( $post_sidebar_layout === 'left' ? 'left' : '' ) );
			}
			?>
			<?php do_action( 'ephemeris_after_main_grid' ); ?>

	</div> <!-- /#content.grid-container.site-content -->
</div> <!-- /#maincontentcontainer -->

<?php get_footer(); ?>
