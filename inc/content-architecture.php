<?php
/**
 * Arquitectura de contenidos, rangos de extensión e interlinking (modelo Órbita).
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tipos de contenido y rangos de palabras.
 *
 * @return array<string, array<string, mixed>>
 */
function mst_get_content_type_definitions() {
	return array(
		'pilar'       => array(
			'label'       => __( 'Post Pilar', 'minimal-seo-theme' ),
			'min_words'   => 3000,
			'max_words'   => 5500,
			'description' => __( 'Tema amplio: mapa conceptual, deriva profundidad a categorías.', 'minimal-seo-theme' ),
			'requires_parent'             => false,
			'requires_ecommerce_decision' => false,
			'requires_related_links'      => false,
			'is_hub'                      => true,
		),
		'categoria'   => array(
			'label'       => __( 'Post Categoría', 'minimal-seo-theme' ),
			'min_words'   => 1800,
			'max_words'   => 3500,
			'description' => __( 'Agrupa una familia de necesidades; puente a posts y e-commerce.', 'minimal-seo-theme' ),
			'requires_parent'             => false,
			'requires_ecommerce_decision' => true,
			'requires_related_links'      => false,
			'is_hub'                      => true,
		),
		'informativo' => array(
			'label'       => __( 'Informativo', 'minimal-seo-theme' ),
			'min_words'   => 800,
			'max_words'   => 1600,
			'description' => __( 'Resuelve una pregunta concreta y educativa.', 'minimal-seo-theme' ),
			'requires_parent'             => true,
			'requires_ecommerce_decision' => true,
			'requires_related_links'      => true,
			'is_hub'                      => false,
		),
		'comparativo' => array(
			'label'       => __( 'Comparativo', 'minimal-seo-theme' ),
			'min_words'   => 1200,
			'max_words'   => 2200,
			'description' => __( 'Opciones y criterios; intención media o comercial.', 'minimal-seo-theme' ),
			'requires_parent'             => true,
			'requires_ecommerce_decision' => true,
			'requires_related_links'      => true,
			'is_hub'                      => false,
		),
		'diagnostico' => array(
			'label'       => __( 'Diagnóstico', 'minimal-seo-theme' ),
			'min_words'   => 1000,
			'max_words'   => 1800,
			'description' => __( 'Molestias, diferencias entre efectos e irritaciones.', 'minimal-seo-theme' ),
			'requires_parent'             => true,
			'requires_ecommerce_decision' => true,
			'requires_related_links'      => true,
			'is_hub'                      => false,
		),
		'guia-compra' => array(
			'label'       => __( 'Guía de compra', 'minimal-seo-theme' ),
			'min_words'   => 1300,
			'max_words'   => 2500,
			'description' => __( 'Criterios de elección y destinos comerciales validados.', 'minimal-seo-theme' ),
			'requires_parent'             => true,
			'requires_ecommerce_decision' => true,
			'requires_related_links'      => true,
			'is_hub'                      => false,
		),
	);
}

/**
 * Matriz de enlaces obligatorios por tipo (salida / entrada).
 *
 * @return array<string, array<string, string>>
 */
