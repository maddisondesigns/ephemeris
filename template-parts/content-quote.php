<?php
/**
 * The template for displaying posts in the Quote post format
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */
?>

<article itemscope="itemscope" itemtype="http://schema.org/Article" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php do_action( 'ephemeris_before_entry_header' ); ?>
	<header class="entry-header">
		<?php do_action( 'ephemeris_after_entry_title' ); ?>
	</header> <!-- /.entry-header -->
	<?php do_action( 'ephemeris_after_entry_header' ); ?>

	<div class="entry-content">
		<blockquote>
			<?php the_content( wp_kses( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'ephemeris' ), array(
				'span' => array(
					'class' => array() )
				) ) ); ?>
			<cite><?php the_title(); ?></cite>
		</blockquote>
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
		<?php edit_post_link( esc_html__( 'Edit', 'ephemeris' ) . ' <i class="fa fa-angle-right"></i>', '<div class="edit-link">', '</div>' ); ?>
	</footer> <!-- /.entry-meta -->
	<?php do_action( 'ephemeris_after_entry_content' ); ?>
</article> <!-- /#post -->
