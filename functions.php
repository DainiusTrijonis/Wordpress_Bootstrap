<?php
/**
 * SubscribeWorld functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package SubscribeWorld
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function subscribeworld_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on SubscribeWorld, use a find and replace
		* to change 'subscribeworld' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'subscribeworld', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'subscribeworld' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'subscribeworld_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'subscribeworld_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function subscribeworld_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'subscribeworld_content_width', 640 );
}
add_action( 'after_setup_theme', 'subscribeworld_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function subscribeworld_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'subscribeworld' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'subscribeworld' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'subscribeworld_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function subscribeworld_scripts() {
	wp_enqueue_style( 'subscribeworld-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'subscribeworld-style', 'rtl', 'replace' );

	wp_enqueue_script( 'subscribeworld-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'subscribeworld_scripts' );

function load_custom() {
	// wp_enqueue_style( 'bootstrap-css', "https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css", array(), '1.0.0', 'all' );
	// wp_enqueue_script( 'jquery', "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js", array('jquery'), '3.3.4', true );
	// wp_enqueue_script( 'bootstrap-js', "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js", array('jquery'), '1.0.0', true );
	wp_enqueue_style( 'main', get_template_directory_uri() . '/css/main.css',false,'1.1','all');
}
add_action('wp_enqueue_scripts', 'load_custom');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}



function subscriptions_create_subscriptions_items(){
    
    //portfolio = subscriptions
    register_post_type('subscriptions', array(
        'labels' => array(
            'name' => __('subscriptions'),
            'singular_name' => __('Subscriptions')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'subscriptions'),
        'show_in_rest' => true,
    ));
}

add_action('init', 'subscriptions_create_subscriptions_items' );

function packages_create_packages_items(){
    
    //portfolio = subscriptions
    register_post_type('packages', array(
        'labels' => array(
            'name' => __('packages'),
            'singular_name' => __('Packages')
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'packages'),
        'show_in_rest' => true,
    ));
}

add_action('init', 'packages_create_packages_items' );
