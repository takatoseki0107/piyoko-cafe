<?php
/**
 * ぴよこcafe テーマの機能定義
 */

/**
 * CSS・JSの読み込み
 */
function piyoko_enqueue_assets() {
	wp_enqueue_style(
		'piyoko-style',                      // 識別名（ハンドル）
		get_stylesheet_uri(),                // style.css のURL
		array(),                             // 依存する他のCSS（なし）
		wp_get_theme()->get( 'Version' )     // バージョン
	);
}
add_action( 'wp_enqueue_scripts', 'piyoko_enqueue_assets' );

/**
 * テーマがサポートする機能
 */
function piyoko_setup() {
	add_theme_support( 'title-tag' );        // <title>を自動出力
	add_theme_support( 'post-thumbnails' );  // アイキャッチ画像を有効化
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'piyoko_setup' );