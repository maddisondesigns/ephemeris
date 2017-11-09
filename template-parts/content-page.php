<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */
?>

<article itemscope="itemscope" itemtype="http://schema.org/WebPage" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( !is_front_page() ) { ?>
		<?php do_action( 'ephemeris_before_entry_header' ); ?>
		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php if ( has_post_thumbnail() && !is_search() && !post_password_required() ) { ?>
				<?php the_post_thumbnail( 'ephemeris_post_feature_full_width' ); ?>
			<?php } ?>
		</header>
	<?php } ?>
	<?php do_action( 'ephemeris_after_entry_header' ); ?>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ephemeris' ),
			'after' => '</div>',
			'link_before' => '<span class="page-numbers">',
			'link_after' => '</span>'
		) ); ?>
	</div><!-- /.entry-content -->
	<footer class="entry-meta">
		<?php edit_post_link( esc_html__( 'Edit', 'ephemeris' ) . ' <i class="fa fa-angle-right" aria-hidden="true"></i>', '<div class="edit-link">', '</div>' ); ?>
	</footer><!-- /.entry-meta -->
	<?php do_action( 'ephemeris_after_entry_content' ); ?>
</article><!-- /#post -->
