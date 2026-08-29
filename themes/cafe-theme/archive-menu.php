<?php
/**
 * メニュー一覧（カスタム投稿タイプ menu のアーカイブ）
 */
get_header();
?>

<section class="page-header">
	<div class="container">
		<p class="section__label">MENU</p>
		<h1 class="page-header__title">メニュー</h1>
		<p class="page-header__text">
			すぐ近くの養鶏場から届く、朝どれのたまごを使っています。
		</p>
	</div>
</section>

<nav class="breadcrumb" aria-label="パンくずリスト">
	<div class="container">
		<ol class="breadcrumb__list">
			<li class="breadcrumb__item">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">ホーム</a>
			</li>
			<li class="breadcrumb__item" aria-current="page">メニュー</li>
		</ol>
	</div>
</nav>

<section class="menu-page">
	<div class="container">

		<?php

		// カテゴリーを一覧で取得
		$categories = get_terms( array(
			'taxonomy'   => 'menu_category',
			'hide_empty' => true,
		) );
		?>

		<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
			<nav class="category-nav" aria-label="カテゴリー">
				<ul class="category-nav__list">
					<?php foreach ( $categories as $category ) : ?>
						<li>
							<a class="category-nav__link" href="#<?php echo esc_attr( $category->slug ); ?>">
								<?php echo esc_html( $category->name ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>

			<?php foreach ( $categories as $category ) : ?>
				<?php
				$menu_query = new WP_Query( array(
					'post_type'      => 'menu',
					'posts_per_page' => -1,
					'orderby'        => 'date',
					'order'          => 'ASC',
					'tax_query'      => array(
						array(
							'taxonomy' => 'menu_category',
							'field'    => 'slug',
							'terms'    => $category->slug,

						),
					),
				) );
				?>

				<?php if ( $menu_query->have_posts() ) : ?>
					<section class="menu-category" id="<?php echo esc_attr( $category->slug ); ?>">
						<h2 class="menu-category__title"><?php echo esc_html( $category->name ); ?></h2>

						<ul class="menu-items">
							<?php while ( $menu_query->have_posts() ) : $menu_query->the_post(); ?>
								<?php $price = get_post_meta( get_the_ID(), '_piyoko_price', true ); ?>
								<li class="menu-item">
									<div class="menu-item__head">
										<h3 class="menu-item__name">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h3>
										<?php if ( $price ) : ?>
											<p class="menu-item__price">
												&yen;<?php echo esc_html( number_format( $price ) ); ?>
											</p>
										<?php endif; ?>
									</div>
									<?php if ( get_the_excerpt() ) : ?>
										<p class="menu-item__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
									<?php endif; ?>
								</li>
							<?php endwhile; ?>
						</ul>
					</section>
				<?php endif; ?>


				<?php wp_reset_postdata(); ?>
			<?php endforeach; ?>
		<?php else : ?>
			<p class="concept__text">メニューはまだ登録されていません。</p>
		<?php endif; ?>

		<p class="menu-note">
			表示価格はすべて税込です。<br />
			仕入れの状況により、メニューが変わることがあります。
		</p>
	</div>
</section>

<?php get_footer(); ?>