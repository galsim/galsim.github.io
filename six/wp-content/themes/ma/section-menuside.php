<div class="menuside table">
    <div class="inner">
	    <div class="card-block card-menu">
		    <div class="card-block__img">
			    <a href="/korzina"></a>
		    </div>
		    <div class="card-block__price"><?php echo WC()->cart->get_cart_subtotal(); ?></div>
	    </div>
        <div class="inside">
            <?php $cur_cat = get_field('menu_cat');
            //            print_r($cur_cat);
            $cur_subcats =  get_term_children( $cur_cat->term_id, 'product_cat' );
            //            print_r($cur_subcats);
            ?>
            <ul class="menu">
                <?php
                foreach ($cur_subcats as $cur_subcat) {
                    $scat = get_term_by('term_id', $cur_subcat, 'product_cat');
//                    print_r($scat);
                    if ($scat->parent == $cur_cat->term_id) {
                        if (!isset($_GET['selected_menu'])) {
                            header("Location: ?selected_menu=".$scat->slug);
                            die();
                        }
                        ?>
                        <li>
                            <a href="?selected_menu=<?php echo $scat->slug; ?>"><?php echo $scat->name; ?></a>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
            <div class="dnl">
                <?php if ( have_rows( 'pdf__links' ) ) : ?>
                    <?php while ( have_rows( 'pdf__links' ) ) : the_row(); ?>
                        <?php if ( get_sub_field( 'pdf__file' ) ) { ?>
                            <div>
                                <a href="<?php the_sub_field( 'pdf__file' ); ?>"><?php the_sub_field( 'pdf__text' ); ?></a>
                            </div>
                        <?php } ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
            <ul class="hh">
                <li><s class="delivery"></s><span> &nbsp; Возможна доставка</span></li>
                <li><s class="choise"></s><span> &nbsp; Выбор шеф-повара</span></li>
            </ul>
        </div>
    </div>
    <div style="top: 15px;" class="card-block__minicart">
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