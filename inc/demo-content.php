<?php
/**
 * Contenido demo — plantilla guiada en lenguaje sencillo
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Crear plantilla demo (una vez, al cargar el admin).
 */
function mst_maybe_seed_demo_content() {
	if ( get_option( 'mst_demo_seeded', '' ) === '2.5.3' ) {
		return;
	}

	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	mst_configure_blog_mode();
	mst_remove_hello_world_post();
	mst_remove_sample_page();
	mst_migrate_legacy_demo_slugs();

	$cat1 = mst_upsert_demo_category(
		'tema-1',
		__( 'TEMA 1', 'minimal-seo-theme' ),
		mst_ph(
			__( 'Descripción de la categoría — TEMA 1', 'minimal-seo-theme' ),
			__( 'Explica en 1 frase qué artículos agrupa TEMA 1. Edítalo en Entradas → Categorías', 'minimal-seo-theme' )
		)
	);

	$cat2 = mst_upsert_demo_category(
		'tema-2',
		__( 'TEMA 2', 'minimal-seo-theme' ),
		mst_ph(
			__( 'Descripción de la categoría — TEMA 2', 'minimal-seo-theme' ),
			__( 'Explica en 1 frase qué artículos agrupa TEMA 2. Edítalo en Entradas → Categorías', 'minimal-seo-theme' )
		)
	);

	mst_seed_demo_posts( $cat1 );
	mst_seed_demo_posts_tema2( $cat2 );
	mst_seed_money_post( $cat1 );

	$pillar1 = mst_seed_pillar_page(
		array(
			'theme_num'  => 1,
			'label'      => 'TEMA 1',
			'slug'       => 'tema-1',
			'category'   => 'tema-1',
			'money_url'  => home_url( '/auditoria-seo-tecnica-wordpress/' ),
			'option_key' => 'mst_pillar_page_id',
		)
	);

	mst_seed_pillar_page(
		array(
			'theme_num'  => 2,
			'label'      => 'TEMA 2',
			'slug'       => 'tema-2',
			'category'   => 'tema-2',
			'money_url'  => '',
			'option_key' => 'mst_pillar_page_id_2',
		)
	);

	mst_update_cta_urls_to_anchor( $pillar1 );
	mst_configure_demo_theme_mods();
	mst_seed_demo_primary_menu();

	update_option( 'mst_demo_seeded', '2.5.3' );
}
add_action( 'admin_init', 'mst_maybe_seed_demo_content' );

/**
 * Asegurar menú demo con «Inicio» en instalaciones ya sembradas.
 */
function mst_maybe_seed_demo_menu() {
	if ( get_option( 'mst_demo_menu_seeded', '' ) === '2.6.6' ) {
		return;
	}

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	mst_ensure_primary_menu_has_home();
	update_option( 'mst_demo_menu_seeded', '2.6.6' );
}
add_action( 'admin_init', 'mst_maybe_seed_demo_menu', 25 );

/**
 * ¿El menú incluye enlace a la portada?
 *
 * @param int $menu_id ID del menú.
 */
function mst_menu_has_home_link( $menu_id ) {
	$menu_id = absint( $menu_id );
	if ( ! $menu_id ) {
		return false;
	}

	$home = trailingslashit( home_url( '/' ) );
	$items = wp_get_nav_menu_items( $menu_id );

	if ( ! $items ) {
		return false;
	}

	foreach ( $items as $item ) {
		if ( trailingslashit( (string) $item->url ) === $home ) {
			return true;
		}
	}

	return false;
}

/**
 * Crear menú principal demo: Inicio + territorios TEMA 1 / TEMA 2.
 */
