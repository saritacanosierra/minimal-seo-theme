<?php
/**
 * Constructor de inicio — Customizer (ligero, sin page builder)
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Defaults del constructor de inicio.
 */
function mst_home_defaults() {
	return array(
		'mst_home_hero_enable'      => true,
		'mst_home_hero_title'       => mst_ph( __( 'Título grande de la portada', 'minimal-seo-theme' ), __( 'Escribe el mensaje principal. Ejemplo: Recetas fáciles para cada día', 'minimal-seo-theme' ) ),
		'mst_home_hero_text'        => mst_ph( __( 'Texto debajo del título', 'minimal-seo-theme' ), __( 'Explica en 2 frases qué encontrará el visitante', 'minimal-seo-theme' ) ),
		'mst_home_hero_btn_text'    => mst_ph( __( 'Texto del botón', 'minimal-seo-theme' ), __( 'Ejemplo: Ver artículos / Empezar aquí', 'minimal-seo-theme' ) ),
		'mst_home_hero_btn_url'     => '#ultimas-publicaciones',
		'mst_home_hero_image'       => 0,
		'mst_home_cluster_enable'   => true,
		'mst_home_cluster_title'    => mst_ph( __( 'Título de la cuadrícula destacada', 'minimal-seo-theme' ), __( 'Ejemplo: Artículos de TEMA 1 (territorio)', 'minimal-seo-theme' ) ),
		'mst_home_cluster_desc'     => __( 'Acceso rápido al territorio. No es la guía completa (post pilar).', 'minimal-seo-theme' ),
		'mst_home_cluster_category' => '',
		'mst_home_cluster_posts'    => 6,
		'mst_home_cluster_columns'  => 3,
		'mst_home_cluster_featured' => 3,
		'mst_home_posts_enable'     => true,
		'mst_home_posts_title'      => mst_ph( __( 'Título de la lista de artículos', 'minimal-seo-theme' ), __( 'Ejemplo: Otros temas y artículos recientes', 'minimal-seo-theme' ) ),
		'mst_home_posts_desc'       => __( 'Artículos fuera de la cuadrícula superior (otros territorios).', 'minimal-seo-theme' ),
		'mst_home_cta_enable'       => true,
		'mst_home_cta_title'        => mst_ph( __( 'Título del recuadro final', 'minimal-seo-theme' ), __( 'Ejemplo: ¿Quieres que te ayudemos?', 'minimal-seo-theme' ) ),
		'mst_home_cta_text'         => mst_ph( __( 'Texto del recuadro final', 'minimal-seo-theme' ), __( '1 o 2 frases invitando a contactar o leer más', 'minimal-seo-theme' ) ),
		'mst_home_cta_btn_text'     => mst_ph( __( 'Texto del botón final', 'minimal-seo-theme' ), __( 'Ejemplo: Contactar / Ver oferta', 'minimal-seo-theme' ) ),
		'mst_home_cta_btn_url'      => '#ultimas-publicaciones',
	);
}

/**
 * Obtener opción del constructor de inicio.
 */
function mst_get_home_mod( $key ) {
	$defaults = mst_home_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	return get_theme_mod( $key, $default );
}

/**
 * Registrar controles del Customizer.
 */
