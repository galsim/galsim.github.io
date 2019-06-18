<?php // Template Name: Доставка

get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <div class="centerside full">
        <div class="inner">
	        <div class="card-block card-delivery" >
		        <div class="card-block__img">
			        <a href="/korzina"></a>
		        </div>
		        <div class="card-block__price"><?php echo WC()->cart->get_cart_subtotal(); ?></div>
                <div class="card-block__minicart">


                            <div class="flex-wrapper">
                                <div class="minicart-headr">Корзина <span>&times;</span></div>
                                <table class="min-cart-tabulu"><?php
                                    foreach ( WC()->cart->get_cart() as $item ) {
//                        print_r(get_the_title($item['product_id']));
//                        print_r(PHP_EOL);
                                        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $item['product_id'] ), 'single-post-thumbnail' );
//                        print_r($image['src']);
//                        print_r(PHP_EOL);
//                        print_r('/?upd_qtyty=Y&key='.$item['key'].'&new_qty=');
//                        print_r(PHP_EOL);
//                        print_r($item['quantity']);
//                        print_r(PHP_EOL);
//                        print_r($item['data']->price);
//                        print_r(PHP_EOL);
//                        print_r($item['line_subtotal']);
//                        print_r(PHP_EOL);
//                        print_r($item['key']);
//                        print_r(PHP_EOL);
                                        ?>
                                        <tr class="mini-prod-row" data-prod_id="<?php echo $item['product_id']; ?>" data-prod_key="<?php echo $item['key']; ?>">
                                            <td class="mini-s-img">
                                                <a href="#" class="prod-times">&times;</a>
                                            </td>
                                            <td class="mini-img">
                                                <span class="prod-imga" style="background-image: url('<?php echo $image['src']; ?>');"></span>
                                            </td>
                                            <td class="mini-text">
                                                <div class="prod-titul"><?php echo get_the_title($item['product_id']); ?></div>
                                                <div class="prod-actns">
                                                    <div class="prod-actsz">
                                                        <span class="mins">&ndash;</span>
                                                        <span class="qtx"><?php echo $item['quantity']; ?></span>
                                                        <span class="plux">+</span>
                                                    </div>
                                                    <div class="prod-prix">
                                                        <?php echo $item['line_subtotal']; ?> руб.
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?></table>
                            </div>
                    <div class="cart-times"></div>
                    <a href="http://rplv.creative-services.ru/oformlenie-zakaza/" class="checkout-button">Оформить заказ <?php echo WC()->cart->get_cart_subtotal(); ?></a>
                </div>
	        </div>
            <div class="product__block">
                <?php
                $args = array(
                    'post_per_page' => -1,
                    'post_type'  => 'product',
                    'meta_query' => array(
                        array(
                            'key'     => 'delivery',
                            'value'   => 1,
                        )
                    )
                );


                $query = new WP_Query( $args );

                if ( $query->have_posts() ) {
                    while ( $query->have_posts() ) {
                        $query->the_post();
                        $prod__item = new WC_Product( get_the_ID() );
                        ?>
                        <div class="product__bar">
                            <div class="product__bar__img" style="background: url('<?echo get_the_post_thumbnail_url($prod__item->ID);?>') 0 0 no-repeat; background-size: cover">
                                <a href="<?php echo get_permalink($products_->post->id)?>"></a>
                            </div>
                            <div class="product__bar__title">
                                <a href="<?php echo get_permalink($products_->post->id)?>"><?php the_title()?></a>
                            </div>
                            <div class="product__bar__price">
                                <?php echo $prod__item ->get_price_html();?>
                                <a rel="nofollow" href="<?php echo $prod__item->add_to_cart_url(); ?>" data-quantity="1" data-product_id="<?php the_ID(); ?>" class="button delivery product_type_simple add_to_cart_button ajax_add_to_cart"></a>
                            </div>
                        </div>

                        <?php
                    }
                }
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
	
<?php endwhile; endif; ?>
<?php get_footer(); ?>