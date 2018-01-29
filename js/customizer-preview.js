jQuery( document ).ready(function($) {
	// Adjust the width of the main content area
	wp.customize('ephemeris_layout_width', function(control) {
		control.bind(function( controlValue ) {
			$('.grid-container').css('max-width', controlValue + 'px');
		});
	});

	// Adjust the Page & Post Header color styles
	wp.customize('ephemeris_color_header_title_normal', function(control) {
		control.bind(function( controlValue ) {
			ephemeris_customizer_preview_update_colors( 'ephemeris_color_header_title_normal', '.entry-header h1', 'color', controlValue )
		});
	});
	wp.customize('ephemeris_color_header_title_link', function(control) {
		control.bind(function( controlValue ) {
			ephemeris_customizer_preview_update_colors( 'ephemeris_color_header_title_link', '.entry-header h1 a', 'color', controlValue )
		});
	});
	wp.customize('ephemeris_color_header_title_hover', function(control) {
		control.bind(function( controlValue ) {
			ephemeris_customizer_preview_update_colors( 'ephemeris_color_header_title_hover', '.entry-header h1 a:hover, .entry-header h1 a:active', 'color', controlValue )
		});
	});
	wp.customize('ephemeris_color_header_title_visited', function(control) {
		control.bind(function( controlValue ) {
			ephemeris_customizer_preview_update_colors( 'ephemeris_color_header_title_visited', '.entry-header h1 a:visited', 'color', controlValue )
		});
	});

	// Adjust the body Header colors styles
	wp.customize('ephemeris_color_header_body_normal', function(control) {
		control.bind(function( controlValue ) {
			ephemeris_customizer_preview_update_colors( 'ephemeris_color_header_body_normal', 'h1, h2, h3, h4, h5, h6', 'color', controlValue )
		});
	});
	wp.customize('ephemeris_color_header_body_link', function(control) {
		control.bind(function( controlValue ) {
			ephemeris_customizer_preview_update_colors( 'ephemeris_color_header_body_link', 'h1 a, h2 a, h3 a, h4 a, h5 a, h6 a', 'color', controlValue )
		});
	});
	wp.customize('ephemeris_color_header_body_hover', function(control) {
		control.bind(function( controlValue ) {
			ephemeris_customizer_preview_update_colors( 'ephemeris_color_header_body_hover', 'h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover, h1 a:active, h2 a:active, h3 a:active, h4 a:active, h5 a:active, h6 a:active', 'color', controlValue )
		});
	});
	wp.customize('ephemeris_color_header_body_visited', function(control) {
		control.bind(function( controlValue ) {
			ephemeris_customizer_preview_update_colors( 'ephemeris_color_header_body_visited', 'h1 a:visited, h2 a:visited, h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited', 'color', controlValue )
		});
	});

	// Adjust the body Text colors styles
	wp.customize('ephemeris_color_text_normal', function(control) {
		control.bind(function( controlValue ) {
			ephemeris_customizer_preview_update_colors( 'ephemeris_color_text_normal', '.site-content, .more-link', 'color', controlValue )
		});
	});
	wp.customize('ephemeris_color_text_link', function(control) {
		control.bind(function( controlValue ) {
			ephemeris_customizer_preview_update_colors( 'ephemeris_color_text_link', 'a, .more-link', 'color', controlValue )
		});
	});
	wp.customize('ephemeris_color_text_hover', function(control) {
		control.bind(function( controlValue ) {
			ephemeris_customizer_preview_update_colors( 'ephemeris_color_text_hover', 'a:hover, a:active, .more-link:hover, .more-link:active', 'color', controlValue )
		});
	});
	wp.customize('ephemeris_color_text_visited', function(control) {
		control.bind(function( controlValue ) {
			ephemeris_customizer_preview_update_colors( 'ephemeris_color_text_visited', 'a:visited, .more-link:visited', 'color', controlValue )
		});
	});

	// Append the search icon list item to the main nav
   wp.customize('ephemeris_search_menu_icon', function(control) {
      control.bind(function( controlValue ) {
			if( controlValue == true ) {
				// If the switch is on, add the search icon
				$('.nav-menu').append('<li class="menu-item menu-item-search"><a href="#" class="nav-search"><i class="fa fa-search"></i></a></li>');
			}
			else {
				// If the switch is off, remove the search icon
				$('li.menu-item-search').remove();
			}
      });
   });

	// Change the footer background color
	wp.customize('ephemeris_footer_background_color', function(control) {
		control.bind(function( controlValue ) {
			$('#footercontainer').css('background-color', controlValue);
			$('#footercreditscontainer').css('background-color', controlValue);
		});
	});

	// Change the footer font color
	wp.customize('ephemeris_footer_credits_font_color', function(control) {
		control.bind(function( controlValue ) {
			$('.site-credits').css('color', controlValue);
		});
	});

	// Inject our color styles in the head as you can't change pseudo-classes using jQuery's css function
	function ephemeris_customizer_preview_update_colors( id, selector, property, value ) {
		if($('style#' + id).length) {
			$('style#' + id).html(selector + ' { ' + property + ': ' + value + '; }');
		} else {
			$('head').append('<style id="' + id + '" type="text/css">' + selector + ' { ' + property + ': ' + value + '; }</style>');
		}
	}

});
