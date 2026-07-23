<?php
/**
 * Minimal SEO Theme — Functions
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

define( 'MST_VERSION', '2.6.1' );
define( 'MST_DIR', get_template_directory() );
define( 'MST_URI', get_template_directory_uri() );

require_once MST_DIR . '/inc/placeholders.php';
require_once MST_DIR . '/inc/webp.php';
require_once MST_DIR . '/inc/customizer.php';
require_once MST_DIR . '/inc/seo.php';
require_once MST_DIR . '/inc/toc.php';
require_once MST_DIR . '/inc/load-more.php';
require_once MST_DIR . '/inc/home-builder.php';
require_once MST_DIR . '/inc/meta-fields.php';
require_once MST_DIR . '/inc/content-architecture.php';
require_once MST_DIR . '/inc/architecture-admin.php';
require_once MST_DIR . '/inc/architecture-beginner-guide.php';
require_once MST_DIR . '/inc/architecture-links.php';
require_once MST_DIR . '/inc/block-patterns.php';
require_once MST_DIR . '/inc/demo-content.php';
require_once MST_DIR . '/inc/layout.php';
require_once MST_DIR . '/inc/related-posts.php';
require_once MST_DIR . '/inc/block-cluster.php';
require_once MST_DIR . '/inc/admin-guide.php';

/**
 * Permitir SVG en subidas (solo admins, para imagen demo).
 */
function mst_allow_svg_uploads( $mimes ) {
	if ( current_user_can( 'manage_options' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}
	return $mimes;
}
add_filter( 'upload_mimes', 'mst_allow_svg_uploads' );

/**
 * Configuración del tema.
 */
function mst_setup() {
	load_theme_textdomain( 'minimal-seo-theme', MST_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_image_size( 'mst-card', 640, 360, true );
	add_image_size( 'mst-hero', 1200, 630, true );

	register_nav_menus( array(
		'primary' => __( 'Menú principal', 'minimal-seo-theme' ),
		'orbital' => __( 'Menú Órbita (móvil)', 'minimal-seo-theme' ),
	) );
}
add_action( 'after_setup_theme', 'mst_setup' );

/**
 * Logo o nombre del sitio (nativo WP, sin JS extra).
 */
function mst_site_branding() {
	$use_h1 = is_front_page() && is_home();

	if ( has_custom_logo() ) {
		$logo = get_custom_logo();
		if ( $logo ) {
			if ( $use_h1 ) {
				echo '<h1 class="site-brand site-brand--logo">' . $logo . '</h1>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo '<div class="site-brand site-brand--logo">' . $logo . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			return;
		}
	}

	if ( $use_h1 ) {
		echo '<h1 class="site-brand"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a></h1>';
	} else {
		echo '<p class="site-brand"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a></p>';
	}
}

/**
 * Priorizar logo en LCP (solo home con últimas entradas).
 */
function mst_custom_logo_attributes( $custom_logo_attr ) {
	if ( is_front_page() && is_home() ) {
		$custom_logo_attr['fetchpriority'] = 'high';
		$custom_logo_attr['loading']       = 'eager';
		$custom_logo_attr['decoding']      = 'async';
	}
	return $custom_logo_attr;
}
add_filter( 'get_custom_logo_image_attributes', 'mst_custom_logo_attributes' );

/**
 * Limpieza del head y recursos innecesarios de WordPress.
 */
function mst_clean_head() {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );
	add_filter( 'feed_links_show_comments_feed', '__return_false' );
}
add_action( 'init', 'mst_clean_head' );

/**
 * Desactivar emojis completamente.
 */
function mst_disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	}
	return array();
}
add_filter( 'tiny_mce_plugins', 'mst_disable_emojis_tinymce' );

/**
 * Desencolar scripts y estilos innecesarios en el frontend.
 */
function mst_dequeue_bloat() {
	if ( is_admin() ) {
		return;
	}

	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'wc-blocks-style' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );

	wp_deregister_script( 'wp-embed' );

	// jQuery no es necesario en el frontend.
	wp_deregister_script( 'jquery' );
	wp_deregister_script( 'jquery-core' );
	wp_deregister_script( 'jquery-migrate' );

	if ( ! is_singular() || ! comments_open() ) {
		wp_dequeue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'mst_dequeue_bloat', 100 );

/**
 * CSS crítico inline (above the fold).
 */
function mst_critical_css() {
	$critical = MST_DIR . '/inc/critical.css';
	if ( ! file_exists( $critical ) ) {
		return;
	}
	echo '<style id="mst-critical-css">' . mst_minify_css( file_get_contents( $critical ) ) . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'mst_critical_css', 1 );

/**
 * Minificador CSS ligero.
 */
function mst_minify_css( $css ) {
	$css = preg_replace( '/\/\*[^*]*\*+([^\/][^*]*\*+)*\//', '', $css );
	$css = preg_replace( '/\s+/', ' ', $css );
	$css = preg_replace( '/\s*([\{\};:,>+~])\s*/', '$1', $css );
	return trim( $css );
}

