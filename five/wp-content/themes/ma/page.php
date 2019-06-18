<?php
if (function_exists('is_woocommerce') && is_woocommerce()) {
    get_template_part('page', 'shop');
    die();
}
get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <?php the_title(); ?>
    <?php the_content(); ?>
<?php endwhile; endif; ?>
<?php get_footer(); ?>