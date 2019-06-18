<div class="inside overlay">
    <h3 style="text-align: center; color: #FFF;"><?php the_field( 'swiper__title' ); ?></h3>
    <div class="slider">
        <div id="swiper_main" class="swiper">
            <div class="swiper-wrapper">
                <?php if ( have_rows( 'swiper__slider' ) ) : ?>
                    <?php while ( have_rows( 'swiper__slider' ) ) : the_row(); ?>
                        <div class="swiper-slide ">
                            <a href="<?php the_sub_field( 'swiper__link' ); ?>/">
                                <?php $swiper_img = get_sub_field( 'swiper__img' ); ?>
                                <?php if ( $swiper_img ) { ?>
                                    <img src="<?php echo $swiper_img['url']; ?>" alt="<?php echo $swiper_img['alt']; ?>" />
                                <?php } ?>
                            </a>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            <div class="swiper-prev" id="prev_main"></div>
            <div class="swiper-next" id="next_main"></div>
        </div>
        <div class="swiper-pgs swiper-pagination-clickable swiper-pagination-bullets">
            <span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span>
            <span class="swiper-pagination-bullet"></span>
        </div>
        <script>
            var swiper_main = new Swiper('#swiper_main', {
                autoplay: {
                    delay: <?php the_field('slider_interval', 'option'); ?>*1000
                },
                spaceBetween: 0,
                speed: 600,
                loop: true,
                // navigation: {
                //     nextEl: '#next_main',
                //     prevEl: '#prev_main',
                // },
                pagination: {
                    el: '.swiper-pgs',
                    clickable: true
                },
                // navigation: {
                //     nextEl: '#next1',
                //     prevEl: '#prev1',
                // }
            });
        </script>
    </div>
    </div>
</div>