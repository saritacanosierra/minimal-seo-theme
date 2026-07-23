<?php
/**
 * Guía del administrador — lenguaje sencillo para editar la plantilla
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Meta del usuario: aviso de bienvenida descartado.
 */
function mst_admin_notice_dismissed_meta() {
	return 'mst_admin_guide_dismissed';
}

/**
 * Aviso al entrar al admin (descartable).
 */
function mst_render_admin_welcome_notice() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	if ( get_user_meta( get_current_user_id(), mst_admin_notice_dismissed_meta(), true ) ) {
		return;
	}

	$guide_url = admin_url( 'themes.php?page=mst-template-guide' );
	?>
	<div class="notice notice-info is-dismissible mst-admin-notice" data-mst-notice="welcome">
		<p><strong><?php esc_html_e( '¿Por dónde empiezo con mi web?', 'minimal-seo-theme' ); ?></strong></p>
		<ol style="margin:0.5em 0 0.75em 1.25em;list-style:decimal">
			<li><?php esc_html_e( 'Portada: Apariencia → Personalizar → Constructor de inicio', 'minimal-seo-theme' ); ?></li>
			<li><?php esc_html_e( 'Páginas índice TEMA 1 y TEMA 2: menú Páginas (ejemplo de sitio con dos temas)', 'minimal-seo-theme' ); ?></li>
			<li><?php esc_html_e( 'Artículos: menú Entradas → cambia los textos que dicen [EDITAR]', 'minimal-seo-theme' ); ?></li>
			<li><?php esc_html_e( 'Categoría del tema: Entradas → Categorías', 'minimal-seo-theme' ); ?></li>
			<li><?php esc_html_e( 'Menú del sitio: Apariencia → Menús (usa nombres cortos)', 'minimal-seo-theme' ); ?></li>
			<li><?php esc_html_e( 'Mapa de decisiones SEO: Apariencia → Mapa de decisiones (empieza aquí si es tu primera web con esta plantilla)', 'minimal-seo-theme' ); ?></li>
		</ol>
		<p>
			<a class="button button-primary" href="<?php echo esc_url( $guide_url ); ?>"><?php esc_html_e( 'Ver guía completa paso a paso', 'minimal-seo-theme' ); ?></a>
			<a class="button" href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=mst_home_builder' ) ); ?>"><?php esc_html_e( 'Editar portada ahora', 'minimal-seo-theme' ); ?></a>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'mst_render_admin_welcome_notice' );

/**
 * Guardar descarte del aviso vía AJAX.
 */
function mst_dismiss_admin_notice() {
	check_ajax_referer( 'mst_admin_guide', 'nonce' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error();
	}

	update_user_meta( get_current_user_id(), mst_admin_notice_dismissed_meta(), '1' );
	wp_send_json_success();
}
add_action( 'wp_ajax_mst_dismiss_admin_notice', 'mst_dismiss_admin_notice' );

/**
 * Script para cerrar el aviso.
 */