function mst_seed_demo_primary_menu() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$menu_name = __( 'Menú principal (demo)', 'minimal-seo-theme' );
	$menu      = wp_get_nav_menu_object( $menu_name );

	if ( $menu && ! is_wp_error( $menu ) ) {
		$menu_id = (int) $menu->term_id;
	} else {
		$menu_id = wp_create_nav_menu( $menu_name );
	}

	if ( is_wp_error( $menu_id ) || ! $menu_id ) {
		return;
	}

	$existing_items = wp_get_nav_menu_items( $menu_id );
	$existing_ids   = array();

	if ( $existing_items ) {
		foreach ( $existing_items as $item ) {
			$existing_ids[] = (int) $item->ID;
		}
	}

	if ( ! mst_menu_has_home_link( $menu_id ) ) {
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'  => __( 'Inicio', 'minimal-seo-theme' ),
				'menu-item-url'    => home_url( '/' ),
				'menu-item-status' => 'publish',
				'menu-item-type'   => 'custom',
				'menu-item-position' => 1,
			)
		);
	}

	$pages = array(
		array(
			'option' => 'mst_pillar_page_id',
			'label'  => 'TEMA 1',
		),
		array(
			'option' => 'mst_pillar_page_id_2',
			'label'  => 'TEMA 2',
		),
	);

	$position = 2;
	foreach ( $pages as $page_config ) {
		$page_id = (int) get_option( $page_config['option'], 0 );
		if ( ! $page_id ) {
			continue;
		}

		$already = false;
		if ( $existing_items ) {
			foreach ( $existing_items as $item ) {
				if ( 'post_type' === $item->type && (int) $item->object_id === $page_id ) {
					$already = true;
					break;
				}
			}
		}

		if ( $already ) {
			continue;
		}

		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => $page_config['label'],
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $page_id,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => $position,
			)
		);
		++$position;
	}

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( empty( $locations['primary'] ) ) {
		$locations['primary'] = $menu_id;
	}
	if ( empty( $locations['orbital'] ) ) {
		$locations['orbital'] = $menu_id;
	}
	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Añadir «Inicio» si falta en el menú principal asignado.
 */
function mst_ensure_primary_menu_has_home() {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$menu_id   = ! empty( $locations['primary'] ) ? (int) $locations['primary'] : 0;

	if ( ! $menu_id ) {
		mst_seed_demo_primary_menu();
		return;
	}

	if ( mst_menu_has_home_link( $menu_id ) ) {
		if ( empty( $locations['orbital'] ) ) {
			$locations['orbital'] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
		return;
	}

	wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-title'  => __( 'Inicio', 'minimal-seo-theme' ),
			'menu-item-url'    => home_url( '/' ),
			'menu-item-status' => 'publish',
			'menu-item-type'   => 'custom',
			'menu-item-position' => 1,
		)
	);
}

/**
 * Renombrar slugs legacy (seo-tecnico → tema-1).
 */
function mst_migrate_legacy_demo_slugs() {
	if ( get_category_by_slug( 'tema-1' ) ) {
		return;
	}

	$legacy_cat = get_category_by_slug( 'seo-tecnico' );
	if ( $legacy_cat ) {
		wp_update_term(
			(int) $legacy_cat->term_id,
			'category',
			array(
				'slug' => 'tema-1',
				'name' => __( 'TEMA 1', 'minimal-seo-theme' ),
			)
		);
	}

	if ( get_page_by_path( 'tema-1', OBJECT, 'page' ) ) {
		return;
	}

	$legacy_page = get_page_by_path( 'seo-tecnico', OBJECT, 'page' );
	if ( $legacy_page ) {
		wp_update_post(
			array(
				'ID'        => (int) $legacy_page->ID,
				'post_name' => 'tema-1',
			)
		);
	}
}

/**
 * Modo blog: portada con últimas entradas.
 */
function mst_configure_blog_mode() {
	update_option( 'show_on_front', 'posts' );
	update_option( 'page_on_front', 0 );
	update_option( 'page_for_posts', 0 );

	$tagline = get_option( 'blogdescription', '' );
	if ( '' === $tagline || 'Just another WordPress site' === $tagline ) {
		update_option(
			'blogdescription',
			mst_ph(
				__( 'Frase bajo el nombre del sitio', 'minimal-seo-theme' ),
				__( 'Escribe en 1 línea de qué trata tu web. Ve a Ajustes → Generales → Descripción corta', 'minimal-seo-theme' )
			)
		);
	}
}

/**
 * Textos de la portada en el personalizador.
 */
