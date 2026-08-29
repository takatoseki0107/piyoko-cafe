<?php
/**
 * 最終的なフォールバックとなるテンプレート
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

  <h1><?php bloginfo( 'name' ); ?></h1>
  <p><?php bloginfo( 'description' ); ?></p>

  <hr>

  <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
      <article>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php the_excerpt(); ?>
      </article>
    <?php endwhile; ?>
  <?php else : ?>
    <p>投稿がありません。</p>
  <?php endif; ?>

  <?php wp_footer(); ?>
</body>

</html>