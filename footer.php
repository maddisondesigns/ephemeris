<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id #maincontentcontainer div and all content after.
 * There are also four footer widgets displayed. These will be displayed from
 * one to four columns, depending on how many widgets are active.
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */
?>

	<?php if ( !ephemeris_has_pagebuilder_template( 'footer' ) ) { ?>
		<?php	do_action( 'ephemeris_after_main_content' ); ?>
		<div id="footercontainer">
			<?php	do_action( 'ephemeris_before_footer_content' ); ?>
			<?php get_sidebar( 'footer' ); ?>
			<?php	do_action( 'ephemeris_after_footer_content' ); ?>
		</div> <!-- /.footercontainer -->
		<div id="footercreditscontainer">
			<?php	do_action( 'ephemeris_before_credits_content' ); ?>
			<div class="grid-container site-credits">
				<div class="grid-100 footer-credits">
					<?php echo ephemeris_get_credits() ?>
				</div> <!-- /.grid-100 -->
			</div> <!-- /.grid-container.site-credits -->
			<?php	do_action( 'ephemeris_after_credits_content' ); ?>
		</div> <!-- /.footercontainer -->
	<?php } ?>

<?php	do_action( 'ephemeris_after_footer' ); ?>
</main> <!-- /.hfeed.site -->

<?php if ( !ephemeris_has_pagebuilder_template( 'header' ) ) { ?>
	<a id="mobile-site-navigation" href="#cd-nav" class="mobile-navigation mobile-nav-trigger">
		<div class="menu-hamburger">
			<span class="line"></span>
			<span class="line"></span>
			<span class="line"></span>
			<span class="mobile-nav-title"><?php _e( 'MENU', 'ephemeris' ) ?></span>
		</div>
	</a>

	<div id="cd-nav" class="grid-container cd-nav">
		<div class="grid-100">
			<div class="cd-navigation-wrapper">
				<h2><?php echo apply_filters( 'ephemeris_mobile_navigation_title', __( 'Navigation', 'ephemeris' ) ) ?></h2>

				<nav role="navigation">
					<?php wp_nav_menu( array( 'theme_location' => 'primary-menu', 'menu_class' => 'mobile-menu' ) ); ?>
				</nav>
			</div><!-- .cd-navigation-wrapper -->
		</div> <!-- .grid-100 -->
	</div> <!-- .cd-nav -->

	<div class="search-overlay">
		<button type="button" class="search-close"><i class="fa fa-times-circle-o"></i></button>
		<?php get_search_form() ?>
	</div>
<?php } ?>

<?php wp_footer(); ?>
</body>

</html>