function mst_configure_demo_theme_mods() {
	set_theme_mod( 'mst_home_cluster_category', 'tema-1' );
	set_theme_mod( 'mst_home_cluster_posts', 6 );
	set_theme_mod( 'mst_home_cluster_featured', 2 );
	set_theme_mod( 'mst_cluster_show_meta', true );
	set_theme_mod( 'mst_cluster_featured_auto', 2 );
	set_theme_mod( 'mst_related_enable', true );
	set_theme_mod( 'mst_related_count', 3 );

	set_theme_mod( 'mst_home_hero_title', mst_ph( __( 'Título grande de la portada', 'minimal-seo-theme' ), __( 'Escribe el mensaje principal. Ejemplo: Recetas fáciles para cada día', 'minimal-seo-theme' ) ) );
	set_theme_mod( 'mst_home_hero_text', mst_ph( __( 'Texto debajo del título', 'minimal-seo-theme' ), __( 'Explica en 2 frases qué encontrará el visitante. Se edita en Personalizar → Constructor de inicio', 'minimal-seo-theme' ) ) );
	set_theme_mod( 'mst_home_hero_btn_text', mst_ph( __( 'Texto del botón', 'minimal-seo-theme' ), __( 'Ejemplo: Ver artículos / Empezar aquí', 'minimal-seo-theme' ) ) );
	set_theme_mod( 'mst_home_cluster_title', mst_ph( __( 'Título de la cuadrícula destacada', 'minimal-seo-theme' ), __( 'Ejemplo: Artículos de TEMA 1 (territorio)', 'minimal-seo-theme' ) ) );
	set_theme_mod( 'mst_home_cluster_desc', __( 'Acceso rápido al territorio. No es la guía completa (post pilar).', 'minimal-seo-theme' ) );
	set_theme_mod( 'mst_home_posts_title', mst_ph( __( 'Título de la lista de artículos', 'minimal-seo-theme' ), __( 'Ejemplo: Otros temas y artículos recientes', 'minimal-seo-theme' ) ) );
	set_theme_mod( 'mst_home_posts_desc', __( 'Artículos fuera de la cuadrícula superior (otros territorios).', 'minimal-seo-theme' ) );
	set_theme_mod( 'mst_home_cta_title', mst_ph( __( 'Título del recuadro final', 'minimal-seo-theme' ), __( 'Ejemplo: ¿Quieres que te ayudemos? / Suscríbete gratis', 'minimal-seo-theme' ) ) );
	set_theme_mod( 'mst_home_cta_text', mst_ph( __( 'Texto del recuadro final', 'minimal-seo-theme' ), __( 'Escribe 1 o 2 frases invitando a contactar, comprar o leer más', 'minimal-seo-theme' ) ) );
	set_theme_mod( 'mst_home_cta_btn_text', mst_ph( __( 'Texto del botón final', 'minimal-seo-theme' ), __( 'Ejemplo: Contactar / Ver oferta / Descargar guía', 'minimal-seo-theme' ) ) );
	set_theme_mod( 'mst_home_hero_btn_url', home_url( '/tema-1/' ) );
}

/**
 * Eliminar la entrada por defecto "Hello world!".
 */
function mst_remove_hello_world_post() {
	if ( ! current_user_can( 'delete_posts' ) ) {
		return;
	}
	$hello = get_page_by_path( 'hello-world', OBJECT, 'post' );
	if ( $hello ) {
		wp_delete_post( (int) $hello->ID, true );
	}
}

/**
 * Eliminar Sample Page.
 */
function mst_remove_sample_page() {
	if ( ! current_user_can( 'delete_pages' ) ) {
		return;
	}
	$sample = get_page_by_path( 'sample-page', OBJECT, 'page' );
	if ( $sample ) {
		wp_delete_post( (int) $sample->ID, true );
	}
}

/**
 * Ancla interna para botones de la portada.
 */
function mst_get_home_posts_anchor( $absolute = false ) {
	$anchor = '#ultimas-publicaciones';
	return $absolute ? home_url( '/' . $anchor ) : $anchor;
}

/**
 * Actualizar enlaces de botones legacy.
 *
 * @param int $pillar1_id ID de la página índice TEMA 1.
 */
function mst_update_cta_urls_to_anchor( $pillar1_id = 0 ) {
	$anchor = mst_get_home_posts_anchor();

	if ( '' === get_theme_mod( 'mst_home_cta_btn_url', '' ) || false !== strpos( (string) get_theme_mod( 'mst_home_cta_btn_url' ), 'sample-page' ) ) {
		set_theme_mod( 'mst_home_cta_btn_url', $anchor );
	}

	$main = get_page_by_path( 'guia-seo-tecnico-wordpress', OBJECT, 'post' );
	if ( $main ) {
		$cta = get_post_meta( $main->ID, '_mst_cta_url', true );
		if ( '' === $cta || false !== strpos( (string) $cta, 'sample-page' ) || false !== strpos( (string) $cta, '/seo-tecnico/' ) ) {
			update_post_meta( $main->ID, '_mst_cta_url', mst_get_home_posts_anchor( true ) );
		}
	}
}

/**
 * Crear o actualizar una categoría demo.
 *
 * @param string $slug Slug de la categoría.
 * @param string $name Nombre visible.
 * @param string $desc Descripción.
 * @return int
 */