function mst_get_link_matrix_rules() {
	return array(
		'pilar'       => array(
			'out' => __( 'Todas las categorías del cluster, guías clave y 1–2 accesos comerciales amplios.', 'minimal-seo-theme' ),
			'in'  => __( 'Todas las categorías hijas y posts estratégicos.', 'minimal-seo-theme' ),
		),
		'categoria'   => array(
			'out' => __( 'Pilar, posts hijos, 1–2 categorías relacionadas y sección e-commerce.', 'minimal-seo-theme' ),
			'in'  => __( 'Pilar y todos sus posts hijos.', 'minimal-seo-theme' ),
		),
		'informativo' => array(
			'out' => __( 'Categoría madre, 2–4 posts relacionados y contenido de seguridad (si aplica).', 'minimal-seo-theme' ),
			'in'  => __( 'Categoría madre y posts complementarios.', 'minimal-seo-theme' ),
		),
		'comparativo' => array(
			'out' => __( 'Guías de opciones, compatibilidad y categoría e-commerce.', 'minimal-seo-theme' ),
			'in'  => __( 'Categoría madre y posts informativos.', 'minimal-seo-theme' ),
		),
		'diagnostico' => array(
			'out' => __( 'Categoría madre, causas, seguridad y orientación profesional.', 'minimal-seo-theme' ),
			'in'  => __( 'Artículos sobre efectos, ingredientes o síntomas.', 'minimal-seo-theme' ),
		),
		'guia-compra' => array(
			'out' => __( 'Educación previa y categorías/productos aprobados.', 'minimal-seo-theme' ),
			'in'  => __( 'Posts de decisión y categoría editorial.', 'minimal-seo-theme' ),
		),
	);
}

/**
 * Direcciones de interlinking operativo.
 *
 * @return array<int, array<string, string>>
 */
function mst_get_interlinking_directions() {
	return array(
		array(
			'key'   => 'ascendente',
			'label' => __( 'Ascendente', 'minimal-seo-theme' ),
			'flow'  => __( 'Post → Categoría | Categoría → Pilar', 'minimal-seo-theme' ),
			'goal'  => __( 'Dar contexto y consolidar jerarquía.', 'minimal-seo-theme' ),
		),
		array(
			'key'   => 'descendente',
			'label' => __( 'Descendente', 'minimal-seo-theme' ),
			'flow'  => __( 'Pilar → Categorías | Categoría → Posts', 'minimal-seo-theme' ),
			'goal'  => __( 'Distribuir autoridad y facilitar exploración.', 'minimal-seo-theme' ),
		),
		array(
			'key'   => 'horizontal',
			'label' => __( 'Horizontal', 'minimal-seo-theme' ),
			'flow'  => __( 'Post → Post complementario', 'minimal-seo-theme' ),
			'goal'  => __( 'Comparar, ampliar, prevenir o continuar.', 'minimal-seo-theme' ),
		),
		array(
			'key'   => 'cruzado',
			'label' => __( 'Cruzado', 'minimal-seo-theme' ),
			'flow'  => __( 'Entre categorías relacionadas', 'minimal-seo-theme' ),
			'goal'  => __( 'Resolver dependencias temáticas.', 'minimal-seo-theme' ),
		),
		array(
			'key'   => 'comercial',
			'label' => __( 'Comercial', 'minimal-seo-theme' ),
			'flow'  => __( 'Contenido → Categoría e-commerce / Producto', 'minimal-seo-theme' ),
			'goal'  => __( 'Paso directo hacia la compra.', 'minimal-seo-theme' ),
		),
	);
}

/**
 * Anclas genéricas prohibidas.
 *
 * @return string[]
 */
function mst_get_forbidden_anchor_patterns() {
	return array(
		'haz clic aqui',
		'haz clic aquí',
		'click aqui',
		'click aquí',
		'ver mas',
		'ver más',
		'leer mas',
		'leer más',
		'click here',
		'read more',
		'see more',
		'aqui',
		'aquí',
		'mas info',
		'más info',
	);
}

/**
 * Etiqueta de rango de palabras.
 *
 * @param string $type Slug del tipo.
 */
function mst_get_word_count_range_label( $type ) {
	$defs = mst_get_content_type_definitions();
	if ( ! isset( $defs[ $type ] ) ) {
		return '';
	}
	return $defs[ $type ]['min_words'] . '-' . $defs[ $type ]['max_words'];
}

/**
 * Contar palabras del contenido publicado.
 *
 * @param int $post_id ID del post.
 */
