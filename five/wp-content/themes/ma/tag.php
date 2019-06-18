<?php get_header(); ?>
<h1 class="entry-title"><?php _e( 'Архив по тегуs: ', 'blankslate' ); ?><?php single_tag_title(); ?></h1>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <?php the_title(); ?>
    <?php the_content(); ?>
<?php endwhile; endif; ?>
<?php get_footer(); ?>