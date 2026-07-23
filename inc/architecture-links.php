<?php
/**
 * Enlaces automáticos desde la matriz y bloqueo de publicación.
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Marcadores del bloque auto-generado al final del contenido.
 */
function mst_arch_links_block_start() {
	return '<!-- mst-arch-links-start -->';
}

function mst_arch_links_block_end() {
	return '<!-- mst-arch-links-end -->';
}

/**
 * ¿Guardado interno del tema (evitar bucles)?
 */
function mst_arch_is_internal_save( $post_id ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return true;
	}
	return (bool) get_post_meta( $post_id, '_mst_arch_syncing', true );
}

/**
 * Enlaces salientes planificados (parent, links_out, comercio).
 *
 * @param int $post_id ID del post.
 * @return array<int, array<string, string>>
 */
function mst_get_planned_outbound_links( $post_id ) {
	$anchors_raw = mst_parse_anchor_texts( get_post_meta( $post_id, '_mst_arch_anchor_texts', true ) );
	$by_target   = array();
	foreach ( $anchors_raw as $row ) {
		$by_target[ $row['target'] ] = $row['anchor'];
	}

	$planned   = array();
	$seen_urls = array();

	$add_link = static function ( $arch_id, $url, $anchor, $direction ) use ( &$planned, &$seen_urls ) {
		$url = esc_url_raw( $url );
		if ( '' === $url || isset( $seen_urls[ $url ] ) ) {
			return;
		}
		if ( mst_is_forbidden_anchor_text( $anchor ) ) {
			return;
		}
		$seen_urls[ $url ] = true;
		$planned[]         = array(
			'arch_id'   => sanitize_text_field( (string) $arch_id ),
			'url'       => $url,
			'anchor'    => sanitize_text_field( (string) $anchor ),
			'direction' => sanitize_key( (string) $direction ),
		);
	};

	$parent = sanitize_text_field( get_post_meta( $post_id, '_mst_arch_parent_id', true ) );
	if ( '' !== $parent ) {
		$parent_post = mst_get_post_id_by_arch_id( $parent );
		if ( $parent_post ) {
			$add_link(
				$parent,
				get_permalink( $parent_post ),
				$by_target[ $parent ] ?? get_the_title( $parent_post ),
				'ascendente'
			);
		}
	}

	foreach ( mst_parse_arch_id_list( get_post_meta( $post_id, '_mst_arch_links_out', true ) ) as $arch_id ) {
		$target_post = mst_get_post_id_by_arch_id( $arch_id );
		if ( ! $target_post || (int) $target_post === (int) $post_id ) {
			continue;
		}
		$add_link(
			$arch_id,
			get_permalink( $target_post ),
			$by_target[ $arch_id ] ?? get_the_title( $target_post ),
			'out'
		);
	}

	if ( (bool) get_post_meta( $post_id, '_mst_arch_ecommerce_enabled', true ) ) {
		$ecom_url  = esc_url_raw( get_post_meta( $post_id, '_mst_arch_ecommerce_url', true ) );
		$ecom_dest = sanitize_text_field( get_post_meta( $post_id, '_mst_arch_ecommerce_destination', true ) );
		if ( $ecom_url && false === stripos( $ecom_dest, 'sin enlace' ) ) {
			$add_link(
				'ecommerce',
				$ecom_url,
				$ecom_dest ? $ecom_dest : __( 'Ver productos recomendados', 'minimal-seo-theme' ),
				'comercial'
			);
		}
	}

	return apply_filters( 'mst_arch_planned_outbound_links', $planned, $post_id );
}

/**
 * ¿El HTML ya enlaza a esa URL?
 *
 * @param string $html HTML.
 * @param string $url  URL destino.
 */
function mst_arch_content_has_link_to_url( $html, $url ) {
	$url = untrailingslashit( esc_url_raw( $url ) );
	if ( '' === $url ) {
		return false;
	}
	$quoted = preg_quote( $url, '/' );
	return (bool) preg_match( '/href\s*=\s*["\']' . $quoted . '\/?["\']/i', $html );
}

/**
 * Enlazar la primera aparición del texto ancla fuera de enlaces existentes.
 *
 * @param string $html    HTML.
 * @param string $anchor  Texto visible.
 * @param string $url     URL.
 * @param string $arch_id ID matriz.
 */
