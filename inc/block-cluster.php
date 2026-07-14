<?php
/**
 * Bloque Gutenberg — Cluster de navegación
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registrar categoría de bloques del tema.
 */
function mst_register_block_category( $categories ) {
	$categories[] = array(
		'slug'  => 'minimal-seo-theme',
		'title' => __( 'Minimal SEO Theme', 'minimal-seo-theme' ),
	);
	return $categories;
}
add_filter( 'block_categories_all', 'mst_register_block_category' );

/**
 * Registrar bloque server-side (sin JS en frontend).
 */
function mst_register_cluster_block() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	register_block_type(
		'mst/cluster',
		array(
			'api_version'     => 2,
			'title'           => __( 'Cluster de navegación', 'minimal-seo-theme' ),
			'description'     => __( 'Cuadrícula de enlaces internos para arquitectura en silo.', 'minimal-seo-theme' ),
			'category'        => 'minimal-seo-theme',
			'icon'            => 'grid-view',
			'keywords'        => array( 'cluster', 'seo', 'silo', 'navegación' ),
			'supports'        => array(
				'html' => false,
			),
			'attributes'      => array(
				'category'       => array( 'type' => 'string', 'default' => '' ),
				'tag'            => array( 'type' => 'string', 'default' => '' ),
				'postsPerPage'   => array( 'type' => 'number', 'default' => 6 ),
				'columns'        => array( 'type' => 'number', 'default' => 3 ),
				'featuredAuto'   => array( 'type' => 'number', 'default' => 3 ),
				'showExcerpt'    => array( 'type' => 'boolean', 'default' => true ),
				'loadMore'       => array( 'type' => 'boolean', 'default' => true ),
			),
			'render_callback' => 'mst_render_cluster_block',
		)
	);
}
add_action( 'init', 'mst_register_cluster_block' );

/**
 * Render del bloque cluster.
 */
function mst_render_cluster_block( $attributes ) {
	$atts = array(
		'category'       => $attributes['category'] ?? '',
		'tag'            => $attributes['tag'] ?? '',
		'posts_per_page' => $attributes['postsPerPage'] ?? 6,
		'columns'        => $attributes['columns'] ?? 3,
		'featured_auto'  => $attributes['featuredAuto'] ?? 3,
		'show_excerpt'   => ! empty( $attributes['showExcerpt'] ) ? 'yes' : 'no',
		'load_more'      => ! empty( $attributes['loadMore'] ) ? 'yes' : 'no',
	);

	return mst_cluster_shortcode( $atts );
}
