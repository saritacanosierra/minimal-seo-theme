<?php
/**
 * Carga AJAX — clusters y archivos
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * HTML de una tarjeta de post.
 */
function mst_get_post_card_html() {
	$post_id       = get_the_ID();
	$thumb_classes = 'post-card__thumb';
	$thumb_style   = '';

	if ( mst_use_abstract_card_media( $post_id ) ) {
		$thumb_classes .= ' post-card__thumb--abstract-' . mst_get_abstract_media_variant( $post_id );
	} elseif ( has_post_thumbnail() ) {
		$thumb_url = get_the_post_thumbnail_url( $post_id, 'mst-card' );
		if ( $thumb_url ) {
			$thumb_style = ' style="background-image:url(' . esc_url( $thumb_url ) . ')"';
		}
	}

	ob_start();
	$title   = get_the_title( $post_id );
	$excerpt = has_excerpt( $post_id ) ? wp_trim_words( get_the_excerpt( $post_id ), 20 ) : '';
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
		<a class="post-card__link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( $title ); ?>">
			<div class="<?php echo esc_attr( $thumb_classes ); ?>"<?php echo $thumb_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> role="img" aria-label="<?php echo esc_attr( $title ); ?>"></div>
			<div class="post-card__body">
				<h2<?php echo mst_placeholder_class_attr( $title, 'post-card__title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $title ); ?></h2>
				<p class="post-card__meta">
					<?php
					$categories = get_the_category();
					if ( ! empty( $categories ) ) {
						echo '<span class="post-card__category">' . esc_html( $categories[0]->name ) . '</span> · ';
					}
					?>
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				</p>
				<?php if ( $excerpt ) : ?>
					<p<?php echo mst_placeholder_class_attr( $excerpt, 'post-card__excerpt' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $excerpt ); ?></p>
				<?php endif; ?>
			</div>
		</a>
	</article>
	<?php
	return ob_get_clean();
}

/**
 * Renderizar tarjetas desde WP_Query.
 */
function mst_render_post_cards( $query ) {
	$html = '';
	while ( $query->have_posts() ) {
		$query->the_post();
		$html .= mst_get_post_card_html();
	}
	wp_reset_postdata();
	return $html;
}

/**
 * Argumentos de consulta para clusters.
 */
function mst_cluster_query_args( $atts, $paged = 1 ) {
	$load_more = empty( $atts['ids'] ) && ( ! isset( $atts['load_more'] ) || 'yes' === strtolower( $atts['load_more'] ) );

	$post_type = sanitize_key( $atts['post_type'] ?? 'post' );
	if ( ! in_array( $post_type, array( 'post', 'page' ), true ) ) {
		$post_type = 'post';
	}

	$args = array(
		'post_type'           => $post_type,
		'post_status'         => 'publish',
		'posts_per_page'      => absint( $atts['posts_per_page'] ),
		'paged'               => max( 1, absint( $paged ) ),
		'orderby'             => sanitize_key( $atts['orderby'] ),
		'order'               => strtoupper( $atts['order'] ) === 'ASC' ? 'ASC' : 'DESC',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => ! $load_more,
	);

	$tax_query = array();

	if ( ! empty( $atts['category'] ) || ! empty( $atts['cat'] ) ) {
		$cat         = ! empty( $atts['category'] ) ? $atts['category'] : $atts['cat'];
		$tax_query[] = array(
			'taxonomy' => 'category',
			'field'    => is_numeric( $cat ) ? 'term_id' : 'slug',
			'terms'    => $cat,
		);
	}

	if ( ! empty( $atts['tag'] ) ) {
		$tax_query[] = array(
			'taxonomy' => 'post_tag',
			'field'    => is_numeric( $atts['tag'] ) ? 'term_id' : 'slug',
			'terms'    => $atts['tag'],
		);
	}

	if ( ! empty( $tax_query ) ) {
		$args['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
	}

	if ( ! empty( $atts['ids'] ) ) {
		$ids = array_filter( array_map( 'absint', explode( ',', $atts['ids'] ) ) );
		if ( ! empty( $ids ) ) {
			$args['post__in'] = $ids;
			$args['orderby']  = 'post__in';
		}
	}

	return $args;
}

/**
 * Descripción para tarjetas de cluster (meta > Yoast > extracto > contenido).
 */
function mst_get_cluster_description( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( ! $post_id ) {
		return '';
	}

	$custom = get_post_meta( $post_id, '_mst_cluster_description', true );
	if ( ! empty( $custom ) ) {
		return wp_strip_all_tags( $custom );
	}

	$yoast = get_post_meta( $post_id, '_yoast_wpseo_metadesc', true );
	if ( ! empty( $yoast ) ) {
		return wp_strip_all_tags( $yoast );
	}

	if ( has_excerpt( $post_id ) ) {
		return wp_strip_all_tags( get_the_excerpt( $post_id ) );
	}

	return wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ), 18, '…' );
}

/**
 * Resolver IDs destacados del cluster (atributo, meta por post o auto N primeros).
 */
