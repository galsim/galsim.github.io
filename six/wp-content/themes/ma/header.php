<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="content-language" content="ru-RU">
    <title><?php echo wp_get_document_title(); ?></title>
    <meta name="viewport" content="initial-scale=1.0, width=device-width">
    <?php wp_site_icon();?>
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i|Roboto+Condensed:300,400,700|Roboto:300,400,500,700&amp;subset=cyrillic" rel="stylesheet">
    <?php wp_head(); ?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri().'/markup/style.css';?>?v=<?=time();?>">
</head>
<body <?php if (!isset($_GET['testd'])) { echo 'class="hide-delivr"'; } ?>>
<?php echo get_template_part('section', 'leftside')?>
