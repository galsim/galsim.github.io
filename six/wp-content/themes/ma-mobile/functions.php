<?php

add_action( 'after_setup_theme', 'blankslate_setup' );

function blankslate_setup()
{
    add_theme_support( 'title-tag' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo');
    add_theme_support( 'menus');

}

//add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

add_action( 'wp_enqueue_scripts', 'blankslate_load_scripts' );

function blankslate_load_scripts()
{
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'mamain', get_bloginfo('template_url').'/main.js' );
}

add_action( 'comment_form_before', 'blankslate_enqueue_comment_reply_script' );

function blankslate_enqueue_comment_reply_script()
{
    if ( get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
}


add_action( 'wp_enqueue_scripts', 'jquery_script' );

function jquery_script() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', get_template_directory_uri().'/markup/js/jquery.js', false, null, true );
    wp_enqueue_script( 'jquery' );
}

add_action( 'wp_enqueue_scripts', 'crawfish_scripts' );

function crawfish_scripts() {
    wp_enqueue_style( 'mobile', get_template_directory_uri().'/markup/mobile.css' );
    wp_enqueue_script( 'script', get_template_directory_uri().'/markup/js/script.js', array('jquery'));
    wp_enqueue_script( 'swiper', get_template_directory_uri().'/markup/js/swiper.min.js', array('jquery'), '1.0.0', false );
}


add_filter( 'the_title', 'blankslate_title' );

function blankslate_title( $title ) {
    if ( $title == '' ) {
        return '&rarr;';
    } else {
        return $title;
    }
}

add_filter( 'wp_title', 'blankslate_filter_wp_title' );

function blankslate_filter_wp_title( $title )
{
    return $title . esc_attr( get_bloginfo( 'name' ) );
}

add_filter( 'get_comments_number', 'blankslate_comments_number' );

function blankslate_comments_number( $count )
{
    if ( !is_admin() ) {
        global $id;
        $comments_by_type = &separate_comments( get_comments( 'status=approve&post_id=' . $id ) );
        return count( $comments_by_type['comment'] );
    } else {
        return $count;
    }
}

if( function_exists('acf_add_options_page') ) {

    acf_add_options_page();

}