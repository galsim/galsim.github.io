<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'toaster_emtnv' );

/** Имя пользователя MySQL */
define( 'DB_USER', 'toaster_emtnv' );

/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', 'a48d1347f' );

/** Имя сервера MySQL */
define( 'DB_HOST', 'emtnv.creative-services.ru' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'cxFa9EO}DE, 5JJ|<BQ&:fwjx)C=Xmo#bb~4>p+flc2eghPL%i%TB<7xmbu)^HNa' );
define( 'SECURE_AUTH_KEY',  'Wfj3;IhR<OyU.5:Rt,jN-BVZm<*VR6a*t:so>,}3RyjvB,?7)6SpNKkN3]XrO|R2' );
define( 'LOGGED_IN_KEY',    'ivnY#pw;J0F#O5:`slLQzhR[(uNqiAH]FO;d~RucsH{Nwb|3N_7Mn7(|9IF|fP)6' );
define( 'NONCE_KEY',        'Gw$S+0I+^rS@L0FeWP8(-n,c{Z+Xn/00k5XouZ.`|O})hRwH.kEA}iPBAeq,IkxJ' );
define( 'AUTH_SALT',        'sV*NoiqVVG_c<pH#om+q`3Q1-%R:3w:L~%^hAL*A1-}UZ`BhD@Aqw<DF)ji!y~E_' );
define( 'SECURE_AUTH_SALT', 'b}!}FR=`|$Q@hqz:J4H3F>0FV:vP+rOuh))JM{Q,Bb5d83CO<.Eqt&vH)cLzz{;X' );
define( 'LOGGED_IN_SALT',   '5: Yy8y;n&mkJ?cq>jc(bE--}Kjq=Ro!Eh]ILTtqb$;=5foy?6GPNm=uK%8U&W9x' );
define( 'NONCE_SALT',       'fBFL:;8]H>ac+@>CEQ$ZA)3NA kgrp{mfqv{Xyo$Y?rQDLU7]e_)[]3vNY0#BGN{' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once( ABSPATH . 'wp-settings.php' );
