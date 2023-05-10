<?php

namespace MV_Slider\settings;

class MV_Slider_Options
{
    private static $instance;

    private function __construct()
    {
    }

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new MV_Slider_Options();
        }
        return self::$instance;
    }

    /**
     * Gets an option from the database or returns the default value
     * if it doesn't exist.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     * @since 1.0.0
     */
    public function get( $name, $default = null ){
        return get_option( $name, $default );
    }

    /**
     * Checks if an option exists in the database.
     * @param string $name
     * @return bool
     * @since 1.0.0
     */
    public function has( $name ){
        return null !== $this->get( $name );
    }

    /**
     * Remove an option from the database.
     * @param string $name
     * @return void 
     * @since 1.0.0
     */
    public function remove( $name ){
        delete_option( $name );
    }

    /**
     * Sets an option in the database.
     * @param string $name
     * @param mixed $value
     * @return void
     * @since 1.0.0
     */
    public function set( $name, $value ){
        update_option( $name, $value );
    }


    /**
     * Validate empty fields
     * @param string $new_value         New value of the field to be saved.
     * @param string $field_name        ID of the field.
     * @return string                   New value of the field to be saved.
     * @since 1.0.0
     */
    public function validate_empty( $new_value, $field_name ){
        $old_value = $this->get( $field_name );
        if( empty( $new_value ) ){
            switch ( $field_name ){
                case 'mv_slider_title':
                    add_settings_error( 'mv_slider_options', 'mv_slider_message', esc_html__( 'The title field can not be left empty', 'mv-slider' ), 'error' );
                    $new_value = esc_html__( 'Please, type some text', 'mv-slider' ); 
            }
        }
        return $new_value;
    }
}