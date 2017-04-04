<?php
/**
 * The template for displaying posts in the Chat post format
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php ephemeris_posted_on(); ?>
	</header> <!-- /.entry-header -->
	<div class="entry-content">
		<?php the_content( wp_kses( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'ephemeris' ), array( 
			'span' => array( 
				'class' => array() ) 
			) ) ); ?>
		<?php wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ephemeris' ),
			'after' => '</div>',
			'link_before' => '<span class="page-numbers">',
			'link_after' => '</span>'
		) ); ?>
	</div> <!-- /.entry-content -->

	<footer class="entry-meta">
		<?php if ( is_singular() ) {
			// Only show the tags on the Single Post page
			ephemeris_entry_meta();
		} ?>
		<?php edit_post_link( esc_html__( 'Edit', 'ephemeris' ) . ' <i class="fa fa-angle-right" aria-hidden="true"></i>', '<div class="edit-link">', '</div>' ); ?>
	</footer> <!-- /.entry-meta -->
</article> <!-- /#post -->