function mst_home_customize_register( $wp_customize ) {
	$defaults = mst_home_defaults();

	$wp_customize->add_section(
		'mst_home_builder',
		array(
			'title'       => __( 'Constructor de inicio', 'minimal-seo-theme' ),
			'description' => __( 'Configura las secciones de tu página de inicio con textos sencillos.', 'minimal-seo-theme' ),
			'panel'       => 'mst_panel',
			'priority'    => 12,
		)
	);

	$checkboxes = array(
		'mst_home_hero_enable'    => __( 'Mostrar cabecera grande al inicio', 'minimal-seo-theme' ),
		'mst_home_cluster_enable' => __( 'Mostrar cuadrícula de artículos destacados', 'minimal-seo-theme' ),
		'mst_home_posts_enable'   => __( 'Mostrar lista de últimos artículos', 'minimal-seo-theme' ),
		'mst_home_cta_enable'     => __( 'Mostrar recuadro final con botón', 'minimal-seo-theme' ),
	);

	foreach ( $checkboxes as $id => $label ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $defaults[ $id ],
				'sanitize_callback' => 'mst_sanitize_checkbox',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => 'mst_home_builder',
				'type'    => 'checkbox',
			)
		);
	}

	$text_fields = array(
		'mst_home_hero_title'       => __( 'Cabecera — Título grande', 'minimal-seo-theme' ),
		'mst_home_hero_btn_text'    => __( 'Cabecera — Texto del botón', 'minimal-seo-theme' ),
		'mst_home_hero_btn_url'     => __( 'Cabecera — Enlace del botón', 'minimal-seo-theme' ),
		'mst_home_cluster_category' => __( 'Cuadrícula — Categoría (vacío = todos los artículos)', 'minimal-seo-theme' ),
		'mst_home_cluster_title'    => __( 'Cuadrícula — Título de sección', 'minimal-seo-theme' ),
		'mst_home_posts_title'      => __( 'Lista de artículos — Título de sección', 'minimal-seo-theme' ),
		'mst_home_cta_title'        => __( 'Recuadro final — Título', 'minimal-seo-theme' ),
		'mst_home_cta_btn_text'     => __( 'Recuadro final — Texto del botón', 'minimal-seo-theme' ),
		'mst_home_cta_btn_url'      => __( 'Recuadro final — Enlace del botón', 'minimal-seo-theme' ),
	);

	foreach ( $text_fields as $id => $label ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $defaults[ $id ],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => 'mst_home_builder',
				'type'    => 'text',
			)
		);
	}

	$areas = array(
		'mst_home_hero_text'        => __( 'Cabecera — Texto debajo del título', 'minimal-seo-theme' ),
		'mst_home_cluster_desc'     => __( 'Cuadrícula — Texto explicativo (debajo del título)', 'minimal-seo-theme' ),
		'mst_home_posts_desc'       => __( 'Lista de artículos — Texto explicativo', 'minimal-seo-theme' ),
		'mst_home_cta_text'         => __( 'Recuadro final — Texto descriptivo', 'minimal-seo-theme' ),
	);

	foreach ( $areas as $id => $label ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $defaults[ $id ],
				'sanitize_callback' => 'sanitize_textarea_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => 'mst_home_builder',
				'type'    => 'textarea',
			)
		);
	}

	$wp_customize->add_setting(
		'mst_home_hero_image',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'mst_home_hero_image',
			array(
				'label'     => __( 'Cabecera — Imagen de fondo (opcional)', 'minimal-seo-theme' ),
				'section'   => 'mst_home_builder',
				'mime_type' => 'image',
			)
		)
	);

	$wp_customize->add_setting(
		'mst_home_cluster_posts',
		array(
			'default'           => $defaults['mst_home_cluster_posts'],
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'mst_home_cluster_posts',
		array(
			'label'   => __( 'Cuadrícula — Cuántas tarjetas mostrar', 'minimal-seo-theme' ),
			'section' => 'mst_home_builder',
			'type'    => 'number',
			'input_attrs' => array( 'min' => 1, 'max' => 12, 'step' => 1 ),
		)
	);

	$wp_customize->add_setting(
		'mst_home_cluster_columns',
		array(
			'default'           => $defaults['mst_home_cluster_columns'],
			'sanitize_callback' => 'mst_sanitize_columns',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'mst_home_cluster_columns',
		array(
			'label'   => __( 'Cuadrícula — Columnas en pantalla grande', 'minimal-seo-theme' ),
			'section' => 'mst_home_builder',
			'type'    => 'select',
			'choices' => array( 1 => '1', 2 => '2', 3 => '3', 4 => '4' ),
		)
	);

	$wp_customize->add_setting(
		'mst_home_cluster_featured',
		array(
			'default'           => $defaults['mst_home_cluster_featured'],
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'mst_home_cluster_featured',
		array(
			'label'       => __( 'Cuadrícula — Cuántas tarjetas destacar arriba', 'minimal-seo-theme' ),
			'description' => __( 'Las primeras tarjetas se muestran en la fila superior.', 'minimal-seo-theme' ),
			'section'     => 'mst_home_builder',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 0, 'max' => 6, 'step' => 1 ),
		)
	);
}
add_action( 'customize_register', 'mst_home_customize_register', 20 );

/**
 * Aplicar textos por defecto del constructor (una vez por versión).
 */
function mst_maybe_seed_home_defaults() {
	$seeded = get_option( 'mst_home_seeded', '' );
	if ( version_compare( (string) $seeded, '2.4.0', '>=' ) ) {
		return;
	}
	foreach ( mst_home_defaults() as $key => $value ) {
		set_theme_mod( $key, $value );
	}
	update_option( 'mst_home_seeded', '2.4.0' );
}
add_action( 'after_setup_theme', 'mst_maybe_seed_home_defaults', 99 );

/**
 * Renderizar secciones superiores de la portada.
 */
function mst_render_home_top_sections() {
	if ( mst_get_home_mod( 'mst_home_hero_enable' ) ) {
		mst_render_home_hero();
	}
	if ( function_exists( 'mst_home_should_show_structure_guide' ) && mst_home_should_show_structure_guide() ) {
		mst_render_home_structure_guide();
	}
	if ( mst_get_home_mod( 'mst_home_cluster_enable' ) ) {
		mst_render_home_cluster();
	}
}

/**
 * Renderizar secciones inferiores de la portada.
 */
function mst_render_home_bottom_sections() {
	if ( mst_get_home_mod( 'mst_home_cta_enable' ) ) {
		mst_render_home_cta();
	}
}

/**
 * Guía visible en portada demo: portada ≠ guía completa (pilar).
 */
function mst_render_home_structure_guide() {
	$pillar1_id = (int) get_option( 'mst_pillar_page_id', 0 );
	$pillar1    = $pillar1_id ? get_permalink( $pillar1_id ) : home_url( '/tema-1/' );
	$guide_url  = admin_url( 'themes.php?page=mst-template-guide' );
	?>
	<aside class="mst-home-structure" role="note">
		<h2 class="mst-home-structure__title"><?php esc_html_e( '¿Qué es esta página?', 'minimal-seo-theme' ); ?></h2>
		<p><?php esc_html_e( 'Esta es la portada (blog). No es la «guía completa» ni el post pilar.', 'minimal-seo-theme' ); ?></p>
		<ul class="mst-home-structure__list">
			<li><strong><?php esc_html_e( 'Portada', 'minimal-seo-theme' ); ?></strong> — <?php esc_html_e( 'acceso rápido y resumen de contenidos', 'minimal-seo-theme' ); ?></li>
			<li><strong><?php esc_html_e( 'Inicio', 'minimal-seo-theme' ); ?></strong> — <?php esc_html_e( 'portada del blog (/)', 'minimal-seo-theme' ); ?></li>
			<li><strong><?php esc_html_e( 'TEMA 1 / TEMA 2', 'minimal-seo-theme' ); ?></strong> — <?php esc_html_e( 'territorios (páginas índice con [cluster])', 'minimal-seo-theme' ); ?></li>
			<li><strong><?php esc_html_e( 'Guía completa', 'minimal-seo-theme' ); ?></strong> — <?php esc_html_e( 'post pilar opcional que agrupa todos los territorios', 'minimal-seo-theme' ); ?></li>
		</ul>
		<p class="mst-home-structure__actions">
			<a class="mst-btn mst-btn--small" href="<?php echo esc_url( $pillar1 ); ?>"><?php esc_html_e( 'Ir al territorio TEMA 1', 'minimal-seo-theme' ); ?></a>
			<a class="mst-home-structure__link" href="<?php echo esc_url( $guide_url ); ?>"><?php esc_html_e( 'Ver guía de la plantilla', 'minimal-seo-theme' ); ?></a>
		</p>
	</aside>
	<?php
}

/**
 * Cabecera de sección reutilizable.
 *
 * @param string $title       Título H2.
 * @param string $description Párrafo opcional.
 * @param string $title_class Clase extra del título.
 */
function mst_render_section_header( $title, $description = '', $title_class = 'mst-section__title' ) {
	if ( ! $title && ! $description ) {
		return;
	}
	echo '<header class="mst-section__header">';
	if ( $title ) {
		echo '<h2' . mst_placeholder_class_attr( $title, $title_class ) . '>' . esc_html( $title ) . '</h2>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	if ( $description ) {
		echo '<p' . mst_placeholder_class_attr( $description, 'mst-section__desc' ) . '>' . esc_html( $description ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	echo '</header>';
}

/**
 * Hero de portada (H2 — el H1 es el nombre del sitio o título de página).
 */
function mst_render_home_hero() {
	$title    = mst_get_home_mod( 'mst_home_hero_title' );
	$text     = mst_get_home_mod( 'mst_home_hero_text' );
	$btn_text = mst_get_home_mod( 'mst_home_hero_btn_text' );
	$btn_url  = mst_get_home_mod( 'mst_home_hero_btn_url' );
	$image_id = absint( mst_get_home_mod( 'mst_home_hero_image' ) );

	if ( empty( $title ) ) {
		$title = get_bloginfo( 'description' );
	}
	if ( empty( $title ) && empty( $text ) && empty( $btn_text ) ) {
		return;
	}

	$style = '';
	if ( $image_id ) {
		$url = wp_get_attachment_image_url( $image_id, 'mst-hero' );
		if ( $url ) {
			$style = ' style="background-image:url(' . esc_url( $url ) . ')"';
		}
	}
	?>
	<section class="mst-section mst-hero"<?php echo $style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="mst-hero__inner">
			<?php if ( $title ) : ?>
				<h2<?php echo mst_placeholder_class_attr( $title, 'mst-hero__title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>
			<?php if ( $text ) : ?>
				<p<?php echo mst_placeholder_class_attr( $text, 'mst-hero__text' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>
			<?php if ( $btn_text && $btn_url ) : ?>
				<p class="mst-hero__actions">
					<a class="mst-btn" href="<?php echo esc_url( $btn_url ); ?>"><?php echo esc_html( $btn_text ); ?></a>
				</p>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Cluster en portada vía shortcode existente.
 */
function mst_render_home_cluster() {
	$cat = mst_get_home_mod( 'mst_home_cluster_category' );
	$atts = array(
		'posts_per_page' => mst_get_home_mod( 'mst_home_cluster_posts' ),
		'columns'        => mst_get_home_mod( 'mst_home_cluster_columns' ),
		'featured_auto'  => mst_get_home_mod( 'mst_home_cluster_featured' ),
		'post_type'      => 'post',
		'load_more'      => 'yes',
	);
	if ( $cat ) {
		$atts['category'] = $cat;
	}
	$shortcode = '[cluster';
	foreach ( $atts as $key => $value ) {
		$shortcode .= ' ' . $key . '="' . esc_attr( $value ) . '"';
	}
	$shortcode .= ']';

	echo '<section class="mst-section mst-section--cluster">';
	mst_render_section_header(
		mst_get_home_mod( 'mst_home_cluster_title' ),
		mst_get_home_mod( 'mst_home_cluster_desc' )
	);
	echo do_shortcode( $shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '</section>';
}

/**
 * Título H2 para bloque de últimas entradas.
 */
function mst_render_home_posts_heading() {
	if ( ! mst_get_home_mod( 'mst_home_posts_enable' ) ) {
		return;
	}
	$title = mst_get_home_mod( 'mst_home_posts_title' );
	$desc  = mst_get_home_mod( 'mst_home_posts_desc' );
	if ( ! $title && ! $desc ) {
		return;
	}
	echo '<div id="ultimas-publicaciones" class="mst-section__header mst-section__header--posts">';
	if ( $title ) {
		echo '<h2' . mst_placeholder_class_attr( $title, 'mst-section__title' ) . '>' . esc_html( $title ) . '</h2>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	if ( $desc ) {
		echo '<p class="mst-section__desc">' . esc_html( $desc ) . '</p>';
	}
	echo '</div>';
}

/**
 * Banner CTA final.
 */
function mst_render_home_cta() {
	$title    = mst_get_home_mod( 'mst_home_cta_title' );
	$text     = mst_get_home_mod( 'mst_home_cta_text' );
	$btn_text = mst_get_home_mod( 'mst_home_cta_btn_text' );
	$btn_url  = mst_get_home_mod( 'mst_home_cta_btn_url' );

	if ( empty( $title ) ) {
		return;
	}
	?>
	<section class="mst-section mst-cta-banner">
		<div class="mst-cta-banner__inner">
			<h2<?php echo mst_placeholder_class_attr( $title, 'mst-cta-banner__title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $title ); ?></h2>
			<?php if ( $text ) : ?>
				<p<?php echo mst_placeholder_class_attr( $text, 'mst-cta-banner__text' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>
			<?php if ( $btn_text && $btn_url ) : ?>
				<p><a class="mst-btn mst-btn--light" href="<?php echo esc_url( $btn_url ); ?>"><?php echo esc_html( $btn_text ); ?></a></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * ¿Mostrar grid de posts en portada estática?
 */
function mst_home_shows_posts_grid() {
	return is_front_page() && ! is_home() && mst_get_home_mod( 'mst_home_posts_enable' );
}

/**
 * Evitar duplicar en portada los artículos ya mostrados en la cuadrícula cluster.
 *
 * @param WP_Query $query Query principal.
 */
function mst_filter_home_posts_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! ( is_front_page() && is_home() ) ) {
		return;
	}

	if ( ! mst_get_home_mod( 'mst_home_cluster_enable' ) ) {
		return;
	}

	$cat = mst_get_home_mod( 'mst_home_cluster_category' );
	if ( ! $cat ) {
		return;
	}

	$tax_query   = $query->get( 'tax_query' );
	$tax_query   = is_array( $tax_query ) ? $tax_query : array();
	$tax_query[] = array(
		'taxonomy' => 'category',
		'field'    => is_numeric( $cat ) ? 'term_id' : 'slug',
		'terms'    => $cat,
		'operator' => 'NOT IN',
	);

	$query->set( 'tax_query', $tax_query ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
}
add_action( 'pre_get_posts', 'mst_filter_home_posts_query' );
