<?php

// enqueue the child theme stylesheet

Function wp_schools_enqueue_scripts() {
wp_register_style( 'childstyle', get_stylesheet_directory_uri() . '/style.css'  );
wp_enqueue_style( 'childstyle' );
}
add_action( 'wp_enqueue_scripts', 'wp_schools_enqueue_scripts', 11);

// disable for posts
add_filter('use_block_editor_for_post', '__return_false', 10);

// disable for post types
add_filter('use_block_editor_for_post_type', '__return_false', 10);

/**
 * Add javascript custom
 */
function gramia_scripts_register() {
    wp_register_script("jquery", "https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js");
    wp_register_script("jquery-core", "https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js");

    //Add js custom on the child theme. get_template_directory_uri just get the url from parent theme, so we need to add the -child below that
    wp_enqueue_script( 'jquery-custom', get_template_directory_uri() . "-child/js/custom.js" );
}
add_action( 'wp_enqueue_scripts', 'gramia_scripts_register' );
/**
 * Create new menu on the right
 */
function register_top_right_navigation() {
    register_nav_menu('top-right-navigation',__( 'Top Right Menu' ));
}
add_action( 'init', 'register_top_right_navigation' );