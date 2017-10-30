(function ( api ) {
	// When the WooCommerce panel is expanded, change preview url to WooCommerce shop page
	api.section( 'woocommerce_layout_section', function( section ) {
		section.expanded.bind( function( isExpanded ) {
			var url;
			if ( isExpanded ) {
				url = ephemeris_customizer_data.ephemeris_woocommerce_url;
				api.previewer.previewUrl.set( url );
			}
		} );
	} );
} ( wp.customize ) );