function mst_count_post_words( $post_id = 0, $content_override = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( ! $post_id && null === $content_override ) {
		return 0;
	}
	if ( null !== $content_override ) {
		$content = $content_override;
	} else {
		$content = get_post_field( 'post_content', $post_id );
	}
	$content = wp_strip_all_tags( strip_shortcodes( (string) $content ) );
	$count   = str_word_count( $content );
	return (int) apply_filters( 'mst_arch_word_count', $count, $post_id );
}

/**
 * Decodificar lista de IDs de arquitectura (JSON o CSV).
 *
 * @param string $raw Valor crudo.
 * @return string[]
 */
function mst_parse_arch_id_list( $raw ) {
	if ( is_array( $raw ) ) {
		$items = $raw;
	} else {
		$raw   = trim( (string) $raw );
		$items = array();
		if ( '' === $raw ) {
			return array();
		}
		if ( '[' === $raw[0] ) {
			$decoded = json_decode( $raw, true );
			if ( is_array( $decoded ) ) {
				$items = $decoded;
			}
		} else {
			$items = preg_split( '/[\s,;]+/', $raw );
		}
	}

	$items = array_map(
		static function ( $item ) {
			return sanitize_text_field( (string) $item );
		},
		$items
	);

	return array_values( array_filter( array_unique( $items ) ) );
}

/**
 * Decodificar matriz de anchor texts.
 *
 * @param string $raw JSON.
 * @return array<int, array<string, string>>
 */
function mst_parse_anchor_texts( $raw ) {
	$raw = trim( (string) $raw );
	if ( '' === $raw ) {
		return array();
	}
	$decoded = json_decode( $raw, true );
	if ( ! is_array( $decoded ) ) {
		return array();
	}
	$out = array();
	foreach ( $decoded as $row ) {
		if ( ! is_array( $row ) || empty( $row['target'] ) || empty( $row['anchor'] ) ) {
			continue;
		}
		$out[] = array(
			'target' => sanitize_text_field( $row['target'] ),
			'anchor' => sanitize_text_field( $row['anchor'] ),
		);
	}
	return $out;
}

/**
 * ¿Ancla prohibida (genérica o ambigua)?
 *
 * @param string $anchor Texto ancla.
 */
function mst_is_forbidden_anchor_text( $anchor ) {
	$anchor = remove_accents( wp_strip_all_tags( strtolower( trim( $anchor ) ) ) );
	if ( '' === $anchor || strlen( $anchor ) < 4 ) {
		return true;
	}
	foreach ( mst_get_forbidden_anchor_patterns() as $pattern ) {
		if ( $anchor === $pattern || false !== strpos( $anchor, $pattern ) ) {
			return true;
		}
	}
	return (bool) apply_filters( 'mst_arch_is_forbidden_anchor', false, $anchor );
}

/**
 * Buscar post/página por ID de arquitectura.
 *
 * @param string $arch_id ID tipo POS-SEN-01.
 * @return int
 */
function mst_get_post_id_by_arch_id( $arch_id ) {
	$arch_id = sanitize_text_field( $arch_id );
	if ( '' === $arch_id ) {
		return 0;
	}
	$query = new WP_Query(
		array(
			'post_type'      => array( 'post', 'page' ),
			'post_status'    => array( 'publish', 'draft', 'pending', 'future', 'private' ),
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_mst_arch_id',
			'meta_value'     => $arch_id,
			'no_found_rows'  => true,
		)
	);
	return ! empty( $query->posts[0] ) ? (int) $query->posts[0] : 0;
}

/**
 * Leer brief de arquitectura de un post.
 *
 * @param int $post_id ID del post.
 * @return array<string, mixed>
 */
function mst_get_arch_brief( $post_id ) {
	$cached = get_post_meta( $post_id, '_mst_arch_brief', true );
	if ( is_string( $cached ) && '' !== $cached ) {
		$decoded = json_decode( $cached, true );
		if ( is_array( $decoded ) ) {
			return $decoded;
		}
	}

	return mst_build_arch_brief( $post_id );
}

