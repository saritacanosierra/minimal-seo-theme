<?php
/**
 * Plantillas de ejemplo por tipo de contenido (duplicables).
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Plantillas del cluster de ejemplo.
 *
 * @return array<int, array<string, mixed>>
 */
function mst_get_architecture_example_templates() {
	return array(
		array(
			'slug'       => 'pilar',
			'type_label' => __( 'Post Pilar', 'minimal-seo-theme' ),
			'words'      => '3.000 – 5.500',
			'title'      => __( 'Guía completa de bienestar íntimo y sensaciones', 'minimal-seo-theme' ),
			'wp_hint'    => __( 'Página índice del tema (o entrada muy larga).', 'minimal-seo-theme' ),
			'view_hint'  => __( 'Mapa del tema: enlaza a categorías sin agotar cada rama. Sin CTA comercial principal.', 'minimal-seo-theme' ),
			'structure'  => array(
				__( 'H1: título del pilar', 'minimal-seo-theme' ),
				__( 'Mapa visual de categorías del cluster', 'minimal-seo-theme' ),
				__( 'Párrafos con anclas hacia CAT-SEN y CAT-SEG', 'minimal-seo-theme' ),
			),
			'meta'       => array(
				'id'        => 'PIL-BIEN',
				'parent'    => '',
				'links_out' => 'CAT-SEN, CAT-SEG, POS-SEN-04',
				'links_in'  => 'CAT-SEN, CAT-SEG',
				'ecom'      => __( 'Sin intención comercial → OMIT_CTA', 'minimal-seo-theme' ),
				'target'    => '',
				'cta_text'  => __( 'Sin enlace comercial', 'minimal-seo-theme' ),
			),
			'anchors'    => '[{"target":"CAT-SEN","anchor":"guía de sensaciones al usar productos"},{"target":"CAT-SEG","anchor":"normas de seguridad e ingredientes"}]',
		),
		array(
			'slug'       => 'categoria',
			'type_label' => __( 'Post Categoría', 'minimal-seo-theme' ),
			'words'      => '1.800 – 3.500',
			'title'      => __( 'Sensaciones al usar productos: guía por tipos', 'minimal-seo-theme' ),
			'wp_hint'    => __( 'Página con bloque [cluster] + extracto del tema.', 'minimal-seo-theme' ),
			'view_hint'  => __( 'Hub: sube al pilar, baja a artículos, enlace cruzado y puente a tienda.', 'minimal-seo-theme' ),
			'structure'  => array(
				__( 'H1: nombre corto del cluster', 'minimal-seo-theme' ),
				__( 'Criterios de agrupación del tema', 'minimal-seo-theme' ),
				__( 'Shortcode [cluster] con tarjetas', 'minimal-seo-theme' ),
			),
			'meta'       => array(
				'id'        => 'CAT-SEN',
				'parent'    => 'PIL-BIEN',
				'links_out' => 'PIL-BIEN, POS-SEN-01, POS-SEN-02, POS-SEN-03, POS-SEN-04, CAT-SEG',
				'links_in'  => 'PIL-BIEN, POS-SEN-01, POS-SEN-02, POS-SEN-03, POS-SEN-04',
				'ecom'      => __( 'Intención SÍ · Comparación SÍ → LINK_FILTERED_CATEGORY', 'minimal-seo-theme' ),
				'target'    => 'https://tutienda.com/categoria/sensaciones',
				'cta_text'  => __( 'Ver productos por tipo de sensación', 'minimal-seo-theme' ),
			),
			'anchors'    => '[{"target":"PIL-BIEN","anchor":"volver al mapa general de bienestar"},{"target":"POS-SEN-01","anchor":"qué es el efecto cálido"}]',
		),
		array(
			'slug'       => 'informativo',
			'type_label' => __( 'Informativo', 'minimal-seo-theme' ),
			'words'      => '800 – 1.600',
			'title'      => __( 'Qué es el efecto cálido (y qué no es)', 'minimal-seo-theme' ),
			'wp_hint'    => __( 'Entrada de blog.', 'minimal-seo-theme' ),
			'view_hint'  => __( 'Respuesta educativa corta. Relacionados abajo. Sin CTA comercial.', 'minimal-seo-theme' ),
			'structure'  => array(
				__( 'H1: pregunta concreta', 'minimal-seo-theme' ),
				__( 'Definición en 2–3 párrafos', 'minimal-seo-theme' ),
				__( 'Incluye la frase ancla hacia el comparativo', 'minimal-seo-theme' ),
			),
			'meta'       => array(
				'id'        => 'POS-SEN-01',
				'parent'    => 'CAT-SEN',
				'links_out' => 'CAT-SEN, POS-SEN-02, POS-SEN-03',
				'links_in'  => 'CAT-SEN, POS-SEN-02, POS-SEN-04',
				'ecom'      => __( 'Sin intención comercial → OMIT_CTA', 'minimal-seo-theme' ),
				'target'    => '',
				'cta_text'  => __( 'Sin enlace comercial', 'minimal-seo-theme' ),
			),
			'anchors'    => '[{"target":"CAT-SEN","anchor":"volver a la guía de sensaciones"},{"target":"POS-SEN-02","anchor":"comparativa entre sensación e irritación"}]',
		),
		array(
			'slug'       => 'comparativo',
			'type_label' => __( 'Comparativo', 'minimal-seo-theme' ),
			'words'      => '1.200 – 2.200',
			'title'      => __( 'Sensación agradable vs. irritación: cómo distinguirlas', 'minimal-seo-theme' ),
			'wp_hint'    => __( 'Entrada con tabla o lista comparativa.', 'minimal-seo-theme' ),
			'view_hint'  => __( 'Comparación + enlace a categoría filtrada en tienda.', 'minimal-seo-theme' ),
			'structure'  => array(
				__( 'H1: A vs B (criterios claros)', 'minimal-seo-theme' ),
				__( 'Tabla o lista de señales', 'minimal-seo-theme' ),
				__( 'Frase «comparar opciones en la tienda» en el cierre', 'minimal-seo-theme' ),
			),
			'meta'       => array(
				'id'        => 'POS-SEN-02',
				'parent'    => 'CAT-SEN',
				'links_out' => 'CAT-SEN, POS-SEN-01, POS-SEN-04',
				'links_in'  => 'CAT-SEN, POS-SEN-01, POS-SEN-04',
				'ecom'      => __( 'Intención SÍ · Comparación SÍ → LINK_FILTERED_CATEGORY', 'minimal-seo-theme' ),
				'target'    => 'https://tutienda.com/categoria/sensaciones?filtro=suave',
				'cta_text'  => __( 'comparar opciones en la tienda', 'minimal-seo-theme' ),
			),
			'anchors'    => '[{"target":"POS-SEN-01","anchor":"qué es el efecto cálido"},{"target":"POS-SEN-04","anchor":"guía para elegir según tu sensibilidad"}]',
		),
		array(
			'slug'       => 'diagnostico',
			'type_label' => __( 'Diagnóstico', 'minimal-seo-theme' ),
			'words'      => '1.000 – 1.800',
			'title'      => __( 'Ardor persistente tras usar un producto: ¿normal o alerta?', 'minimal-seo-theme' ),
			'wp_hint'    => __( 'Entrada sobre molestias o señales de alerta.', 'minimal-seo-theme' ),
			'view_hint'  => __( 'Prioriza seguridad. El árbol omite CTA comercial automáticamente.', 'minimal-seo-theme' ),
			'structure'  => array(
				__( 'H1: síntoma o duda de salud', 'minimal-seo-theme' ),
				__( 'Normal vs. alerta', 'minimal-seo-theme' ),
				__( 'Enlaces a CAT-SEG — sin botón de compra', 'minimal-seo-theme' ),
			),
			'meta'       => array(
				'id'        => 'POS-SEN-03',
				'parent'    => 'CAT-SEN',
				'links_out' => 'CAT-SEN, CAT-SEG, POS-SEN-01, POS-SEN-02',
				'links_in'  => 'POS-SEN-01, POS-SEN-02, CAT-SEN',
				'ecom'      => __( 'Dolor/irritación SÍ → OMIT_CTA', 'minimal-seo-theme' ),
				'target'    => '',
				'cta_text'  => __( 'Sin enlace comercial', 'minimal-seo-theme' ),
			),
			'anchors'    => '[{"target":"CAT-SEG","anchor":"guía de seguridad e ingredientes a evitar"},{"target":"POS-SEN-02","anchor":"diferencias entre sensación e irritación"}]',
		),
		array(
			'slug'       => 'guia-compra',
			'type_label' => __( 'Guía de compra', 'minimal-seo-theme' ),
			'words'      => '1.300 – 2.500',
			'title'      => __( 'Cómo elegir productos según tu sensibilidad', 'minimal-seo-theme' ),
			'wp_hint'    => __( 'Entrada money page del cluster.', 'minimal-seo-theme' ),
			'view_hint'  => __( 'Criterios de compra + enlace a producto validado o categoría aprobada.', 'minimal-seo-theme' ),
			'structure'  => array(
				__( 'H1: cómo elegir…', 'minimal-seo-theme' ),
				__( '3–5 criterios de compra', 'minimal-seo-theme' ),
				__( 'CTA a ficha estable o categoría aprobada', 'minimal-seo-theme' ),
			),
			'meta'       => array(
				'id'        => 'POS-SEN-04',
				'parent'    => 'CAT-SEN',
				'links_out' => 'CAT-SEN, POS-SEN-01, POS-SEN-02',
				'links_in'  => 'CAT-SEN, POS-SEN-02, PIL-BIEN',
				'ecom'      => __( 'Producto validado + URL estable → LINK_DIRECT_PRODUCT', 'minimal-seo-theme' ),
				'target'    => 'https://tutienda.com/producto/base-sensible-certificada',
				'cta_text'  => __( 'ver la ficha del producto recomendado', 'minimal-seo-theme' ),
			),
			'anchors'    => '[{"target":"POS-SEN-01","anchor":"entender el efecto cálido antes de comprar"},{"target":"POS-SEN-02","anchor":"comparar sensación e irritación"}]',
		),
	);
}

