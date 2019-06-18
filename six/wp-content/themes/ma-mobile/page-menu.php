<?php // Template Name: Меню и Бар

get_header(); ?>
    <div class="page">
        <div class="inner inner-menu">
            <h2 class="tt"><?php the_title(); ?></h2>
            <?php $cur_cat = get_field('menu_cat');
            //            print_r($cur_cat);
            $cur_subcats =  get_term_children( $cur_cat->term_id, 'product_cat' );
//                        print_r($cur_subcats);
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
            <ul class="hh">
                <li><s class="delivery"></s> &nbsp; Возможна доставка</li>
                <li><s class="choise"></s> &nbsp; Выбор шеф-повара</li>
            </ul>
        </div>
        <div class="products">


            <div class="pic">
                <img src="<?php the_post_thumbnail_url(); ?>">
            </div>
            <div class="tt"">
            <?php
            $subcat_name = $_GET['selected_menu'];
            $cat_name = get_term_by('slug', $subcat_name, 'product_cat');
            ?>
            <h3><?php echo $cat_name->name; ?></h3>
            </div>

            <?php
            $subcat_selected_slug = $_GET['selected_menu'];
            $cat_final = get_term_by('slug', $subcat_selected_slug, 'product_cat');


            $cur_subcats_final =  get_term_children( $cat_final->term_id, 'product_cat' );

            //            print_r($cur_subcats_final);
            foreach ($cur_subcats_final as $cur_subcat_final) {
                $scat_f = get_term_by('term_id', $cur_subcat_final, 'product_cat');

                ?>
                <h3><?php echo $scat_f->name; ?></h3>
                <?php
                $args = array(
                    'post_type'             => 'product',
                    'post_status'           => 'publish',
                    'ignore_sticky_posts'   => 1,
                    'posts_per_page'        => -1,
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
                while ($products_->have_posts()) { $products_->the_post()
                    ?>
                    <p>
                        <?php if ( get_field( 'choise' ) == 1 ) {?>
                            <s class="choise"></s> &nbsp; <?php
                        } else {} ?>
                        <?php if ( get_field( 'delivery' ) == 1 ) {?>
                            <s class="delivery"></s> &nbsp; <?php
                        } else {} ?>

                        <a href="<?php echo get_permalink($product->post->id)?>"><?php the_title(); ?></a>
                    </p>
                    <strong>
                        <?php echo $regular_price = get_post_meta( get_the_ID(), '_regular_price', true); ?>
                        <span class="rub">руб</span>
                    </strong>
                    <?php
                }
                wp_reset_query();
            }
            ?>
        </div>
        <br>
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
        <div class="hr"></div>
    </div>
<?php get_footer(); ?>