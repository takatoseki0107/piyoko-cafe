<?php
/**
 * 投稿詳細（お知らせ）
 */
get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php $categories = get_the_category(); ?>

	<section class="page-header">
		<div class="container">
			<p class="section__label">NEWS</p>
			<h1 class="page-header__title"><?php the_title(); ?></h1>
			<p class="page-header__text">
				<time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
					<?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?>
				</time>
				<?php if ( ! empty( $categories ) ) : ?>
					／ <?php echo esc_html( $categories[0]->name ); ?>
				<?php endif; ?>
			</p>
		</div>
	</section>

	<nav class="breadcrumb" aria-label="パンくずリスト">
		<div class="container">
			<ol class="breadcrumb__list">
				<li class="breadcrumb__item">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">ホーム</a>
				</li>

				<li class="breadcrumb__item">
					<a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">お知らせ</a>
				</li>
				<li class="breadcrumb__item" aria-current="page"><?php the_title(); ?></li>
			</ol>
		</div>
	</nav>

	<section class="news-single">
		<div class="container">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="news-single__image">
					<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
				</div>
			<?php endif; ?>

			<div class="news-single__body">
				<?php the_content(); ?>
			</div>

			<nav class="news-single__nav" aria-label="前後の記事">
				<div class="news-single__prev"><?php previous_post_link( '%link', '&larr; 前のお知らせ' ); ?></div>
				<div class="news-single__next"><?php next_post_link( '%link', '次のお知らせ &rarr;' ); ?></div>
			</nav>

			<div class="news-single__back">
				<a class="button" href="<?php echo esc_url( home_url( '/news/' ) ); ?>">お知らせ一覧へ戻る</a>
			</div>
		</div>
	</section>

<?php endwhile; ?>


<?php get_footer(); ?>