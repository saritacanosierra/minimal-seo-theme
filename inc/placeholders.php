<?php
/**
 * Textos de plantilla intuitivos — marcadores [EDITAR] en lenguaje sencillo
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Prefijo de textos reemplazables en la plantilla demo.
 */
function mst_placeholder_prefix() {
	return '[EDITAR]';
}

/**
 * ¿Es un texto de plantilla pendiente de reemplazar?
 *
 * @param string $text Texto a comprobar.
 */
function mst_is_placeholder_text( $text ) {
	if ( ! is_string( $text ) || '' === $text ) {
		return false;
	}

	return 0 === strpos( $text, mst_placeholder_prefix() );
}

/**
 * Clase CSS extra si el texto es plantilla.
 *
 * @param string $text Texto a comprobar.
 * @param string $base Clases base.
 */
function mst_placeholder_class_attr( $text, $base = '' ) {
	$classes = trim( $base . ( mst_is_placeholder_text( $text ) ? ' mst-placeholder' : '' ) );
	return $classes ? ' class="' . esc_attr( $classes ) . '"' : '';
}

/**
 * Construir frase de plantilla: [EDITAR] Zona — qué escribir.
 *
 * @param string $zone        Nombre de la zona (sin tecnicismos).
 * @param string $instruction Instrucción en lenguaje llano.
 */
function mst_ph( $zone, $instruction ) {
	return mst_placeholder_prefix() . ' ' . $zone . ' — ' . $instruction;
}

/**
 * Título corto para H1, menú y migas de pan (sin instrucción larga).
 *
 * @param string $label Etiqueta breve. Ej: "TEMA 2", "Artículo 3".
 */
function mst_ph_title( $label ) {
	return mst_placeholder_prefix() . ' ' . $label;
}

/**
 * Variante de fondo abstracto (sin texto en imagen) para tarjetas demo.
 *
 * @param int $post_id ID del post.
 */
function mst_get_abstract_media_variant( $post_id ) {
	$variants = array( 'a', 'b', 'c', 'd', 'e' );
	$index    = absint( $post_id ) % count( $variants );
	return $variants[ $index ];
}

/**
 * ¿Usar gradiente en lugar de miniatura demo con texto superpuesto?
 *
 * @param int $post_id ID del post.
 */
function mst_use_abstract_card_media( $post_id ) {
	$post_id = absint( $post_id );
	if ( ! $post_id ) {
		return false;
	}

	if ( mst_is_placeholder_text( get_the_title( $post_id ) ) ) {
		return true;
	}

	if ( function_exists( 'mst_is_demo_placeholder_thumbnail' ) ) {
		$thumb_id = get_post_thumbnail_id( $post_id );
		if ( $thumb_id && mst_is_demo_placeholder_thumbnail( $thumb_id ) ) {
			return true;
		}
	}

	return false;
}

/**
 * ¿Mostrar guía de estructura en la portada (plantilla demo)?
 */
function mst_home_should_show_structure_guide() {
	if ( ! is_front_page() || ! is_home() ) {
		return false;
	}

	$checks = array(
		mst_get_home_mod( 'mst_home_hero_title' ),
		mst_get_home_mod( 'mst_home_hero_text' ),
		mst_get_home_mod( 'mst_home_cluster_title' ),
	);

	foreach ( $checks as $text ) {
		if ( mst_is_placeholder_text( (string) $text ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Párrafo de ejemplo con instrucción clara.
 */
function mst_ph_lorem() {
	return mst_ph(
		__( 'Texto del artículo', 'minimal-seo-theme' ),
		__( 'Escribe aquí el contenido de este apartado. Puedes borrar este ejemplo y poner el tuyo', 'minimal-seo-theme' )
	) . ' ' . __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'minimal-seo-theme' );
}

/**
 * Bloque de ayuda visible en la página (plantilla demo).
 *
 * @param string $message Instrucción contextual.
 */
function mst_get_editor_hint_html( $message ) {
	return '<aside class="mst-editor-hint" role="note"><strong>' . esc_html__( '¿Qué poner aquí?', 'minimal-seo-theme' ) . '</strong> ' . esc_html( $message ) . '</aside>';
}

/**
 * Sección con subtítulo grande + párrafo de plantilla.
 *
 * @param string $example Ejemplo de tema para el subtítulo.
 */
function mst_build_placeholder_section( $example ) {
	$h2 = mst_ph(
		__( 'Subtítulo de sección', 'minimal-seo-theme' ),
		sprintf(
			/* translators: %s: example section name */
			__( 'Escribe aquí un subtítulo. Ejemplo: %s', 'minimal-seo-theme' ),
			$example
		)
	);

	$html  = '<h2' . mst_placeholder_class_attr( $h2, 'mst-placeholder' ) . '>' . esc_html( $h2 ) . '</h2>';
	$html .= '<p' . mst_placeholder_class_attr( mst_ph_lorem(), 'mst-placeholder' ) . '>' . esc_html( mst_ph_lorem() ) . '</p>';

	return $html;
}
