<?php get_header(); ?>
<?php if ( have_posts() ) : ?>
<h1 class="entry-title"><?php printf( __( 'Поиск: %s', 'blankslate' ), get_search_query() ); ?></h1>
<?php while ( have_posts() ) : the_post(); ?>
        <?php the_title(); ?>
        <?php the_content(); ?>
<?php endwhile; ?>
    <?php global $wp_query; if ( $wp_query->max_num_pages > 1 ) { ?>
        <nav id="nav-below" class="navigation" role="navigation">
            <div class="nav-previous"><?php next_posts_link(sprintf( __( '%s назад', 'blankslate' ), '<span class="meta-nav">&larr;</span>' ) ) ?></div>
            <div class="nav-next"><?php previous_posts_link(sprintf( __( 'вперед %s', 'blankslate' ), '<span class="meta-nav">&rarr;</span>' ) ) ?></div>
        </nav>
    <?php } ?>
<?php else : ?>
    Ничего не найдено
<?php endif; ?>
<?php get_footer(); ?>