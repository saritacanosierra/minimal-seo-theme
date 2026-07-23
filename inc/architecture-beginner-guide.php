<?php
/**
 * Guía para principiantes — arquitectura e interlinking (sin jerga).
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registrar página de guía fácil.
 */
function mst_register_architecture_beginner_guide_page() {
	add_submenu_page(
		'themes.php',
		__( 'Guía fácil: enlaces entre artículos', 'minimal-seo-theme' ),
		__( 'Guía fácil: enlaces', 'minimal-seo-theme' ),
		'edit_posts',
		'mst-architecture-beginner-guide',
		'mst_render_architecture_beginner_guide_page'
	);
}
add_action( 'admin_menu', 'mst_register_architecture_beginner_guide_page', 11 );

/**
 * URL de la guía fácil.
 */
function mst_get_architecture_beginner_guide_url() {
	return admin_url( 'themes.php?page=mst-architecture-beginner-guide' );
}

/**
 * Render guía en el admin.
 */
function mst_render_architecture_beginner_guide_page() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	$matrix_url = admin_url( 'themes.php?page=mst-architecture-matrix' );
	$guide_url  = admin_url( 'themes.php?page=mst-template-guide' );
	$posts_url  = admin_url( 'edit.php' );
	?>
	<div class="wrap mst-beginner-guide">
		<h1><?php esc_html_e( 'Guía fácil: cómo conectar tus artículos', 'minimal-seo-theme' ); ?></h1>
		<p class="description"><?php esc_html_e( 'Sin código. Sin marketing complicado. Solo WordPress y estos pasos.', 'minimal-seo-theme' ); ?></p>

		<div class="mst-guide-card mst-guide-card--highlight">
			<h2><?php esc_html_e( '¿Para qué sirve?', 'minimal-seo-theme' ); ?></h2>
			<p><?php esc_html_e( 'Tu web es como una biblioteca: un libro grande (Pilar), estanterías (Categorías) y libros concretos (artículos). Esta herramienta te ayuda a planificar qué página enlaza con cuál ANTES de escribir.', 'minimal-seo-theme' ); ?></p>
			<p><strong><?php esc_html_e( 'Dónde rellenarlo:', 'minimal-seo-theme' ); ?></strong>
				<?php esc_html_e( 'Edita una entrada o página → baja a la caja «Arquitectura SEO e interlinking» → guarda.', 'minimal-seo-theme' ); ?>
				<a href="<?php echo esc_url( $posts_url ); ?>"><?php esc_html_e( 'Ir a mis entradas', 'minimal-seo-theme' ); ?></a>
			</p>
		</div>

		<div class="mst-guide-card">
			<h2><?php esc_html_e( 'Las 3 piezas (elige una por página)', 'minimal-seo-theme' ); ?></h2>
			<table class="widefat striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Nombre en la caja', 'minimal-seo-theme' ); ?></th>
						<th><?php esc_html_e( 'Qué es', 'minimal-seo-theme' ); ?></th>
						<th><?php esc_html_e( 'Ejemplo', 'minimal-seo-theme' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><strong><?php esc_html_e( 'Post Pilar', 'minimal-seo-theme' ); ?></strong></td>
						<td><?php esc_html_e( 'La página madre de todo un tema', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Todo sobre cuidado de la piel', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Post Categoría', 'minimal-seo-theme' ); ?></strong></td>
						<td><?php esc_html_e( 'Agrupa artículos parecidos', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Sensaciones al usar cremas', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Informativo / Comparativo / etc.', 'minimal-seo-theme' ); ?></strong></td>
						<td><?php esc_html_e( 'Un artículo que responde una pregunta', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Qué es el efecto cálido', 'minimal-seo-theme' ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="mst-guide-card">
			<h2><?php esc_html_e( 'Paso a paso (en orden)', 'minimal-seo-theme' ); ?></h2>
			<ol class="mst-guide-steps">
				<li><strong><?php esc_html_e( '1. Crea primero el Pilar y la Categoría', 'minimal-seo-theme' ); ?></strong> — <?php esc_html_e( 'Luego los artículos pequeños.', 'minimal-seo-theme' ); ?></li>
				<li><strong><?php esc_html_e( '2. Inventa un código (ID) para cada una', 'minimal-seo-theme' ); ?></strong> — <?php esc_html_e( 'Ejemplos: PIL-PIEL, CAT-SEN, POS-SEN-01. Sin espacios. Sin repetir.', 'minimal-seo-theme' ); ?></li>
				<li><strong><?php esc_html_e( '3. Elige el tipo de contenido', 'minimal-seo-theme' ); ?></strong> — <?php esc_html_e( 'En el desplegable de la caja.', 'minimal-seo-theme' ); ?></li>
				<li><strong><?php esc_html_e( '4. Parent ID = quién es el padre', 'minimal-seo-theme' ); ?></strong> — <?php esc_html_e( 'Pilar: vacío. Categoría: código del Pilar. Artículo: código de la Categoría.', 'minimal-seo-theme' ); ?></li>
				<li><strong><?php esc_html_e( '5. links_out = a dónde enlaza ESTA página', 'minimal-seo-theme' ); ?></strong> — <?php esc_html_e( 'Escribe códigos separados por comas. Mínimo 2 en artículos normales.', 'minimal-seo-theme' ); ?></li>
				<li><strong><?php esc_html_e( '6. links_in = quién debería enlazar HACIA aquí', 'minimal-seo-theme' ); ?></strong> — <?php esc_html_e( 'Es tu nota de planificación.', 'minimal-seo-theme' ); ?></li>
				<li><strong><?php esc_html_e( '7. E-commerce', 'minimal-seo-theme' ); ?></strong> — <?php esc_html_e( 'Marca la casilla y escribe el destino de tienda o «Sin enlace comercial».', 'minimal-seo-theme' ); ?></li>
				<li><strong><?php esc_html_e( '8. anchor_texts = texto del enlace azul', 'minimal-seo-theme' ); ?></strong> — <?php esc_html_e( 'Frases claras. Nunca «haz clic aquí» ni «ver más».', 'minimal-seo-theme' ); ?></li>
				<li><strong><?php esc_html_e( '9. Guarda y lee los avisos', 'minimal-seo-theme' ); ?></strong> — <?php esc_html_e( 'Rojo = corrige. Amarillo = sugerencia.', 'minimal-seo-theme' ); ?></li>
				<li><strong><?php esc_html_e( '10. Guarda: el tema inserta enlaces y valida', 'minimal-seo-theme' ); ?></strong> — <?php esc_html_e( 'Si hay errores rojos, no podrás publicar hasta corregir.', 'minimal-seo-theme' ); ?></li>
			</ol>
		</div>

		<div class="mst-guide-card">
			<h2><?php esc_html_e( 'Ejemplo para copiar y pegar', 'minimal-seo-theme' ); ?></h2>
			<p><?php esc_html_e( 'Artículo: «Qué es el efecto cálido»', 'minimal-seo-theme' ); ?></p>
			<table class="widefat striped">
				<tbody>
					<tr><th scope="row"><?php esc_html_e( 'ID', 'minimal-seo-theme' ); ?></th><td><code>POS-SEN-01</code></td></tr>
					<tr><th scope="row"><?php esc_html_e( 'Tipo', 'minimal-seo-theme' ); ?></th><td><?php esc_html_e( 'Informativo', 'minimal-seo-theme' ); ?></td></tr>
					<tr><th scope="row"><?php esc_html_e( 'Parent ID', 'minimal-seo-theme' ); ?></th><td><code>CAT-SEN</code></td></tr>
					<tr><th scope="row">links_out</th><td><code>CAT-SEN, POS-SEN-02, ING-03</code></td></tr>
					<tr><th scope="row">links_in</th><td><code>CAT-SEN, POS-SEN-04</code></td></tr>
					<tr><th scope="row"><?php esc_html_e( 'E-commerce', 'minimal-seo-theme' ); ?></th><td><?php esc_html_e( 'Marcado + «Categoría sensaciones»', 'minimal-seo-theme' ); ?></td></tr>
				</tbody>
			</table>
			<p><strong>anchor_texts</strong> (copia en la caja del editor):</p>
			<pre class="mst-guide-code">[
  {"target": "POS-SEN-02", "anchor": "comparativa entre sensación e irritación"}
]</pre>
		</div>

		<div class="mst-guide-card">
			<h2><?php esc_html_e( 'Cuánto debe medir cada texto', 'minimal-seo-theme' ); ?></h2>
			<table class="widefat striped">
				<thead><tr><th><?php esc_html_e( 'Tipo', 'minimal-seo-theme' ); ?></th><th><?php esc_html_e( 'Palabras aprox.', 'minimal-seo-theme' ); ?></th></tr></thead>
				<tbody>
					<tr><td><?php esc_html_e( 'Post Pilar', 'minimal-seo-theme' ); ?></td><td>3.000 – 5.500</td></tr>
					<tr><td><?php esc_html_e( 'Post Categoría', 'minimal-seo-theme' ); ?></td><td>1.800 – 3.500</td></tr>
					<tr><td><?php esc_html_e( 'Informativo', 'minimal-seo-theme' ); ?></td><td>800 – 1.600</td></tr>
					<tr><td><?php esc_html_e( 'Comparativo', 'minimal-seo-theme' ); ?></td><td>1.200 – 2.200</td></tr>
					<tr><td><?php esc_html_e( 'Diagnóstico', 'minimal-seo-theme' ); ?></td><td>1.000 – 1.800</td></tr>
					<tr><td><?php esc_html_e( 'Guía de compra', 'minimal-seo-theme' ); ?></td><td>1.300 – 2.500</td></tr>
				</tbody>
			</table>
			<p class="description"><?php esc_html_e( 'WordPress te avisa al guardar si te sales del rango. No hace falta contar a mano.', 'minimal-seo-theme' ); ?></p>
		</div>

		<div class="mst-guide-card">
			<h2><?php esc_html_e( 'Checklist antes de publicar', 'minimal-seo-theme' ); ?></h2>
			<ul class="mst-guide-checklist">
				<li><?php esc_html_e( 'Código único (ID)', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( 'Tipo elegido', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( 'Parent ID (si no es Pilar)', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( 'Al menos 2 links_out (artículos normales)', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( 'Decisión e-commerce (sí o no)', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( 'Frases de enlace claras (no «ver más»)', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( 'Enlaces visibles en el artículo (automáticos)', 'minimal-seo-theme' ); ?></li>
				<li><?php esc_html_e( 'Sin avisos rojos si quieres publicar', 'minimal-seo-theme' ); ?></li>
			</ul>
		</div>

		<div class="mst-guide-card">
			<h2><?php esc_html_e( 'Preguntas frecuentes', 'minimal-seo-theme' ); ?></h2>
			<dl class="mst-guide-faq">
				<dt><?php esc_html_e( '¿Tengo que saber programar?', 'minimal-seo-theme' ); ?></dt>
				<dd><?php esc_html_e( 'No. Solo copia los ejemplos y cambia los códigos.', 'minimal-seo-theme' ); ?></dd>
				<dt><?php esc_html_e( '¿El visitante ve el código (ID)?', 'minimal-seo-theme' ); ?></dt>
				<dd><?php esc_html_e( 'No. Solo lo ves tú en WordPress.', 'minimal-seo-theme' ); ?></dd>
				<dt><?php esc_html_e( '¿Dónde veo todo mi mapa?', 'minimal-seo-theme' ); ?></dt>
				<dd><a href="<?php echo esc_url( $matrix_url ); ?>"><?php esc_html_e( 'Apariencia → Matriz SEO', 'minimal-seo-theme' ); ?></a></dd>
				<dt><?php esc_html_e( '¿Qué hace solo la web?', 'minimal-seo-theme' ); ?></dt>
				<dd><?php esc_html_e( 'Inserta enlaces al guardar (anclas en el texto o bloque al final), avisa si falta algo y bloquea la publicación si hay errores rojos.', 'minimal-seo-theme' ); ?></dd>
			</dl>
		</div>

		<p>
			<a class="button button-primary" href="<?php echo esc_url( $posts_url ); ?>"><?php esc_html_e( 'Editar mis entradas', 'minimal-seo-theme' ); ?></a>
			<?php if ( function_exists( 'mst_get_architecture_examples_url' ) ) : ?>
				<a class="button" href="<?php echo esc_url( mst_get_architecture_examples_url() ); ?>"><?php esc_html_e( 'Plantillas por tipo (duplicar)', 'minimal-seo-theme' ); ?></a>
			<?php endif; ?>
			<a class="button" href="<?php echo esc_url( $matrix_url ); ?>"><?php esc_html_e( 'Ver Matriz SEO', 'minimal-seo-theme' ); ?></a>
			<a class="button" href="<?php echo esc_url( $guide_url ); ?>"><?php esc_html_e( 'Guía general de la plantilla', 'minimal-seo-theme' ); ?></a>
		</p>
	</div>
	<?php
}

/**
 * Estilos guía principiantes.
 *
 * @param string $hook Hook admin.
 */
function mst_architecture_beginner_guide_styles( $hook ) {
	if ( 'appearance_page_mst-architecture-beginner-guide' !== $hook ) {
		return;
	}
	wp_add_inline_style(
		'wp-admin',
		'.mst-beginner-guide .mst-guide-card{background:#fff;border:1px solid #c3c4c7;border-radius:6px;padding:1.25rem 1.5rem;margin:1rem 0;max-width:920px}
		.mst-beginner-guide .mst-guide-card--highlight{background:#f0f6fc;border-left:4px solid #2271b1}
		.mst-beginner-guide .mst-guide-card h2{margin-top:0}
		.mst-beginner-guide .mst-guide-steps li{margin-bottom:0.75em;line-height:1.55}
		.mst-beginner-guide .mst-guide-code{background:#1e1e1e;color:#d4d4d4;padding:12px;border-radius:4px;overflow:auto}
		.mst-beginner-guide .mst-guide-checklist{list-style:disc;margin-left:1.25em}
		.mst-beginner-guide .mst-guide-faq dt{font-weight:600;margin-top:0.75em}
		.mst-beginner-guide .mst-guide-faq dd{margin-left:0;margin-bottom:0.5em}'
	);
}
add_action( 'admin_enqueue_scripts', 'mst_architecture_beginner_guide_styles' );
