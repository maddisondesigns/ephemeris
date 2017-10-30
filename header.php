<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="maincontentcontainer">
 *
 * @package Ephemeris
 * @since Ephemeris 1.0
 */
?><!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head(); ?>
</head>

<body itemscope="itemscope" itemtype="http://schema.org/CreativeWork" <?php body_class(); ?>>

<main class="hfeed site" role="main">

	<div class="visuallyhidden skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to main content', 'ephemeris' ); ?>"><?php esc_html_e( 'Skip to main content', 'ephemeris' ); ?></a></div>

	<?php	do_action( 'ephemeris_before_header' ); ?>
	<?php if ( !ephemeris_has_pagebuilder_template( 'header' ) ) { ?>
		<div class="top-header">
			<div class="announcement-bar grid-container">
				<?php	do_action( 'ephemeris_announcement_bar_content' ); ?>
			</div>
		</div>

		<div id="headercontainer">
			<?php	do_action( 'ephemeris_before_header_content' ); ?>

			<header id="masthead" class="grid-container site-header" role="banner">
				<?php	do_action( 'ephemeris_header_content' ); ?>
			</header> <!-- /#masthead.grid-container.site-header -->

			<?php	do_action( 'ephemeris_after_header_content' ); ?>
		</div> <!-- /#headercontainer -->
		<?php	do_action( 'ephemeris_before_main_content' ); ?>
	<?php } ?>
