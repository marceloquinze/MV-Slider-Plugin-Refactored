<?php 

namespace MV_Slider\shortcodes;

use MV_Slider\settings\MV_Slider_Options;

if( ! class_exists('MV_Slider_Shortcode')){
    class MV_Slider_Shortcode{

        /**
         * Constructor
         * @return void
         * @since 1.0.0
         * @access public
         */
        public function __construct(){
            add_shortcode( 'mv_slider', array( $this, 'add_shortcode' ) );
        }

        /**
         * Add shortcode
         * @param array $atts
         * @param string $content
         * @param string $tag
         * @return string
         * @since 1.0.0
         * @access public
         */
        public function add_shortcode( $atts = array(), $content = null, $tag = '' ){

            // Normalize attribute keys, lowercase
            $atts = array_change_key_case( (array) $atts, CASE_LOWER );

            // Override default attributes with user attributes
            extract( shortcode_atts(
                array(
                    'id' => '',
                    'orderby' => 'date'
                ),
                $atts,
                $tag
            ));

            // If the slider ID is not empty, convert it to an array
            if( !empty( $id ) ){
                $id = array_map( 'absint', explode( ',', $id ) );
            }

            // Start output buffering
            ob_start();
                // Getting slider options
                $options = MV_Slider_Options::get_instance();
                $title = $options->get('mv_slider_title');
                $style = $options->get('mv_slider_style');
                $bullets = $options->get( 'mv_slider_bullets' ) == 1 ? true : false;

                // Enqueue scripts and styles
                wp_enqueue_script( 'mv-slider-main-jq' );
                wp_enqueue_style( 'mv-slider-main-css' );
                wp_enqueue_style( 'mv-slider-style-css' );
                wp_enqueue_script( 'mv-slider-options-js', MV_SLIDER_URL . 'src/assets/vendor/flexslider/flexslider.js', array( 'jquery' ), MV_SLIDER_VERSION, true );
                
                // Bullets are injected via JS so we need to localize the script
                wp_localize_script( 'mv-slider-options-js', 'SLIDER_OPTIONS', array(
                    'controlNav' => $bullets
                    ) );

                // Finally, render the slider
                require( MV_SLIDER_PATH . 'src/views/mv-slider_shortcode.php' );

            // End output buffering and return its contents
            return ob_get_clean();
        }
        
    }
}
