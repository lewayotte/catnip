<?php
/**
 * @package catnip
 * @since 1.0.0
 */
if ( ! class_exists( 'catnip_shortcodes' ) ) {
	
	/**
	 * This class defines and returns the shortcodes
	 * @since 1.0.0
	 */
	class catnip_shortcodes {
		
		/**
		 * Class Constructor
		 * @since 1.0.0
		 */
		function catnip_shortcodes() {
				
			add_shortcode( 'catnip', array( &$this, 'do_catnip_shortcode' ) );
		
		}
		
		/**
		 * Primary Shortcode for catnip
		 * @since 1.0.0
		 */
		function do_catnip_shortcode( $atts ) {
			
			$the_cat_api = new the_cat_api();
			
			$options = get_option( 'catnip_plugin_options' );
				
			$output = '';	
			
			$defaults = array( 
				'api_key'				=> $options['api_key'],
				'image_id'				=> '',		//Unique id of the Image to return.
				'results_per_page'		=> 1, 		//1-100, if format is set to src then 1 will be returned
				'type'					=> '', 		//jpg, png, or gif (blank = all)
				'category'				=> '', 		//hats, space, boxes, sunglasses, ties
				'size'					=> 'full', 	//small, med, full
				'sub_id'				=> ''		//This lets you pass a unique identifier, like a Facebook or Twitter User id, with your API requests. This way you can get all the Favourites and Votes set by one of your Users.
			);
			
			// Merge defaults with passed atts
			$catapi_args = shortcode_atts( $defaults, $atts );
			
			$xml = new SimpleXMLElement( $the_cat_api->get_cat_images( $catapi_args ) );
			
			$output .= '<div class="catnip_shortcode catnip_images catnip_image_size_' . $args['size'] . '">';
			foreach( $xml->data->images->image as $image ) {
			
				$output .= '<div id="' . $image->id . '" class="the_cat_api_image">';
				$output .= '<a href="' . $image->source_url . '"><img src="' . $image->url . '"></a>';
				$output .= '</div>';
				
			}
			$output .= '</div>';
			
			return $output;
			
		}
	
	}
	
}