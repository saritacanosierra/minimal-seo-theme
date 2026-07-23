<?php
/**
 * Árbol de decisión e-commerce para interlinking comercial.
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Acciones posibles del árbol comercial.
 *
 * @return array<string, string>
 */
function mst_get_ecommerce_action_results() {
	return array(
		'OMIT_CTA'                => __( 'Omitir CTA comercial principal', 'minimal-seo-theme' ),
		'LINK_FILTERED_CATEGORY'  => __( 'Enlazar categoría filtrada', 'minimal-seo-theme' ),
		'LINK_APPROVED_CATEGORY'  => __( 'Enlazar categoría aprobada', 'minimal-seo-theme' ),
		'LINK_DIRECT_PRODUCT'     => __( 'Enlazar ficha de producto', 'minimal-seo-theme' ),
	);
}

/**
 * Leer inputs del árbol desde meta/overrides.
 *
 * @param int                  $post_id    ID del post.
 * @param array<string, mixed> $overrides  Overrides de meta.
 * @return array<string, mixed>
 */
function mst_get_ecommerce_decision_inputs( $post_id, $overrides = array() ) {
	$raw = mst_arch_get_meta_value( $post_id, '_mst_arch_ecommerce_decision_tree', $overrides );

	if ( is_string( $raw ) && '' !== $raw ) {
		$decoded = json_decode( $raw, true );
		if ( is_array( $decoded ) ) {
			return mst_normalize_ecommerce_decision_inputs( $decoded );
		}
	}

	if ( is_array( $raw ) ) {
		return mst_normalize_ecommerce_decision_inputs( $raw );
	}

	return mst_normalize_ecommerce_decision_inputs(
		array(
			'treats_pain_or_injury'  => (bool) mst_arch_get_meta_value( $post_id, '_mst_arch_ecom_treats_pain', $overrides ),
			'has_commercial_intent'  => (bool) mst_arch_get_meta_value( $post_id, '_mst_arch_ecom_has_commercial_intent', $overrides ),
			'requires_comparison'    => (bool) mst_arch_get_meta_value( $post_id, '_mst_arch_ecom_requires_comparison', $overrides ),
			'product_is_validated'   => (bool) mst_arch_get_meta_value( $post_id, '_mst_arch_ecom_product_validated', $overrides ),
			'product_url_is_stable'  => (bool) mst_arch_get_meta_value( $post_id, '_mst_arch_ecom_product_url_stable', $overrides ),
			'target_url'             => mst_arch_get_meta_value( $post_id, '_mst_arch_ecom_target_url', $overrides ),
		)
	);
}

/**
 * Normalizar inputs del árbol.
 *
 * @param array<string, mixed> $input Input crudo.
 */
function mst_normalize_ecommerce_decision_inputs( $input ) {
	return array(
		'treats_pain_or_injury' => ! empty( $input['treats_pain_or_injury'] ),
		'has_commercial_intent' => ! empty( $input['has_commercial_intent'] ),
		'requires_comparison'   => ! empty( $input['requires_comparison'] ),
		'product_is_validated'  => ! empty( $input['product_is_validated'] ),
		'product_url_is_stable' => ! empty( $input['product_url_is_stable'] ),
		'target_url'            => esc_url_raw( (string) ( $input['target_url'] ?? '' ) ),
	);
}

/**
 * Resolver acción comercial según el diagrama de flujo.
 *
 * @param array<string, mixed> $input Inputs normalizados.
 */
function mst_resolve_ecommerce_action_result( $input ) {
	if ( ! empty( $input['treats_pain_or_injury'] ) ) {
		return 'OMIT_CTA';
	}
	if ( empty( $input['has_commercial_intent'] ) ) {
		return 'OMIT_CTA';
	}
	if ( ! empty( $input['requires_comparison'] ) ) {
		return 'LINK_FILTERED_CATEGORY';
	}
	if ( ! empty( $input['product_is_validated'] ) ) {
		if ( empty( $input['product_url_is_stable'] ) ) {
			return 'LINK_APPROVED_CATEGORY';
		}
		return 'LINK_DIRECT_PRODUCT';
	}
	return 'OMIT_CTA';
}

/**
 * Construir objeto ecommerce_decision_tree del brief.
 *
 * @param int                  $post_id   ID del post.
 * @param array<string, mixed> $overrides Overrides.
 */
