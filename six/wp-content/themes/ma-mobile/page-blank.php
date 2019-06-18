<?php // Template Name: Типичная страница

get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <div class="page">
        <div class="inner">
            <h2 class="tt"><?php the_title(); ?></h2>
            <div class="pic"><img src="<?php the_post_thumbnail_url( $size ); ?>"></div>
            <div class="info"><?php the_field( 'page__subtitle' ); ?></div>
        </div>
        <div class="anounce"><?php the_field( 'page__announce' ); ?></div>
        <div class="content"><?php the_content(); ?></p>
        </div>
    </div>
<?php endwhile; endif; ?>
<?php get_footer(); ?>