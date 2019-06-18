<?php // Template Name: Главная

get_header(); ?>

<div class="page">
    <?php get_template_part('section', 'swiper')?>
    <div class="centralmain">
        <a href="/galereya/"><div class="pic" style="background: url('<?php the_post_thumbnail_url(); ?>') no-repeat 50% 50%"></div></a>
        <div class="ins">
            <div class="goto"></div>
        </div>
    </div>
    <div class="hr"></div>
</div>

<?php get_footer(); ?>