/**
 * Registrar página de ejemplos.
 */
function mst_register_architecture_examples_page() {
	add_submenu_page(
		'themes.php',
		__( 'Plantillas de arquitectura SEO', 'minimal-seo-theme' ),
		__( 'Plantillas SEO', 'minimal-seo-theme' ),
		'edit_posts',
		'mst-architecture-examples',
		'mst_render_architecture_examples_page'
	);
}
add_action( 'admin_menu', 'mst_register_architecture_examples_page', 12 );

/**
 * URL de plantillas.
 */
function mst_get_architecture_examples_url() {
	return admin_url( 'themes.php?page=mst-architecture-examples' );
}

/**
 * Render página de plantillas.
 */
function mst_render_architecture_examples_page() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	$templates  = mst_get_architecture_example_templates();
	$guide_url  = function_exists( 'mst_get_architecture_beginner_guide_url' ) ? mst_get_architecture_beginner_guide_url() : '';
	$matrix_url = admin_url( 'themes.php?page=mst-architecture-matrix' );
	$posts_url  = admin_url( 'edit.php' );
	?>
	<div class="wrap mst-arch-examples">
		<h1><?php esc_html_e( 'Plantillas duplicables por tipo de contenido', 'minimal-seo-theme' ); ?></h1>
		<p class="description"><?php esc_html_e( 'Copia cada bloque al meta box «Arquitectura SEO». Ejemplos alineados al mapa de decisiones (lubricantes / Sensaciones). Sustituye títulos, IDs y URLs de tienda.', 'minimal-seo-theme' ); ?></p>
		<p>
			<a class="button button-primary" href="<?php echo esc_url( $posts_url ); ?>"><?php esc_html_e( 'Crear / editar entradas', 'minimal-seo-theme' ); ?></a>
			<?php if ( function_exists( 'mst_get_architecture_client_map_url' ) ) : ?>
				<a class="button" href="<?php echo esc_url( mst_get_architecture_client_map_url() ); ?>"><?php esc_html_e( 'Mapa de decisiones', 'minimal-seo-theme' ); ?></a>
			<?php endif; ?>
			<?php if ( $guide_url ) : ?>
				<a class="button" href="<?php echo esc_url( $guide_url ); ?>"><?php esc_html_e( 'Guía fácil', 'minimal-seo-theme' ); ?></a>
			<?php endif; ?>
			<a class="button" href="<?php echo esc_url( $matrix_url ); ?>"><?php esc_html_e( 'Matriz SEO', 'minimal-seo-theme' ); ?></a>
		</p>

		<div class="mst-guide-card mst-guide-card--highlight">
			<h2><?php esc_html_e( 'Mapa del cluster de ejemplo', 'minimal-seo-theme' ); ?></h2>
			<pre class="mst-arch-tree">PIL-BIEN (Pilar)