function mst_build_ecommerce_decision_tree( $post_id, $overrides = array() ) {
	$input  = mst_get_ecommerce_decision_inputs( $post_id, $overrides );
	$action = mst_resolve_ecommerce_action_result( $input );

	$target_url = $input['target_url'];
	if ( 'OMIT_CTA' === $action ) {
		$target_url = '';
	} elseif ( '' === $target_url ) {
		$legacy = esc_url_raw( mst_arch_get_meta_value( $post_id, '_mst_arch_ecommerce_url', $overrides ) );
		if ( $legacy ) {
			$target_url = $legacy;
		}
	}

	$tree = array(
		'treats_pain_or_injury' => $input['treats_pain_or_injury'],
		'has_commercial_intent' => $input['has_commercial_intent'],
		'requires_comparison'   => $input['requires_comparison'],
		'product_is_validated'  => $input['product_is_validated'],
		'product_url_is_stable' => $input['product_url_is_stable'],
		'action_result'         => $action,
		'target_url'            => $target_url,
	);

	return apply_filters( 'mst_arch_ecommerce_decision_tree', $tree, $post_id, $overrides );
}

/**
 * Inputs del árbol desde POST del meta box.
 */
function mst_ecommerce_decision_inputs_from_post() {
	if ( ! isset( $_POST['mst_arch_nonce'] ) ) {
		return array();
	}

	return mst_normalize_ecommerce_decision_inputs(
		array(
			'treats_pain_or_injury' => isset( $_POST['mst_arch_ecom_treats_pain'] ),
			'has_commercial_intent' => isset( $_POST['mst_arch_ecom_has_commercial_intent'] ),
			'requires_comparison'   => isset( $_POST['mst_arch_ecom_requires_comparison'] ),
			'product_is_validated'  => isset( $_POST['mst_arch_ecom_product_validated'] ),
			'product_url_is_stable' => isset( $_POST['mst_arch_ecom_product_url_stable'] ),
			'target_url'            => isset( $_POST['mst_arch_ecom_target_url'] ) ? wp_unslash( $_POST['mst_arch_ecom_target_url'] ) : '',
		)
	);
}

/**
 * Persistir árbol de decisión e-commerce.
 *
 * @param int                  $post_id   ID del post.
 * @param array<string, mixed> $overrides Overrides opcionales.
 */
function mst_persist_ecommerce_decision_tree( $post_id, $overrides = array() ) {
	if ( empty( $overrides ) ) {
		$post_inputs = mst_ecommerce_decision_inputs_from_post();
		if ( ! empty( $post_inputs ) ) {
			$overrides['_mst_arch_ecommerce_decision_tree'] = wp_json_encode( $post_inputs );
		}
	}

	$tree = mst_build_ecommerce_decision_tree( $post_id, $overrides );

	update_post_meta( $post_id, '_mst_arch_ecom_treats_pain', $tree['treats_pain_or_injury'] );
	update_post_meta( $post_id, '_mst_arch_ecom_has_commercial_intent', $tree['has_commercial_intent'] );
	update_post_meta( $post_id, '_mst_arch_ecom_requires_comparison', $tree['requires_comparison'] );
	update_post_meta( $post_id, '_mst_arch_ecom_product_validated', $tree['product_is_validated'] );
	update_post_meta( $post_id, '_mst_arch_ecom_product_url_stable', $tree['product_url_is_stable'] );
	update_post_meta( $post_id, '_mst_arch_ecom_target_url', $tree['target_url'] );
	update_post_meta( $post_id, '_mst_arch_ecommerce_decision_tree', wp_json_encode( $tree, JSON_UNESCAPED_UNICODE ) );

	if ( 'OMIT_CTA' === $tree['action_result'] ) {
		update_post_meta( $post_id, '_mst_arch_ecommerce_url', '' );
		if ( ! get_post_meta( $post_id, '_mst_arch_ecommerce_destination', true ) ) {
			update_post_meta( $post_id, '_mst_arch_ecommerce_destination', __( 'Sin enlace comercial', 'minimal-seo-theme' ) );
		}
	} elseif ( $tree['target_url'] ) {
		update_post_meta( $post_id, '_mst_arch_ecommerce_url', $tree['target_url'] );
	}

	return $tree;
}

/**
 * Enlace comercial resuelto para inserción automática.
 *
 * @param int $post_id ID del post.
 * @return array<string, string>|null
 */
