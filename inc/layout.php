<?php
/**
 * Layout con sidebar — Minimal SEO Theme
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registrar sidebar principal.
 */
function mst_register_sidebar() {
	register_sidebar(
		array(
			'name'          => __( 'Barra lateral', 'minimal-seo-theme' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Widgets en entradas, páginas y archivos (sin H1-H6).', 'minimal-seo-theme' ),
			'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<p class="sidebar-widget__label">',
			'after_title'   => '</p>',
		)
	);
}
add_action( 'widgets_init', 'mst_register_sidebar', 11 );

/**
 * ¿Usar layout con sidebar en la vista actual?
 */
function mst_uses_sidebar_layout() {
	if ( is_front_page() && is_home() ) {
		return false;
	}
	if ( is_front_page() && ! is_home() ) {
		return false;
	}

	$layout = mst_get_mod( 'mst_sidebar_layout' );
	return in_array( $layout, array( 'left', 'right' ), true ) && is_active_sidebar( 'sidebar-1' );
}

/**
 * Clase de layout activo.
 */
function mst_get_sidebar_layout() {
	$layout = mst_get_mod( 'mst_sidebar_layout' );
	return mst_uses_sidebar_layout() ? $layout : 'none';
}

/**
 * Abrir contenedor de layout.
 */
function mst_layout_open() {
	$layout = mst_get_sidebar_layout();
	echo '<div class="site-layout site-layout--' . esc_attr( $layout ) . '">';

	if ( 'left' === $layout ) {
		mst_render_sidebar();
	}

	echo '<div class="site-layout__content">';
}

/**
 * Cerrar contenedor de layout.
 */
function mst_layout_close() {
	echo '</div>';

	if ( 'right' === mst_get_sidebar_layout() ) {
		mst_render_sidebar();
	}

	echo '</div>';
}

/**
 * Renderizar sidebar.
 */
function mst_render_sidebar() {
	if ( ! mst_uses_sidebar_layout() ) {
		return;
	}
	?>
	<aside class="site-sidebar" role="complementary" aria-label="<?php esc_attr_e( 'Barra lateral', 'minimal-seo-theme' ); ?>">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</aside>
	<?php
}