/**
 * Construir brief JSON desde meta del post.
 *
 * @param int $post_id ID del post.
 * @return array<string, mixed>
 */
function mst_build_arch_brief( $post_id ) {
	$type = sanitize_key( get_post_meta( $post_id, '_mst_arch_content_type', true ) );

	$brief = array(
		'id'                    => sanitize_text_field( get_post_meta( $post_id, '_mst_arch_id', true ) ),
		'page_title'            => get_the_title( $post_id ),
		'parent_id'             => sanitize_text_field( get_post_meta( $post_id, '_mst_arch_parent_id', true ) ),
		'content_type'          => $type ? mst_get_content_type_definitions()[ $type ]['label'] ?? $type : '',
		'content_type_slug'     => $type,
		'word_count_range'      => mst_get_word_count_range_label( $type ),
		'word_count_actual'     => mst_count_post_words( $post_id ),
		'links_out'             => mst_parse_arch_id_list( get_post_meta( $post_id, '_mst_arch_links_out', true ) ),
		'links_in'              => mst_parse_arch_id_list( get_post_meta( $post_id, '_mst_arch_links_in', true ) ),
		'ecommerce_destination' => sanitize_text_field( get_post_meta( $post_id, '_mst_arch_ecommerce_destination', true ) ),
		'ecommerce_url'         => esc_url_raw( get_post_meta( $post_id, '_mst_arch_ecommerce_url', true ) ),
		'ecommerce_enabled'     => (bool) get_post_meta( $post_id, '_mst_arch_ecommerce_enabled', true ),
		'anchor_texts'          => mst_parse_anchor_texts( get_post_meta( $post_id, '_mst_arch_anchor_texts', true ) ),
		'link_matrix'           => isset( mst_get_link_matrix_rules()[ $type ] ) ? mst_get_link_matrix_rules()[ $type ] : array(),
		'ecommerce_decision_tree' => mst_build_ecommerce_decision_tree( $post_id ),
	);

	return apply_filters( 'mst_arch_brief', $brief, $post_id );
}

/**
 * Validar brief / meta de arquitectura.
 *
 * @param int $post_id ID del post.
 * @return array<int, array<string, string>> Errores y avisos.
 */
function mst_arch_get_meta_value( $post_id, $meta_key, $overrides = array() ) {
	if ( array_key_exists( $meta_key, $overrides ) ) {
		return $overrides[ $meta_key ];
	}
	return get_post_meta( $post_id, $meta_key, true );
}

/**
 * ¿Aplicar validación estricta a esta URL?
 *
 * @param int $post_id ID del post.
 */
function mst_arch_should_enforce_validation( $post_id ) {
	$type    = (string) mst_arch_get_meta_value( $post_id, '_mst_arch_content_type' );
	$arch_id = (string) mst_arch_get_meta_value( $post_id, '_mst_arch_id' );
	return '' !== $type || '' !== $arch_id;
}

/**
 * Validar brief / meta de arquitectura.
 *
 * @param int                  $post_id          ID del post.
 * @param array<string, mixed> $meta_overrides   Meta opcional (REST / guardado).
 * @param string|null          $content_override Contenido para contar palabras.
 */
