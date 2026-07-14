<?php
/**
 * Tabla de contenidos nativa — Minimal SEO Theme
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Generar ID único para encabezado.
 */
function mst_heading_id( $text, &$used_ids ) {
	$id   = sanitize_title( $text );
	$base = $id;
	$i    = 2;

	while ( in_array( $id, $used_ids, true ) ) {
		$id = $base . '-' . $i;
		++$i;
	}

	$used_ids[] = $id;
	return $id;
}

/**
 * Construir HTML de la tabla de contenidos.
 */
function mst_build_toc_html( $headings ) {
	$items = '';
	foreach ( $headings as $heading ) {
		$items .= sprintf(
			'<li class="mst-toc__item"><a class="mst-toc__link" href="#%s">%s</a></li>',
			esc_attr( $heading['id'] ),
			esc_html( $heading['text'] )
		);
	}

	return sprintf(
		'<nav class="mst-toc" aria-label="%1$s">
			<button type="button" class="mst-toc__toggle" aria-expanded="true" aria-controls="mst-toc-list">%1$s</button>
			<ol id="mst-toc-list" class="mst-toc__list">%2$s</ol>
		</nav>',
		esc_attr__( 'Tabla de contenidos', 'minimal-seo-theme' ),
		$items
	);
}

/**
 * Insertar TOC e IDs en encabezados H2 del contenido.
 */
function mst_insert_toc( $content ) {
	if ( is_admin() || ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	if ( ! preg_match_all( '/<h2(\s[^>]*)?>(.*?)<\/h2>/is', $content, $matches ) ) {
		return $content;
	}

	if ( count( $matches[0] ) < 3 ) {
		return $content;
	}

	$headings = array();
	$used_ids = array();

	$content = preg_replace_callback(
		'/<h2(\s[^>]*)?>(.*?)<\/h2>/is',
		function ( $m ) use ( &$headings, &$used_ids ) {
			$attrs = $m[1] ?? '';
			$inner = $m[2];
			$text  = wp_strip_all_tags( $inner );

			if ( preg_match( '/\sid=(["\'])([^"\']+)\1/i', $attrs, $id_match ) ) {
				$id = $id_match[2];
				if ( ! in_array( $id, $used_ids, true ) ) {
					$used_ids[] = $id;
				}
			} else {
				$id    = mst_heading_id( $text, $used_ids );
				$attrs = ' id="' . esc_attr( $id ) . '"' . $attrs;
			}

			$headings[] = array(
				'id'   => $id,
				'text' => $text,
			);

			return '<h2' . $attrs . '>' . $inner . '</h2>';
		},
		$content
	);

	$toc      = mst_build_toc_html( $headings );
	$replaced = preg_replace( '/<h2\b/i', $toc . '<h2', $content, 1 );

	return $replaced ? $replaced : $content;
}
add_filter( 'the_content', 'mst_insert_toc', 12 );
