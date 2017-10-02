<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */

get_header(); ?>

<div id="maincontentcontainer">
	<div id="content" class="grid-container site-content" role="main">

		<div <?php ephemeris_main_class(); ?>>

			<article id="post-0" class="post error404 no-results not-found">
				<header class="entry-header">
					<h1 class="entry-title"><i class="fa fa-frown-o fa-lg"></i> <?php esc_html_e( 'Uh Oh! This is somewhat embarrassing!', 'ephemeris' ); ?></h1>
				</header>
				<div class="entry-content">
					<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'ephemeris' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- /.entry-content -->
			</article><!-- /#post -->

		</div>

	</div> <!-- /#content.grid-container.site-content -->
</div> <!-- /#maincontentcontainer -->

<?php get_footer(); ?>
