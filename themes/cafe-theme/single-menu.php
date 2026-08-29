<?php
/**
 * メニュー個別ページ
 */
get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php
	$price      = get_post_meta( get_the_ID(), '_piyoko_price', true );
	$categories = get_the_terms( get_the_ID(), 'menu_category' );
	?>

	<section class="page-header">
		<div class="container">
			<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
				<p class="section__label"><?php echo esc_html( $categories[0]->name ); ?></p>
			<?php endif; ?>
			<h1 class="page-header__title"><?php the_title(); ?></h1>
		</div>
	</section>

	<nav class="breadcrumb" aria-label="パンくずリスト">
		<div class="container">
			<ol class="breadcrumb__list">
				<li class="breadcrumb__item">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">ホーム</a>
				</li>
				<li class="breadcrumb__item">
					<a href="<?php echo esc_url( get_post_type_archive_link( 'menu' ) ); ?>">メニュー</a>
				</li>

				<li class="breadcrumb__item" aria-current="page"><?php the_title(); ?></li>
			</ol>
		</div>
	</nav>

	<section class="menu-single">
		<div class="container">
			<div class="menu-single__inner">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="menu-single__image">
						<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
					</div>
				<?php endif; ?>

				<div class="menu-single__body">
					<?php if ( $price ) : ?>
						<p class="menu-single__price">
							&yen;<?php echo esc_html( number_format( $price ) ); ?>
							<span class="menu-single__tax">（税込）</span>
						</p>
					<?php endif; ?>

					<div class="menu-single__text">
						<?php the_content(); ?>
					</div>

					<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
						<p class="menu-single__category">
							カテゴリー：
							<?php foreach ( $categories as $category ) : ?>
								<a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
									<?php echo esc_html( $category->name ); ?>

								</a>
							<?php endforeach; ?>
						</p>
					<?php endif; ?>
				</div>
			</div>

			<div class="menu-single__back">
				<a class="button" href="<?php echo esc_url( get_post_type_archive_link( 'menu' ) ); ?>">
					メニュー一覧へ戻る
				</a>
			</div>
		</div>
	</section>

<?php endwhile; ?>

<?php get_footer(); ?>