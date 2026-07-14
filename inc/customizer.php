<?php
/**
 * WordPress Customizer — Minimal SEO Theme
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Pilas de fuentes del sistema (sin Google Fonts).
 */
function mst_font_stacks() {
	return array(
		'system'  => 'system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
		'segoe'   => '"Segoe UI", system-ui, -apple-system, sans-serif',
		'roboto'  => 'Roboto, system-ui, -apple-system, sans-serif',
		'georgia' => 'Georgia, "Times New Roman", Times, serif',
		'serif'   => '"Palatino Linotype", Palatino, Georgia, serif',
		'mono'    => 'ui-monospace, "Cascadia Code", "Courier New", monospace',
	);
}

/**
 * Defaults del Customizer.
 */
function mst_customizer_defaults() {
	return array(
		'mst_color_text'         => '#1a1a1a',
		'mst_color_muted'        => '#5c5c5c',
		'mst_color_bg'           => '#ffffff',
		'mst_color_accent'       => '#0066cc',
		'mst_color_accent_hover' => '#004d99',
		'mst_font_stack'         => 'system',
		'mst_font_size'          => 16,
		'mst_line_height'        => 1.6,
		'mst_max_width'          => 1200,
		'mst_content_width'      => 700,
		'mst_cluster_columns'    => 3,
		'mst_cluster_posts'      => 6,
		'mst_cluster_cta'        => __( 'Leer más', 'minimal-seo-theme' ),
		'mst_cluster_excerpt'    => true,
		'mst_cluster_show_meta'  => true,
		'mst_cluster_featured_auto' => 3,
		'mst_sidebar_layout'     => 'none',
		'mst_related_enable'     => true,
		'mst_related_count'      => 4,
		'mst_related_title'      => __( 'Artículos relacionados', 'minimal-seo-theme' ),
		'mst_adsense_enabled'    => false,
		'mst_adsense_code'       => '',
		'mst_adsense_label'      => '',
		'mst_adsense_min_height' => 250,
	);
}

/**
 * Obtener valor del Customizer con fallback.
 */
function mst_get_mod( $key ) {
	$defaults = mst_customizer_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	return get_theme_mod( $key, $default );
}

/**
 * Registrar opciones del Customizer.
 */
