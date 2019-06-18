<!doctype html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/assets/css/reset.css">
    <link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Mono:400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php bloginfo('template_directory') ?>/assets/css/swiper.min.css">
</head>

<body>
    <div class="first_block">

        <div class="first_block__container">
            <div class="first_block__nav">
            <?php wp_nav_menu( array(
            	'theme_location'  => '',
            	'menu'            => '',
            	'container'       => 'ul',
            	'container_class' => '',
            	'container_id'    => '',
            	'menu_class'      => '',
            	'menu_id'         => '',
            	'echo'            => true,
            	'fallback_cb'     => '',
            	'before'          => '',
            	'after'           => '',
            	'link_before'     => '',
            	'link_after'      => '',
            	'items_wrap'      => '<ul id = "%1$s" class = "%2$s">%3$s</ul>',
            	'depth'           => 0,
            	'walker'          => '',
            ) ); ?>
            </div>

            <img src="<?php bloginfo('template_directory') ?>/assets/img/logo/e-motion_logo.png" alt="logo" class="first_block__logo">
            <div class="first_block__contact">

                <div class="first_block__contact__adres">
                    <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_Telegram_4490637.png" alt="map">
                    <p class="adress">
                        г. Санкт-Петербург, м. Фрунзенская,
                    </p>
                    <p class="street">
                        ул. Смоленская, д. 9, оф. 216
                    </p>
                </div>

                <div class="first_block__contact__tel">
                    <div class="social_mobile">
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/Telegram.png" alt="telegram">
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/icons8-whatsapp-filled-100.png" alt="whatsapp">
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/yandex_di.png" alt="yandex">
                        <p>+7 (912) 318-79-90</p>
                    </div>
                    <div class="call_mobile">
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_phone_1608790.png" alt="tel">
                        <p>+7 (812) 318-79-90</p>
                    </div>
                </div>

                <div class="first_block__contact__social_button">
                    <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_01_111032.png" alt="">
                    <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_vk_312653.png" alt="">
                    <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_instagram_2639840.png" alt="">
                    <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_twitter_246540.png" alt="">
                    <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/480px-Yandex_Zen_Logo.png" alt="">
                    <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/Aura.png" alt="">
                </div>
            </div>
        </div>
        <h1 class="first_block__title">
            Мы решаем ваши задачи <br> <span>в программировании</span>
        </h1>

        <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/mouse_scroll.png" alt="mouse_scroll" class="first_block__mouse_scroll">
        <div class="separator"></div>
    </div>
    <div class="second_block">
        <h1 class="second_block__title">
            Преимущества компании <span>E-motion</span>
        </h1>

        <div class="years">
            <span>10</span>
            <span>1530</span>
            <span>706</span>
            <span>9999</span>
        </div>

        <div class="advantage_title">
            <p>лет на <br> рынке</p>
            <p>положительных <br> отзывов</p>
            <p>успешных <br> проектов</p>
            <p>реализованных <br> задач</p>
        </div>

        <div class="branching">
            <p class="branching__title">
                В наши задачи входит:
            </p>

            <div class="branching__value">
                <p>Вывод Вашего бизнеса на новый уровень</p>
                <p>Написание понятного кода, легко обслуживаемого, <br>
                    без починок и поддержания</p>
                <p>Выполнение в срок</p>
            </div>
        </div>
    </div>
    <div class="third_block">
        <h1>
            Возможности компании <br>
            <span>«E-motion»:</span>
        </h1>

        <div class="third_block__fork">
            <p class="third_block__fork__title">
                Заголовок 1
            </p>
            <div class="third_block__fork__container">
                <p class="third_block__fork__value">Реализация задач любой сложности "под ключ"</p>
                <p class="third_block__fork__value">Проектирование и реализация архитектуры веб-приложений</p>
                <p class="third_block__fork__value">Создание конверсионного сайта под ключ</p>
                <p class="third_block__fork__value">Создание продающего лэндинга</p>
            </div>
        </div>

        <div class="third_block__fork">
            <p class="third_block__fork__title">
                Заголовок 2
            </p>
            <div class="third_block__fork__container">
                <p class="third_block__fork__value">Доработка функционала сайтов и веб-приложений</p>
                <p class="third_block__fork__value">Исправление ошибок на сайтах</p>
                <p class="third_block__fork__value">Технические работы по SEO</p>
                <p class="third_block__fork__value">Технический аудит сайта</p>
                <p class="third_block__fork__value">Улучшение показателей скорости загрузки страниц</p>

            </div>
        </div>
        <div class="third_block__fork">
            <p class="third_block__fork__title">
                Заголовок 3
            </p>
            <div class="third_block__fork__container">
                <p class="third_block__fork__value">Написание модулей для CMS</p>
                <p class="third_block__fork__value third_block__fork__value__margin_pixelperfect">Создание шаблонов для
                    CMS по дизайн-макету</p>
                <p class="third_block__fork__value third_block__fork__value__pixelperfect">Кросс-браузерная адаптивная
                    верстка по технологии <br> PixelPerfect (100% соответствие макету)</p>
                <p class="third_block__fork__value">Кросс-браузерная адаптивная верстка по BEM</p>
                <p class="third_block__fork__value">Написание различных ботов</p>
            </div>
        </div>
        <div class="third_block__contact_block">
            <p>Нужно что-то ещё?</p>
            <button>свяжитесь с нами</button>
        </div>
    </div>
    <div class="fourth_block">
        <h1>Рассчитайте свой проект <br><span>в несколько кликов</span></h1>
        <div class="fourth_block__container">
            <div class="fourth_block__main">
                <div class="task_question">
                    <p class="level">Уровень первый:</p>
                    <p class="question">Есть ли у Вас ТехЗадание?</p>
                    <div class="answer">
                        <div class="yes"><img src="<?php bloginfo('template_directory') ?>/assets/img/image/yes_active.png" alt="yes"><span>Да</span></div>
                        <div class="no"><img src="<?php bloginfo('template_directory') ?>/assets/img/image/no_disable.png" alt="yes"><span>Нет</span></div>
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
                    <p class="first_line">Вы тратите время и силы,</p>
                    <p class="second_line">чтобы быть <br>
                        такими,</p>
                    <p class="second_line__second_phrase">как все?</p>
                    <p class="third_line">мы - на то, чтобы вы</p>
                    <p class="fourth_line">были собой</p>
                    <p class="fives_line">затраты <br>одинаковые</p>
                    <p class="fives_line__second_phrase">результат разный</p>
                </div>
            </div>

        </div>
    </div>
    <div class="five_block">
        <h1>Широкий стек <span>технологий</span></h1>
        <div class="five_block__content">
            <div class="skills">
                <p class="skills__title">Языки программирования<span>:</span></p>
                <p class="skills__property">PHP, Python, Ruby, Javascript</p>
            </div>
            <div class="skills">
                <p class="skills__title">Языки разметки<span>:</span> </p>
                <p class="skills__property">HTML5 + CSS3</p>
            </div>
            <div class="skills">
                <p class="skills__title">СУБД<span>:</span></p>
                <p class="skills__property">MySQL, PostgreSQL</p>
            </div>
            <div class="skills">
                <p class="skills__title">CMS<span>:</span> </p>
                <p class="skills__property">
                    Wordpress, 1C-Bitrix, Joomla!, <br>
                    OpenCart/OcStore, <br>
                    ModX(Revo/Evo), UMI.CMS </p>
            </div>
            <div class="skills">
                <p class="skills__title">Фреймворки<span>:</span></p>
                <p class="skills__property">
                    Yii2 (PHP), Rails (Ruby), <br>
                    Django (Python), <br>
                    Symfony 3 (PHP)</p>
            </div>
            <div class="skills">
                <p class="skills__title">Системы контроля версий<span>:</span> </p>
                <p class="skills__property">Git, SVN</p>
            </div>
        </div>
    </div>
    <div class="six_block">
        <h1>наше <span>портфолио</span></h1>
        <!-- вставить swiperslider -->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">

                    <div class="main_case">
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/image/logo_mini.png" alt="" class="logo_mini">
                        <h2>Разработка сайта</h2>
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/image/mockup.png" alt="" class="mockup">
                        <button>подробнее</button>
                    </div>
                </div>
                <div class="swiper-slide">

                    <div class="main_case">
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/image/logo_mini.png" alt="" class="logo_mini">
                        <h2>Разработка сайта</h2>
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/image/mockup.png" alt="" class="mockup">
                        <button>подробнее</button>
                    </div>
                </div>
                <div class="swiper-slide">

                    <div class="main_case">
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/image/logo_mini.png" alt="" class="logo_mini">
                        <h2>Разработка сайта</h2>
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/image/mockup.png" alt="" class="mockup">
                        <button>подробнее</button>
                    </div>
                </div>
                <div class="swiper-slide">

                    <div class="main_case">
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/image/logo_mini.png" alt="" class="logo_mini">
                        <h2>Разработка сайта</h2>
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/image/mockup.png" alt="" class="mockup">
                        <button>подробнее</button>
                    </div>
                </div>
                <div class="swiper-slide">

                    <div class="main_case">
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/image/logo_mini.png" alt="" class="logo_mini">
                        <h2>Разработка сайта</h2>
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/image/mockup.png" alt="" class="mockup">
                        <button>подробнее</button>
                    </div>
                </div>
                <div class="swiper-slide">

                    <div class="main_case">
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/image/logo_mini.png" alt="" class="logo_mini">
                        <h2>Разработка сайта</h2>
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/image/mockup.png" alt="" class="mockup">
                        <button>подробнее</button>
                    </div>
                </div>
                <div class="swiper-slide">

                    <div class="main_case">
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/image/logo_mini.png" alt="" class="logo_mini">
                        <h2>Разработка сайта</h2>
                        <img src="<?php bloginfo('template_directory') ?>/assets/img/image/mockup.png" alt="" class="mockup">
                        <button>подробнее</button>
                    </div>
                </div>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination">
                привет
            </div>
        </div>

    </div>
    <div class="seven_block">
        <h1>наши <span>клиенты</span></h1>
        <div class="client_logo">
            <img src="<?php bloginfo('template_directory') ?>/assets/img/logo/logo_vityaz.png" alt="">
            <img src="<?php bloginfo('template_directory') ?>/assets/img/logo/logo_dmc.png" alt="">
            <img src="<?php bloginfo('template_directory') ?>/assets/img/logo/logo_sloboda.png" alt="">
            <img src="<?php bloginfo('template_directory') ?>/assets/img/logo/Gn.png" alt="">
            <img src="<?php bloginfo('template_directory') ?>/assets/img/logo/logo_chili.png" alt="">
            <img src="<?php bloginfo('template_directory') ?>/assets/img/logo/mdfight.png" alt="">
            <img src="<?php bloginfo('template_directory') ?>/assets/img/logo/logo_au.png" alt="">
            <img src="<?php bloginfo('template_directory') ?>/assets/img/logo/logo_aer.png" alt="">
        </div>
    </div>
    <div class="eight_block">
        <h1>Остались вопросы?</h1>
        <form action="#" class="contact_form">
            <div class="flex_container">
                <label for="name">Имя</label>
                <input id="name" type="text" placeholder="Полное имя">
            </div>
            <div class="flex_container">
                <label for="tel">телефон</label>
                <input id="tel" type="text" placeholder="+7 912 652 14 74">
            </div>
            <div class="flex_container">
                <label for="mess">сообщение</label>
                <input id="mess" type="text" placeholder="Чем можем помочь?">
            </div>
            <button>отправить</button>
        </form>
    </div>
    <div class="footer">
        <div class="footer__container">
            <div class="footer__contact">
                <h2>контакты для связи</h2>
                <p>+7 (812) 318-79-90</p>
                <p>+7 (912) 318-79-90</p>
            </div>
            <img src="<?php bloginfo('template_directory') ?>/assets/img/logo/e-motion_logo.png" alt="logo">
            <div class="footer__adres">
                <h2>наш офис</h2>
                <p>г. Санкт-Петербург, м. Фрунзенская, <br>
                    ул. Смоленская, д. 9, оф. 216
                </p>
            </div>
        </div>
        <div class="footer__social_button">
            <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_01_111032.png" alt="">
            <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_vk_312653.png" alt="">
            <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_instagram_2639840.png" alt="">
            <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/iconfinder_twitter_246540.png" alt="">
            <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/480px-Yandex_Zen_Logo.png" alt="">
            <img src="<?php bloginfo('template_directory') ?>/assets/img/icons/Aura.png" alt="">
        </div>
    </div>
    <script src="<?php bloginfo('template_directory') ?>/assets/script/swiper.min.js"></script>

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
                depth: 100,
                modifier: 1,
                slideShadows: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },

        });
    </script>
</body>

</html>