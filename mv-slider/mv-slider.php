<?php

/**
 * Plugin Name: MV Slider
 * Plugin URI: https://www.wordpress.org/mv-slider
 * Description: My plugin's description
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Marcelo Vieira
 * Author URI: https://www.codigowp.net
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mv-slider
 * Domain Path: /languages
 */

 /*
MV Slider is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
MV Slider is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with MV Slider. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if( ! defined( 'ABSPATH') ){
    exit;
}

// Load Composer dependencies
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

// Load plugin classes
use MV_Slider\post_types\MV_Slider_Post_Type;
use MV_Slider\settings\MV_Slider_Options;
use MV_Slider\settings\MV_Slider_Settings;
use MV_Slider\shortcodes\MV_Slider_Shortcode;

if( ! class_exists( 'MV_Slider' ) ){
    class MV_Slider{

        /**
         * Constructor
         * @return void
         * @since 1.0.0
         * @access public
         */
        function __construct(){
            // Load plugin text domain and define constants
            $this->define_constants();
            $this->load_textdomain();
            
            // Load plugin dependencies
            // They are being loaded by Composer
            new MV_Slider_Post_Type();
            $options = MV_Slider_Options::get_instance();
            new MV_Slider_Settings( $options );
            new MV_Slider_Shortcode();

            // Register scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ), 999 );
            add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts') );
        }

        /**
         * Define constants
         * @return void
         * @since 1.0.0
         * @access public
         */
        public function define_constants(){
            define( 'MV_SLIDER_PATH', plugin_dir_path( __FILE__ ) );
            define( 'MV_SLIDER_URL', plugin_dir_url( __FILE__ ) );
            define( 'MV_SLIDER_VERSION', '1.0.0' );
            define( 'MV_SLIDER_POST_TYPE', 'mv-slider' );
        }

        /**
         * Activate plugin
         * @return void
         * @since 1.0.0
         * @access public
         */
        public static function activate(){
            update_option( 'rewrite_rules', '' );
        }

        /**
         * Deactivate plugin
         * @return void
         * @since 1.0.0
         * @access public
         */
        public static function deactivate(){
            flush_rewrite_rules();
            unregister_post_type( MV_SLIDER_POST_TYPE );
        }

        /**
         * Uninstall plugin
         * @return void
         * @since 1.0.0
         * @access public
         */
        public static function uninstall(){

            delete_option( 'mv_slider_options' );

            $posts = get_posts(
                array(
                    'post_type' => MV_SLIDER_POST_TYPE,
                    'number_posts'  => -1,
                    'post_status'   => 'any'
                )
            );

            foreach( $posts as $post ){
                wp_delete_post( $post->ID, true );
            }
        }

        /**
         * Load plugin text domain
         * @return void
         * @since 1.0.0
         * @access public
         */
        public function load_textdomain(){
            load_plugin_textdomain(
                'mv-slider',
                false,
                dirname( plugin_basename( __FILE__ ) ) . '/languages/'
            );
        }

        /**
         * Register scripts
         * @return void
         * @since 1.0.0
         * @access public
         */
        public function register_scripts(){
            wp_register_script( 'mv-slider-main-jq', MV_SLIDER_URL . 'src/assets/vendor/flexslider/jquery.flexslider-min.js', array( 'jquery' ), MV_SLIDER_VERSION, true );
            wp_register_style( 'mv-slider-main-css', MV_SLIDER_URL . 'src/assets/vendor/flexslider/flexslider.css', array(), MV_SLIDER_VERSION, 'all' );
            wp_register_style( 'mv-slider-style-css', MV_SLIDER_URL . 'src/assets/css/frontend.css', array(), MV_SLIDER_VERSION, 'all' );
        }

        /**
         * Register admin scripts
         * @return void
         * @since 1.0.0
         * @access public
         */
        public function register_admin_scripts(){
            global $typenow;
            if( $typenow == 'mv-slider'){
                wp_enqueue_style( 'mv-slider-admin', MV_SLIDER_URL . 'assets/css/admin.css' );
            }
        }

    }
}

// Instantiate plugin
if( class_exists( 'MV_Slider' ) ){
    register_activation_hook( __FILE__, array( 'MV_Slider', 'activate' ) );
    register_deactivation_hook( __FILE__, array( 'MV_Slider', 'deactivate' ) );
    register_uninstall_hook( __FILE__, array( 'MV_Slider', 'uninstall' ) );

    $mv_slider = new MV_Slider();
} 
