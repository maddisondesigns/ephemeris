<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url() ); ?>">
	<label>
		<span class="screen-reader-text">Search for:</span>
		<input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Search &hellip;', 'ephemeris' ); ?>" value="<?php the_search_query(); ?>" name="s"/>
	</label>
	<button type="submit" class="search-submit"><i class="fas fa-search"></i></button>
<input type='hidden' name='lang' value='en' /></form>
