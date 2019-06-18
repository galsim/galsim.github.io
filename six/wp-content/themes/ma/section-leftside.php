<div class="page">
    <div class="leftside">
        <div class="inner">
            <div class="inner-td">
                <div class="logo" ><?php echo get_custom_logo(/* $blog_id */);?></div>
                <ul class="menu">
                    <?php wp_nav_menu(); ?>
                </ul>
                <div class="booking">
                    <p>Забронировать<br>
                        <?php the_field( 'booking__service','option' ); ?>/<a href="/zabronirovat-banket/">банкет</a>
                    </p>
                </div>
                <div class="phone">
                    <a href="tel:<?php the_field( 'phone', 'option'); ?>">
                        <?php the_field( 'phone', 'option' ); ?>
                    </a>
                </div>
                <div class="address">
                    <a target="_blank" href="<?php the_field( 'coordinates', 'option' ); ?>"><?php the_field( 'adress', 'option' ); ?></a><br>
                    <strong>Бесплатная парковка</strong>
                </div>
                <div class="socials leftside__socials">
                    <a target="_blank" href="<?php the_field( 'tripadvisor', 'option' ); ?>" class="tp"><s></s></a>
                    <a target="_blank" href="<?php the_field( 'facebook', 'option' ); ?>" class="fb"><s></s></a>
                    <a target="_blank" href="<?php the_field( 'instagram', 'option' ); ?>" class="in"><s></s></a>
                </div>
            </div>
        </div>
    </div>

