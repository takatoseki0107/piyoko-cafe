<?php
/**
 * ぴよこcafe テーマの機能
 */

/**
 * CSSとフォントの読み込み
 */
function piyoko_enqueue_assets() {
	// Google Fonts
	wp_enqueue_style(
		'piyoko-fonts',
		'https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@500;700&family=Noto+Sans+JP:wght@400;500;700&display=swap',
		array(),
		null
	);

	// テーマ本体のCSS
	wp_enqueue_style(
		'piyoko-style',
		get_theme_file_uri( '/assets/css/style.css' ),
		array( 'piyoko-fonts' ),
		filemtime( get_theme_file_path( '/assets/css/style.css' ) )
	);

	// JavaScript
	wp_enqueue_script(
		'piyoko-script',
		get_theme_file_uri( '/assets/js/main.js' ),
		array(),
		filemtime( get_theme_file_path( '/assets/js/main.js' ) ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'piyoko_enqueue_assets' );

/**
 * テーマがサポートする機能
 */
function piyoko_setup() {
	add_theme_support( 'title-tag' );

	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

	// ナビゲーションメニューの位置を登録
	register_nav_menus( array(
		'primary' => 'ヘッダーナビ',
	) );
}
add_action( 'after_setup_theme', 'piyoko_setup' );