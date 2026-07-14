<?php
/**
 * SEO técnico avanzado — Minimal SEO Theme
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enlace ofuscado (Base64) para reducir rastreo de enlaces secundarios.
 *
 * @param string $url  URL destino.
 * @param string $text Texto visible.
 * @param array  $args Atributos extra (class, rel, etc.).
 */
function mst_obfuscated_link( $url, $text, $args = array() ) {
	$url  = esc_url( $url );
	$text = wp_strip_all_tags( $text );

	if ( empty( $url ) || empty( $text ) ) {
		return '';
	}

	$defaults = array(
		'class' => 'obfuscated-link',
	);
	$args = wp_parse_args( $args, $defaults );

	$attrs = '';
	foreach ( $args as $key => $value ) {
		if ( 'class' === $key ) {
			continue;
		}
		$attrs .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
	}

	return sprintf(
		'<span class="%s" data-link="%s" role="link" tabindex="0"%s>%s</span>',
		esc_attr( $args['class'] ),
		esc_attr( base64_encode( $url ) ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		$attrs,
		esc_html( $text )
	);
}

/**
 * Shortcode [oblink url="https://..."]Texto[/oblink]
 */
function mst_oblink_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			'url' => '',
		),
		$atts,
		'oblink'
	);

	if ( empty( $atts['url'] ) || empty( $content ) ) {
		return '';
	}

	return mst_obfuscated_link( $atts['url'], do_shortcode( $content ) );
}
add_shortcode( 'oblink', 'mst_oblink_shortcode' );

/**
 * Migas de pan — datos estructurados.
 */
function mst_get_breadcrumbs() {
	$crumbs = array(
		array(
			'name' => __( 'Inicio', 'minimal-seo-theme' ),
			'url'  => home_url( '/' ),
		),
	);

	if ( is_front_page() ) {
		return array();
	}

	if ( is_singular( 'post' ) ) {
		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			$primary = $categories[0];
			$crumbs[] = array(
				'name' => $primary->name,
				'url'  => get_category_link( $primary->term_id ),
			);
		}
		$crumbs[] = array(
			'name' => get_the_title(),
			'url'  => get_permalink(),
		);
	} elseif ( is_singular( 'page' ) ) {
		$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
		foreach ( $ancestors as $ancestor_id ) {
			$crumbs[] = array(
				'name' => get_the_title( $ancestor_id ),
				'url'  => get_permalink( $ancestor_id ),
			);
		}
		$crumbs[] = array(
			'name' => get_the_title(),
			'url'  => get_permalink(),
		);
	} elseif ( is_category() ) {
		$crumbs[] = array(
			'name' => single_cat_title( '', false ),
			'url'  => get_category_link( get_queried_object_id() ),
		);
	} elseif ( is_tag() ) {
		$crumbs[] = array(
			'name' => single_tag_title( '', false ),
			'url'  => get_tag_link( get_queried_object_id() ),
		);
	} else {
		return array();
	}

	return $crumbs;
}

/**
 * JSON-LD BreadcrumbList en el head.
 */
function mst_breadcrumbs_json_ld() {
	if ( ! is_singular( array( 'post', 'page' ) ) ) {
		return;
	}

	$crumbs = mst_get_breadcrumbs();
	if ( count( $crumbs ) < 2 ) {
		return;
	}

	$items = array();
	foreach ( $crumbs as $index => $crumb ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $index + 1,
			'name'     => wp_strip_all_tags( $crumb['name'] ),
			'item'     => esc_url( $crumb['url'] ),
		);
	}

	$schema = array(
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $items,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'mst_breadcrumbs_json_ld', 15 );

/**
 * HTML semántico de migas de pan.
 */
function mst_breadcrumbs() {
	if ( ! is_singular( array( 'post', 'page' ) ) ) {
		return;
	}

	$crumbs = mst_get_breadcrumbs();
	if ( count( $crumbs ) < 2 ) {
		return;
	}

	$last = count( $crumbs ) - 1;
	?>
	<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Migas de pan', 'minimal-seo-theme' ); ?>">
		<ol class="breadcrumbs__list">
			<?php foreach ( $crumbs as $index => $crumb ) : ?>
				<li class="breadcrumbs__item">
					<?php if ( $index < $last ) : ?>
						<a class="breadcrumbs__link" href="<?php echo esc_url( $crumb['url'] ); ?>"><?php echo esc_html( $crumb['name'] ); ?></a>
					<?php else : ?>
						<span class="breadcrumbs__current" aria-current="page"><?php echo esc_html( $crumb['name'] ); ?></span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</nav>
	<?php
}

/**
 * Imagen destacada: LCP prioritario en singulares.
 */
