		</main>

		<footer class="footer">
			<div class="container footer__inner">
				<p class="footer__logo"><?php bloginfo( 'name' ); ?></p>
				<p class="footer__text"><?php bloginfo( 'description' ); ?></p>
				<p class="footer__copyright">
					&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> piyoko cafe
				</p>
			</div>
		</footer>

		<?php wp_footer(); ?>
	</body>
</html>