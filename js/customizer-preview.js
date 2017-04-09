jQuery( document ).ready(function($) {
   wp.customize('sample_checkbox_switch', function(control) {
      control.bind(function( controlValue ) {
			if( controlValue == true ) {
				// If the switch is on, add the search icon
				$('#menu-short').append('<li class="search"><i class="fa fa-search"></i></li>');
			}
			else {
				// If the switch is off, remove the search icon
				$('li.search').remove();
			}
      });
   });

	wp.customize('sample_slider_control', function(control) {
      control.bind(function( controlValue ) {
			$('h1').css('font-size', controlValue + 'px');
      });
   });
});