├── CAT-SEN (Categoría)
│   ├── POS-SEN-01 Informativo
│   ├── POS-SEN-02 Comparativo
│   ├── POS-SEN-03 Diagnóstico
│   └── POS-SEN-04 Guía de compra
└── CAT-SEG (Categoría cruzada)</pre>
		</div>

		<?php foreach ( $templates as $tpl ) : ?>
			<div class="mst-guide-card">
				<h2><?php echo esc_html( $tpl['type_label'] ); ?> — <?php echo esc_html( $tpl['words'] ); ?></h2>
				<p><strong><?php esc_html_e( 'Título:', 'minimal-seo-theme' ); ?></strong> <?php echo esc_html( $tpl['title'] ); ?></p>
				<p class="description"><?php echo esc_html( $tpl['wp_hint'] ); ?> · <?php echo esc_html( $tpl['view_hint'] ); ?></p>
				<h3><?php esc_html_e( 'Estructura del contenido', 'minimal-seo-theme' ); ?></h3>
				<ol><?php foreach ( $tpl['structure'] as $line ) : ?><li><?php echo esc_html( $line ); ?></li><?php endforeach; ?></ol>
				<h3><?php esc_html_e( 'Meta box — copiar', 'minimal-seo-theme' ); ?></h3>
				<table class="widefat striped">
					<tbody>
						<tr><th>ID</th><td><code><?php echo esc_html( $tpl['meta']['id'] ); ?></code></td></tr>
						<tr><th>Parent ID</th><td><code><?php echo esc_html( $tpl['meta']['parent'] ?: '—' ); ?></code></td></tr>
						<tr><th>links_out</th><td><code><?php echo esc_html( $tpl['meta']['links_out'] ); ?></code></td></tr>
						<tr><th>links_in</th><td><code><?php echo esc_html( $tpl['meta']['links_in'] ); ?></code></td></tr>
						<tr><th><?php esc_html_e( 'Árbol e-commerce', 'minimal-seo-theme' ); ?></th><td><?php echo esc_html( $tpl['meta']['ecom'] ); ?></td></tr>
						<tr><th>target_url</th><td><code><?php echo esc_html( $tpl['meta']['target'] ?: '—' ); ?></code></td></tr>
						<tr><th><?php esc_html_e( 'Texto CTA', 'minimal-seo-theme' ); ?></th><td><?php echo esc_html( $tpl['meta']['cta_text'] ); ?></td></tr>
					</tbody>
				</table>
				<p><strong>anchor_texts</strong></p>
				<textarea class="large-text code" rows="3" readonly><?php echo esc_textarea( $tpl['anchors'] ); ?></textarea>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Estilos página plantillas.
 *
 * @param string $hook Hook admin.
 */
function mst_architecture_examples_styles( $hook ) {
	if ( 'appearance_page_mst-architecture-examples' !== $hook ) {
		return;
	}
	wp_add_inline_style(
		'wp-admin',
		'.mst-arch-examples .mst-guide-card{background:#fff;border:1px solid #c3c4c7;border-radius:6px;padding:1.25rem 1.5rem;margin:1rem 0;max-width:960px}
		.mst-arch-examples .mst-guide-card--highlight{background:#f0f6fc;border-left:4px solid #2271b1}
		.mst-arch-examples .mst-arch-tree{background:#1e1e1e;color:#d4d4d4;padding:12px;border-radius:4px}'
	);
}
add_action( 'admin_enqueue_scripts', 'mst_architecture_examples_styles' );