function mst_validate_architecture( $post_id, $meta_overrides = array(), $content_override = null ) {
	$issues    = array();
	$type      = sanitize_key( mst_arch_get_meta_value( $post_id, '_mst_arch_content_type', $meta_overrides ) );
	$defs      = mst_get_content_type_definitions();
	$arch_id   = sanitize_text_field( mst_arch_get_meta_value( $post_id, '_mst_arch_id', $meta_overrides ) );
	$parent    = sanitize_text_field( mst_arch_get_meta_value( $post_id, '_mst_arch_parent_id', $meta_overrides ) );
	$links_out = mst_parse_arch_id_list( mst_arch_get_meta_value( $post_id, '_mst_arch_links_out', $meta_overrides ) );
	$anchors   = mst_parse_anchor_texts( mst_arch_get_meta_value( $post_id, '_mst_arch_anchor_texts', $meta_overrides ) );
	$ecom_on   = (bool) mst_arch_get_meta_value( $post_id, '_mst_arch_ecommerce_enabled', $meta_overrides );
	$ecom_dest = sanitize_text_field( mst_arch_get_meta_value( $post_id, '_mst_arch_ecommerce_destination', $meta_overrides ) );

	if ( '' === $type ) {
		$issues[] = array(
			'level'   => 'error',
			'code'    => 'missing_type',
			'message' => __( 'Debes asignar un tipo de contenido de la arquitectura.', 'minimal-seo-theme' ),
		);
		return apply_filters( 'mst_arch_validation_issues', $issues, $post_id );
	}

	if ( ! isset( $defs[ $type ] ) ) {
		$issues[] = array(
			'level'   => 'error',
			'code'    => 'invalid_type',
			'message' => __( 'El tipo de contenido no es válido.', 'minimal-seo-theme' ),
		);
		return apply_filters( 'mst_arch_validation_issues', $issues, $post_id );
	}

	$def = $defs[ $type ];

	if ( '' === $arch_id ) {
		$issues[] = array(
			'level'   => 'error',
			'code'    => 'missing_arch_id',
			'message' => __( 'Falta el ID de arquitectura (ej: POS-SEN-01).', 'minimal-seo-theme' ),
		);
	}

	if ( $def['requires_parent'] && '' === $parent ) {
		$issues[] = array(
			'level'   => 'error',
			'code'    => 'missing_parent',
			'message' => __( 'Los posts específicos requieren un padre (Parent ID) de categoría o pilar.', 'minimal-seo-theme' ),
		);
	} elseif ( '' !== $parent && ! mst_get_post_id_by_arch_id( $parent ) && $parent !== $arch_id ) {
		$issues[] = array(
			'level'   => 'warning',
			'code'    => 'parent_not_found',
			'message' => __( 'El Parent ID no coincide con ninguna página registrada en la matriz.', 'minimal-seo-theme' ),
		);
	}

	if ( $def['requires_ecommerce_decision'] && ! $ecom_on ) {
		$issues[] = array(
			'level'   => 'error',
			'code'    => 'ecommerce_undecided',
			'message' => __( 'Debes completar el árbol de decisión e-commerce (marca la casilla de decisión tomada).', 'minimal-seo-theme' ),
		);
	}

	if ( $ecom_on && function_exists( 'mst_validate_ecommerce_decision_tree' ) ) {
		mst_validate_ecommerce_decision_tree( $post_id, $meta_overrides, $ecom_on, $issues );
	} elseif ( $ecom_on && '' === $ecom_dest && '' === esc_url_raw( mst_arch_get_meta_value( $post_id, '_mst_arch_ecommerce_url', $meta_overrides ) ) ) {
		$issues[] = array(
			'level'   => 'warning',
			'code'    => 'ecommerce_empty',
			'message' => __( 'Marcaste enlace comercial pero falta categoría/producto de destino.', 'minimal-seo-theme' ),
		);
	}

	if ( $def['requires_related_links'] && count( $links_out ) < 2 ) {
		$issues[] = array(
			'level'   => 'error',
			'code'    => 'links_out_min',
			'message' => __( 'Define al menos 2 enlaces salientes (links_out) en la matriz.', 'minimal-seo-theme' ),
		);
	}

	if ( 'pilar' === $type && count( $links_out ) < 1 ) {
		$issues[] = array(
			'level'   => 'warning',
			'code'    => 'pilar_links_out',
			'message' => __( 'El pilar debe enlazar hacia sus categorías del cluster.', 'minimal-seo-theme' ),
		);
	}

	if ( 'categoria' === $type && ( '' === $parent || count( $links_out ) < 1 ) ) {
		$issues[] = array(
			'level'   => 'warning',
			'code'    => 'categoria_links',
			'message' => __( 'La categoría debe enlazar al pilar (parent_id) y a posts hijos en links_out.', 'minimal-seo-theme' ),
		);
	}

	foreach ( $links_out as $target_id ) {
		if ( ! mst_get_post_id_by_arch_id( $target_id ) ) {
			$issues[] = array(
				'level'   => 'warning',
				'code'    => 'unknown_target',
				'message' => sprintf(
					/* translators: %s: architecture id */
					__( 'links_out: no existe contenido con ID %s en la matriz.', 'minimal-seo-theme' ),
					$target_id
				),
			);
		}
	}

	$words = mst_count_post_words( $post_id, $content_override );
	if ( $words > 0 && ( $words < $def['min_words'] || $words > $def['max_words'] ) ) {
		$issues[] = array(
			'level'   => 'warning',
			'code'    => 'word_count',
			'message' => sprintf(
				/* translators: 1: actual words, 2: expected range */
				__( 'Extensión actual: %1$d palabras. Rango esperado para este tipo: %2$s.', 'minimal-seo-theme' ),
				$words,
				mst_get_word_count_range_label( $type )
			),
		);
	}

	foreach ( $anchors as $row ) {
		if ( mst_is_forbidden_anchor_text( $row['anchor'] ) ) {
			$issues[] = array(
				'level'   => 'error',
				'code'    => 'forbidden_anchor',
				'message' => sprintf(
					/* translators: %s: anchor text */
					__( 'Ancla prohibida o demasiado genérica: «%s».', 'minimal-seo-theme' ),
					$row['anchor']
				),
			);
		}
	}

	return apply_filters( 'mst_arch_validation_issues', $issues, $post_id );
}

