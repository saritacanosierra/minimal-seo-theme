<?php
/**
 * Patrones de bloques nativos — Minimal SEO Theme
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registrar soporte y patrones del tema.
 */
function mst_register_block_patterns() {
	remove_theme_support( 'core-block-patterns' );

	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	register_block_pattern_category(
		'minimal-seo-theme',
		array(
			'label' => __( 'Minimal SEO Theme', 'minimal-seo-theme' ),
		)
	);

	$patterns = array(
		'hero' => array(
			'title'       => __( 'Hero — Portada', 'minimal-seo-theme' ),
			'description' => _x( 'Sección principal con título H2, texto y botón.', 'Block pattern description', 'minimal-seo-theme' ),
			'content'     => '<!-- wp:group {"className":"mst-section mst-hero mst-pattern-hero","layout":{"type":"constrained"}} -->
<div class="wp-block-group mst-section mst-hero mst-pattern-hero"><div class="mst-hero__inner"><!-- wp:heading {"level":2,"className":"mst-hero__title"} -->
<h2 class="wp-block-heading mst-hero__title">' . esc_html__( 'Tu título principal aquí', 'minimal-seo-theme' ) . '</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mst-hero__text"} -->
<p class="mst-hero__text">' . esc_html__( 'Describe en una frase clara de qué trata tu sitio o landing.', 'minimal-seo-theme' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mst-hero__actions"} -->
<p class="mst-hero__actions"><a class="mst-btn" href="#">' . esc_html__( 'Empezar ahora', 'minimal-seo-theme' ) . '</a></p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:group -->',
		),
		'cta'  => array(
			'title'       => __( 'Banner CTA', 'minimal-seo-theme' ),
			'description' => _x( 'Llamada a la acción con fondo de acento.', 'Block pattern description', 'minimal-seo-theme' ),
			'content'     => '<!-- wp:group {"className":"mst-section mst-cta-banner","layout":{"type":"constrained"}} -->
<div class="wp-block-group mst-section mst-cta-banner"><div class="mst-cta-banner__inner"><!-- wp:heading {"level":2,"className":"mst-cta-banner__title"} -->
<h2 class="wp-block-heading mst-cta-banner__title">' . esc_html__( '¿Listo para dar el siguiente paso?', 'minimal-seo-theme' ) . '</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mst-cta-banner__text"} -->
<p class="mst-cta-banner__text">' . esc_html__( 'Añade aquí una frase que invite a contactar o leer más.', 'minimal-seo-theme' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a class="mst-btn mst-btn--light" href="#">' . esc_html__( 'Contactar', 'minimal-seo-theme' ) . '</a></p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:group -->',
		),
		'columns' => array(
			'title'       => __( 'Dos columnas — Texto', 'minimal-seo-theme' ),
			'description' => _x( 'Bloque de dos columnas responsive.', 'Block pattern description', 'minimal-seo-theme' ),
			'content'     => '<!-- wp:columns {"className":"mst-section mst-columns"} -->
<div class="wp-block-columns mst-section mst-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">' . esc_html__( 'Beneficio 1', 'minimal-seo-theme' ) . '</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html__( 'Explica la ventaja principal de tu producto o contenido.', 'minimal-seo-theme' ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">' . esc_html__( 'Beneficio 2', 'minimal-seo-theme' ) . '</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html__( 'Segunda columna con otro argumento clave para el lector.', 'minimal-seo-theme' ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->',
		),
		'cluster' => array(
			'title'       => __( 'Cluster de navegación', 'minimal-seo-theme' ),
			'description' => _x( 'Cuadrícula SEO de enlaces internos.', 'Block pattern description', 'minimal-seo-theme' ),
			'content'     => '<!-- wp:group {"className":"mst-section mst-section--cluster","layout":{"type":"constrained"}} -->
<div class="wp-block-group mst-section mst-section--cluster"><!-- wp:heading {"level":2,"className":"mst-section__title"} -->
<h2 class="wp-block-heading mst-section__title">' . esc_html__( 'Explora por temas', 'minimal-seo-theme' ) . '</h2>
<!-- /wp:heading -->

<!-- wp:shortcode -->
[cluster posts_per_page="6" columns="3" load_more="yes"]
<!-- /wp:shortcode --></div>
<!-- /wp:group -->',
		),
	);

	foreach ( $patterns as $slug => $pattern ) {
		register_block_pattern(
			'minimal-seo-theme/' . $slug,
			array(
				'title'       => $pattern['title'],
				'description' => $pattern['description'],
				'content'     => $pattern['content'],
				'categories'  => array( 'minimal-seo-theme' ),
				'keywords'    => array( 'seo', 'hero', 'cta', 'cluster' ),
			)
		);
	}
}
add_action( 'init', 'mst_register_block_patterns' );

/**
 * Estilos del editor (solo admin — no afecta frontend).
 */
function mst_editor_styles() {
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'mst_editor_styles', 20 );

/**
 * CSS mínimo de bloques en frontend solo si el contenido usa bloques.
 */
function mst_maybe_enqueue_block_layout_css() {
	if ( is_admin() || ! is_singular() ) {
		return;
	}
	$post = get_post();
	if ( ! $post || ! has_blocks( $post->post_content ) ) {
		return;
	}
	wp_add_inline_style(
		'mst-theme',
		'.mst-columns{display:grid;grid-template-columns:1fr;gap:var(--mst-space)}@media(min-width:768px){.mst-columns,.wp-block-columns.mst-columns{grid-template-columns:1fr 1fr;display:grid}}.wp-block-columns.mst-columns{display:grid}.wp-block-columns.mst-columns>.wp-block-column{margin:0}'
	);
}
add_action( 'wp_enqueue_scripts', 'mst_maybe_enqueue_block_layout_css', 25 );
