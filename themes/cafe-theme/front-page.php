<?php
/**
 * トップページ
 */
get_header();
?>

<section class="hero">
	<div class="container hero__inner">
		<p class="hero__lead">山の古民家で、たまご料理を。</p>
		<h1 class="hero__title"><?php bloginfo( 'name' ); ?></h1>
		<p class="hero__text">
			朝どれのたまごと、木のぬくもり。<br />
			ゆっくり流れる時間の中で、ほっとひと息つきませんか。
		</p>
		<a class="button" href="#menu">メニューを見る</a>
	</div>
</section>

<section class="concept" id="concept">
	<div class="container">
		<p class="section__label">CONCEPT</p>
		<h2 class="section__title">山のてっぺんの、小さなたまご屋</h2>
		<p class="concept__text">
			築80年の古民家を、少しずつ手を入れながら改装しました。<br />
			すぐ近くの養鶏場から届く朝どれのたまごを使って、<br />
			シンプルだけど記憶に残る料理をお出ししています。
		</p>
		<p class="concept__text">
			木の床がきしむ音と、鳥の声。<br />
			ここでは、ちょっとだけ時間がゆっくり流れます。
		</p>

		<img
			class="concept__image"
			src="<?php echo esc_url( get_theme_file_uri( '/assets/images/exterior.jpg' ) ); ?>"
			alt="山の中にあるぴよこcafeの外観"
			loading="lazy"
		/>
	</div>
</section>

<section class="menu" id="menu">
	<div class="container">
		<p class="section__label">MENU</p>
		<h2 class="section__title">おすすめメニュー</h2>

		<?php
		$menu_query = new WP_Query( array(
			'post_type'      => 'menu',
			'posts_per_page' => 3,
			'orderby'        => 'date',
			'order'          => 'ASC',
		) );
		?>

		<?php if ( $menu_query->have_posts() ) : ?>
			<ul class="menu__list">
				<?php while ( $menu_query->have_posts() ) : $menu_query->the_post(); ?>
					<?php $price = get_post_meta( get_the_ID(), '_piyoko_price', true ); ?>
					<li class="card">
						<a href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'medium_large', array(
									'class'   => 'card__image',
									'loading' => 'lazy',
								) ); ?>
							<?php else : ?>
								<img
									class="card__image"
									src="<?php echo esc_url( get_theme_file_uri( '/assets/images/no-image.jpg' ) ); ?>"
									alt=""
									loading="lazy"
								/>

							<?php endif; ?>

							<div class="card__body">
								<h3 class="card__title"><?php the_title(); ?></h3>
								<?php if ( get_the_excerpt() ) : ?>
									<p class="card__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
								<?php endif; ?>
								<?php if ( $price ) : ?>
									<p class="card__price">
										&yen;<?php echo esc_html( number_format( $price ) ); ?>
									</p>
								<?php endif; ?>
							</div>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>

			<div class="menu__more">
				<a class="button" href="<?php echo esc_url( get_post_type_archive_link( 'menu' ) ); ?>">
					メニューをすべて見る
				</a>
			</div>
		<?php else : ?>
			<p class="concept__text">メニューはまだ登録されていません。</p>
		<?php endif; ?>

		<?php wp_reset_postdata(); ?>
	</div>
</section>

<section class="news" id="news">
	<div class="container">
		<p class="section__label">NEWS</p>
		<h2 class="section__title">お知らせ</h2>

		<?php
		$news_query = new WP_Query( array(
			'post_type'      => 'post',
			'posts_per_page' => 3,
		) );
		?>

		<?php if ( $news_query->have_posts() ) : ?>
			<ul class="news__list">
				<?php while ( $news_query->have_posts() ) : $news_query->the_post(); ?>
					<li class="news__item">
						<a href="<?php the_permalink(); ?>">
							<time class="news__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
								<?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?>
							</time>
							<span class="news__title"><?php the_title(); ?></span>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>

			<div class="news__more">
				<a class="button" href="<?php echo esc_url( home_url( '/news/' ) ); ?>">お知らせをすべて見る</a>
			</div>
		<?php else : ?>

			<p class="concept__text">お知らせはまだありません。</p>
		<?php endif; ?>

		<?php wp_reset_postdata(); ?>
	</div>
</section>

<section class="access" id="access">
	<div class="container">
		<p class="section__label">ACCESS</p>
		<h2 class="section__title">アクセス</h2>

		<dl class="access__list">
			<div class="access__row">
				<dt>住所</dt>
				<dd>〒000-0000<br />神奈川県横浜市○○区山の上1-2-3</dd>
			</div>
			<div class="access__row">
				<dt>営業時間</dt>
				<dd>9:00 - 17:00（ラストオーダー 16:30）</dd>
			</div>
			<div class="access__row">
				<dt>定休日</dt>
				<dd>火曜日・水曜日</dd>
			</div>
			<div class="access__row">
				<dt>アクセス</dt>
				<dd>
					○○駅からバスで15分「山の上」下車、徒歩5分<br />駐車場あり（5台）
				</dd>
			</div>
		</dl>

	</div>
</section>

<?php get_footer(); ?>