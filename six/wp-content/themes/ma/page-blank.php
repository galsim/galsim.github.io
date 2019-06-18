<?php // Template Name: Типичная страница

get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <?php echo get_template_part('section', 'centerside')?>
    <div class="rightside">
        <div class="inner">
            <div class="anounce"><?php the_field( 'page__announce' ); ?></div>
            <div class="content"><?php the_content(); ?></div>
            <br><br><br>
        </div>
        <?php echo get_template_part('section', 'socials')?>
    </div>
<?php endwhile; endif; ?>
<?php get_footer(); ?>