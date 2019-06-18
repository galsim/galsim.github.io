<?php get_header(); ?>
<h1 class="entry-title"><?php 
if ( is_day() ) { printf( __( 'Архив за день: %s', 'blankslate' ), get_the_time( get_option( 'date_format' ) ) ); }
elseif ( is_month() ) { printf( __( 'Архив за месяц: %s', 'blankslate' ), get_the_time( 'F Y' ) ); }
elseif ( is_year() ) { printf( __( 'Архив за год: %s', 'blankslate' ), get_the_time( 'Y' ) ); }
else { _e( 'Записи', 'blankslate' ); }
?></h1>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php the_title(); ?>
<?php the_content(); ?>
<?php endwhile; endif; ?>
<?php global $wp_query; if ( $wp_query->max_num_pages > 1 ) { ?>
    <nav id="nav-below" class="navigation" role="navigation">
        <div class="nav-previous"><?php next_posts_link(sprintf( __( '%s назад', 'blankslate' ), '<span class="meta-nav">&larr;</span>' ) ) ?></div>
        <div class="nav-next"><?php previous_posts_link(sprintf( __( 'вперед %s', 'blankslate' ), '<span class="meta-nav">&rarr;</span>' ) ) ?></div>
    </nav>
<?php } ?>
<?php get_footer(); ?>