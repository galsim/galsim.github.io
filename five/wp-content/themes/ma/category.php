<?php get_header(); ?>
<h1 class="entry-title">Категория: <?php single_cat_title(); ?></h1>
<?php if ( '' != category_description() ) echo apply_filters( 'archive_meta', '<div class="archive-meta">' . category_description() . '</div>' ); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <?php the_title(); ?>
    <?php the_content(); ?>
<?php endwhile; endif; ?>
<?php get_footer(); ?>