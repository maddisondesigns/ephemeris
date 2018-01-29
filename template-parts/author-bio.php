<?php
/**
 * The template for displaying Author bios.
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */
?>

<div class="author-info">
	<div class="author-avatar">
		<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'ephemeris_author_bio_avatar_size', 96 ) ); ?>
	</div> <!-- /.author-avatar -->
	<div class="author-description">
		<h2><?php printf( esc_html__( 'About %s', 'ephemeris' ), get_the_author() ); ?></h2>
		<p><?php the_author_meta( 'description' ); ?></p>
		<p class="social-meta">
			<?php if ( get_the_author_meta( 'url' ) ) { ?>
				<a href="<?php the_author_meta( 'url' ) ?>" title="<?php esc_html_e( 'Website', 'ephemeris' ) ?>"><i class="fa fa-link fa-fw"></i></a>
			<?php } ?>
			<?php if ( get_the_author_meta( 'twitter' ) ) { ?>
				<a href="<?php the_author_meta( 'twitter' ) ?>" title="<?php esc_html_e( 'Twitter', 'ephemeris' ) ?>"><i class="fa fa-twitter fa-fw"></i></a>
			<?php } ?>
			<?php if ( get_the_author_meta( 'facebook' ) ) { ?>
				<a href="<?php the_author_meta( 'facebook' ) ?>" title="<?php esc_html_e( 'Facebook', 'ephemeris' ) ?>"><i class="fa fa-facebook fa-fw"></i></a>
			<?php } ?>
			<?php if ( get_the_author_meta( 'googleplus' ) ) { ?>
				<a href="<?php the_author_meta( 'googleplus' ) ?>" title="<?php esc_html_e( 'Google+', 'ephemeris' ) ?>"><i class="fa fa-google-plus fa-fw"></i></a>
			<?php } ?>
		</p>
		<div class="author-link">
			<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
				<?php printf( wp_kses( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'ephemeris' ), array(
					'span' => array(
						'class' => array() )
				) ), get_the_author() ); ?>
			</a>
		</div> <!-- /.author-link	-->
	</div> <!-- /.author-description -->
</div> <!-- /.author-info -->