function mst_arch_linkify_first_anchor( $html, $anchor, $url, $arch_id = '' ) {
	$anchor = trim( (string) $anchor );
	$url    = esc_url_raw( $url );
	if ( '' === $anchor || '' === $url || mst_arch_content_has_link_to_url( $html, $url ) ) {
		return $html;
	}

	$parts = preg_split( '/(<a\b[^>]*>.*?<\/a>)/is', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
	if ( ! is_array( $parts ) ) {
		return $html;
	}

	$linked = false;
	foreach ( $parts as $index => $part ) {
		if ( $linked || preg_match( '/^<a\b/i', $part ) ) {
			continue;
		}

		$pos = mb_stripos( $part, $anchor );
		if ( false === $pos ) {
			continue;
		}

		$before = mb_substr( $part, 0, $pos );
		$match  = mb_substr( $part, $pos, mb_strlen( $anchor ) );
		$after  = mb_substr( $part, $pos + mb_strlen( $anchor ) );

		$link = sprintf(
			'<a href="%1$s" class="mst-arch-link" data-mst-arch="%2$s">%3$s</a>',
			esc_url( $url ),
			esc_attr( $arch_id ),
			esc_html( $match )
		);

		$parts[ $index ] = $before . $link . $after;
		$linked          = true;
	}

	return $linked ? implode( '', $parts ) : $html;
}

/**
 * Quitar bloque auto-generado previo.
 *
 * @param string $html HTML.
 */
function mst_arch_remove_auto_links_block( $html ) {
	$start = preg_quote( mst_arch_links_block_start(), '/' );
	$end   = preg_quote( mst_arch_links_block_end(), '/' );
	return (string) preg_replace( '/' . $start . '.*?' . $end . '/s', '', $html );
}

/**
 * Añadir bloque con enlaces que no cupieron en el texto.
 *
 * @param string                            $html    HTML.
 * @param array<int, array<string, string>> $missing Enlaces pendientes.
 */
function mst_arch_append_missing_links_block( $html, $missing ) {
	if ( empty( $missing ) ) {
		return mst_arch_remove_auto_links_block( $html );
	}

	$html = mst_arch_remove_auto_links_block( $html );

	$items = '';
	foreach ( $missing as $link ) {
		$items .= sprintf(
			'<li><a href="%1$s" class="mst-arch-link" data-mst-arch="%2$s">%3$s</a></li>',
			esc_url( $link['url'] ),
			esc_attr( $link['arch_id'] ),
			esc_html( $link['anchor'] )
		);
	}

	$block = mst_arch_links_block_start();
	$block .= '<nav class="mst-arch-links" aria-label="' . esc_attr__( 'Enlaces del mapa de contenidos', 'minimal-seo-theme' ) . '">';
	$block .= '<p class="mst-arch-links__title">' . esc_html__( 'Sigue leyendo en este tema', 'minimal-seo-theme' ) . '</p>';
	$block .= '<ul class="mst-arch-links__list">' . $items . '</ul>';
	$block .= '</nav>';
	$block .= mst_arch_links_block_end();

	return rtrim( $html ) . "\n\n" . $block;
}

/**
 * Aplicar enlaces planificados al contenido HTML.
 *
 * @param string $content HTML.
 * @param int    $post_id ID del post.
 */
function mst_apply_architecture_links_to_content( $content, $post_id ) {
	$planned = mst_get_planned_outbound_links( $post_id );
	if ( empty( $planned ) ) {
		return mst_arch_remove_auto_links_block( $content );
	}

	$missing = array();
	foreach ( $planned as $link ) {
		$updated = mst_arch_linkify_first_anchor( $content, $link['anchor'], $link['url'], $link['arch_id'] );
		if ( $updated === $content && ! mst_arch_content_has_link_to_url( $content, $link['url'] ) ) {
			$missing[] = $link;
		}
		$content = $updated;
	}

	return mst_arch_append_missing_links_block( $content, $missing );
}

/**
 * Sincronizar enlaces en post_content al guardar.
 *
 * @param int $post_id ID del post.
 */
function mst_sync_architecture_content_links( $post_id ) {
	if ( mst_arch_is_internal_save( $post_id ) ) {
		return;
	}

	$post = get_post( $post_id );
	if ( ! $post || ! in_array( $post->post_type, array( 'post', 'page' ), true ) ) {
		return;
	}

	if ( ! mst_arch_should_enforce_validation( $post_id ) && empty( mst_get_planned_outbound_links( $post_id ) ) ) {
		return;
	}

	$new_content = mst_apply_architecture_links_to_content( $post->post_content, $post_id );
	if ( $new_content === $post->post_content ) {
		return;
	}

	update_post_meta( $post_id, '_mst_arch_syncing', '1' );

	wp_update_post(
		array(
			'ID'           => $post_id,
			'post_content' => $new_content,
		)
	);

	delete_post_meta( $post_id, '_mst_arch_syncing' );
}

/**
 * Meta overrides desde POST clásico.
 *
 * @param int $post_id ID del post.
 */
function mst_arch_meta_overrides_from_post( $post_id ) {
	unset( $post_id );
	if ( ! isset( $_POST['mst_arch_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mst_arch_nonce'] ) ), 'mst_save_architecture' ) ) {
		return array();
	}

	$overrides = array();
	$map       = array(
		'mst_arch_id'                    => '_mst_arch_id',
		'mst_arch_parent_id'             => '_mst_arch_parent_id',
		'mst_arch_content_type'          => '_mst_arch_content_type',
		'mst_arch_links_out'             => '_mst_arch_links_out',
		'mst_arch_links_in'              => '_mst_arch_links_in',
		'mst_arch_ecommerce_destination' => '_mst_arch_ecommerce_destination',
		'mst_arch_ecommerce_url'         => '_mst_arch_ecommerce_url',
		'mst_arch_anchor_texts'          => '_mst_arch_anchor_texts',
	);

	foreach ( $map as $field => $meta_key ) {
		if ( isset( $_POST[ $field ] ) ) {
			$value = wp_unslash( $_POST[ $field ] );
			if ( in_array( $meta_key, array( '_mst_arch_links_out', '_mst_arch_links_in' ), true ) ) {
				$value = wp_json_encode( mst_parse_arch_id_list( $value ) );
			}
			$overrides[ $meta_key ] = $value;
		}
	}

	$overrides['_mst_arch_ecommerce_enabled'] = isset( $_POST['mst_arch_ecommerce_enabled'] );

	return $overrides;
}

