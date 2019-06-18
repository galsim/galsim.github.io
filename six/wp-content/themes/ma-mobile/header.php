<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="content-language" content="ru-RU">
    <title><?php echo wp_get_document_title(); ?></title>
    <meta name="description" content="SEO description">
    <meta name="keywords" content="SEO keywords">
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://dev.rakipoplavok.ru/delivery/">
    <meta property="og:title" content="Рачевня Поплавок">
    <meta property="og:description" content="SEO description">
    <meta property="og:image" content="http://dev.rakipoplavok.ru/images/share.jpg">
    <meta property="og:site_name" content="Рачевня Поплавок">
    <meta name="viewport" content="initial-scale=1.0, width=device-width">
    <?php wp_site_icon();?>
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i|Roboto+Condensed:300,400,700|Roboto:300,400,500,700&amp;subset=cyrillic" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body>
    <div class="header">
        <div class="logo">
            <a href="/"></a>
        </div>
        <div class="mn"><s></s><s></s><s></s><s></s></div>
    </div>
    <?php echo get_template_part('section','menupop')?>
    <div class="overlay"></div>
