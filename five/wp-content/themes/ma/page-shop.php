<?php // Woocommerce Shop Page
get_header(); ?><!-- wcsp -->
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <?php the_title(); ?>
    <?php the_content(); ?>
<?php endwhile; endif; ?>
<?php get_footer(); ?>