function mst_upsert_demo_category( $slug, $name, $desc ) {
	$term = get_category_by_slug( $slug );

	if ( $term ) {
		wp_update_term(
			(int) $term->term_id,
			'category',
			array(
				'name'        => $name,
				'description' => $desc,
			)
		);
		return (int) $term->term_id;
	}

	$result = wp_insert_term(
		$name,
		'category',
		array(
			'slug'        => $slug,
			'description' => $desc,
		)
	);

	return is_wp_error( $result ) ? 0 : (int) $result['term_id'];
}

/**
 * Artículos de ejemplo de TEMA 1.
 */
function mst_get_demo_posts_definitions() {
	$pillar = home_url( '/tema-1/' );

	$templates = array(
		array(
			'slug'             => 'guia-seo-tecnico-wordpress',
			'article_num'      => 1,
			'section_example'  => __( 'Qué es y por qué te interesa', 'minimal-seo-theme' ),
			'cluster_featured' => true,
			'image'            => 'demo-featured.webp',
			'hint'             => __( 'TEMA 1 — ARTÍCULO 1: cambia el título arriba. En la columna derecha edita el resumen corto y la imagen destacada. En "Campos extra del tema" puedes poner subtítulo, texto de introducción y botón al final.', 'minimal-seo-theme' ),
		),
		array(
			'slug'             => 'core-web-vitals-wordpress',
			'article_num'      => 2,
			'section_example'  => __( 'Pasos para hacerlo', 'minimal-seo-theme' ),
			'cluster_featured' => true,
			'image'            => 'demo-cwv.webp',
			'hint'             => __( 'TEMA 1 — ARTÍCULO 2: escribe sobre un subtema de TEMA 1. Al final del artículo puedes poner un botón que lleve a tu página índice o a otra entrada.', 'minimal-seo-theme' ),
		),
		array(
			'slug'             => 'schema-json-ld-wordpress',
			'article_num'      => 3,
			'section_example'  => __( 'Consejos prácticos', 'minimal-seo-theme' ),
			'cluster_featured' => false,
			'image'            => 'demo-schema.webp',
			'hint'             => __( 'TEMA 1 — ARTÍCULO 3: usa varios subtítulos grandes en el contenido (como los amarillos de abajo). Si pones 3 o más, aparece un índice automático al inicio.', 'minimal-seo-theme' ),
		),
		array(
			'slug'             => 'crawl-budget-wordpress',
			'article_num'      => 4,
			'section_example'  => __( 'Errores que debes evitar', 'minimal-seo-theme' ),
			'cluster_featured' => false,
			'image'            => 'demo-crawl.webp',
			'hint'             => __( 'TEMA 1 — ARTÍCULO 4: sube una foto o imagen en "Imagen destacada" para que la tarjeta en la portada se vea mejor.', 'minimal-seo-theme' ),
		),
	);

	return mst_build_demo_posts_from_templates( $templates, $pillar, 'TEMA 1' );
}

/**
 * Artículos de ejemplo de TEMA 2.
 */
function mst_get_demo_posts_tema2_definitions() {
	$pillar = home_url( '/tema-2/' );

	$templates = array(
		array(
			'slug'             => 'tema-2-articulo-1',
			'article_num'      => 1,
			'section_example'  => __( 'Por dónde empezar', 'minimal-seo-theme' ),
			'cluster_featured' => true,
			'image'            => 'demo-featured.webp',
			'hint'             => __( 'TEMA 2 — ARTÍCULO 1: este es el primer artículo del segundo tema de ejemplo. Cambia el título y el contenido por tu tema real.', 'minimal-seo-theme' ),
		),
		array(
			'slug'             => 'tema-2-articulo-2',
			'article_num'      => 2,
			'section_example'  => __( 'Ideas y ejemplos', 'minimal-seo-theme' ),
			'cluster_featured' => true,
			'image'            => 'demo-cwv.webp',
			'hint'             => __( 'TEMA 2 — ARTÍCULO 2: segundo artículo del segundo tema. Enlázalo desde la página índice TEMA 2.', 'minimal-seo-theme' ),
		),
	);

	return mst_build_demo_posts_from_templates( $templates, $pillar, 'TEMA 2' );
}

/**
 * Construir definiciones de artículos desde plantillas.
 *
 * @param array  $templates Plantillas base.
 * @param string $pillar    URL de la página índice.
 * @param string $theme     Etiqueta del tema (TEMA 1 / TEMA 2).
 * @return array
 */