/**
 * ¿Se intenta publicar en este guardado?
 *
 * @param int $post_id ID del post.
 */
function mst_arch_is_publish_attempt( $post_id ) {
	if ( isset( $_POST['post_status'] ) && 'publish' === sanitize_key( wp_unslash( $_POST['post_status'] ) ) ) {
		return true;
	}
	return 'publish' === get_post_status( $post_id );
}

/**
 * Revertir a borrador por errores críticos.
 *
 * @param int                              $post_id ID del post.
 * @param array<int, array<string,string>> $issues  Errores.
 */
function mst_arch_revert_to_draft( $post_id, $issues ) {
	update_post_meta( $post_id, '_mst_arch_syncing', '1' );
	wp_update_post(
		array(
			'ID'          => $post_id,
			'post_status' => 'draft',
		)
	);
	delete_post_meta( $post_id, '_mst_arch_syncing' );

	set_transient( 'mst_arch_publish_blocked_' . get_current_user_id() . '_' . $post_id, $issues, 120 );
}

/**
 * Pipeline tras guardar: validar, enlazar, bloquear publicación.
 *
 * @param int $post_id ID del post.
 */
function mst_architecture_post_save_pipeline( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( mst_arch_is_internal_save( $post_id ) ) {
		return;
	}

	$post = get_post( $post_id );
	if ( ! $post || ! in_array( $post->post_type, array( 'post', 'page' ), true ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$overrides = mst_arch_meta_overrides_from_post( $post_id );
	$content   = isset( $_POST['content'] ) ? wp_unslash( $_POST['content'] ) : $post->post_content;

	if ( ! empty( $overrides ) ) {
		foreach ( $overrides as $meta_key => $value ) {
			if ( '_mst_arch_ecommerce_enabled' === $meta_key ) {
				update_post_meta( $post_id, $meta_key, (bool) $value );
			} else {
				update_post_meta( $post_id, $meta_key, mst_sanitize_architecture_meta( $value, $meta_key ) );
			}
		}
	}

	$issues = mst_validate_architecture( $post_id, $overrides, $content );
	update_post_meta( $post_id, '_mst_arch_brief', wp_json_encode( mst_build_arch_brief( $post_id ), JSON_UNESCAPED_UNICODE ) );
	update_post_meta( $post_id, '_mst_arch_validation', wp_json_encode( $issues, JSON_UNESCAPED_UNICODE ) );
	set_transient( 'mst_arch_notice_' . get_current_user_id() . '_' . $post_id, $issues, 60 );

	if ( mst_arch_should_enforce_validation( $post_id ) && mst_arch_is_publish_attempt( $post_id ) && mst_arch_has_blocking_errors( $issues ) ) {
		mst_arch_revert_to_draft( $post_id, $issues );
		return;
	}

	mst_sync_architecture_content_links( $post_id );
}
add_action( 'save_post', 'mst_architecture_post_save_pipeline', 25 );

/**
 * Bloquear publicación vía REST (editor de bloques).
 *
 * @param WP_Post|object    $prepared_post Post preparado.
 * @param WP_REST_Request   $request       Request.
 */
function mst_rest_prevent_arch_publish( $prepared_post, $request ) {
	if ( ! is_object( $prepared_post ) || empty( $prepared_post->post_status ) || 'publish' !== $prepared_post->post_status ) {
		return $prepared_post;
	}

	$post_id = ! empty( $prepared_post->ID ) ? (int) $prepared_post->ID : 0;
	$meta    = $request->get_param( 'meta' );
	$meta    = is_array( $meta ) ? $meta : array();

	$should = false;
	if ( $post_id && mst_arch_should_enforce_validation( $post_id ) ) {
		$should = true;
	}
	if ( ! empty( $meta['_mst_arch_content_type'] ) || ! empty( $meta['_mst_arch_id'] ) ) {
		$should = true;
	}
	if ( ! $should ) {
		return $prepared_post;
	}

	$issues = mst_validate_architecture( $post_id, $meta, $prepared_post->post_content );
	if ( ! mst_arch_has_blocking_errors( $issues ) ) {
		return $prepared_post;
	}

	$messages = wp_list_pluck(
		array_filter(
			$issues,
			static function ( $i ) {
				return 'error' === ( $i['level'] ?? '' );
			}
		),
		'message'
	);

	return new WP_Error(
		'mst_arch_publish_blocked',
		__( 'No se puede publicar: corrige la arquitectura SEO.', 'minimal-seo-theme' ) . ' ' . implode( ' · ', $messages ),
		array(
			'status' => 403,
			'issues' => $issues,
		)
	);
}
add_filter( 'rest_pre_insert_post', 'mst_rest_prevent_arch_publish', 20, 2 );
add_filter( 'rest_pre_insert_page', 'mst_rest_prevent_arch_publish', 20, 2 );

/**
 * Aviso cuando la publicación fue revertida a borrador.
 */
function mst_architecture_publish_blocked_notice() {
	if ( ! is_admin() || ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || ! in_array( $screen->base, array( 'post', 'page' ), true ) ) {
		return;
	}

	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! $post_id ) {
		return;
	}

	$issues = get_transient( 'mst_arch_publish_blocked_' . get_current_user_id() . '_' . $post_id );
	if ( false === $issues || ! is_array( $issues ) ) {
		return;
	}
	delete_transient( 'mst_arch_publish_blocked_' . get_current_user_id() . '_' . $post_id );

	echo '<div class="notice notice-error"><p><strong>' . esc_html__( 'Publicación cancelada.', 'minimal-seo-theme' ) . '</strong> ';
	echo esc_html__( 'Hay errores críticos en la arquitectura SEO. El contenido se guardó como borrador.', 'minimal-seo-theme' );
	echo '</p><ul>';
	foreach ( $issues as $issue ) {
		if ( 'error' !== ( $issue['level'] ?? '' ) ) {
			continue;
		}
		echo '<li>' . esc_html( $issue['message'] ) . '</li>';
	}
	echo '</ul></div>';
}
add_action( 'admin_notices', 'mst_architecture_publish_blocked_notice', 5 );

/**
 * Estilos mínimos del bloque de enlaces automáticos.
 */
function mst_architecture_links_styles() {
	if ( ! is_singular( array( 'post', 'page' ) ) ) {
		return;
	}
	?>
	<style>
		.mst-arch-links{margin:2rem 0 0;padding:1.25rem 1.5rem;border:1px solid var(--mst-border,#ddd);border-radius:8px;background:var(--mst-surface,#f9f9f9)}
		.mst-arch-links__title{margin:0 0 .75rem;font-weight:600}
		.mst-arch-links__list{margin:0;padding-left:1.25rem}
		.mst-arch-links__list li{margin:.35rem 0}
	</style>
	<?php
}
add_action( 'wp_head', 'mst_architecture_links_styles', 99 );