function mst_attachment_image_attributes( $attr, $attachment, $size ) {
	if ( is_singular() && (int) get_post_thumbnail_id() === (int) $attachment->ID ) {
		$attr['loading']       = 'eager';
		$attr['fetchpriority'] = 'high';
		$attr['decoding']      = 'async';
		return $attr;
	}

	if ( is_singular() ) {
		$attr['loading']  = 'lazy';
		$attr['decoding'] = 'async';
	} elseif ( empty( $attr['loading'] ) ) {
		$attr['loading'] = 'lazy';
	}

	if ( empty( $attr['decoding'] ) ) {
		$attr['decoding'] = 'async';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'mst_attachment_image_attributes', 10, 3 );

/**
 * Lazy loading obligatorio en imágenes del contenido del post.
 */
function mst_lazyload_content_images( $content ) {
	if ( ! is_singular() || false === stripos( $content, '<img' ) ) {
		return $content;
	}

	return preg_replace_callback(
		'/<img\b([^>]*?)>/i',
		function ( $matches ) {
			$tag = $matches[0];

			if ( preg_match( '/\sloading\s*=/i', $tag ) ) {
				return preg_replace( '/\sloading\s*=\s*["\'][^"\']*["\']/i', ' loading="lazy"', $tag );
			}

			return str_replace( '<img', '<img loading="lazy"', $tag );
		},
		$content
	);
}
add_filter( 'the_content', 'mst_lazyload_content_images', 20 );

/**
 * Redirigir páginas de adjuntos al post padre (301).
 */
function mst_redirect_attachments() {
	if ( ! is_attachment() ) {
		return;
	}

	$parent_id = wp_get_post_parent_id( get_queried_object_id() );
	if ( $parent_id ) {
		wp_safe_redirect( get_permalink( $parent_id ), 301 );
		exit;
	}

	wp_safe_redirect( home_url( '/' ), 301 );
	exit;
}
add_action( 'template_redirect', 'mst_redirect_attachments' );

/**
 * Deshabilitar archivos por fecha (301 a home).
 */
function mst_disable_date_archives() {
	if ( is_date() ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'mst_disable_date_archives' );

/**
 * Deshabilitar archivos de autor si solo hay un administrador.
 */
function mst_disable_single_author_archive() {
	if ( ! is_author() ) {
		return;
	}

	$admins = get_users(
		array(
			'role'   => 'administrator',
			'fields' => 'ID',
			'number' => 2,
		)
	);

	if ( count( $admins ) <= 1 ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'mst_disable_single_author_archive' );

/**
 * Eliminar feeds RSS del head.
 */
function mst_remove_feed_links() {
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
}
add_action( 'after_setup_theme', 'mst_remove_feed_links', 11 );

/**
 * Deshabilitar endpoints RSS (404).
 */
function mst_disable_feeds() {
	wp_safe_redirect( home_url( '/' ), 301 );
	exit;
}

function mst_register_feed_disabling() {
	$feeds = array( 'do_feed', 'do_feed_rdf', 'do_feed_rss', 'do_feed_rss2', 'do_feed_atom', 'do_feed_rss2_comments', 'do_feed_atom_comments' );
	foreach ( $feeds as $feed ) {
		add_action( $feed, 'mst_disable_feeds', 1 );
	}
}
add_action( 'init', 'mst_register_feed_disabling' );

/**
 * Schema Article completo (JSON-LD).
 */
function mst_schema_article() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	$post_id = get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( $content ), 40 );

	$schema = array(
		'@context'         => 'https://schema.org',
		'@type'            => 'Article',
		'@id'              => get_permalink( $post_id ) . '#article',
		'mainEntityOfPage' => array(
			'@type' => 'WebPage',
			'@id'   => get_permalink( $post_id ),
		),
		'headline'         => wp_strip_all_tags( get_the_title( $post_id ) ),
		'description'      => wp_strip_all_tags( $excerpt ),
		'datePublished'    => get_the_date( 'c', $post_id ),
		'dateModified'     => get_the_modified_date( 'c', $post_id ),
		'inLanguage'       => get_bloginfo( 'language' ),
		'wordCount'        => str_word_count( wp_strip_all_tags( $content ) ),
		'author'           => array(
			'@type' => 'Person',
			'name'  => get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) ),
			'url'   => get_author_posts_url( get_post_field( 'post_author', $post_id ) ),
		),
		'publisher'        => mst_schema_publisher(),
	);

	$categories = get_the_category( $post_id );
	if ( ! empty( $categories ) ) {
		$schema['articleSection'] = $categories[0]->name;
	}

	if ( has_post_thumbnail( $post_id ) ) {
		$thumb_id = get_post_thumbnail_id( $post_id );
		$schema['image'] = array(
			'@type'  => 'ImageObject',
			'url'    => get_the_post_thumbnail_url( $post_id, 'mst-hero' ),
			'width'  => 1200,
			'height' => 630,
		);
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}

/**
 * Datos del publisher para Schema.
 */
function mst_schema_publisher() {
	$publisher = array(
		'@type' => 'Organization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
	);

	$logo_id = get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
		if ( $logo_url ) {
			$publisher['logo'] = array(
				'@type' => 'ImageObject',
				'url'   => $logo_url,
			);
		}
	}

	return $publisher;
}

add_action( 'wp_head', 'mst_schema_article', 20 );