function mst_build_demo_posts_from_templates( $templates, $pillar, $theme ) {
	$posts = array();

	foreach ( $templates as $tpl ) {
		$n = (int) $tpl['article_num'];
		$posts[] = array(
			'slug'                => $tpl['slug'],
			'title'               => mst_ph_title(
				sprintf(
					/* translators: 1: theme label, 2: article number */
					__( '%1$s — Art. %2$d', 'minimal-seo-theme' ),
					$theme,
					$n
				)
			),
			'excerpt'             => mst_ph( __( 'Resumen corto', 'minimal-seo-theme' ), __( 'Aparece en tarjetas y listas. Escribe 1 o 2 frases. Caja "Extracto" en el editor', 'minimal-seo-theme' ) ),
			'subtitle'            => mst_ph(
				__( 'Línea bajo el título', 'minimal-seo-theme' ),
				sprintf(
					/* translators: 1: theme label, 2: article number */
					__( 'Escribe el nombre real del artículo %2$d de %1$s. Ejemplo: Guía básica para principiantes', 'minimal-seo-theme' ),
					$theme,
					$n
				)
			),
			'lead'                => mst_ph( __( 'Texto de introducción', 'minimal-seo-theme' ), __( '1 o 2 frases que enganchen al lector. Campo "Texto de introducción" en la barra lateral', 'minimal-seo-theme' ) ),
			'cluster_description' => mst_ph( __( 'Texto en la tarjeta', 'minimal-seo-theme' ), __( 'Frase corta que se ve en la cuadrícula de enlaces. Campo "Texto en la tarjeta" en la barra lateral', 'minimal-seo-theme' ) ),
			'cta_text'            => mst_ph( __( 'Texto del botón al final', 'minimal-seo-theme' ), __( 'Ejemplo: Leer más / Descargar / Contactar', 'minimal-seo-theme' ) ),
			'cluster_featured'    => $tpl['cluster_featured'],
			'image'               => $tpl['image'],
			'image_title'         => sprintf( __( 'Imagen de ejemplo %s, artículo %d', 'minimal-seo-theme' ), $theme, $n ),
			'hint'                => $tpl['hint'],
			'sections'            => array(
				array( 'example' => __( 'Qué es y por qué importa', 'minimal-seo-theme' ) ),
				array( 'example' => $tpl['section_example'] ),
				array( 'example' => __( 'Resumen y qué hacer ahora', 'minimal-seo-theme' ) ),
			),
			'intro'               => mst_ph_lorem(),
			'cta_url'             => $pillar,
		);
	}

	return $posts;
}

/**
 * Crear o actualizar entradas demo de TEMA 1.
 */
function mst_seed_demo_posts( $cat_id = 0 ) {
	$main_id = 0;

	foreach ( mst_get_demo_posts_definitions() as $def ) {
		$post_id = mst_upsert_demo_post( $def, $cat_id );
		if ( 'guia-seo-tecnico-wordpress' === $def['slug'] && $post_id ) {
			$main_id = $post_id;
			update_option( 'mst_demo_post_id', $post_id );
		}
	}

	return $main_id;
}

/**
 * Crear o actualizar entradas demo de TEMA 2.
 */
function mst_seed_demo_posts_tema2( $cat_id = 0 ) {
	foreach ( mst_get_demo_posts_tema2_definitions() as $def ) {
		mst_upsert_demo_post( $def, $cat_id );
	}
}

/**
 * Artículo para vender o conseguir contactos (TEMA 1).
 */
