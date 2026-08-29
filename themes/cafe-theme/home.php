<?php
/**
 * 投稿アーカイブ（お知らせ一覧）
 */
get_header();
?>

<section class="page-header">
	<div class="container">
		<p class="section__label">NEWS</p>
		<h1 class="page-header__title">お知らせ</h1>
		<p class="page-header__text">
			季節のメニューや、営業日についてのご案内です。
		</p>
	</div>
</section>

<nav class="breadcrumb" aria-label="パンくずリスト">
	<div class="container">
		<ol class="breadcrumb__list">
			<li class="breadcrumb__item">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">ホーム</a>
			</li>
			<li class="breadcrumb__item" aria-current="page">お知らせ</li>
		</ol>
	</div>
</nav>

<section class="news-page">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<ul class="news-archive">

				<?php while ( have_posts() ) : the_post(); ?>
					<?php $categories = get_the_category(); ?>
					<li class="news-archive__item">
						<a class="news-archive__link" href="<?php the_permalink(); ?>">
							<div class="news-archive__meta">
								<time class="news-archive__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
									<?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?>
								</time>
								<?php if ( ! empty( $categories ) ) : ?>
									<span class="news-archive__category">
										<?php echo esc_html( $categories[0]->name ); ?>
									</span>
								<?php endif; ?>
							</div>
							<h2 class="news-archive__title"><?php the_title(); ?></h2>
							<p class="news-archive__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>

			<?php
			// ページ送り
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => '前へ',
				'next_text' => '次へ',
			) );
			?>
		<?php else : ?>
			<p class="concept__text">お知らせはまだありません。</p>
		<?php endif; ?>

	</div>
</section>

<?php get_footer(); ?>