/**
 * ¿Hay errores bloqueantes?
 *
 * @param array<int, array<string, string>> $issues Issues.
 */
function mst_arch_has_blocking_errors( $issues ) {
	foreach ( $issues as $issue ) {
		if ( isset( $issue['level'] ) && 'error' === $issue['level'] ) {
			return true;
		}
	}
	return false;
}

/**
 * Exportar matriz completa del sitio.
 *
 * @return array<int, array<string, mixed>>
 */
function mst_export_architecture_matrix() {
	$query = new WP_Query(
		array(
			'post_type'      => array( 'post', 'page' ),
			'post_status'    => array( 'publish', 'draft', 'pending', 'future', 'private' ),
			'posts_per_page' => -1,
			'meta_key'       => '_mst_arch_id',
			'orderby'        => 'title',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);

	$matrix = array();
	if ( $query->have_posts() ) {
		foreach ( $query->posts as $post ) {
			$matrix[] = mst_build_arch_brief( $post->ID );
		}
	}

	return apply_filters( 'mst_arch_export_matrix', $matrix );
}

/**
 * Query de posts relacionados por matriz links_out.
 *
 * @param int $post_id ID del post.
 * @return WP_Query|null
 */
function mst_get_arch_related_query( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( ! $post_id ) {
		return null;
	}

	$targets = mst_parse_arch_id_list( get_post_meta( $post_id, '_mst_arch_links_out', true ) );
	if ( empty( $targets ) ) {
		return null;
	}

	$post_ids = array();
	foreach ( $targets as $arch_id ) {
		$found = mst_get_post_id_by_arch_id( $arch_id );
		if ( $found && (int) $found !== (int) $post_id ) {
			$post_ids[] = $found;
		}
	}

	if ( empty( $post_ids ) ) {
		return null;
	}

	return new WP_Query(
		array(
			'post_type'           => array( 'post', 'page' ),
			'post_status'         => 'publish',
			'post__in'            => $post_ids,
			'orderby'             => 'post__in',
			'posts_per_page'      => count( $post_ids ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
}