function mst_seed_money_post( $cat_id = 0 ) {
	$pillar = home_url( '/tema-1/' );

	$def = array(
		'slug'                => 'auditoria-seo-tecnica-wordpress',
		'title'               => mst_ph_title( __( 'TEMA 1 — Oferta', 'minimal-seo-theme' ) ),
		'excerpt'             => mst_ph( __( 'Resumen corto', 'minimal-seo-theme' ), __( 'Resume en 1 frase qué ofreces en esta página', 'minimal-seo-theme' ) ),
		'subtitle'            => mst_ph( __( 'Línea bajo el título', 'minimal-seo-theme' ), __( 'Ejemplo: Te respondemos en menos de 24 horas', 'minimal-seo-theme' ) ),
		'lead'                => mst_ph( __( 'Texto de introducción', 'minimal-seo-theme' ), __( 'Explica qué gana la persona si te contacta o compra', 'minimal-seo-theme' ) ),
		'cluster_description' => mst_ph( __( 'Texto en la tarjeta', 'minimal-seo-theme' ), __( 'Frase corta para destacar esta oferta en la cuadrícula', 'minimal-seo-theme' ) ),
		'cta_text'            => mst_ph( __( 'Texto del botón al final', 'minimal-seo-theme' ), __( 'Ejemplo: Pedir información / Comprar ahora', 'minimal-seo-theme' ) ),
		'cta_url'             => $pillar,
		'cluster_featured'    => true,
		'image'               => 'demo-money.webp',
		'image_title'         => __( 'Imagen de ejemplo oferta TEMA 1', 'minimal-seo-theme' ),
		'hint'                => __( 'TEMA 1 — PÁGINA DE OFERTA: aquí conviene poner lo que vendes o el formulario de contacto. Enlázala desde la página índice TEMA 1.', 'minimal-seo-theme' ),
		'intro'               => mst_ph_lorem(),
		'sections'            => array(
			array( 'example' => __( 'Qué incluye tu oferta', 'minimal-seo-theme' ) ),
			array( 'example' => __( 'Para quién es', 'minimal-seo-theme' ) ),
			array( 'example' => __( 'Cómo dar el siguiente paso', 'minimal-seo-theme' ) ),
		),
	);

	return mst_upsert_demo_post( $def, $cat_id );
}

/**
 * Página índice de un tema (lista tus artículos).
 *
 * @param array $config Configuración del tema.
 * @return int
 */
function mst_seed_pillar_page( $config ) {
	$theme_num = (int) $config['theme_num'];
	$label     = $config['label'];
	$slug      = $config['slug'];
	$category  = $config['category'];
	$money_url = isset( $config['money_url'] ) ? $config['money_url'] : '';
	$option    = isset( $config['option_key'] ) ? $config['option_key'] : 'mst_pillar_page_id';

	$existing = get_page_by_path( $slug, OBJECT, 'page' );

	$hint = 1 === $theme_num
		? __( 'TEMA 1 — PÁGINA ÍNDICE: agrupa los artículos de tu primer tema. En el menú: Inicio (portada) + nombre corto del territorio (ej: Sensaciones). No borres el bloque [cluster].', 'minimal-seo-theme' )
		: __( 'TEMA 2 — PÁGINA ÍNDICE: segundo tema de ejemplo. Así se ve un sitio con dos temas. Crea una categoría TEMA 2, artículos en esa categoría y este bloque [cluster] con el slug correcto.', 'minimal-seo-theme' );

	$content  = mst_get_editor_hint_html( $hint );
	$content .= '<p class="mst-placeholder">' . esc_html(
		mst_ph(
			__( 'Primer párrafo', 'minimal-seo-theme' ),
			sprintf(
				/* translators: %s: theme label */
				__( 'Presenta %s en 2 o 3 frases. Ejemplo: Aquí encontrarás guías sobre…', 'minimal-seo-theme' ),
				$label
			)
		)
	) . '</p>';
	$content .= '<h2 class="mst-placeholder">' . esc_html( mst_ph( __( 'Título de la sección de tarjetas', 'minimal-seo-theme' ), __( 'Ejemplo: Artículos de este tema / Explora las guías', 'minimal-seo-theme' ) ) ) . '</h2>';
	$content .= '<p class="mst-placeholder">' . esc_html( mst_ph( __( 'Texto antes de las tarjetas', 'minimal-seo-theme' ), __( 'Una frase que invite a hacer clic en los artículos de abajo', 'minimal-seo-theme' ) ) ) . '</p>';
	$content .= sprintf(
		'[cluster category="%s" posts_per_page="6" columns="3" featured_auto="2" load_more="no"]',
		esc_attr( $category )
	);

	if ( $money_url ) {
		$content .= '<h2 class="mst-placeholder">' . esc_html( mst_ph( __( 'Título de la sección de oferta', 'minimal-seo-theme' ), __( 'Ejemplo: ¿Necesitas ayuda? / Trabaja con nosotros', 'minimal-seo-theme' ) ) ) . '</h2>';
		$content .= '<p class="mst-placeholder">' . esc_html( mst_ph( __( 'Texto con enlace a tu oferta', 'minimal-seo-theme' ), __( 'Escribe 1 o 2 frases y enlaza a tu página de venta o contacto', 'minimal-seo-theme' ) ) ) . '</p>';
	}

	$args = array(
		'post_title'   => mst_ph_title( $label ),
		'post_name'    => $slug,
		'post_content' => $content,
		'post_excerpt' => mst_ph( __( 'Resumen para buscadores', 'minimal-seo-theme' ), __( '1 frase que resuma esta página (opcional)', 'minimal-seo-theme' ) ),
		'post_status'  => 'publish',
		'post_type'    => 'page',
		'post_author'  => mst_get_demo_author_id(),
	);

	if ( $existing ) {
		$args['ID'] = $existing->ID;
		$page_id    = wp_update_post( $args, true );
	} else {
		$page_id = wp_insert_post( $args, true );
	}

	if ( is_wp_error( $page_id ) || ! $page_id ) {
		return 0;
	}

	update_post_meta(
		$page_id,
		'_mst_subtitle',
		mst_ph(
			__( 'Línea bajo el título', 'minimal-seo-theme' ),
			sprintf(
				/* translators: %s: theme label */
				__( 'Ejemplo: Marketing digital / Recetas saludables. Cambia el título corto de arriba (ahora dice %s) en Páginas → Título', 'minimal-seo-theme' ),
				$label
			)
		)
	);
	update_post_meta( $page_id, '_mst_lead', mst_ph( __( 'Texto de introducción', 'minimal-seo-theme' ), __( '1 o 2 frases de bienvenida. Campo en la barra lateral', 'minimal-seo-theme' ) ) );

	if ( $money_url ) {
		update_post_meta( $page_id, '_mst_cta_text', mst_ph( __( 'Texto del botón', 'minimal-seo-theme' ), __( 'Ejemplo: Ver oferta / Pedir cita', 'minimal-seo-theme' ) ) );
		update_post_meta( $page_id, '_mst_cta_url', $money_url );
	}

	mst_attach_demo_image( $page_id, 'demo-pillar.webp', sprintf( __( 'Imagen de ejemplo página índice %s', 'minimal-seo-theme' ), $label ), true );
	update_option( $option, $page_id );

	return $page_id;
}

