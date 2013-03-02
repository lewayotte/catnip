<?php
/**
 * Register our widgets with WP
 *
 * @since 1.0.0
 */
function register_catnip_widgets() {
	
	register_widget( 'catnip_widget' );

}
add_action( 'widgets_init', 'register_catnip_widgets' );

/**
 * This class registers and returns the funny/cute cat images
 *
 * @since 1.0.0
 */
class catnip_widget extends WP_Widget {
	
	/**
	 * Set's widget name and description
	 *
	 * @since 1.0.0
	 */
	function catnip_widget() {
		
		$widget_ops = array('classname' => 'catnip_widget', 'description' => __( "It's Caturday everyday in WordPress with The Cat API.", 'catnip' ) );
		$this->WP_Widget( 'catnip', __( 'Catnip Widget', 'catnip' ), $widget_ops );
	
	}
	
	/**
	 * Displays the widget on the front end
	 *
	 * @since 1.0.0
	 */
	function widget( $args, $instance ) {
		
		$the_cat_api = new the_cat_api();
		
		$output = '';
		
		extract( $args );
		
		$xml = new SimpleXMLElement( $the_cat_api->get_cat_images( $instance ) );
		
		$output .= '<div class="catnip_widget catnip_images catnip_image_size_' . $args['size'] . '">';
		foreach( $xml->data->images->image as $image ) {
		
			$output .= '<div id="' . $image->id . '" class="the_cat_api_image">';
			$output .= '<a href="' . $image->source_url . '"><img src="' . $image->url . '"></a>';
			$output .= '</div>';
			
		}
		$output .= '</div>';

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'The Cat API', 'catnip' ) : $instance['title'], $instance, $this->id_base );
		
		if ( ! empty( $output ) ) {
			
			echo $before_widget;
			
			if ( $title)
				echo $before_title . $title . $after_title;
			
			echo $output; 
			
			echo $after_widget;	
		
		}
	
	}

	/**
	 * Save's the widgets options on submit
	 *
	 * @since 0.3
	 */
	function update( $new_instance, $old_instance ) {
		
		$instance 						= $old_instance;
		$instance['title'] 				= strip_tags( $new_instance['title'] );
		$instance['api_key'] 			= strip_tags( $new_instance['api_key'] );
		$instance['image_id'] 			= strip_tags( $new_instance['image_id'] );
		$instance['results_per_page'] 	= strip_tags( $new_instance['results_per_page'] );
		$instance['type'] 				= strip_tags( $new_instance['type'] );
		$instance['category'] 			= strip_tags( $new_instance['category'] );
		$instance['size'] 				= strip_tags( $new_instance['size'] );
		$instance['sub_id'] 			= strip_tags( $new_instance['sub_id'] );
	
		return $instance;
	
	}

	/**
	 * Displays the widget options in the dashboard
	 *
	 * @since 0.3
	 */
	function form( $instance ) {
		
		$options = get_option( 'catnip_plugin_options' );
		
		//Defaults
		$defaults = array( 
			'title'					=> '',
			'api_key'				=> $options['api_key'],
			'image_id'				=> '',		//Unique id of the Image to return.
			'results_per_page'		=> 1, 		//1-100, if format is set to src then 1 will be returned
			'type'					=> '', 		//jpg, png, or gif (blank = all)
			'category'				=> '', 		//hats, space, boxes, sunglasses, ties
			'size'					=> 'small', //small, med, full
			'sub_id'				=> ''		//This lets you pass a unique identifier, like a Facebook or Twitter User id, with your API requests. This way you can get all the Favourites and Votes set by one of your Users.
		);
		
		extract( wp_parse_args( (array)$instance, $defaults ) );
 
		?>
        
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'catnip' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( strip_tags( $title ) ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('api_key'); ?>"><?php _e( 'API Key:', 'catnip' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('api_key'); ?>" name="<?php echo $this->get_field_name('api_key'); ?>" type="text" value="<?php echo esc_attr( strip_tags( $api_key ) ); ?>" />
            <small><?php _e( 'This gives you access to all the images, voting, favouriting, uploading, etc.', 'catnip' ); ?></small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('sub_id'); ?>"><?php _e( 'Sub ID:', 'catnip' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('sub_id'); ?>" name="<?php echo $this->get_field_name('sub_id'); ?>" type="text" value="<?php echo esc_attr( strip_tags( $sub_id ) ); ?>" />
            <small><?php _e( 'This will return if a Favourite or Vote has been set with this same sub_id for the Image.', 'catnip' ); ?></small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('image_id'); ?>"><?php _e( 'Image ID:', 'catnip' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image_id'); ?>" name="<?php echo $this->get_field_name('image_id'); ?>" type="text" value="<?php echo esc_attr( strip_tags( $image_id ) ); ?>" />
            <small><?php _e( 'Unique id of the Image to return. Will only ever return one Image.', 'catnip' ); ?></small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'results_per_page' ); ?>"><?php _e( 'How Many Images?', 'catnip' ); ?></label>
            <select name="<?php echo $this->get_field_name( 'results_per_page' ); ?>" id="<?php echo $this->get_field_id( 'results_per_page' ); ?>">
            <?php for ( $i = 1; $i <= 100; $i++ ) { ?>
                <option value="<?php echo $i; ?>" <?php selected( $results_per_page, $i ); ?>><?php echo $i; ?></option>
            <?php } ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Image Type:', 'catnip' ); ?></label>
            <select name="<?php echo $this->get_field_name( 'type' ); ?>" id="<?php echo $this->get_field_id( 'type' ); ?>">
                <option value="" <?php selected( $type, '' ); ?>><?php _e( 'All Image Types', 'catnip' ); ?></option>
                <option value="jpg" <?php selected( $type, 'jpg' ); ?>><?php _e( 'JPGs Only', 'catnip' ); ?></option>
                <option value="png" <?php selected( $type, 'png' ); ?>><?php _e( 'PNGs Only', 'catnip' ); ?></option>
                <option value="gif" <?php selected( $type, 'gif' ); ?>><?php _e( 'GIFs Only', 'catnip' ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:', 'catnip' ); ?></label>
            <select name="<?php echo $this->get_field_name( 'category' ); ?>" id="<?php echo $this->get_field_id( 'category' ); ?>">
                <option value="" <?php selected( $category, '' ); ?>><?php _e( 'All Image Categories', 'catnip' ); ?></option>
                <option value="hats" <?php selected( $category, 'hats' ); ?>><?php _e( 'Hats Only', 'hats' ); ?></option>
                <option value="space" <?php selected( $category, 'space' ); ?>><?php _e( 'Space Only', 'catnip' ); ?></option>
                <option value="boxes" <?php selected( $category, 'boxes' ); ?>><?php _e( 'Boxes Only', 'catnip' ); ?></option>
                <option value="sunglasses" <?php selected( $category, 'sunglasses' ); ?>><?php _e( 'Sunglasses Only', 'catnip' ); ?></option>
                <option value="ties" <?php selected( $category, 'ties' ); ?>><?php _e( 'Ties Only', 'catnip' ); ?></option>
            </select>        
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Image Size:', 'catnip' ); ?></label>
            <select name="<?php echo $this->get_field_name( 'size' ); ?>" id="<?php echo $this->get_field_id( 'size' ); ?>">
                <option value="small" <?php selected( $size, 'small' ); ?>><?php _e( 'Small Images (250x)', 'hats' ); ?></option>
                <option value="med" <?php selected( $size, 'med' ); ?>><?php _e( 'Medium Images (500x)', 'catnip' ); ?></option>
                <option value="full" <?php selected( $size, 'full' ); ?>><?php _e( 'Full Images (original size)', 'catnip' ); ?></option>
            </select>        
        </p>
        
      	<?php
    
	}

}