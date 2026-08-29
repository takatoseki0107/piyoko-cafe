<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="preconnect" href="https://fonts.googleapis.com" />
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>

		<header class="header">
			<div class="container header__inner">
				<a class="header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php bloginfo( 'name' ); ?>
				</a>

				<button
					class="hamburger"
					id="hamburger"
					aria-label="メニューを開く"
					aria-expanded="false"
				>
					<span></span>
					<span></span>
					<span></span>
				</button>

				<nav class="nav" id="nav">
					<?php
					wp_nav_menu( array(

						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'nav__list',
						'fallback_cb'    => false,
						'depth'          => 1,
					) );
					?>
				</nav>
			</div>
		</header>

		<main>