/**
 * Insertar o actualizar una entrada demo.
 */
function mst_upsert_demo_post( $def, $cat_id = 0 ) {
	$existing = get_page_by_path( $def['slug'], OBJECT, 'post' );
	$content  = mst_build_demo_post_html( $def );

	$args = array(
		'post_title'   => $def['title'],
		'post_name'    => $def['slug'],
		'post_content' => $content,
		'post_excerpt' => $def['excerpt'],
		'post_status'  => 'publish',
		'post_type'    => 'post',
		'post_author'  => mst_get_demo_author_id(),
	);

	if ( $existing ) {
		$args['ID'] = $existing->ID;
		$post_id    = wp_update_post( $args, true );
	} else {
		$post_id = wp_insert_post( $args, true );
	}

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return 0;
	}

	update_post_meta( $post_id, '_mst_subtitle', $def['subtitle'] );
	update_post_meta( $post_id, '_mst_lead', $def['lead'] );
	update_post_meta( $post_id, '_mst_cta_text', $def['cta_text'] );
	update_post_meta( $post_id, '_mst_cta_url', isset( $def['cta_url'] ) ? $def['cta_url'] : mst_get_home_posts_anchor( true ) );
	update_post_meta( $post_id, '_mst_cluster_description', $def['cluster_description'] );
	update_post_meta( $post_id, '_mst_cluster_featured', ! empty( $def['cluster_featured'] ) );

	if ( $cat_id ) {
		wp_set_post_categories( $post_id, array( $cat_id ) );
	}

	mst_attach_demo_image( $post_id, $def['image'], $def['image_title'], true );

	return $post_id;
}

/**
 * Construir contenido del artículo con subtítulos de ejemplo.
 */
function mst_build_demo_post_html( $def ) {
	$html = '';

	if ( ! empty( $def['hint'] ) ) {
		$html .= mst_get_editor_hint_html( $def['hint'] );
	}

	$intro = isset( $def['intro'] ) ? $def['intro'] : mst_ph_lorem();
	$html .= '<p' . mst_placeholder_class_attr( $intro, 'mst-placeholder' ) . '>' . esc_html( $intro ) . '</p>';

	if ( empty( $def['sections'] ) ) {
		return $html;
	}

	foreach ( $def['sections'] as $section ) {
		$example = isset( $section['example'] ) ? $section['example'] : __( 'Mi sección', 'minimal-seo-theme' );
		$h2      = mst_ph(
			__( 'Subtítulo de sección', 'minimal-seo-theme' ),
			sprintf(
				/* translators: %s: example section name */
				__( 'Escribe un subtítulo. Ejemplo: %s', 'minimal-seo-theme' ),
				$example
			)
		);
		$html   .= '<h2' . mst_placeholder_class_attr( $h2, 'mst-placeholder' ) . '>' . esc_html( $h2 ) . '</h2>';
		$html   .= '<p' . mst_placeholder_class_attr( mst_ph_lorem(), 'mst-placeholder' ) . '>' . esc_html( mst_ph_lorem() ) . '</p>';
	}

	return $html;
}