function mst_get_ecommerce_commercial_link( $post_id ) {
	if ( ! (bool) get_post_meta( $post_id, '_mst_arch_ecommerce_enabled', true ) ) {
		return null;
	}

	$tree = mst_build_ecommerce_decision_tree( $post_id );
	if ( 'OMIT_CTA' === $tree['action_result'] || '' === $tree['target_url'] ) {
		return null;
	}

	$anchor = sanitize_text_field( get_post_meta( $post_id, '_mst_arch_ecommerce_destination', true ) );
	if ( '' === $anchor || false !== stripos( $anchor, 'sin enlace' ) ) {
		$labels = mst_get_ecommerce_action_results();
		$anchor   = $labels[ $tree['action_result'] ] ?? __( 'Ver opciones en tienda', 'minimal-seo-theme' );
	}

	return array(
		'arch_id'       => 'ecommerce',
		'url'           => $tree['target_url'],
		'anchor'        => $anchor,
		'direction'     => 'comercial',
		'action_result' => $tree['action_result'],
	);
}

/**
 * Validar árbol de decisión e-commerce.
 *
 * @param int                               $post_id         ID del post.
 * @param array<string, mixed>              $meta_overrides  Overrides.
 * @param bool                              $ecom_on         Decisión tomada.
 * @param array<int, array<string, string>> $issues          Issues acumulados.
 */
function mst_validate_ecommerce_decision_tree( $post_id, $meta_overrides, $ecom_on, &$issues ) {
	if ( ! $ecom_on ) {
		return;
	}

	$tree   = mst_build_ecommerce_decision_tree( $post_id, $meta_overrides );
	$action = $tree['action_result'];
	$labels = mst_get_ecommerce_action_results();

	if ( ! isset( $labels[ $action ] ) ) {
		$issues[] = array(
			'level'   => 'error',
			'code'    => 'ecom_tree_invalid_action',
			'message' => __( 'El árbol de decisión e-commerce devolvió una acción no válida.', 'minimal-seo-theme' ),
		);
		return;
	}

	if ( 'OMIT_CTA' === $action ) {
		if ( $tree['target_url'] ) {
			$issues[] = array(
				'level'   => 'error',
				'code'    => 'ecom_omit_with_url',
				'message' => __( 'El árbol indica omitir CTA comercial, pero hay target_url o URL comercial definida.', 'minimal-seo-theme' ),
			);
		}
		if ( $tree['treats_pain_or_injury'] ) {
			$issues[] = array(
				'level'   => 'warning',
				'code'    => 'ecom_pain_omit',
				'message' => __( 'Contenido sobre dolor/irritación: se omite el CTA comercial principal (priorizar seguridad).', 'minimal-seo-theme' ),
			);
		}
		return;
	}

	if ( '' === $tree['target_url'] ) {
		$issues[] = array(
			'level'   => 'error',
			'code'    => 'ecom_missing_target_url',
			'message' => sprintf(
				/* translators: %s: action label */
				__( 'La acción «%s» requiere target_url (categoría filtrada, aprobada o ficha de producto).', 'minimal-seo-theme' ),
				$labels[ $action ]
			),
		);
	}

	if ( 'LINK_FILTERED_CATEGORY' === $action && ! $tree['requires_comparison'] ) {
		$issues[] = array(
			'level'   => 'error',
			'code'    => 'ecom_tree_conflict_comparison',
			'message' => __( 'Inconsistencia: acción de categoría filtrada sin marcar «requiere comparar opciones».', 'minimal-seo-theme' ),
		);
	}

	if ( 'LINK_DIRECT_PRODUCT' === $action ) {
		if ( ! $tree['product_is_validated'] || ! $tree['product_url_is_stable'] ) {
			$issues[] = array(
				'level'   => 'error',
				'code'    => 'ecom_tree_conflict_product',
				'message' => __( 'Inconsistencia: enlace directo a producto requiere producto validado y URL estable.', 'minimal-seo-theme' ),
			);
		}
	}

	if ( 'LINK_APPROVED_CATEGORY' === $action ) {
		if ( ! $tree['product_is_validated'] || $tree['product_url_is_stable'] ) {
			$issues[] = array(
				'level'   => 'error',
				'code'    => 'ecom_tree_conflict_category',
				'message' => __( 'Inconsistencia: categoría aprobada aplica cuando el producto no tiene ficha estable.', 'minimal-seo-theme' ),
			);
		}
	}

	if ( $tree['treats_pain_or_injury'] && 'OMIT_CTA' !== $action ) {
		$issues[] = array(
			'level'   => 'error',
			'code'    => 'ecom_pain_commercial',
			'message' => __( 'Contenido sobre dolor/irritación no puede llevar CTA comercial principal.', 'minimal-seo-theme' ),
		);
	}

	if ( ! $tree['has_commercial_intent'] && 'OMIT_CTA' !== $action ) {
		$issues[] = array(
			'level'   => 'error',
			'code'    => 'ecom_no_intent_link',
			'message' => __( 'Sin intención comercial no puede haber enlace comercial principal.', 'minimal-seo-theme' ),
		);
	}
}
