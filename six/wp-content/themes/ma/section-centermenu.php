<div class="centermenu">
    <div class="inner">
        <div class="pic">
            <img src="<?php the_post_thumbnail_url(); ?>">
        </div>
        <div class="tt">
            <?php
            if (!isset($_GET['selected_menu'])) {
                echo "<script>location.href = '?selected_menu=main_menu';</script>";
                $_GET['selected_menu'] = 'main_menu';
            }
            $subcat_name = $_GET['selected_menu'];
            $cat_name = get_term_by('slug', $subcat_name, 'product_cat');
            ?>
            <h3><?php echo $cat_name->name; ?></h3>
        </div>
        <table>
            <tbody>
            <?php
            $subcat_selected_slug = $_GET['selected_menu'];
            $cat_final = get_term_by('slug', $subcat_selected_slug, 'product_cat');



            $cur_subcats_final =  get_term_children( $cat_final->term_id, 'product_cat' );

//            print_r($cur_subcats_final);
            foreach ($cur_subcats_final as $cur_subcat_final) {
                $scat_f = get_term_by('term_id', $cur_subcat_final, 'product_cat');

                ?>
                <tr>
                    <td class="cat__name" colspan="2">
                        <h3><?php echo $scat_f->name; ?></h3>
                    </td>
                </tr>
                <?php
                $args = array(
                    'post_type'             => 'product',
                    'post_status'           => 'publish',
                    'ignore_sticky_posts'   => 1,
                    'posts_per_page'        => -1,
                    'orderby'               => 'menu_order',
                    'order'                 => 'ASC',
                    'tax_query'             => array(
                        array(
                            'taxonomy'      => 'product_cat',
                            'field' => 'term_id', //This is optional, as it defaults to 'term_id'
                            'terms'         => $scat_f->term_id,
                            'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
                        ),
                        array(
                            'taxonomy'      => 'product_visibility',
                            'field'         => 'slug',
                            'terms'         => 'exclude-from-catalog', // Possibly 'exclude-from-search' too
                            'operator'      => 'NOT IN'
                        )
                    )
                );
                $products_ = new WP_Query($args);
                while ($products_->have_posts()) {
                    $products_->the_post();
                    ?>
                    <tr>
                        <td class="service__available">
                            <?php if ( get_field( 'choise' ) == 1 ) {?>
                                <s class="choise"></s><?php
                            } ?>
                        </td>
                        <td>
                            <p>
                                <?php if ( get_field( 'delivery' ) == 1 ) {?>
                                    <a href="<?php echo get_permalink($products_->post->id)?>"><?php the_title(); ?></a>
                                <?php } else {
                                    the_title();
                                } ?>
                            </p>
                        </td>
                        <td class="p">
                            <?php echo $regular_price = get_post_meta( get_the_ID(), '_regular_price', true); ?><span class="rub"></span>
                        </td>
                        <td class="service__available">
                            <?php
                            $_product = new WC_Product( get_the_ID() );
                            if ( get_field( 'delivery' ) == 1 ) {
                                echo "<a rel=\"nofollow\" href=\"".$_product->add_to_cart_url()."\" data-quantity=\"1\" data-product_id=\"".get_the_ID()."\" class=\"button delivery product_type_simple add_to_cart_button ajax_add_to_cart\"></a>";
                            }?>
                        </td>
                    </tr>
                    <?php
                }
                wp_reset_query();
            }
            ?>
            </tbody>
        </table>
    </div>
</div>