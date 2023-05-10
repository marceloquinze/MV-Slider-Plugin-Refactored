<?php

if(!defined('WP_UNINSTALL_PLUGIN')){
    die;
}

delete_option( 'mv_slider_options' );

$posts = get_posts(
    array(
        'post_type' => 'mv-slider',
        'number_posts'  => -1,
        'post_status'   => 'any'
    )
);

foreach( $posts as $post ){
    wp_delete_post( $post->ID, true );
}