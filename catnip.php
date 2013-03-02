<?php
/*
Plugin Name: catnip
Plugin URI: http://wptraining.lewayotte.com/catnip
Description: A simple WordPress plugin that shows a random picture of a cat from The Cat Api - http://thecatapi.com/ - as an example of the power of WordPress for WordPress Training - http://wptraining.lewayotte.com/.
Author: Lew Ayotte @ lewayotte.com
Version: 1.0.1
Author URI: http://lewayotte.com/about/
Tags: cats, lolcats, the cat api, widget, shortcode, sidebar, images, funny, wptraining, example code
*/

/**
 * @package catnip
 * @since 1.0.0
 */
if ( ! class_exists( 'catnip' ) ) {
	
	/**
	 * This class defines and returns the shortcodes
	 * @since 1.0.0
	 */
	class catnip {
		
		function catnip() {
				
			add_action('admin_menu', array( $this, 'add_catnip_admin_page' ) );
			add_action( 'admin_init', array( $this, 'catnip_admin_init' ) );
			
		}
		
		function add_catnip_admin_page() {
			
			add_options_page( __( 'Catnip Options', 'catnip' ), 'Catnip', 'manage_options', 'catnip_options', array( $this, 'catnip_options_page' ) );
			
		}
		
		function catnip_options_page() {
			
			?>
			
			<div class="wrap">
				<div class="icon32" id="icon-options-general"><br></div>
				<h2><?php _e( 'Catnip Options', 'catnip' ); ?></h2>
				<form action="options.php" method="post">
				<?php settings_fields( 'catnip_plugin_options' ); ?>
				<?php do_settings_sections( 'catnip_options' ); ?>
				
				<p class="submit">
				<input name="submit" class="button-primary" type="submit" value="<?php esc_attr_e( 'Save Changes', 'catnip' ); ?>" />
				</p>
				</form>
			</div>
			
			<?php
		
		}
		
		function catnip_admin_init() {
			
			register_setting( 'catnip_plugin_options', 'catnip_plugin_options', array( $this, 'catnip_options_validate' ) );
			add_settings_section( 'catnip_plugin_main', __( 'Primary Settings', 'catnip' ), array( $this, 'main_settings_output' ), 'catnip_options' );
			add_settings_field( 'the_cat_api_key', __( 'The Cat API - API Key', 'catnip' ), array( $this, 'the_cat_api_key_input' ), 'catnip_options', 'catnip_plugin_main' );
			
			
		}
		
		function main_settings_output() {
		
			_e( '<p>The Cat API Settings.</p>', 'catnip' );
			
		}
		
		function the_cat_api_key_input() {
		
			$options = get_option( 'catnip_plugin_options' );
			echo '<input id="the_cat_api_key" class="regular-text" name="catnip_plugin_options[api_key]" type="text" value="' . $options['api_key'] . '" />';
			echo '<p class="description">Optional: <a href="http://thecatapi.com/api-key-registration.html" target="_blank">Get an API Key.</a></p>';
			
		}
		
		function catnip_options_validate( $input ) {
			
			$options = get_option( 'catnip_plugin_options' );
		
			$options['api_key'] = trim( $input['api_key'] );
				
			return $options;
			
		}
		
	}
	
}

include_once( plugin_dir_path( __FILE__ ) . '/the-cat-api.php' );
include_once( plugin_dir_path( __FILE__ ) . '/catnip-shortcodes.php' );
include_once( plugin_dir_path( __FILE__ ) . '/catnip-widgets.php' );

$catnip 			= new catnip();
$catnip_shortcodes	= new catnip_shortcodes();