function mst_customize_register( $wp_customize ) {
	$defaults = mst_customizer_defaults();
	$fonts    = mst_font_stacks();

	$wp_customize->add_panel(
		'mst_panel',
		array(
			'title'       => __( 'Minimal SEO Theme', 'minimal-seo-theme' ),
			'description' => __( 'Colores, tipografía, diseño, clusters y monetización.', 'minimal-seo-theme' ),
			'priority'    => 30,
		)
	);

	/* --- Colores --- */
	$wp_customize->add_section(
		'mst_colors',
		array(
			'title'    => __( 'Colores', 'minimal-seo-theme' ),
			'panel'    => 'mst_panel',
			'priority' => 10,
		)
	);

	$colors = array(
		'mst_color_text'         => __( 'Color de texto', 'minimal-seo-theme' ),
		'mst_color_muted'        => __( 'Color de texto secundario', 'minimal-seo-theme' ),
		'mst_color_bg'           => __( 'Color de fondo', 'minimal-seo-theme' ),
		'mst_color_accent'       => __( 'Color de acento', 'minimal-seo-theme' ),
		'mst_color_accent_hover' => __( 'Color de acento (hover)', 'minimal-seo-theme' ),
	);

	foreach ( $colors as $id => $label ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $defaults[ $id ],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$id,
				array(
					'label'   => $label,
					'section' => 'mst_colors',
				)
			)
		);
	}

	/* --- Tipografía --- */
	$wp_customize->add_section(
		'mst_typography',
		array(
			'title'       => __( 'Tipografía', 'minimal-seo-theme' ),
			'description' => __( 'Fuentes del sistema. No se cargan recursos externos para máximo rendimiento.', 'minimal-seo-theme' ),
			'panel'       => 'mst_panel',
			'priority'    => 15,
		)
	);

	$wp_customize->add_setting(
		'mst_font_stack',
		array(
			'default'           => $defaults['mst_font_stack'],
			'sanitize_callback' => 'mst_sanitize_font_stack',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'mst_font_stack',
		array(
			'label'   => __( 'Familia tipográfica', 'minimal-seo-theme' ),
			'section' => 'mst_typography',
			'type'    => 'select',
			'choices' => array(
				'system'  => __( 'System UI (recomendada)', 'minimal-seo-theme' ),
				'segoe'   => 'Segoe UI',
				'roboto'  => 'Roboto (sistema)',
				'georgia' => 'Georgia',
				'serif'   => __( 'Serif clásica', 'minimal-seo-theme' ),
				'mono'    => __( 'Monoespaciada', 'minimal-seo-theme' ),
			),
		)
	);

	$wp_customize->add_setting(
		'mst_font_size',
		array(
			'default'           => $defaults['mst_font_size'],
			'sanitize_callback' => 'mst_sanitize_font_size',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'mst_font_size',
		array(
			'label'       => __( 'Tamaño base (px)', 'minimal-seo-theme' ),
			'section'     => 'mst_typography',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 14,
				'max'  => 22,
				'step' => 1,
			),
		)
	);

	$wp_customize->add_setting(
		'mst_line_height',
		array(
			'default'           => $defaults['mst_line_height'],
			'sanitize_callback' => 'mst_sanitize_line_height',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'mst_line_height',
		array(
			'label'       => __( 'Interlineado', 'minimal-seo-theme' ),
			'section'     => 'mst_typography',
			'type'        => 'select',
			'choices'     => array(
				'1.4'  => '1.4 — Compacto',
				'1.5'  => '1.5',
				'1.6'  => '1.6 — Recomendado',
				'1.7'  => '1.7',
				'1.75' => '1.75 — Lectura cómoda',
				'1.8'  => '1.8 — Amplio',
				'2'    => '2.0',
			),
		)
	);

	/* --- Diseño / Layout --- */
	$wp_customize->add_section(
		'mst_layout',
		array(
			'title'       => __( 'Diseño', 'minimal-seo-theme' ),
			'description' => __( 'Controla el ancho máximo del sitio y del contenido de lectura.', 'minimal-seo-theme' ),
			'panel'       => 'mst_panel',
			'priority'    => 18,
		)
	);

	$wp_customize->add_setting(
		'mst_max_width',
		array(
			'default'           => $defaults['mst_max_width'],
			'sanitize_callback' => 'mst_sanitize_max_width',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'mst_max_width',
		array(
			'label'       => __( 'Ancho máximo del sitio (px)', 'minimal-seo-theme' ),
			'description' => __( 'Ancho de texto, tarjetas, cabecera y todas las secciones.', 'minimal-seo-theme' ),
			'section'     => 'mst_layout',
			'type'        => 'select',
			'choices'     => array(
				960  => '960 px',
				1080 => '1080 px',
				1200 => '1200 px — Por defecto',
				1320 => '1320 px',
				1400 => '1400 px',
			),
		)
	);

	$wp_customize->add_setting(
		'mst_content_width',
		array(
			'default'           => $defaults['mst_content_width'],
			'sanitize_callback' => 'mst_sanitize_content_width',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'mst_content_width',
		array(
			'label'       => __( 'Ancho del contenido (px)', 'minimal-seo-theme' ),
			'description' => __( 'Opcional: ancho estrecho solo si activas bloques de lectura en el futuro.', 'minimal-seo-theme' ),
			'section'     => 'mst_layout',
			'type'        => 'select',
			'choices'     => array(
				600 => '600 px',
				650 => '650 px',
				700 => '700 px — Por defecto',
				750 => '750 px',
				800 => '800 px',
			),
		)
	);

	$wp_customize->add_setting(
		'mst_sidebar_layout',
		array(
			'default'           => $defaults['mst_sidebar_layout'],
			'sanitize_callback' => 'mst_sanitize_sidebar_layout',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'mst_sidebar_layout',
		array(
			'label'       => __( 'Barra lateral', 'minimal-seo-theme' ),
			'description' => __( 'Unibody = sin sidebar. Requiere widgets en Barra lateral.', 'minimal-seo-theme' ),
			'section'     => 'mst_layout',
			'type'        => 'select',
			'choices'     => array(
				'none'  => __( 'Unibody (sin sidebar)', 'minimal-seo-theme' ),
				'left'  => __( 'Sidebar izquierda', 'minimal-seo-theme' ),
				'right' => __( 'Sidebar derecha', 'minimal-seo-theme' ),
			),
		)
	);

	/* --- Clusters --- */
	$wp_customize->add_section(
		'mst_clusters',
		array(
			'title'       => __( 'Clusters', 'minimal-seo-theme' ),
			'description' => __( 'Valores por defecto del shortcode [cluster]. Puedes sobrescribirlos con atributos en cada shortcode.', 'minimal-seo-theme' ),
			'panel'       => 'mst_panel',
			'priority'    => 20,
		)
	);

	$wp_customize->add_setting(
		'mst_cluster_columns',
		array(
			'default'           => $defaults['mst_cluster_columns'],
			'sanitize_callback' => 'mst_sanitize_columns',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'mst_cluster_columns',
		array(
			'label'       => __( 'Columnas por defecto', 'minimal-seo-theme' ),
			'description' => __( 'Entre 1 y 6 columnas en escritorio.', 'minimal-seo-theme' ),
			'section'     => 'mst_clusters',
			'type'        => 'select',
			'choices'     => array(
				1 => '1',
				2 => '2',
				3 => '3',
				4 => '4',
				5 => '5',
				6 => '6',
			),
		)
	);

	$wp_customize->add_setting(
		'mst_cluster_posts',
		array(
			'default'           => $defaults['mst_cluster_posts'],
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'mst_cluster_posts',
		array(
			'label'       => __( 'Entradas por cluster', 'minimal-seo-theme' ),
			'section'     => 'mst_clusters',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 24,
				'step' => 1,
			),
		)
	);

	$wp_customize->add_setting(
		'mst_cluster_cta',
		array(
			'default'           => $defaults['mst_cluster_cta'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'mst_cluster_cta',
		array(
			'label'   => __( 'Texto del botón CTA', 'minimal-seo-theme' ),
			'section' => 'mst_clusters',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'mst_cluster_excerpt',
		array(
			'default'           => $defaults['mst_cluster_excerpt'],
			'sanitize_callback' => 'mst_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'mst_cluster_excerpt',
		array(
			'label'   => __( 'Mostrar extracto en tarjetas', 'minimal-seo-theme' ),
			'section' => 'mst_clusters',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'mst_cluster_show_meta',
		array(
			'default'           => $defaults['mst_cluster_show_meta'],
			'sanitize_callback' => 'mst_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'mst_cluster_show_meta',
		array(
			'label'       => __( 'Mostrar meta en clusters', 'minimal-seo-theme' ),
			'description' => __( 'Categoría, fecha y autor en cada tarjeta.', 'minimal-seo-theme' ),
			'section'     => 'mst_clusters',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'mst_cluster_featured_auto',
		array(
			'default'           => $defaults['mst_cluster_featured_auto'],
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'mst_cluster_featured_auto',
		array(
			'label'       => __( 'Destacados automáticos', 'minimal-seo-theme' ),
			'description' => __( 'Primeras N entradas en ancho completo si no hay destacados manuales.', 'minimal-seo-theme' ),
			'section'     => 'mst_clusters',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 6,
				'step' => 1,
			),
		)
	);

	/* --- Entradas relacionadas --- */
	$wp_customize->add_section(
		'mst_related',
		array(
			'title'       => __( 'Entradas relacionadas', 'minimal-seo-theme' ),
			'description' => __( 'Grid al final de cada entrada, por categoría compartida.', 'minimal-seo-theme' ),
			'panel'       => 'mst_panel',
			'priority'    => 22,
		)
	);

	$wp_customize->add_setting(
		'mst_related_enable',
		array(
			'default'           => $defaults['mst_related_enable'],
			'sanitize_callback' => 'mst_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'mst_related_enable',
		array(
			'label'   => __( 'Mostrar entradas relacionadas', 'minimal-seo-theme' ),
			'section' => 'mst_related',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'mst_related_count',
		array(
			'default'           => $defaults['mst_related_count'],
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'mst_related_count',
		array(
			'label'       => __( 'Número de entradas', 'minimal-seo-theme' ),
			'section'     => 'mst_related',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 12,
				'step' => 1,
			),
		)
	);

	$wp_customize->add_setting(
		'mst_related_title',
		array(
			'default'           => $defaults['mst_related_title'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'mst_related_title',
		array(
			'label'   => __( 'Título de sección (H2)', 'minimal-seo-theme' ),
			'section' => 'mst_related',
			'type'    => 'text',
		)
	);

	/* --- AdSense / Afiliación --- */
	$wp_customize->add_section(
		'mst_monetization',
		array(
			'title'       => __( 'Monetización', 'minimal-seo-theme' ),
			'description' => __( 'Pega el código de AdSense o HTML de afiliación. Se muestra al final de cada entrada. Primero marca la casilla de activar.', 'minimal-seo-theme' ),
			'panel'       => 'mst_panel',
			'priority'    => 30,
		)
	);

	$wp_customize->add_setting(
		'mst_adsense_enabled',
		array(
			'default'           => $defaults['mst_adsense_enabled'],
			'sanitize_callback' => 'mst_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'mst_adsense_enabled',
		array(
			'label'   => __( 'Activar zona de publicidad en entradas', 'minimal-seo-theme' ),
			'section' => 'mst_monetization',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'mst_adsense_code',
		array(
			'default'           => $defaults['mst_adsense_code'],
			'sanitize_callback' => 'mst_sanitize_adsense_code',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'mst_adsense_code',
		array(
			'label'       => __( 'Código AdSense / HTML', 'minimal-seo-theme' ),
			'description' => __( 'Pega aquí el código completo (AdSense, banner de afiliado con enlace e imagen, etc.).', 'minimal-seo-theme' ),
			'section'     => 'mst_monetization',
			'type'        => 'textarea',
			'input_attrs' => array(
				'rows'        => 10,
				'placeholder' => '<a href="..."><img src="..." alt="..." width="728" height="90"></a>',
			),
		)
	);

	$wp_customize->add_setting(
		'mst_adsense_label',
		array(
			'default'           => $defaults['mst_adsense_label'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'mst_adsense_label',
		array(
			'label'       => __( 'Etiqueta accesible (opcional)', 'minimal-seo-theme' ),
			'description' => __( 'Solo visible para lectores de pantalla. Déjalo vacío para "Publicidad".', 'minimal-seo-theme' ),
			'section'     => 'mst_monetization',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'mst_adsense_min_height',
		array(
			'default'           => $defaults['mst_adsense_min_height'],
			'sanitize_callback' => 'mst_sanitize_ad_min_height',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'mst_adsense_min_height',
		array(
			'label'       => __( 'Altura mínima del anuncio (px)', 'minimal-seo-theme' ),
			'description' => __( 'Reserva espacio para evitar CLS cuando cargue AdSense.', 'minimal-seo-theme' ),
			'section'     => 'mst_monetization',
			'type'        => 'select',
			'choices'     => array(
				90  => '90 px — Banner horizontal',
				250 => '250 px — Rectángulo medio',
				280 => '280 px — Responsive',
				600 => '600 px — Half page / grande',
			),
		)
	);
}
add_action( 'customize_register', 'mst_customize_register' );

/**
 * Sanitizar altura mínima de anuncio.
 */
function mst_sanitize_ad_min_height( $value ) {
	$allowed = array( 90, 250, 280, 600 );
	$value   = absint( $value );
	return in_array( $value, $allowed, true ) ? $value : 250;
}

/**
 * Sanitizar pila de fuentes.
 */
function mst_sanitize_font_stack( $value ) {
	$fonts = mst_font_stacks();
	return isset( $fonts[ $value ] ) ? $value : 'system';
}

/**
 * Sanitizar tamaño de fuente base.
 */
function mst_sanitize_font_size( $value ) {
	return max( 14, min( 22, absint( $value ) ) );
}

/**
 * Sanitizar interlineado.
 */
function mst_sanitize_line_height( $value ) {
	$allowed = array( '1.4', '1.5', '1.6', '1.7', '1.75', '1.8', '2' );
	$value   = (string) $value;
	return in_array( $value, $allowed, true ) ? $value : '1.6';
}

/**
 * Sanitizar ancho máximo del sitio.
 */
function mst_sanitize_max_width( $value ) {
	$allowed = array( 960, 1080, 1200, 1320, 1400 );
	$value   = absint( $value );
	return in_array( $value, $allowed, true ) ? $value : 1200;
}

/**
 * Sanitizar ancho del contenido.
 */
function mst_sanitize_content_width( $value ) {
	$allowed = array( 600, 650, 700, 750, 800 );
	$value   = absint( $value );
	return in_array( $value, $allowed, true ) ? $value : 700;
}

/**
 * Sanitizar columnas del cluster (1-6).
 */
function mst_sanitize_columns( $value ) {
	return max( 1, min( 6, absint( $value ) ) );
}

/**
 * Sanitizar posición de sidebar.
 */
function mst_sanitize_sidebar_layout( $value ) {
	$allowed = array( 'none', 'left', 'right' );
	return in_array( $value, $allowed, true ) ? $value : 'none';
}

/**
 * Sanitizar checkbox.
 */
function mst_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * Sanitizar código AdSense (permite scripts a administradores).
 */
function mst_sanitize_adsense_code( $value ) {
	if ( current_user_can( 'unfiltered_html' ) ) {
		return wp_check_invalid_utf8( $value );
	}
	return wp_kses_post( $value );
}

/**
 * Generar bloque :root con variables del Customizer.
 */
function mst_build_customizer_css() {
	$fonts = mst_font_stacks();
	$stack = mst_get_mod( 'mst_font_stack' );
	$font  = isset( $fonts[ $stack ] ) ? $fonts[ $stack ] : $fonts['system'];

	$css  = ':root{';
	$css .= '--mst-font:' . $font . ';';
	$css .= '--mst-font-size-base:' . absint( mst_get_mod( 'mst_font_size' ) ) . 'px;';
	$css .= '--mst-line-height:' . mst_get_mod( 'mst_line_height' ) . ';';
	$css .= '--mst-max-width:' . absint( mst_get_mod( 'mst_max_width' ) ) . 'px;';
	$css .= '--mst-content-width:' . absint( mst_get_mod( 'mst_content_width' ) ) . 'px;';
	$css .= '--mst-section-width:' . absint( mst_get_mod( 'mst_max_width' ) ) . 'px;';

	$color_map = array(
		'mst_color_text'         => '--mst-color-text',
		'mst_color_muted'        => '--mst-color-muted',
		'mst_color_bg'           => '--mst-color-bg',
		'mst_color_accent'       => '--mst-color-accent',
		'mst_color_accent_hover' => '--mst-color-accent-hover',
	);

	foreach ( $color_map as $mod => $var ) {
		$value = sanitize_hex_color( mst_get_mod( $mod ) );
		if ( $value ) {
			$css .= $var . ':' . $value . ';';
		}
	}

	$css .= '}';

	return $css;
}

/**
 * CSS inline con variables del Customizer.
 */
function mst_customizer_inline_css() {
	wp_add_inline_style( 'mst-theme', mst_build_customizer_css() );
}
add_action( 'wp_enqueue_scripts', 'mst_customizer_inline_css', 20 );

/**
 * Preview en vivo en el Customizer.
 */
function mst_customize_preview_js() {
	$fonts = mst_font_stacks();

	wp_enqueue_script(
		'mst-customizer-preview',
		MST_URI . '/assets/js/customizer-preview.js',
		array( 'customize-preview' ),
		MST_VERSION,
		true
	);

	wp_localize_script(
		'mst-customizer-preview',
		'mstPreview',
		array(
			'fontStacks' => $fonts,
		)
	);
}
add_action( 'customize_preview_init', 'mst_customize_preview_js' );

/**
 * Altura mínima reservada para anuncios (anti-CLS).
 */
function mst_get_ad_min_height() {
	return absint( mst_get_mod( 'mst_adsense_min_height' ) );
}

/**
 * Atributo style para zona de anuncio.
 */
function mst_ad_zone_style_attr() {
	return ' style="--mst-ad-min-height:' . mst_get_ad_min_height() . 'px"';
}

/**
 * Renderizar zona de afiliación / AdSense.
 */
function mst_render_affiliate_zone() {
	if ( ! is_singular( 'post' ) || ! mst_get_mod( 'mst_adsense_enabled' ) ) {
		return;
	}

	$code = mst_get_mod( 'mst_adsense_code' );
	if ( empty( trim( $code ) ) ) {
		return;
	}

	$label = mst_get_mod( 'mst_adsense_label' );
	if ( empty( $label ) ) {
		$label = __( 'Publicidad', 'minimal-seo-theme' );
	}
	?>
	<aside class="affiliate-zone affiliate-zone--ad"<?php echo mst_ad_zone_style_attr(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> aria-label="<?php echo esc_attr( $label ); ?>">
		<span class="affiliate-zone__badge" aria-hidden="true"><?php esc_html_e( 'Publicidad', 'minimal-seo-theme' ); ?></span>
		<div class="affiliate-zone__slot">
			<?php echo mst_adsense_output( $code ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</aside>
	<?php
}

/**
 * Placeholder de zona publicitaria (sin código configurado).
 */
function mst_render_ad_placeholder() {
	?>
	<aside class="affiliate-zone affiliate-zone--placeholder affiliate-zone--ad"<?php echo mst_ad_zone_style_attr(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> aria-label="<?php esc_attr_e( 'Zona de afiliación o publicidad', 'minimal-seo-theme' ); ?>">
		<span class="affiliate-zone__badge" aria-hidden="true"><?php esc_html_e( 'Publicidad', 'minimal-seo-theme' ); ?></span>
		<div class="affiliate-zone__slot">
			<p class="affiliate-zone__label"><?php esc_html_e( 'Espacio para enlaces de afiliación o AdSense', 'minimal-seo-theme' ); ?></p>
		</div>
	</aside>
	<?php
}

/**
 * Output seguro del código AdSense.
 */
function mst_adsense_output( $code ) {
	$allowed = array(
		'script' => array(
			'async'                      => true,
			'src'                        => true,
			'crossorigin'                => true,
			'type'                       => true,
			'data-ad-client'             => true,
			'data-ad-slot'               => true,
			'data-ad-format'             => true,
			'data-full-width-responsive' => true,
		),
		'ins'    => array(
			'class'                      => true,
			'style'                      => true,
			'data-ad-client'             => true,
			'data-ad-slot'               => true,
			'data-ad-format'             => true,
			'data-full-width-responsive' => true,
		),
		'div'    => array(
			'class' => true,
			'id'    => true,
			'style' => true,
		),
		'a'      => array(
			'href'   => true,
			'rel'    => true,
			'target' => true,
			'class'  => true,
		),
		'img'    => array(
			'src'     => true,
			'alt'     => true,
			'width'   => true,
			'height'  => true,
			'class'   => true,
			'loading' => true,
		),
		'iframe' => array(
			'src'             => true,
			'width'           => true,
			'height'          => true,
			'frameborder'     => true,
			'marginwidth'     => true,
			'marginheight'    => true,
			'scrolling'       => true,
			'allow'           => true,
			'allowfullscreen' => true,
			'loading'         => true,
			'title'           => true,
		),
	);

	if ( current_user_can( 'unfiltered_html' ) ) {
		return $code;
	}

	return wp_kses( $code, $allowed );
}