function mst_resolve_cluster_featured_ids( $atts, $query ) {
	$featured = array_filter( array_map( 'absint', explode( ',', $atts['featured'] ?? '' ) ) );

	if ( ! empty( $featured ) ) {
		return $featured;
	}

	if ( $query && $query->have_posts() ) {
		foreach ( $query->posts as $post ) {
			if ( get_post_meta( $post->ID, '_mst_cluster_featured', true ) ) {
				$featured[] = (int) $post->ID;
			}
		}
	}

	if ( ! empty( $featured ) ) {
		return array_values( array_unique( $featured ) );
	}

	$auto = isset( $atts['featured_auto'] ) ? absint( $atts['featured_auto'] ) : absint( mst_get_mod( 'mst_cluster_featured_auto' ) );
	if ( $auto > 0 && $query && $query->have_posts() ) {
		$featured = array_slice( wp_list_pluck( $query->posts, 'ID' ), 0, $auto );
	}

	return array_map( 'absint', $featured );
}

/**
 * Metadatos de tarjeta cluster (categoría, fecha, autor).
 */
function mst_get_cluster_card_meta_html( $post_id = 0 ) {
	if ( ! mst_get_mod( 'mst_cluster_show_meta' ) ) {
		return '';
	}

	$post_id = $post_id ? $post_id : get_the_ID();
	if ( ! $post_id ) {
		return '';
	}

	$parts = array();

	$categories = get_the_category( $post_id );
	if ( ! empty( $categories ) ) {
		$cat     = $categories[0];
		$parts[] = '<span class="cluster-card__category">' . esc_html( $cat->name ) . '</span>';
	}

	$parts[] = '<time class="cluster-card__date" datetime="' . esc_attr( get_the_date( 'c', $post_id ) ) . '">' . esc_html( get_the_date( '', $post_id ) ) . '</time>';
	$parts[] = '<span class="cluster-card__author">' . esc_html( get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) ) ) . '</span>';

	return '<p class="cluster-card__meta">' . implode( '', $parts ) . '</p>';
}

/**
 * Una tarjeta de cluster.
 *
 * @param WP_Post $post        Post a renderizar.
 * @param array   $atts        Atributos del shortcode.
 * @param bool    $is_featured Si la tarjeta es destacada.
 */
function mst_render_single_cluster_card( $post, $atts, $is_featured ) {
	$post_id      = (int) $post->ID;
	$show_excerpt = 'yes' === strtolower( $atts['show_excerpt'] );
	$cta_text     = esc_html( $atts['cta_text'] );
	$thumb_id     = get_post_thumbnail_id( $post_id );
	$bg_style     = '';
	$media_class  = 'cluster-card__media';

	if ( mst_use_abstract_card_media( $post_id ) ) {
		$media_class .= ' cluster-card__media--abstract-' . mst_get_abstract_media_variant( $post_id );
	} elseif ( $thumb_id ) {
		$thumb_url = wp_get_attachment_image_url( $thumb_id, $is_featured ? 'mst-hero' : 'mst-card' );
		if ( $thumb_url ) {
			$bg_style = ' style="background-image:url(' . esc_url( $thumb_url ) . ')"';
		}
	}

	$html  = '<article class="cluster-card' . ( $is_featured ? ' cluster-card--featured' : '' ) . '">';
	$html .= '<div class="' . esc_attr( $media_class ) . '"' . $bg_style . ' aria-hidden="true"></div>';
	$html .= '<div class="cluster-card__overlay">';
	$html .= mst_get_cluster_card_meta_html( $post_id );
	$html .= '<p class="cluster-card__title' . ( mst_is_placeholder_text( get_the_title( $post_id ) ) ? ' mst-placeholder mst-placeholder--on-dark' : '' ) . '">' . esc_html( get_the_title( $post_id ) ) . '</p>';

	if ( $show_excerpt ) {
		$description = mst_get_cluster_description( $post_id );
		if ( $description ) {
			$html .= '<p class="cluster-card__excerpt">' . esc_html( wp_trim_words( $description, $is_featured ? 28 : 18 ) ) . '</p>';
		}
	}

	$html .= '<span class="cluster-card__cta">' . $cta_text . '</span>';
	$html .= '</div>';
	$html .= '<a class="cluster-card__link" href="' . esc_url( get_permalink( $post_id ) ) . '" aria-label="' . esc_attr( get_the_title( $post_id ) ) . '">';
	$html .= '<span class="screen-reader-text">' . esc_html( get_the_title( $post_id ) ) . '</span>';
	$html .= '</a></article>';

	return $html;
}

/**
 * ¿La entrada del cluster está marcada como destacada?
 *
 * @param int   $post_id      ID del post.
 * @param int[] $featured_ids IDs destacados resueltos.
 */
function mst_cluster_post_is_featured( $post_id, $featured_ids ) {
	return in_array( (int) $post_id, $featured_ids, true ) || get_post_meta( $post_id, '_mst_cluster_featured', true );
}

/**
 * HTML de tarjetas de cluster (destacadas primero, misma cuadrícula).
 */
