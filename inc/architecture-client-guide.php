<?php
/**
 * Guía Mapa de decisiones SEO — alineada al documento del cliente.
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registrar página del mapa de decisiones.
 */
function mst_register_architecture_client_map_page() {
	add_submenu_page(
		'themes.php',
		__( 'Mapa de decisiones SEO', 'minimal-seo-theme' ),
		__( 'Mapa de decisiones', 'minimal-seo-theme' ),
		'edit_posts',
		'mst-architecture-client-map',
		'mst_render_architecture_client_map_page'
	);
}
add_action( 'admin_menu', 'mst_register_architecture_client_map_page', 10 );

/**
 * URL del mapa de decisiones en admin.
 */
function mst_get_architecture_client_map_url() {
	return admin_url( 'themes.php?page=mst-architecture-client-map' );
}

/**
 * Render página mapa de decisiones.
 */
function mst_render_architecture_client_map_page() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	$templates_url = function_exists( 'mst_get_architecture_examples_url' ) ? mst_get_architecture_examples_url() : '';
	$easy_url      = function_exists( 'mst_get_architecture_beginner_guide_url' ) ? mst_get_architecture_beginner_guide_url() : '';
	$matrix_url    = admin_url( 'themes.php?page=mst-architecture-matrix' );
	?>
	<div class="wrap mst-client-map-guide">
		<h1><?php esc_html_e( 'Mapa de decisiones SEO — cómo usar la plantilla', 'minimal-seo-theme' ); ?></h1>
		<p class="description"><?php esc_html_e( 'Arquitectura antes que redacción. Educación antes que venta. Esta pantalla traduce el mapa de decisiones del proyecto a WordPress.', 'minimal-seo-theme' ); ?></p>

		<div class="mst-guide-card mst-guide-card--highlight">
			<h2><?php esc_html_e( 'Vocabulario: demo vs mapa del proyecto', 'minimal-seo-theme' ); ?></h2>
			<table class="widefat striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Mapa de decisiones', 'minimal-seo-theme' ); ?></th>
						<th><?php esc_html_e( 'Demo (TEMA 1 / 2)', 'minimal-seo-theme' ); ?></th>
						<th><?php esc_html_e( 'Tipo en meta box', 'minimal-seo-theme' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php esc_html_e( 'Territorio (Sensaciones, Sabores…)', 'minimal-seo-theme' ); ?></td>
						<td><strong>TEMA 1</strong>, TEMA 2</td>
						<td><?php esc_html_e( 'Post categoría', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Mapa de todos los territorios', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'No incluido en demo (créalo)', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Post pilar', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Artículo educativo / comparativa / diagnóstico', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Art. 1, 2, 3, 4', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Informativo, Comparativo, Diagnóstico…', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Conversión (contacto / compra)', 'minimal-seo-theme' ); ?></td>
						<td><strong><?php esc_html_e( 'Oferta', 'minimal-seo-theme' ); ?></strong></td>
						<td><?php esc_html_e( 'Guía de compra', 'minimal-seo-theme' ); ?></td>
					</tr>
				</tbody>
			</table>
			<p><strong><?php esc_html_e( 'Importante:', 'minimal-seo-theme' ); ?></strong> <?php esc_html_e( 'TEMA 1 no es el pilar. TEMA 1 es un territorio (post categoría). El pilar agrupa varios territorios.', 'minimal-seo-theme' ); ?></p>
		</div>

		<div class="mst-guide-card">
			<h2><?php esc_html_e( '8 puertas antes de publicar', 'minimal-seo-theme' ); ?></h2>
			<ol>
				<li><?php esc_html_e( 'Tema coherente con la marca (decisión del equipo).', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( 'Medición confiable (Google Analytics / eventos).', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( 'Oportunidad SEO viable (keywords, SERP).', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( 'Tipo, ID y Parent ID en «Arquitectura SEO».', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( 'links_out, links_in, anchor_texts y árbol e-commerce.', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( 'Brief aprobado (sin errores rojos al guardar).', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( 'Contenido y schema revisados.', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( 'Publicación conectada: enlace desde padre y relacionados (no huérfana).', 'minimal-seo-theme' ); ?></li>
			</ol>
		</div>

		<div class="mst-guide-card">
			<h2><?php esc_html_e( 'Árbol e-commerce (marketplace tercero)', 'minimal-seo-theme' ); ?></h2>
			<p><?php esc_html_e( 'El contenido no enlaza al marketplace por defecto. En el meta box, responde el árbol en este orden:', 'minimal-seo-theme' ); ?></p>
			<ol>
				<li><?php esc_html_e( '¿Dolor o irritación? → Omitir CTA comercial.', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( '¿Sin intención comercial? → Omitir CTA.', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( '¿Debe comparar? → Categoría filtrada (target_url).', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( '¿Producto validado y URL estable? → Ficha de producto.', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( '¿Ficha inestable? → Categoría aprobada o sin enlace.', 'minimal-seo-theme' ); ?></li>
			</ol>
			<p class="description"><?php esc_html_e( 'Secuencia en el texto: necesidad → respuesta → criterios → precauciones → comparación → enlace comercial (nunca en el primer párrafo).', 'minimal-seo-theme' ); ?></p>
		</div>

		<div class="mst-guide-card">
			<h2><?php esc_html_e( 'Orden de publicación recomendado', 'minimal-seo-theme' ); ?></h2>
			<ol>
				<li><?php esc_html_e( 'Pilar (si aplica) + 2–3 categorías (territorios).', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( '2 posts por categoría + seguridad/compatibilidad.', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( 'Comparativas y guías de compra.', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( 'Keywords amplias cuando haya soporte editorial.', 'minimal-seo-theme' ); ?></li>
			</ol>
		</div>

		<p>
			<?php if ( $templates_url ) : ?>
				<a class="button button-primary" href="<?php echo esc_url( $templates_url ); ?>"><?php esc_html_e( 'Plantillas por tipo', 'minimal-seo-theme' ); ?></a>
			<?php endif; ?>
			<?php if ( $easy_url ) : ?>
				<a class="button" href="<?php echo esc_url( $easy_url ); ?>"><?php esc_html_e( 'Guía fácil', 'minimal-seo-theme' ); ?></a>
			<?php endif; ?>
			<a class="button" href="<?php echo esc_url( $matrix_url ); ?>"><?php esc_html_e( 'Matriz SEO', 'minimal-seo-theme' ); ?></a>
			<a class="button" href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>"><?php esc_html_e( 'Editar entradas', 'minimal-seo-theme' ); ?></a>
		</p>
	</div>
	<?php
}

/**
 * Estilos mapa cliente.
 *
 * @param string $hook Hook admin.
 */
function mst_architecture_client_map_styles( $hook ) {
	if ( 'appearance_page_mst-architecture-client-map' !== $hook ) {
		return;
	}
	wp_add_inline_style(
		'wp-admin',
		'.mst-client-map-guide .mst-guide-card{background:#fff;border:1px solid #c3c4c7;border-radius:6px;padding:1.25rem 1.5rem;margin:1rem 0;max-width:960px}
		.mst-client-map-guide .mst-guide-card--highlight{background:#f0f6fc;border-left:4px solid #2271b1}'
	);
}
add_action( 'admin_enqueue_scripts', 'mst_architecture_client_map_styles' );
