<?php
/**
 * @package catnip
 * @since 1.0.0
 */
if ( ! class_exists( 'the_cat_api' ) ) {
	
	/**
	 * This class defines and returns the shortcodes
	 * @since 1.0.0
	 */
	class the_cat_api {
		
		/**
		 * Class Constructor
		 * @since 1.0.0
		 */
		function the_cat_api() {

			$this->cat_api_url 		= 'http://thecatapi.com';
			$this->get_uri 			= '/api/images/get';
			//$this->vote_uri			= '/api/images/vote';
			//$this->fav_uri			= '/api/images/favourite';
			//$this->getfav_uri		= '/api/images/getfavourites';
			//$this->report_uri		= '/api/images/report';
		
		}
		
		/**
		 * Get the cat images from The Cat API
		 * @since 1.0.0
		 */
		function get_cat_images( $query = array() ) {
			
			$query['format'] = 'xml';
			
			//	http://thecatapi.com/api/images/get
			$response = wp_remote_get( $this->cat_api_url . $this->get_uri . '?' . http_build_query( array_filter( $query ) ) );
			
			if ( is_wp_error( $response ) )
				return $response->get_error_message();
			else
				return wp_remote_retrieve_body( $response );	
			
		}
	
	}
	
}