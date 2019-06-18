<?php // Template Name: Контакты

get_header(); ?>
    <div class="page">
        <div class="inner">
            <h2 class="tt"><?php the_title(); ?></h2>
            <div class="map" style="height: 284px;"><iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A4ef950da660087a85537cdf1306417ec2f8508949e6c675a09f7f958e8fa416f&amp;source=constructor" width="100%" height="400" frameborder="0"></iframe></div>
            <div class="info"><?php the_field( 'page__subtitle' ); ?></div>
            <div class="spx"><p><strong><a href="<?php the_field( 'coordinates', 'option' ); ?>">Открыть в картах</a></strong></p></div>
        </div>
        <div class="anounce"></div>
        <div class="contact-boxes">
            <h3>Бронирование столов</h3>

            <p><a href="tel:<?php the_field( 'phone_1', 'option' ); ?>"><?php the_field( 'phone_1', 'option' ); ?></a><br>
                <button type="button" class="smart-reserve-button" data-restaurant-id="453872">забронировать стол</button></p>

            <h3>Бронирование банкетов</h3>

            <p><a href="tel:<?php the_field( 'phone_2', 'option' ); ?>"><?php the_field( 'phone_2', 'option' ); ?></a><br>
                <a href="/booking/" class="btn">забронировать банкет</a></p>

            <h3>Доставка</h3>

            <p><a href="tel:<?php the_field( 'phone_3', 'option' ); ?>"><?php the_field( 'phone_3', 'option' ); ?></a></p>

            <h3>Email</h3>

            <p><a class="mail" href="mailto:<?php the_field( 'e-mail', 'option' ); ?>"><?php the_field( 'e-mail', 'option' ); ?></a></p>
        </div>
    </div>
<?php get_footer(); ?>