function mst_admin_guide_scripts( $hook ) {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	wp_enqueue_script(
		'mst-admin-guide',
		MST_URI . '/assets/js/admin-guide.js',
		array( 'jquery' ),
		MST_VERSION,
		true
	);

	wp_localize_script(
		'mst-admin-guide',
		'mstAdminGuide',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'mst_admin_guide' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'mst_admin_guide_scripts' );

/**
 * Página de guía bajo Apariencia.
 */
function mst_register_template_guide_page() {
	add_theme_page(
		__( 'Guía de la plantilla', 'minimal-seo-theme' ),
		__( 'Guía: qué editar', 'minimal-seo-theme' ),
		'edit_posts',
		'mst-template-guide',
		'mst_render_template_guide_page'
	);
}
add_action( 'admin_menu', 'mst_register_template_guide_page' );

/**
 * Enlaces directos útiles para la guía.
 */
function mst_get_admin_guide_links() {
	$pillar_id  = (int) get_option( 'mst_pillar_page_id', 0 );
	$pillar2_id = (int) get_option( 'mst_pillar_page_id_2', 0 );
	$pillar     = $pillar_id ? get_edit_post_link( $pillar_id, 'raw' ) : admin_url( 'edit.php?post_type=page' );
	$pillar2    = $pillar2_id ? get_edit_post_link( $pillar2_id, 'raw' ) : admin_url( 'edit.php?post_type=page' );

	return array(
		'customizer_home' => admin_url( 'customize.php?autofocus[section]=mst_home_builder' ),
		'customizer_site' => admin_url( 'customize.php?autofocus[section]=title_tagline' ),
		'posts'           => admin_url( 'edit.php' ),
		'pages'           => admin_url( 'edit.php?post_type=page' ),
		'pillar'          => $pillar,
		'pillar_2'        => $pillar2,
		'categories'      => admin_url( 'edit-tags.php?taxonomy=category' ),
		'menus'           => admin_url( 'nav-menus.php' ),
	);
}

/**
 * HTML de la guía completa.
 */
function mst_render_template_guide_page() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	$links = mst_get_admin_guide_links();
	?>
	<div class="wrap mst-guide-wrap">
		<h1><?php esc_html_e( 'Guía de la plantilla — qué editar y dónde', 'minimal-seo-theme' ); ?></h1>
		<p class="description"><?php esc_html_e( 'Esta guía usa palabras sencillas. Los textos [EDITAR] en tu web son recordatorios: cámbialos por tu contenido real.', 'minimal-seo-theme' ); ?></p>

		<div class="mst-guide-card">
			<h2><?php esc_html_e( '¿Qué significa cada menú de WordPress?', 'minimal-seo-theme' ); ?></h2>
			<table class="widefat striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'WordPress dice…', 'minimal-seo-theme' ); ?></th>
						<th><?php esc_html_e( 'En la práctica es…', 'minimal-seo-theme' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><strong><?php esc_html_e( 'Entradas', 'minimal-seo-theme' ); ?></strong></td>
						<td><?php esc_html_e( 'Tus artículos del blog (el contenido largo de cada tema)', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Páginas', 'minimal-seo-theme' ); ?></strong></td>
						<td><?php esc_html_e( 'Páginas fijas: la página índice de tu tema, contacto, legal, etc.', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Categorías', 'minimal-seo-theme' ); ?></strong></td>
						<td><?php esc_html_e( 'El nombre de cada tema (TEMA 1, TEMA 2… agrupa artículos relacionados)', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Apariencia → Personalizar', 'minimal-seo-theme' ); ?></strong></td>
						<td><?php esc_html_e( 'Textos e imágenes de la portada, colores y logo', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Apariencia → Menús', 'minimal-seo-theme' ); ?></strong></td>
						<td><?php esc_html_e( 'Los enlaces del menú superior (usa nombres cortos: Inicio, Tu tema, Contacto)', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Campos extra del tema', 'minimal-seo-theme' ); ?></strong></td>
						<td><?php esc_html_e( 'Caja en la barra lateral al editar: subtítulo, introducción, botón y texto de tarjeta', 'minimal-seo-theme' ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="mst-guide-card">
			<h2><?php esc_html_e( 'Orden recomendado (paso a paso)', 'minimal-seo-theme' ); ?></h2>
			<ol class="mst-guide-steps">
				<li>
					<strong><?php esc_html_e( '1. Portada', 'minimal-seo-theme' ); ?></strong> —
					<?php esc_html_e( 'Título grande, texto de bienvenida y botón.', 'minimal-seo-theme' ); ?>
					<a href="<?php echo esc_url( $links['customizer_home'] ); ?>"><?php esc_html_e( 'Abrir constructor de inicio', 'minimal-seo-theme' ); ?></a>
				</li>
				<li>
					<strong><?php esc_html_e( '2. Nombre y logo del sitio', 'minimal-seo-theme' ); ?></strong> —
					<?php esc_html_e( 'Nombre de tu web y frase corta debajo.', 'minimal-seo-theme' ); ?>
					<a href="<?php echo esc_url( $links['customizer_site'] ); ?>"><?php esc_html_e( 'Abrir identidad del sitio', 'minimal-seo-theme' ); ?></a>
				</li>
				<li>
					<strong><?php esc_html_e( '3. Páginas índice TEMA 1 y TEMA 2', 'minimal-seo-theme' ); ?></strong> —
					<?php esc_html_e( 'En el mapa de decisiones esto es un territorio (Post categoría), no el pilar. Renombra TEMA 1 por tu territorio real (ej: Sensaciones). No borres el bloque [cluster].', 'minimal-seo-theme' ); ?>
					<a href="<?php echo esc_url( $links['pillar'] ); ?>"><?php esc_html_e( 'Editar TEMA 1', 'minimal-seo-theme' ); ?></a>
					·
					<a href="<?php echo esc_url( $links['pillar_2'] ); ?>"><?php esc_html_e( 'Editar TEMA 2', 'minimal-seo-theme' ); ?></a>
				</li>
				<li>
					<strong><?php esc_html_e( '4. Artículos', 'minimal-seo-theme' ); ?></strong> —
					<?php esc_html_e( 'Cambia título, resumen, imagen y textos [EDITAR] de cada artículo.', 'minimal-seo-theme' ); ?>
					<a href="<?php echo esc_url( $links['posts'] ); ?>"><?php esc_html_e( 'Ver entradas', 'minimal-seo-theme' ); ?></a>
				</li>
				<li>
					<strong><?php esc_html_e( '5. Categorías TEMA 1 y TEMA 2', 'minimal-seo-theme' ); ?></strong> —
					<?php esc_html_e( 'Pon el nombre real de cada tema (ej: Recetas, Marketing, Mascotas). Una categoría por tema.', 'minimal-seo-theme' ); ?>
					<a href="<?php echo esc_url( $links['categories'] ); ?>"><?php esc_html_e( 'Editar categorías', 'minimal-seo-theme' ); ?></a>
				</li>
				<li>
					<strong><?php esc_html_e( '6. Página de oferta', 'minimal-seo-theme' ); ?></strong> —
					<?php esc_html_e( 'Conversión (venta/contacto). En arquitectura SEO: tipo Guía de compra. No confundir con artículos educativos.', 'minimal-seo-theme' ); ?>
					<a href="<?php echo esc_url( $links['posts'] ); ?>"><?php esc_html_e( 'Editar artículos', 'minimal-seo-theme' ); ?></a>
				</li>
				<li>
					<strong><?php esc_html_e( '7. Menú del sitio', 'minimal-seo-theme' ); ?></strong> —
					<?php esc_html_e( 'Inicio, tu tema (nombre corto) y Contacto.', 'minimal-seo-theme' ); ?>
					<a href="<?php echo esc_url( $links['menus'] ); ?>"><?php esc_html_e( 'Editar menús', 'minimal-seo-theme' ); ?></a>
				</li>
				<li>
					<strong><?php esc_html_e( '8. Mapa de decisiones SEO', 'minimal-seo-theme' ); ?></strong> —
					<?php esc_html_e( 'Antes de redactar: tipo, enlaces y marketplace en cada URL.', 'minimal-seo-theme' ); ?>
					<a href="<?php echo esc_url( function_exists( 'mst_get_architecture_client_map_url' ) ? mst_get_architecture_client_map_url() : admin_url( 'themes.php?page=mst-architecture-client-map' ) ); ?>"><?php esc_html_e( 'Abrir mapa de decisiones', 'minimal-seo-theme' ); ?></a>
				</li>
			</ol>
		</div>

		<div class="mst-guide-card">
			<h2><?php esc_html_e( 'TEMA vs Pilar vs Territorio (mapa del cliente)', 'minimal-seo-theme' ); ?></h2>
			<table class="widefat striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Nombre', 'minimal-seo-theme' ); ?></th>
						<th><?php esc_html_e( 'Qué es', 'minimal-seo-theme' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><strong><?php esc_html_e( 'Territorio', 'minimal-seo-theme' ); ?></strong></td>
						<td><?php esc_html_e( 'Un bloque temático del negocio (ej: Sensaciones, Sabores). En la demo = TEMA 1.', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Post categoría', 'minimal-seo-theme' ); ?></strong></td>
						<td><?php esc_html_e( 'Página índice del territorio (/tema-1/) con tarjetas [cluster].', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Post pilar', 'minimal-seo-theme' ); ?></strong></td>
						<td><?php esc_html_e( 'Mapa de TODOS los territorios. Opcional al inicio; créalo cuando tengas varios.', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Oferta', 'minimal-seo-theme' ); ?></strong></td>
						<td><?php esc_html_e( 'Página de conversión. Tipo Guía de compra en arquitectura SEO.', 'minimal-seo-theme' ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="mst-guide-card">
			<h2><?php esc_html_e( 'Qué va en cada zona de una entrada', 'minimal-seo-theme' ); ?></h2>
			<table class="widefat striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Zona', 'minimal-seo-theme' ); ?></th>
						<th><?php esc_html_e( 'Dónde se edita', 'minimal-seo-theme' ); ?></th>
						<th><?php esc_html_e( 'Qué escribir', 'minimal-seo-theme' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php esc_html_e( 'Título principal', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Arriba del editor', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Nombre del artículo', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Resumen corto', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Caja "Extracto" (columna derecha)', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( '1–2 frases para listas y tarjetas', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Línea bajo el título', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Campos extra → Línea bajo el título', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Frase que complementa el título', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Texto de introducción', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Campos extra → Texto de introducción', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Engancha al lector en 1–2 frases', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Contenido del artículo', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Editor principal', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Párrafos y subtítulos grandes (reemplaza Lorem ipsum)', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Imagen destacada', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Columna derecha → Imagen destacada', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Foto o imagen del artículo', 'minimal-seo-theme' ); ?></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Botón al final', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Campos extra → Botón al final', 'minimal-seo-theme' ); ?></td>
						<td><?php esc_html_e( 'Texto y enlace (Contactar, Ver más, etc.)', 'minimal-seo-theme' ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<?php
}

/**
 * Estilos de la guía en el admin.
 */
function mst_admin_guide_styles( $hook ) {
	if ( 'appearance_page_mst-template-guide' !== $hook && 'index.php' !== $hook ) {
		return;
	}

	wp_add_inline_style(
		'wp-admin',
		'.mst-guide-wrap .mst-guide-card{background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:1.25rem 1.5rem;margin:1.25rem 0;max-width:900px}
		.mst-guide-wrap .mst-guide-card h2{margin-top:0}
		.mst-guide-steps li{margin-bottom:0.85em;line-height:1.5}
		.mst-guide-steps a{margin-left:0.35em}
		#mst_guide_dashboard .inside ul{margin:0 0 0 1.1em;list-style:disc}
		#mst_guide_dashboard .inside li{margin-bottom:0.5em}'
	);
}
add_action( 'admin_enqueue_scripts', 'mst_admin_guide_styles' );

/**
 * Widget en el escritorio de WordPress.
 */
function mst_register_dashboard_guide_widget() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	wp_add_dashboard_widget(
		'mst_guide_dashboard',
		__( 'Tu plantilla — por dónde empezar', 'minimal-seo-theme' ),
		'mst_render_dashboard_guide_widget'
	);
}
add_action( 'wp_dashboard_setup', 'mst_register_dashboard_guide_widget' );

/**
 * Contenido del widget del escritorio.
 */
function mst_render_dashboard_guide_widget() {
	$links = mst_get_admin_guide_links();
	?>
	<ul>
		<li><a href="<?php echo esc_url( $links['customizer_home'] ); ?>"><?php esc_html_e( 'Editar textos de la portada', 'minimal-seo-theme' ); ?></a></li>
		<li><a href="<?php echo esc_url( $links['pillar'] ); ?>"><?php esc_html_e( 'Editar página índice TEMA 1', 'minimal-seo-theme' ); ?></a></li>
		<li><a href="<?php echo esc_url( $links['pillar_2'] ); ?>"><?php esc_html_e( 'Editar página índice TEMA 2', 'minimal-seo-theme' ); ?></a></li>
		<li><a href="<?php echo esc_url( $links['posts'] ); ?>"><?php esc_html_e( 'Editar artículos ([EDITAR])', 'minimal-seo-theme' ); ?></a></li>
		<li><a href="<?php echo esc_url( $links['menus'] ); ?>"><?php esc_html_e( 'Configurar menú (nombres cortos)', 'minimal-seo-theme' ); ?></a></li>
		<?php if ( function_exists( 'mst_get_architecture_beginner_guide_url' ) ) : ?>
			<li><a href="<?php echo esc_url( mst_get_architecture_beginner_guide_url() ); ?>"><?php esc_html_e( 'Guía fácil: enlaces entre artículos', 'minimal-seo-theme' ); ?></a></li>
		<?php endif; ?>
	</ul>
	<p><a class="button button-primary" href="<?php echo esc_url( admin_url( 'themes.php?page=mst-template-guide' ) ); ?>"><?php esc_html_e( 'Ver guía completa', 'minimal-seo-theme' ); ?></a></p>
	<?php
}

/**
 * Ayuda contextual al editar entradas y páginas.
 */
function mst_render_editor_help_notice() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || ! in_array( $screen->base, array( 'post', 'page' ), true ) ) {
		return;
	}

	if ( ! in_array( $screen->post_type, array( 'post', 'page' ), true ) ) {
		return;
	}

	$is_page = 'page' === $screen->post_type;
	?>
	<div class="notice notice-info inline" style="margin:1em 0">
		<p>
			<strong><?php esc_html_e( '¿Qué poner aquí?', 'minimal-seo-theme' ); ?></strong>
			<?php
			if ( $is_page ) {
				esc_html_e( 'Esta es una página fija. Si es tu página índice del tema: texto corto de presentación + no borres el bloque [cluster]. Los campos de la barra lateral completan el subtítulo y el botón.', 'minimal-seo-theme' );
			} else {
				esc_html_e( 'Artículo del blog: cambia el título, el resumen (Extracto), la imagen destacada y todos los textos [EDITAR]. Revisa "Campos extra del tema" en la barra lateral.', 'minimal-seo-theme' );
			}
			?>
			<a href="<?php echo esc_url( admin_url( 'themes.php?page=mst-template-guide' ) ); ?>"><?php esc_html_e( 'Ver guía', 'minimal-seo-theme' ); ?></a>
		</p>
	</div>
	<?php
}
add_action( 'edit_form_top', 'mst_render_editor_help_notice' );
