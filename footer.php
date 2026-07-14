</main><!-- #main-content -->

<footer class="site-footer" role="contentinfo">
	<div class="site-footer__inner">
		<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
			<div class="footer-widgets">
				<?php dynamic_sidebar( 'footer-1' ); ?>
			</div>
		<?php endif; ?>

		<p class="site-footer__copy">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>.
			<?php esc_html_e( 'Todos los derechos reservados.', 'minimal-seo-theme' ); ?>
		</p>
	</div>
</footer>

<?php
$orbital_items = mst_get_orbital_menu_items();
if ( ! empty( $orbital_items ) ) :
	?>
	<nav class="orbital-menu" id="orbital-menu" aria-label="<?php esc_attr_e( 'Menú Órbita', 'minimal-seo-theme' ); ?>" data-orbital-menu>
		<ul class="orbital-menu__items" role="list">
			<?php
			$count = 0;
			foreach ( $orbital_items as $item ) :
				if ( 'custom' !== $item->type && ! in_array( $item->object, array( 'page', 'post', 'category', 'custom' ), true ) ) {
					continue;
				}
				if ( $count >= 6 ) {
					break;
				}
				$label = wp_trim_words( $item->title, 3, '' );
				?>
				<li class="orbital-menu__item">
					<?php echo mst_render_orbital_menu_link( $item, $label ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</li>
				<?php
				++$count;
			endforeach;
			?>
		</ul>
		<button type="button" class="orbital-menu__toggle" aria-expanded="false" aria-controls="orbital-menu" data-orbital-toggle>
			<span class="orbital-menu__toggle-icon" aria-hidden="true"></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Abrir menú Órbita', 'minimal-seo-theme' ); ?></span>
		</button>
	</nav>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
