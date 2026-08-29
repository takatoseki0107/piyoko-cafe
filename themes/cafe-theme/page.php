<?php
/**
 * 固定ページ共通テンプレート
 */
get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<section class="page-header">
		<div class="container">
			<h1 class="page-header__title"><?php the_title(); ?></h1>
		</div>
	</section>

	<nav class="breadcrumb" aria-label="パンくずリスト">
		<div class="container">
			<ol class="breadcrumb__list">
				<li class="breadcrumb__item">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">ホーム</a>
				</li>
				<li class="breadcrumb__item" aria-current="page"><?php the_title(); ?></li>
			</ol>
		</div>
	</nav>

	<section class="page-content">
		<div class="container">
			<?php the_content(); ?>
		</div>
	</section>


<?php endwhile; ?>

<?php get_footer(); ?>