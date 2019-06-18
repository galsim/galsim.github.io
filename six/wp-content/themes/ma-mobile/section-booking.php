<div class="booking">
    <p>Забронировать<br>
        <?php the_field( 'booking__service','option' ); ?> / <a href="/zabronirovat-banket/">банкет</a>
    </p>
</div>
<div class="phone">
    <a href="tel:<?php the_field( 'phone', 'option'); ?>"><?php the_field( 'phone', 'option'); ?></a>
</div>
<div class="address">
    <a href="<?php the_field( 'coordinates', 'option' ); ?>"><?php the_field( 'adress', 'option' ); ?></a><br>
    <strong>Бесплатная парковка</strong>
</div>