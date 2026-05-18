<?php
if (!defined('ABSPATH')) {
    exit;
}

function reparaya_producto4_setup() {
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    add_theme_support('responsive-embeds');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');

    add_editor_style('assets/css/reparaya-app.css');
}
add_action('after_setup_theme', 'reparaya_producto4_setup');

function reparaya_producto4_assets() {
    $theme_dir = get_stylesheet_directory();
    $theme_uri = get_stylesheet_directory_uri();

    wp_enqueue_style(
        'reparaya-fonts',
        'https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800;900&family=Orbitron:wght@500;700;800;900&family=Rajdhani:wght@400;500;600;700&family=Share+Tech+Mono&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'reparaya-app',
        $theme_uri . '/assets/css/reparaya-app.css',
        array('reparaya-fonts'),
        filemtime($theme_dir . '/assets/css/reparaya-app.css')
    );

    wp_enqueue_script(
        'reparaya-app',
        $theme_uri . '/assets/js/reparaya-app.js',
        array(),
        filemtime($theme_dir . '/assets/js/reparaya-app.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'reparaya_producto4_assets');


/* Limpieza de metadatos técnicos del head público */
add_action('init', function () {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
});

/**
 * Precarga el logo principal para evitar parpadeo al navegar entre páginas.
 */
function reparaya_preload_main_logo() {
    echo '<link rel="preload" as="image" href="' . esc_url( get_template_directory_uri() . '/assets/img/reparaya-logo.png' ) . '" fetchpriority="high">' . "\n";
}
add_action( 'wp_head', 'reparaya_preload_main_logo', 1 );