/**
 * ID del autor (primer administrador).
 */
function mst_get_demo_author_id() {
	$admins = get_users(
		array(
			'role'   => 'administrator',
			'number' => 1,
			'fields' => 'ID',
		)
	);
	return ! empty( $admins ) ? (int) $admins[0] : 1;
}

/**
 * ¿La miniatura actual es un placeholder demo (SVG o archivo mst-demo-*)?
 *
 * @param int $thumb_id ID del adjunto.
 */
function mst_is_demo_placeholder_thumbnail( $thumb_id ) {
	if ( ! $thumb_id ) {
		return false;
	}

	if ( 'image/svg+xml' === get_post_mime_type( $thumb_id ) ) {
		return true;
	}

	$file = get_attached_file( $thumb_id );
	return $file && false !== strpos( basename( $file ), 'mst-demo-' );
}

/**
 * Adjuntar imagen demo (WebP) a una entrada o página.
 *
 * @param int    $post_id             ID del post.
 * @param string $filename            Nombre del archivo en assets/images.
 * @param string $title               Título del adjunto.
 * @param bool   $replace_placeholder Reemplazar miniaturas demo previas.
 */
function mst_attach_demo_image( $post_id, $filename, $title = '', $replace_placeholder = false ) {
	$thumb_id = get_post_thumbnail_id( $post_id );
	if ( $thumb_id ) {
		if ( ! $replace_placeholder || ! mst_is_demo_placeholder_thumbnail( $thumb_id ) ) {
			return;
		}
		delete_post_thumbnail( $post_id );
	}

	$source = MST_DIR . '/assets/images/' . $filename;
	if ( ! file_exists( $source ) ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$upload = wp_upload_dir();
	if ( ! empty( $upload['error'] ) ) {
		return;
	}

	$dest_name = 'mst-' . sanitize_file_name( $filename );
	$dest      = trailingslashit( $upload['path'] ) . $dest_name;

	if ( ! file_exists( $dest ) ) {
		copy( $source, $dest );
	}

	$ext  = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
	$mime = 'image/png';
	if ( 'svg' === $ext ) {
		$mime = 'image/svg+xml';
	} elseif ( 'jpg' === $ext || 'jpeg' === $ext ) {
		$mime = 'image/jpeg';
	} elseif ( 'webp' === $ext ) {
		$mime = 'image/webp';
	}

	$attachment = array(
		'post_mime_type' => $mime,
		'post_title'     => $title ? $title : basename( $filename, '.' . $ext ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	$attach_id = wp_insert_attachment( $attachment, $dest, $post_id );
	if ( is_wp_error( $attach_id ) || ! $attach_id ) {
		return;
	}

	$metadata = wp_generate_attachment_metadata( $attach_id, $dest );
	if ( ! is_wp_error( $metadata ) && $metadata ) {
		wp_update_attachment_metadata( $attach_id, $metadata );
	}

	set_post_thumbnail( $post_id, $attach_id );
}

/**
 * @deprecated 2.1.0 Usar mst_seed_demo_posts().
 */
function mst_seed_demo_post( $cat_id = 0 ) {
	return mst_seed_demo_posts( $cat_id );
}

/**
 * @deprecated 2.5.0 Usar mst_upsert_demo_category().
 */
function mst_seed_demo_category() {
	return mst_upsert_demo_category(
		'tema-1',
		__( 'TEMA 1', 'minimal-seo-theme' ),
		mst_ph(
			__( 'Descripción de la categoría — TEMA 1', 'minimal-seo-theme' ),
			__( 'Explica en 1 frase qué artículos agrupa TEMA 1. Edítalo en Entradas → Categorías', 'minimal-seo-theme' )
		)
	);
}

/**
 * @deprecated 2.1.0 Usar mst_attach_demo_image().
 */
function mst_seed_demo_featured_image( $post_id ) {
	mst_attach_demo_image( $post_id, 'demo-featured.webp', __( 'Imagen de ejemplo', 'minimal-seo-theme' ), true );
}

/**
 * @deprecated 2.1.0 Usar mst_build_demo_post_html().
 */
function mst_get_demo_post_content() {
	$defs = mst_get_demo_posts_definitions();
	return mst_build_demo_post_html( $defs[0] );
}
