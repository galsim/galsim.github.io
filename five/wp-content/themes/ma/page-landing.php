<?php // Template Name: Landing

get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="first_block">

        <div class="first_block__container">

            <div class="first_block__nav">
                <?php if (have_rows('main_menu')) : ?>
                    <ul>
                        <?php while (have_rows('main_menu')) : the_row(); ?>
                            <li>
                                <a href="<?php the_sub_field('adres_a'); ?>"><?php the_sub_field('text_a'); ?></a></li>
                        <?php endwhile; ?>
                    </ul>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>


            <?php $logo = get_field('logo'); ?>
            <?php if ($logo) { ?>
                <div style="background-image: url('<?php echo $logo['url']; ?>')" alt="<?php echo $logo['alt']; ?>"
                     class="first_block__logo"></div>
            <?php } ?>
            <div class="first_block__contact">

                <div class="first_block__contact__adres">
                    <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_Telegram_4490637.png"
                         alt="map">
                    <?php the_field('adres', 'option'); ?>
                </div>

                <div class="first_block__contact__tel">
                    <div class="social_mobile">
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/Telegram.png" alt="telegram">
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/icons8-whatsapp-filled-100.png"
                             alt="whatsapp">
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/yandex_di.png" alt="yandex">
                        <p><?php the_field('telephone_for_social', 'option'); ?></p>
                    </div>
                    <div class="call_mobile">
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_phone_1608790.png"
                             alt="tel">
                        <p><?php the_field('telephone_for_number', 'option'); ?></p>
                    </div>
                </div>

                <div class="first_block__contact__social_button">
                    <div class="first_block__contact__social_button_img" style="background-image: url('<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_01_111032.png')" alt=""></div>
                    <div class="first_block__contact__social_button_img" style="background-image: url('<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_vk_312653.png')" alt=""></div>
                    <div class="first_block__contact__social_button_img" style="background-image: url('<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_instagram_2639840.png')" alt=""></div>
                    <div class="first_block__contact__social_button_img" style="background-image: url('<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_twitter_246540.png')" alt=""></div>
                    <div class="first_block__contact__social_button_img" style="background-image: url('<?php bloginfo('template_directory') ?>/assets/img/icons/480px-Yandex_Zen_Logo.png')" alt=""></div>
                    <div class="first_block__contact__social_button_img" style="background-image: url('<?php bloginfo('template_directory') ?>/assets/img/icons/Aura.png')" alt=""></div>
                </div>
            </div>
        </div>
        <h1 class="first_block__title">
            <?php the_field('slag_screen_one'); ?>
        </h1>

        <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/mouse_scroll.png" alt="mouse_scroll"
             class="first_block__mouse_scroll">
        <div class="separator"></div>
    </div>
    <div class="second_block">
        <h1 class="second_block__title">
            <?php the_field('second_block_title'); ?>
        </h1>

        <div class="years">
            <?php if (have_rows('first_fork')) : ?>
                <?php while (have_rows('first_fork')) : the_row(); ?>

                    <span><?php the_sub_field('value'); ?></span>
                <?php endwhile; ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>

        <div class="advantage_title">
            <?php if (have_rows('first_fork')) : ?>
                <?php while (have_rows('first_fork')) : the_row(); ?>
                    <p><?php the_sub_field('title'); ?></p>
                <?php endwhile; ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>

        <div class="branching">
            <p class="branching__title">
                <?php the_field('title_second_fork'); ?>
            </p>

            <div class="branching__value">
                <?php if (have_rows('second_fork_value')) : ?>
                    <?php while (have_rows('second_fork_value')) : the_row(); ?>
                        <p><?php the_sub_field('branch_title'); ?></p>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="third_block">
        <h1>
            <?php the_field('third_block_title'); ?>
        </h1>

        <div class="third_block__fork">
            <p class="third_block__fork__title">
                <?php the_field('3st_first_fork_title'); ?>

            </p>
            <div class="third_block__fork__container">
                <?php if (have_rows('3st_first_fork_value')) : ?>
                    <?php while (have_rows('3st_first_fork_value')) : the_row(); ?>
                        <p class="third_block__fork__value"> <?php the_sub_field('branch_title'); ?></p>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="third_block__fork">
            <p class="third_block__fork__title">
                <?php the_field('3st_second_fork_title'); ?>
            </p>
            <div class="third_block__fork__container">
                <?php if (have_rows('3st_second_fork_value')) : ?>
                    <?php while (have_rows('3st_second_fork_value')) : the_row(); ?>
                        <p class="third_block__fork__value "> <?php the_sub_field('branch_title'); ?></p>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>

            </div>
        </div>
        <div class="third_block__fork">
            <p class="third_block__fork__title">
                <?php the_field('3st_third_block_title'); ?>
            </p>
            <div class="third_block__fork__container">
                <?php if (have_rows('3st_third_fork_value')) : ?>
                    <?php while (have_rows('3st_third_fork_value')) : the_row(); ?>
                        <p class="third_block__fork__value"><?php the_sub_field('branch_title'); ?></p>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="third_block__contact_block">
            <p><?php the_field('question_third_block'); ?></p>
            <button type="submit"><?php the_field('button_third_block'); ?></button>
        </div>
    </div>
    <div class="fourth_block">
        <h1><?php the_field('fourth_block_title'); ?></h1>
        <div class="fourth_block__container">
            <div class="fourth_block__main">
                <div class="task_question">
                    <p class="level"><?php the_field('level_title'); ?></p>
                    <p class="question"><?php the_field('tetris_question'); ?></p>
                    <div class="answer">
                        <div class="yes"><img
                                    src="<?php bloginfo('template_directory') ?>/assets/img/image/yes_active.png"
                                    alt="yes"><span><?php the_field('tetris_answer_true'); ?></span></div>
                        <div class="no"><img
                                    src="<?php bloginfo('template_directory') ?>/assets/img/image/no_disable.png"
                                    alt="yes"><span><?php the_field('tetris_answer_false'); ?></span></div>
                    </div>
                    <button>Вперед</button>
                </div>
                <div class="tetris">

                </div>
                <div class="scale">
                    <div class="scale__separator"></div>
                    <div class="scale__separator"></div>
                    <div class="scale__separator"></div>
                    <div class="scale__separator"></div>
                    <div class="scale__separator"></div>
                    <div class="scale__separator"></div>
                </div>
                <div class="motivation">
                    <p class="first_line">
                        <?php the_field('first_line_motivation'); ?>
                    </p>
                    <p class="second_line">
                        <?php the_field('second_line_motivation'); ?>
                    </p>
                    <p class="second_line__second_phrase">
                        <?php the_field('second_line_second_phrase'); ?>
                    </p>
                    <p class="third_line">
                        <?php the_field('third_line_motivation'); ?>
                    </p>
                    <p class="fourth_line">
                        <?php the_field('fourth_line_motivation'); ?>
                    </p>
                    <p class="fives_line">
                        <?php the_field('five_line_motivation'); ?>
                    </p>
                    <p class="fives_line__second_phrase">
                        <?php the_field('five_line_second_phrase'); ?>
                    </p>
                </div>
            </div>

        </div>
    </div>
    <div class="five_block">
        <h1>
            <?php the_field('название_четвертого_блока'); ?>
        </h1>
        <div class="five_block__content">

            <?php if (have_rows('stack_techlogies')) : ?>
                <?php while (have_rows('stack_techlogies')) : the_row(); ?>
                    <div class="skills">
                        <p class="skills__title"> <?php the_sub_field('stack_techlogies_title'); ?> <span>:</span></p>
                        <p class="skills__property"><?php the_sub_field('stack_techlogies_value'); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="six_block">
        <h1><?php the_field('six_block_title'); ?></h1>
        <!-- вставить swiperslider -->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php if (have_rows('page_in_slider')) : ?>

                    <?php while (have_rows('page_in_slider')) : the_row(); ?>
                        <div class="swiper-slide">
                            <div class="main_case">
                                <?php $mini_logo_in_slider = get_sub_field('mini_logo_in_slider'); ?>
                                <?php if ($mini_logo_in_slider) { ?>
                                    <div style="background-image: url('<?php echo $mini_logo_in_slider['url']; ?>')"
                                         class="logo_mini"></div>
                                <?php } ?>
                                <h2><?php the_sub_field('service_title'); ?></h2>

                                <?php $img_in_slider = get_sub_field('img_in_slider'); ?>
                                <?php if ($img_in_slider) { ?>
                                    <div style="background-image: url('<?php echo $img_in_slider['url']; ?>')"
                                         alt="<?php echo $img_in_slider['alt']; ?>" class="mockup"></div>
                                <?php } ?>

                                <button><?php the_sub_field('text_in_button'); ?></button>
                            </div>
                        </div>
                    <?php endwhile; ?>

                <?php else : ?>
                    <?php // no rows found ?>
                <?php endif; ?>


            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination">
                привет
            </div>
        </div>

    </div>
    <div class="seven_block">
        <h1><?php the_field('seven_block_title'); ?></h1>
        <div class="client_logo">
            <?php if (have_rows('companies_logo')) : ?>
                <?php while (have_rows('companies_logo')) : the_row(); ?>
                    <?php $img_company = get_sub_field('img_company'); ?>
                    <?php if ($img_company) { ?>
                        <img src="<?php echo $img_company['url']; ?>" alt="<?php echo $img_company['alt']; ?>"/>
                    <?php } ?>
                <?php endwhile; ?>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="eight_block">
        <h1><?php the_field('eight_block_title'); ?></h1>
        <form action="#" class="contact_form">
            <div class="flex_container">
                <label for="name"><?php the_field('title_input_first'); ?></label>
                <input id="name" type="text" placeholder="<?php the_field('title_in_input_first'); ?>">
            </div>
            <div class="flex_container">
                <label for="tel"><?php the_field('title_input_second'); ?></label>
                <input id="tel" type="text" placeholder="<?php the_field('title_in_input_second'); ?>">
            </div>
            <div class="flex_container">
                <label for="mess"><?php the_field('title_input_third'); ?></label>
                <input id="mess" type="text" placeholder="<?php the_field('title_in_input_third'); ?>">
            </div>
            <button type="submit"><?php the_field('text_button_eight_block'); ?></button>
        </form>
    </div>
    <div class="footer">
        <div class="footer__container">
            <div class="footer__contact">
                <h2><?php the_field('block_with_telephone'); ?></h2>
                <p><?php the_field('telephone_for_number', 'option'); ?></p>
                <p><?php the_field('telephone_for_social', 'option'); ?></p>
            </div>
            <?php $logo = get_field('logo'); ?>
            <?php if ($logo) { ?>
                <div style="background-image: url('<?php echo $logo['url']; ?>')" alt="<?php echo $logo['alt']; ?>"
                     class="footer__logo"></div>
            <?php } ?>
            <div class="footer__adres">
                <h2><?php the_field('title_block_with_adres'); ?></h2>
                <?php the_field('adres', 'option'); ?>
            </div>
        </div>
        <div class="footer__social_button">
            <div class="footer__social_button_img" style="background-image: url('<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_01_111032.png')" alt=""></div>
            <div class="footer__social_button_img" style="background-image: url('<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_vk_312653.png')" alt=""></div>
            <div class="footer__social_button_img" style="background-image: url('<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_instagram_2639840.png')" alt=""></div>
            <div class="footer__social_button_img" style="background-image: url('<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_twitter_246540.png')" alt=""></div>
            <div class="footer__social_button_img" style="background-image: url('<?php bloginfo('template_directory') ?>/assets/img/icons/480px-Yandex_Zen_Logo.png')" alt=""></div>
            <div class="footer__social_button_img" style="background-image: url('<?php bloginfo('template_directory') ?>/assets/img/icons/Aura.png')" alt=""></div>
        </div>
    </div>
    <script src="<?php bloginfo('template_directory') ?>/assets/script/swiper.min.js"></script>
    <script src="<?php bloginfo('template_directory') ?>./dist/js/swiper.min.js"></script>
    <script src="<?php bloginfo('template_directory') ?>/assets/script/script.js"></script>
    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper('.swiper-container', {
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            spaceBetween: 40,
            initialSlide: 3,
            slidesPerView: 3,
            effect: 'coverflow',
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: 'auto',
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                depth: 0,
                modifier: 1,
                slideShadows: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },

        });
    </script>

<?php endwhile; endif; ?>
<?php get_footer(); ?>