<div class="centerside table">
    <div class="inner">
        <h2 class="tt"><?php the_title(); ?></h2>
        <div class="pic">
            <img src="<?php the_post_thumbnail_url( $size ); ?>" width="500">
        </div>
        <div class="info"><?php the_field( 'page__subtitle' ); ?></div>
    </div>
</div>