function mst_render_cluster_cards( $query, $atts ) {
	$featured_ids = mst_resolve_cluster_featured_ids( $atts, $query );
	$featured     = array();
	$regular      = array();

	foreach ( $query->posts as $post ) {
		if ( mst_cluster_post_is_featured( $post->ID, $featured_ids ) ) {
			$featured[] = $post;
		} else {
			$regular[] = $post;
		}
	}

	$html = '';
	foreach ( array_merge( $featured, $regular ) as $post ) {
		$html .= mst_render_single_cluster_card(
			$post,
			$atts,
			(bool) mst_cluster_post_is_featured( $post->ID, $featured_ids )
		);
	}

	return $html;
}

/**
 * Botón "Cargar más".
 */
function mst_load_more_button( $type, $page, $max_pages, $query_data = array() ) {
	if ( $max_pages <= $page ) {
		return '';
	}

	return sprintf(
		'<div class="mst-load-more-bar"><button type="button" class="mst-load-more" data-type="%1$s" data-page="%2$d" data-max="%3$d" data-query="%4$s">%5$s</button></div>',
		esc_attr( $type ),
		absint( $page ),
		absint( $max_pages ),
		esc_attr( wp_json_encode( $query_data ) ),
		esc_html__( 'Cargar más', 'minimal-seo-theme' )
	);
}

/**
 * Datos de archivo para AJAX.
 */
function mst_get_archive_query_data() {
	if ( is_category() ) {
		return array(
			'archive' => 'category',
			'term_id' => get_queried_object_id(),
		);
	}
	if ( is_tag() ) {
		return array(
			'archive' => 'tag',
			'term_id' => get_queried_object_id(),
		);
	}
	if ( is_author() ) {
		return array(
			'archive' => 'author',
			'term_id' => get_queried_object_id(),
		);
	}
	return array();
}

/**
 * Registrar endpoint REST.
 */
function mst_register_load_more_route() {
	register_rest_route(
		'mst/v1',
		'/load-more',
		array(
			'methods'             => 'GET',
			'callback'            => 'mst_rest_load_more',
			'permission_callback' => '__return_true',
			'args'                => array(
				'type'  => array(
					'required' => true,
					'type'     => 'string',
					'enum'     => array( 'cluster', 'archive' ),
				),
				'page'  => array(
					'default'           => 1,
					'sanitize_callback' => 'absint',
				),
				'query' => array(
					'default'           => '{}',
					'sanitize_callback' => 'sanitize_text_field',
				),
				'nonce' => array(
					'required'          => true,
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'mst_register_load_more_route' );

/**
 * Handler REST load-more.
 */
function mst_rest_load_more( WP_REST_Request $request ) {
	if ( ! wp_verify_nonce( $request->get_param( 'nonce' ), 'mst_load_more' ) ) {
		return new WP_REST_Response( array( 'message' => 'Invalid nonce' ), 403 );
	}

	$type = $request->get_param( 'type' );
	$page = max( 1, absint( $request->get_param( 'page' ) ) );
	$data = json_decode( $request->get_param( 'query' ), true );

	if ( ! is_array( $data ) ) {
		$data = array();
	}

	if ( 'cluster' === $type ) {
		$atts  = wp_parse_args(
			$data,
			array(
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
				'show_excerpt'   => mst_get_mod( 'mst_cluster_excerpt' ) ? 'yes' : 'no',
				'cta_text'       => mst_get_mod( 'mst_cluster_cta' ),
				'load_more'      => 'yes',
				'post_type'      => 'post',
			)
		);
		$query = new WP_Query( mst_cluster_query_args( $atts, $page ) );
		$html  = mst_render_cluster_cards( $query, $atts );
		$max   = (int) $query->max_num_pages;
	} else {
		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'paged'          => $page,
			'posts_per_page' => get_option( 'posts_per_page' ),
		);

		if ( ! empty( $data['archive'] ) && ! empty( $data['term_id'] ) ) {
			$term_id = absint( $data['term_id'] );
			switch ( $data['archive'] ) {
				case 'category':
					$args['cat'] = $term_id;
					break;
				case 'tag':
					$args['tag_id'] = $term_id;
					break;
				case 'author':
					$args['author'] = $term_id;
					break;
			}
		}

		$query = new WP_Query( $args );
		$html  = mst_render_post_cards( $query );
		$max   = (int) $query->max_num_pages;
	}

	return new WP_REST_Response(
		array(
			'html'     => $html,
			'page'     => $page,
			'maxPages' => $max,
			'hasMore'  => $page < $max,
		),
		200
	);
}

/**
 * Encolar script load-more.
 */
function mst_enqueue_load_more() {
	wp_enqueue_script(
		'mst-load-more',
		MST_URI . '/assets/js/load-more.js',
		array(),
		MST_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	wp_localize_script(
		'mst-load-more',
		'mstLoadMore',
		array(
			'endpoint' => rest_url( 'mst/v1/load-more' ),
			'nonce'    => wp_create_nonce( 'mst_load_more' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'mst_enqueue_load_more' );
