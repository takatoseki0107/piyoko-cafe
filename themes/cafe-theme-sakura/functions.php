<?php
/**
 * ぴよこcafe 桜（子テーマ）
 */

/**
 * 親テーマと子テーマのCSSを読み込む
 */
function piyoko_sakura_enqueue_styles() {
	// 親テーマのCSS（すでに親の functions.php で読み込み済み）
	// 子テーマの style.css を、その後に読み込む
	wp_enqueue_style(
		'piyoko-sakura-style',
		get_stylesheet_uri(),
		array( 'piyoko-style' ),
		filemtime( get_stylesheet_directory() . '/style.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'piyoko_sakura_enqueue_styles', 20 );