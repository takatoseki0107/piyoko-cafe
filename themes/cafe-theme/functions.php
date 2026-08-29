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

/**
 * カスタム投稿タイプ「メニュー」
 */
function piyoko_register_post_types() {
	register_post_type( 'menu', array(
		'label'         => 'メニュー',
		'public'        => true,
		'has_archive'   => true,
		'menu_position' => 5,
		'menu_icon'     => 'dashicons-food',
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'rewrite'       => array( 'slug' => 'menu-item' ),
		'show_in_rest'  => true,
	) );
}
add_action( 'init', 'piyoko_register_post_types' );

/**
 * カスタムタクソノミー「メニューカテゴリー」
 */
function piyoko_register_taxonomies() {
	register_taxonomy( 'menu_category', 'menu', array(
		'label'             => 'メニューカテゴリー',
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => 'menu-category' ),
		'show_in_rest'      => true,
	) );
}
add_action( 'init', 'piyoko_register_taxonomies' );

/**
 * カスタムフィールド「価格」
 */
function piyoko_add_price_metabox() {
	add_meta_box(
		'piyoko_price',              // ID
		'価格',                       // 見出し
		'piyoko_price_metabox_html', // 中身を描画する関数
		'menu',                      // 表示する投稿タイプ
		'side'                       // 表示位置（サイドバー）
	);
}
add_action( 'add_meta_boxes', 'piyoko_add_price_metabox' );

/**
 * 価格入力欄のHTML
 */
function piyoko_price_metabox_html( $post ) {
	// 保存済みの値を取り出す
	$price = get_post_meta( $post->ID, '_piyoko_price', true );

	// なりすまし送信を防ぐための照合用の値
	wp_nonce_field( 'piyoko_save_price', 'piyoko_price_nonce' );
	?>
	<p>
		<label for="piyoko_price_field">税込価格（半角数字のみ）</label><br />
		<input
			type="number"
			id="piyoko_price_field"
			name="piyoko_price_field"
			value="<?php echo esc_attr( $price ); ?>"

			style="width: 100%;"
			placeholder="880"
		/>
	</p>
	<?php
}

/**
 * 価格の保存
 */
function piyoko_save_price( $post_id ) {
	// 自動保存のときは何もしない
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// 正規の送信か確認する
	if ( ! isset( $_POST['piyoko_price_nonce'] )
		|| ! wp_verify_nonce( $_POST['piyoko_price_nonce'], 'piyoko_save_price' ) ) {
		return;
	}

	// 編集権限があるか確認する
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['piyoko_price_field'] ) ) {
		$price = absint( $_POST['piyoko_price_field'] );
		update_post_meta( $post_id, '_piyoko_price', $price );
	}
}

add_action( 'save_post_menu', 'piyoko_save_price' );

/**
 * ブロックエディタにテーマのCSSを読み込む
 */
function piyoko_editor_styles() {
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/style.css' );
}
add_action( 'after_setup_theme', 'piyoko_editor_styles' );