/**
 * Encolar estilos y scripts del tema.
 */
function mst_enqueue_assets() {
	wp_enqueue_style(
		'mst-theme',
		MST_URI . '/assets/css/theme.css',
		array(),
		MST_VERSION,
		'all'
	);

	wp_enqueue_script(
		'mst-navigation',
		MST_URI . '/assets/js/navigation.js',
		array(),
		MST_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'mst_enqueue_assets' );

/**
 * Preload del CSS principal.
 */
function mst_resource_hints() {
	echo '<link rel="preload" href="' . esc_url( MST_URI . '/assets/css/theme.css' ) . '" as="style">' . "\n";
}
add_action( 'wp_head', 'mst_resource_hints', 2 );

/**
 * Atributos defer/async en scripts del tema (compatibilidad WP < 6.3).
 */
function mst_script_loader_tag( $tag, $handle, $src ) {
	$defer_handles = array( 'mst-navigation', 'mst-load-more' );
	if ( in_array( $handle, $defer_handles, true ) ) {
		if ( false === strpos( $tag, ' defer' ) ) {
			$tag = str_replace( ' src', ' defer src', $tag );
		}
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'mst_script_loader_tag', 10, 3 );

/**
 * Obtener menú para el Menú Órbita (fallback al principal).
 */
function mst_get_orbital_menu_items() {
	$locations = get_nav_menu_locations();
	$menu_id   = 0;

	if ( ! empty( $locations['orbital'] ) ) {
		$menu_id = $locations['orbital'];
	} elseif ( ! empty( $locations['primary'] ) ) {
		$menu_id = $locations['primary'];
	}

	if ( ! $menu_id ) {
		return array();
	}

	$items = wp_get_nav_menu_items( $menu_id );
	return is_array( $items ) ? $items : array();
}

/**
 * URLs del menú principal (para detectar duplicados en Órbita).
 *
 * @return string[]
 */
function mst_get_primary_menu_urls() {
	$locations = get_nav_menu_locations();
	if ( empty( $locations['primary'] ) ) {
		return array();
	}

	$items = wp_get_nav_menu_items( $locations['primary'] );
	if ( ! is_array( $items ) ) {
		return array();
	}

	$urls = array();
	foreach ( $items as $item ) {
		if ( ! empty( $item->url ) ) {
			$urls[] = untrailingslashit( $item->url );
		}
	}

	return array_values( array_unique( $urls ) );
}

/**
 * ¿Ofuscar enlace del Menú Órbita?
 *
 * Por defecto: sí si la URL también está en el menú principal (páginas secundarias
 * duplicadas: Contacto, Sobre mí, etc.). Nunca ofusca la home.
 *
 * En el editor de menús puedes forzar con clases CSS del ítem:
 * - mst-obfuscate / obfuscate → siempre ofuscar
 * - mst-no-obfuscate → nunca ofuscar
 *
 * @param object $item Ítem de wp_get_nav_menu_items().
 */
function mst_should_obfuscate_orbital_link( $item ) {
	$url  = untrailingslashit( $item->url ?? '' );
	$home = untrailingslashit( home_url( '/' ) );

	if ( empty( $url ) || $url === $home ) {
		return false;
	}

	$classes = is_array( $item->classes ?? null ) ? $item->classes : array();
	if ( in_array( 'mst-no-obfuscate', $classes, true ) ) {
		return false;
	}
	if ( in_array( 'mst-obfuscate', $classes, true ) || in_array( 'obfuscate', $classes, true ) ) {
		return true;
	}

	$primary_urls = mst_get_primary_menu_urls();
	$is_duplicate = in_array( $url, $primary_urls, true );

	/**
	 * Filtrar si un ítem del Menú Órbita debe ofuscarse.
	 *
	 * @param bool   $obfuscate    Valor por defecto.
	 * @param object $item         Ítem del menú.
	 * @param string[] $primary_urls URLs del menú principal.
	 */
	return (bool) apply_filters( 'mst_orbital_should_obfuscate_link', $is_duplicate, $item, $primary_urls );
}

/**
 * HTML de un enlace del Menú Órbita (normal u ofuscado).
 *
 * @param object $item  Ítem del menú.
 * @param string $label Texto visible.
 */
function mst_render_orbital_menu_link( $item, $label ) {
	if ( mst_should_obfuscate_orbital_link( $item ) ) {
		return mst_obfuscated_link(
			$item->url,
			$label,
			array( 'class' => 'obfuscated-link orbital-menu__link' )
		);
	}

	$target = ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
	$rel    = '_blank' === $item->target ? ' rel="noopener noreferrer"' : '';

	return sprintf(
		'<a class="orbital-menu__link" href="%s"%s%s>%s</a>',
		esc_url( $item->url ),
		$target,
		$rel,
		esc_html( $label )
	);
}

/**
 * Shortcode [cluster] — cuadrícula de enlaces internos para silos SEO.
 *
 * Atributos:
 * - category / cat   : slug o ID de categoría
 * - tag              : slug o ID de etiqueta
 * - ids              : IDs de posts separados por coma
 * - posts_per_page   : número de elementos (default 6)
 * - columns          : columnas CSS (default 3)
 * - orderby          : date, title, modified, menu_order, rand
 * - order            : ASC o DESC
 * - featured         : IDs destacados separados por coma
 * - show_excerpt     : yes|no
 * - cta_text         : texto del botón CTA
 */
function mst_cluster_shortcode( $atts ) {
	$defaults = array(
		'category'       => '',
		'cat'            => '',
		'tag'            => '',
		'ids'            => '',
		'posts_per_page' => mst_get_mod( 'mst_cluster_posts' ),
		'columns'        => mst_get_mod( 'mst_cluster_columns' ),
		'orderby'        => 'date',
		'order'          => 'DESC',
		'featured'       => '',
		'featured_auto'  => mst_get_mod( 'mst_cluster_featured_auto' ),
		'post_type'      => 'post',
		'show_excerpt'   => mst_get_mod( 'mst_cluster_excerpt' ) ? 'yes' : 'no',
		'cta_text'       => mst_get_mod( 'mst_cluster_cta' ),
		'load_more'      => 'yes',
	);

	$atts  = shortcode_atts( $defaults, $atts, 'cluster' );
	$query = new WP_Query( mst_cluster_query_args( $atts, 1 ) );

	if ( ! $query->have_posts() ) {
		return '';
	}

	$columns   = max( 1, min( 6, absint( $atts['columns'] ) ) );
	$load_more = empty( $atts['ids'] ) && 'yes' === strtolower( $atts['load_more'] );
	$max_pages = (int) $query->max_num_pages;

	ob_start();
	?>
	<div class="mst-load-more-wrap">
		<div class="cluster-grid cluster-grid--cols-<?php echo esc_attr( $columns ); ?> mst-load-more__grid" role="navigation" aria-label="<?php esc_attr_e( 'Cluster de navegación', 'minimal-seo-theme' ); ?>">
			<?php echo mst_render_cluster_cards( $query, $atts ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<?php
		if ( $load_more ) {
			echo mst_load_more_button( 'cluster', 1, $max_pages, $atts ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'cluster', 'mst_cluster_shortcode' );

/**
 * Widgets del footer sin encabezados H*.
 */
function mst_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer', 'minimal-seo-theme' ),
			'id'            => 'footer-1',
			'description'   => __( 'Widgets del pie de página (sin H1-H6).', 'minimal-seo-theme' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<p class="footer-widget__label">',
			'after_title'   => '</p>',
		)
	);
}
add_action( 'widgets_init', 'mst_widgets_init' );

/**
 * Meta description básica si no hay plugin SEO.
 */
function mst_meta_description() {
	if ( is_singular() ) {
		$description = get_post_meta( get_the_ID(), '_yoast_wpseo_metadesc', true );
		if ( empty( $description ) ) {
			$description = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 30 );
		}
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$description = term_description();
	} elseif ( is_home() || is_front_page() ) {
		$description = get_bloginfo( 'description' );
	} else {
		return;
	}

	$description = wp_strip_all_tags( $description );
	if ( empty( $description ) ) {
		return;
	}

	echo '<meta name="description" content="' . esc_attr( wp_trim_words( $description, 35 ) ) . '">' . "\n";
}
add_action( 'wp_head', 'mst_meta_description', 3 );

/**
 * Schema.org WebSite en la home.
 */
function mst_schema_website() {
	if ( ! is_front_page() ) {
		return;
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'WebSite',
		'name'     => get_bloginfo( 'name' ),
		'url'      => home_url( '/' ),
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'mst_schema_website', 20 );

/**
 * Eliminar versión de query strings en assets propios (cache busting via version).
 */
function mst_remove_query_strings( $src ) {
	if ( strpos( $src, '?ver=' ) !== false && strpos( $src, MST_URI ) !== false ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}
add_filter( 'style_loader_src', 'mst_remove_query_strings', 10, 1 );
add_filter( 'script_loader_src', 'mst_remove_query_strings', 10, 1 );

/**
 * Menú fallback si no hay menú asignado.
 */
function mst_fallback_menu() {
	echo '<ul class="primary-nav__list">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Inicio', 'minimal-seo-theme' ) . '</a></li>';

	$pages = get_pages( array( 'sort_column' => 'menu_order', 'number' => 5 ) );
	foreach ( $pages as $page ) {
		echo '<li><a href="' . esc_url( get_permalink( $page ) ) . '">' . esc_html( $page->post_title ) . '</a></li>';
	}

	echo '</ul>';
}
