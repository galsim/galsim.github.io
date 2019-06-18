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
define('DB_NAME', 'toaster_rplv');

/** Имя пользователя MySQL */
define('DB_USER', 'toaster_rplv');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', 'a48d1347f');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8mb4');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '@f:O;K=);o|lV#:BWE7@)|&g//>e7GX$&3!oIQ2Dk?1S/{k8hL`If2e*<2@/rG#h');
define('SECURE_AUTH_KEY',  'VnMgA^OWB.b:M%4mMUX[.?cd}f4~s>DdA9{$o;Kz&vEroO~l[p5&WCS<R+w`g.]T');
define('LOGGED_IN_KEY',    '-X=Ek$B3cg64oVcQ!%UZRi2nd|,i-^^hzT+HvA7OpEs5MFkl%fhb523Y&Ed$T4Su');
define('NONCE_KEY',        '] -ZIC:13Yu8s[U*su<tJ-H?$m}*2FBY*p7Id&yJS7.=ER)x,c;!+K&-2cW,DOW6');
define('AUTH_SALT',        'C[dL3$YpbK}RAN%fMl%v[-9=x9LlQ(oBvpr/bX@HTH^gabJMhR&nvxa~p$(>j),t');
define('SECURE_AUTH_SALT', 'Qz_ke*fY;O+-~3+^>u+<DDs!BUkb}S.w03U5@k/4tCgGR/]q}-vnGTt-:21JU?K*');
define('LOGGED_IN_SALT',   'Z)~OE qoo.vGr&oLb_Dw8bY$S7hw<nYKzP*7[cOB`Z;L,n@tX^hs$wo=WG6WLbP4');
define('NONCE_SALT',       '$[*y-eD3x`/f{CstDGK8;w<;=AinWXP.td=+@2TNvF/kve}DMArB_$*l,ij([&K2');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

//define( 'WP_CONTENT_URL', '/assets' );
define( 'WP_DEFAULT_THEME', 'ma' );

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
