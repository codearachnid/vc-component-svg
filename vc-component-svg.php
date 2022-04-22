<?php
/*
 * @link              https://codearachnid.pro
 * @since             1.0.0
 * @package           Vc_Component_Svg
 *
 * @wordpress-plugin
 * Plugin Name:       SVG for WPBakery
 * Plugin URI:        https://codearachnid.pro
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            codearachnid @codearachnid
 * Author URI:        https://codearachnid.pro
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       vc-component-svg
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'VC_COMPONENT_SVG_VERSION', '1.0.0' );

if ( ! class_exists( 'VcComponentSvg' ) ) {

	class VcComponentSvg extends WPBakeryShortCode {

		function __construct() {
			add_action( 'init', array( $this, 'create_shortcode' ), 999 );            
			add_shortcode( 'vc_component_svg', array( $this, 'render_shortcode' ) );

		}        

		public function create_shortcode() {
			// Stop all if VC is not enabled
			if ( !defined( 'WPB_VC_VERSION' ) ) {
				return;
			}        

			// Map blockquote with vc_map()
			vc_map( array(
				'name'          => __('SVG Element', 'vccomponentsvg'),
				'base'          => 'vc_component_svg',
				'description'  	=> __( '', 'vccomponentsvg' ),
				'category'      => __( 'Content', 'vccomponentsvg'),                
				'params' => array(
	
					array(
						"type" => "attach_image",
						"holder" => "div",
						"class" => "",                     
						"heading" => __( "SVG Path", 'vccomponentsvg' ),
						"param_name" => "svg_id",
						"value" => '',
						"description" => __( "Enter valid SVG Path.", 'vccomponentsvg' )
					),
					
					array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",                     
						"heading" => __( "Element ID", 'vccomponentsvg' ),
						"param_name" => "element_id",
						"value" => '',
						"description" => __( "Enter element ID (Note: make sure it is unique and valid according to ", 'vccomponentsvg' ) . '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">w3c specification</a> ).'
					) , 
					
					array(
						"type" => "textfield",
						"holder" => "div",
						"class" => "",                     
						"heading" => __( "Width", 'vccomponentsvg' ),
						"param_name" => "svg_width",
						"value" => '',
						"description" => __( "Enter width.", 'vccomponentsvg' )
					) ,
					
					array(
						"type" => "dropdown",
						"holder" => "div",
						"class" => "",                     
						"heading" => __( "SVG alignment", 'vccomponentsvg' ),
						'value'         => array(
							__( 'Default', 'vccomponentsvg' )    => 'default',
							__( 'Left', 'vccomponentsvg' )    => 'left',
							__( 'Right', 'vccomponentsvg' )  => 'right',
							__( 'Center', 'vccomponentsvg' )   => 'center'
						  ),
						"param_name" => "svg_alignment",
						"description" => __( "Select image alignment.", 'vccomponentsvg' )
					) ,          
				),
			));             

		}

		public function render_shortcode( $atts, $content, $tag ) {
			$atts = (shortcode_atts(array(
				'svg_id'   => '',
				'svg_width' => '',
				'svg_alignment' => '',
				'extra_class'       => '',
				'element_id'        => ''
			), $atts));

// look for valid xml heading tag
/* <?xml version="1.0" encoding="utf-8"?> */


			$svg_object  = wp_get_attachment_image_src($atts['svg_id'], 'full');
			$extra_class        = esc_attr($atts['extra_class']);
			$element_id         = esc_attr($atts['element_id']);


			$output = '';
			
			if( !empty($svg_object[0]) ){
				if( !empty($atts['svg_width'])){
					$width = sprintf(' width="%s"', $atts['svg_width'] );
				}
				
				$svg_to_img = sprintf('<img src="%s" %s />', $svg_object[0], $width );	
				
				if( !empty($atts['svg_alignment'])){
					$svg_alignment_css = '';
					switch ($atts['svg_alignment']) {
						case 'left':
						case 'right':
							break;
						case 'center':
							$svg_alignment_css = 'display: flex; justify-content: center; align-items: center;';
							break;
					}
					$svg_ouput = sprintf('<div style="%s">%s</div>', $svg_alignment_css, $svg_to_img);	
				} else {
					$svg_ouput = $svg_to_img;
				}
				
				$output = $svg_ouput;
			}

			return $output;                  

		}

	}

	new VcComponentSvg();

}
