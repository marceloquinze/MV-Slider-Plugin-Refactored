<h3><?php echo ( ! empty ( $content ) ) ? esc_html( $content ) : esc_html( $title ); ?></h3>
<div class="mv-slider flexslider <?php echo ( ! empty ( $style ) ) ? $style : 'style-1' ?>">

    <ul class="slides">
    <?php
    
    $args = array(
        'post_type' => MV_SLIDER_POST_TYPE,
        'post_status'   => 'publish',
        'post__in'  => $id,
        'orderby' => $orderby
    );

    $my_query = new WP_Query( $args );

    if( $my_query->have_posts() ):
        while( $my_query->have_posts() ) : $my_query->the_post();

        $button_text = get_post_meta( get_the_ID(), 'mv_slider_link_text', true );
        $button_url = get_post_meta( get_the_ID(), 'mv_slider_link_url', true );

    ?>
        <li>
        <?php 
        if( has_post_thumbnail() ){
            the_post_thumbnail( 'full', array( 'class' => 'img-fluid' ) );
        }else{
            ?>
            <img src="<?php echo MV_SLIDER_URL; ?>/src/assets/images/default.jpg" class='img-fluid wp-post-image' />
            <?php
        }
         
        ?>
            <div class="mvs-container">
                <div class="slider-details-container">
                    <div class="wrapper">
                        <div class="slider-title">
                            <h2><?php the_title(); ?></h2>
                        </div>
                        <div class="slider-description">
                            <div class="subtitle"><?php the_content(); ?></div>
                            <a class="link" href="<?php echo esc_attr( $button_url ); ?>"><?php echo esc_html( $button_text ); ?></a>
                        </div>
                    </div>
                </div>              
            </div>
        </li>
        <?php
        endwhile; 
        wp_reset_postdata();
    endif; 
    ?>
    </ul>
</div>