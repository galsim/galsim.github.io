<?php // Template Name: Главная

    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'page'
    );
    $query = new WP_Query( $args );
    $back_img = array();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $post_object = get_field('background__images');
             if ( have_rows( 'background__images' ) ) :
                 while ( have_rows( 'background__images' ) ) : the_row();
                 $background_img = get_sub_field( 'background__img' );
                if ( $background_img ) {
                 $back_img[] = $background_img['sizes']['large'];
             }
             endwhile;
             endif;
        }
        $random_img = $back_img[array_rand($back_img)];

    }

    wp_reset_postdata();

get_header(); ?>
        <div class="home__back" style="background: url('<?php echo $random_img; ?>') center center no-repeat; background-size: cover"></div>
        <div class="centerside centralmain table">
            <div class="inner">
            </div>
        </div>
        <div class="rightside rightmain table">
            <div class="inner ">
                <?php echo get_template_part('section', 'swiper')?>
            </div>
        </div>
<?php get_footer(); ?>

