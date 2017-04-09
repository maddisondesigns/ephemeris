jQuery( document ).ready( function( $ ){

	/**
	 * Responsive menu
	 */
	var isLateralNavAnimating = false;

	//open/close lateral navigation
	$( '.mobile-nav-trigger' ).on( 'click', function( event ){
		event.preventDefault();
		//stop if nav animation is running
		if( !isLateralNavAnimating ) {
			if( $( this ).parents( '.csstransitions' ).length > 0 ) isLateralNavAnimating = true;

			$( 'body' ).toggleClass( 'navigation-is-open' );
			$( '.cd-navigation-wrapper' ).one( 'webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
				//animation is over
				isLateralNavAnimating = false;
			} );
		}
	} );

	/**
	 * Fullscreen search
	 */
	$(document).on('click', '.search-close', function(e) {
		e.preventDefault();
		$('.search-overlay').toggleClass('open');
	});

	$('.menu-item-search').on('click', '.nav-search', function(e) {
		e.preventDefault();
		$('.search-overlay').toggleClass('open');
		$('.search-overlay form input[name="s"]').focus();
	});

	$('.search-overlay form input[name="s"]').keyup(function(e) {
		e.preventDefault();
		if (e.keyCode == 27) {
			$('.search-overlay').toggleClass('open');
		}
	});

} );
