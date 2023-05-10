<?php 

if( ! class_exists( 'MV_Slider_Settings' )){
    class MV_Slider_Settings{

        /**
         * Options object
         * @var MV_Slider_Options
         */
        private $options;

        /**
         * Constructor
         * @param MV_Slider_Options $options
         * @return void
         * @since 1.0.0
         * @access public
         */
        public function __construct( MV_Slider_Options $options ){
            $this->options = $options;
            $this->register();
        }

        /**
         * Register hooks
         * @return void
         * @since 1.0.0
         * @access public
         */
        public function register( ){
            add_action( 'admin_init', array( $this, 'configure' ) );
            add_action( 'admin_menu', array( $this, 'add_admin_page' ) );
        }

        /**
         * Add admin page
         * @return void
         * @since 1.0.0
         * @access public
         */
        public function add_admin_page(){
            add_menu_page(
                esc_html__( 'MV Slider Options', 'mv-slider' ),
                'MV Slider',
                'install_plugins',
                'mv_slider_admin',
                array( $this, 'render' ),
                'dashicons-images-alt2'
            );

            add_submenu_page(
                'mv_slider_admin',
                esc_html__( 'Manage Slides', 'mv-slider' ),
                esc_html__( 'Manage Slides', 'mv-slider' ),
                'install_plugins',
                'edit.php?post_type=mv-slider',
                null,
                null
            );

            add_submenu_page(
                'mv_slider_admin',
                esc_html__( 'Add New Slide', 'mv-slider' ),
                esc_html__( 'Add New Slide', 'mv-slider' ),
                'install_plugins',
                'post-new.php?post_type=mv-slider',
                null,
                null
            );
        }

        /**
         * Register settings and fields
         * @return void
         * @since 1.0.0
         * @access public
         */
        public function configure(){
        
            // List of fields to be registered
            register_setting( 'mv_slider_options', 'mv_slider_title', 
                [ 'sanitize_callback' => fn( $new_value ) => $this->options->validate_empty( $new_value, 'mv_slider_title' ) ] 
            );
            register_setting( 'mv_slider_options', 'mv_slider_bullets', 
                [ 'sanitize_callback' => 'sanitize_text_field' ] 
            );
            register_setting( 'mv_slider_options', 'mv_slider_style', 
                [ 'sanitize_callback' => 'sanitize_text_field' ] 
            );

            // List of sections to be rendered
            add_settings_section(
                'mv_slider_main_section',
                esc_html__( 'How does it work?', 'mv-slider' ),
                null,
                'mv_slider_page1'
            );

            add_settings_section(
                'mv_slider_second_section',
                esc_html__( 'Other Plugin Options', 'mv-slider' ),
                null,
                'mv_slider_page2'
            );

            // List of fields to be rendered
            add_settings_field(
                'mv_slider_shortcode',
                esc_html__( 'Shortcode', 'mv-slider' ),
                array( $this, 'mv_slider_shortcode_callback' ),
                'mv_slider_page1',
                'mv_slider_main_section'
            );

            add_settings_field(
                'mv_slider_title',
                esc_html__( 'Slider Title', 'mv-slider' ),
                array( $this, 'mv_slider_title_callback' ),
                'mv_slider_page2',
                'mv_slider_second_section'
            );

            add_settings_field(
                'mv_slider_bullets',
                esc_html__( 'Display Bullets', 'mv-slider' ),
                array( $this, 'mv_slider_bullets_callback' ),
                'mv_slider_page2',
                'mv_slider_second_section'
            );

            add_settings_field(
                'mv_slider_style',
                esc_html__( 'Slider Style', 'mv-slider' ),
                array( $this, 'mv_slider_style_callback' ),
                'mv_slider_page2',
                'mv_slider_second_section',
                array(
                    'items' => array(
                        'style-1',
                        'style-2'
                    )
                )
                
            );
        }

        /**
         * Render the shortcode field
         * @return void
         * @since 1.0.0
         * @access public
         */
        public function mv_slider_shortcode_callback(){
            ?>
            <span><?php esc_html_e( 'Use the shortcode [mv_slider] to display the slider in any page/post/widget', 'mv-slider' ); ?></span>
            <?php
        }

        /**
         * Render the title field
         * @return void
         * @since 1.0.0
         * @access public
         */
        public function mv_slider_title_callback(){

            $title = '';
            if( $this->options->has( 'mv_slider_title' ) ){
                $title = $this->options->get( 'mv_slider_title' );
            }

            ?>
                <input 
                type="text" 
                name="mv_slider_title" 
                id="mv_slider_title"
                value="<?php echo esc_attr( $title ); ?>"
                >
            <?php
        }
        
        /**
         * Render the bullets field
         * @return void
         * @since 1.0.0
         * @access public
         */
        public function mv_slider_bullets_callback(){

            $bullets = '';
            if( $this->options->has( 'mv_slider_bullets' ) ){
                $bullets = $this->options->get( 'mv_slider_bullets' );
            }

            ?>
                <input 
                    type="checkbox"
                    name="mv_slider_bullets"
                    id="mv_slider_bullets"
                    value="1"
                    <?php 
                        checked( "1", $bullets, true );  
                    ?>
                />
                <label for="mv_slider_bullets"><?php esc_html_e( 'Whether to display bullets or not', 'mv-slider' ); ?></label>
                
            <?php
        }

        /**
         * Render the style field
         * @return void
         * @since 1.0.0
         * @access public
         */
        public function mv_slider_style_callback( $args ){

            $style = '';
            if( $this->options->has( 'mv_slider_style' ) ){
                $style = $this->options->get( 'mv_slider_style' );
            }

            ?>
            <select 
                id="mv_slider_style" 
                name="mv_slider_style">
                <?php 
                foreach( $args['items'] as $item ):
                ?>
                    <option value="<?php echo esc_attr( $item ); ?>" 
                        <?php 
                        selected( $item, $style, true ); 
                        ?>
                    >
                        <?php echo esc_html( ucfirst( str_replace( '-', ' ', $item ) ) ); ?>
                    </option>                
                <?php endforeach; ?>
            </select>
            <?php
        }

        /**
         * Render the settings page
         * @return void
         * @since 1.0.0
         * @access public
         */
        public function render(){
            if( ! current_user_can( 'install_plugins' ) ){
                return;
            }

            if( isset( $_GET['settings-updated'] ) ){
                add_settings_error( 'mv_slider_options', 'mv_slider_message', esc_html__( 'Settings Saved', 'mv-slider' ), 'success' );
            }

            settings_errors( 'mv_slider_options' );

            require( MV_SLIDER_PATH . 'views/settings-page.php' );
        }

    }
}