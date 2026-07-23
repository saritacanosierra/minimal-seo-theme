<?php
/**
 * Admin — arquitectura SEO, brief e interlinking.
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registrar meta de arquitectura.
 */
function mst_register_architecture_meta() {
	$string_keys = array(
		'_mst_arch_id',
		'_mst_arch_parent_id',
		'_mst_arch_content_type',
		'_mst_arch_links_out',
		'_mst_arch_links_in',
		'_mst_arch_ecommerce_destination',
		'_mst_arch_ecommerce_url',
		'_mst_arch_anchor_texts',
		'_mst_arch_brief',
		'_mst_arch_validation',
	);

	foreach ( array( 'post', 'page' ) as $post_type ) {
		foreach ( $string_keys as $key ) {
			register_post_meta(
				$post_type,
				$key,
				array(
					'single'            => true,
					'type'              => 'string',
					'show_in_rest'      => true,
					'auth_callback'     => function () {
						return current_user_can( 'edit_posts' );
					},
					'sanitize_callback' => 'mst_sanitize_architecture_meta',
				)
			);
		}
		register_post_meta(
			$post_type,
			'_mst_arch_ecommerce_enabled',
			array(
				'single'            => true,
				'type'              => 'boolean',
				'show_in_rest'      => true,
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
				'sanitize_callback' => 'rest_sanitize_boolean',
			)
		);
	}
}
add_action( 'init', 'mst_register_architecture_meta', 20 );

/**
 * Sanitizar meta de arquitectura.
 *
 * @param mixed  $value    Valor.
 * @param string $meta_key Clave.
 */
function mst_sanitize_architecture_meta( $value, $meta_key = '' ) {
	if ( '_mst_arch_ecommerce_url' === $meta_key ) {
		return esc_url_raw( $value );
	}
	if ( in_array( $meta_key, array( '_mst_arch_links_out', '_mst_arch_links_in', '_mst_arch_anchor_texts', '_mst_arch_brief', '_mst_arch_validation' ), true ) ) {
		return is_string( $value ) ? $value : wp_json_encode( $value );
	}
	return sanitize_text_field( (string) $value );
}

/**
 * Meta box de arquitectura.
 */
function mst_add_architecture_meta_box() {
	foreach ( array( 'post', 'page' ) as $screen ) {
		add_meta_box(
			'mst-architecture',
			__( 'Arquitectura SEO e interlinking', 'minimal-seo-theme' ),
			'mst_render_architecture_meta_box',
			$screen,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'mst_add_architecture_meta_box' );

/**
 * Render meta box.
 *
 * @param WP_Post $post Post.
 */
function mst_render_architecture_meta_box( $post ) {
	wp_nonce_field( 'mst_save_architecture', 'mst_arch_nonce' );

	$arch_id   = get_post_meta( $post->ID, '_mst_arch_id', true );
	$parent_id = get_post_meta( $post->ID, '_mst_arch_parent_id', true );
	$type      = get_post_meta( $post->ID, '_mst_arch_content_type', true );
	$links_out = get_post_meta( $post->ID, '_mst_arch_links_out', true );
	$links_in  = get_post_meta( $post->ID, '_mst_arch_links_in', true );
	$ecom_dest = get_post_meta( $post->ID, '_mst_arch_ecommerce_destination', true );
	$ecom_url  = get_post_meta( $post->ID, '_mst_arch_ecommerce_url', true );
	$ecom_on   = (bool) get_post_meta( $post->ID, '_mst_arch_ecommerce_enabled', true );
	$anchors   = get_post_meta( $post->ID, '_mst_arch_anchor_texts', true );
	$brief     = mst_build_arch_brief( $post->ID );
	$issues    = mst_validate_architecture( $post->ID );
	$matrix    = mst_get_link_matrix_rules();
	$rules     = isset( $matrix[ $type ] ) ? $matrix[ $type ] : null;
	$words     = mst_count_post_words( $post->ID );
	$types     = mst_get_content_type_definitions();
	$guide_url = function_exists( 'mst_get_architecture_beginner_guide_url' ) ? mst_get_architecture_beginner_guide_url() : '';
	?>
	<?php if ( $guide_url ) : ?>
		<p>
			<a class="button" href="<?php echo esc_url( $guide_url ); ?>"><?php esc_html_e( '¿Primera vez? Lee la guía fácil (sin tecnicismos)', 'minimal-seo-theme' ); ?></a>
		</p>
	<?php endif; ?>
	<p class="description"><?php esc_html_e( 'Al guardar, el tema inserta enlaces según anchor_texts y links_out. Si hay errores críticos (rojos), no se puede publicar.', 'minimal-seo-theme' ); ?></p>

	<?php if ( ! empty( $issues ) ) : ?>
		<div class="mst-arch-notice mst-arch-notice--issues">
			<p><strong><?php esc_html_e( 'Estado de validacion', 'minimal-seo-theme' ); ?></strong></p>
			<ul>
				<?php foreach ( $issues as $issue ) : ?>
					<li class="mst-arch-notice__<?php echo esc_attr( $issue['level'] ); ?>">
						<?php echo esc_html( $issue['message'] ); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<table class="form-table mst-arch-form" role="presentation">
		<tr>
			<th scope="row"><label for="mst_arch_id"><?php esc_html_e( 'ID de arquitectura', 'minimal-seo-theme' ); ?></label></th>
			<td>
				<input type="text" class="regular-text" id="mst_arch_id" name="mst_arch_id" value="<?php echo esc_attr( $arch_id ); ?>" placeholder="POS-SEN-01">
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="mst_arch_content_type"><?php esc_html_e( 'Tipo de contenido', 'minimal-seo-theme' ); ?></label></th>
			<td>
				<select id="mst_arch_content_type" name="mst_arch_content_type">
					<option value=""><?php esc_html_e( '— Seleccionar —', 'minimal-seo-theme' ); ?></option>
					<?php foreach ( $types as $slug => $def ) : ?>
						<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $type, $slug ); ?>>
							<?php echo esc_html( $def['label'] . ' (' . mst_get_word_count_range_label( $slug ) . ')' ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="mst_arch_parent_id"><?php esc_html_e( 'Parent ID', 'minimal-seo-theme' ); ?></label></th>
			<td>
				<input type="text" class="regular-text" id="mst_arch_parent_id" name="mst_arch_parent_id" value="<?php echo esc_attr( $parent_id ); ?>" placeholder="CAT-SEN">
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="mst_arch_links_out"><?php esc_html_e( 'links_out', 'minimal-seo-theme' ); ?></label></th>
			<td>
				<textarea id="mst_arch_links_out" name="mst_arch_links_out" rows="2" class="large-text"><?php echo esc_textarea( (string) $links_out ); ?></textarea>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="mst_arch_links_in"><?php esc_html_e( 'links_in', 'minimal-seo-theme' ); ?></label></th>
			<td>
				<textarea id="mst_arch_links_in" name="mst_arch_links_in" rows="2" class="large-text"><?php echo esc_textarea( (string) $links_in ); ?></textarea>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'E-commerce', 'minimal-seo-theme' ); ?></th>
			<td>
				<label>
					<input type="checkbox" name="mst_arch_ecommerce_enabled" value="1" <?php checked( $ecom_on ); ?>>
					<?php esc_html_e( 'Decision tomada sobre enlace comercial', 'minimal-seo-theme' ); ?>
				</label>
				<p>
					<input type="text" class="regular-text" id="mst_arch_ecommerce_destination" name="mst_arch_ecommerce_destination" value="<?php echo esc_attr( $ecom_dest ); ?>" placeholder="<?php esc_attr_e( 'Categoria e-commerce', 'minimal-seo-theme' ); ?>">
				</p>
				<p>
					<input type="url" class="large-text" id="mst_arch_ecommerce_url" name="mst_arch_ecommerce_url" value="<?php echo esc_url( $ecom_url ); ?>">
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="mst_arch_anchor_texts"><?php esc_html_e( 'anchor_texts (JSON)', 'minimal-seo-theme' ); ?></label></th>
			<td>
				<textarea id="mst_arch_anchor_texts" name="mst_arch_anchor_texts" rows="4" class="large-text code"><?php echo esc_textarea( (string) $anchors ); ?></textarea>
			</td>
		</tr>
	</table>

	<?php if ( $rules ) : ?>
		<div class="mst-arch-matrix-hint">
			<p><strong><?php esc_html_e( 'Sale hacia:', 'minimal-seo-theme' ); ?></strong> <?php echo esc_html( $rules['out'] ); ?></p>
			<p><strong><?php esc_html_e( 'Recibe de:', 'minimal-seo-theme' ); ?></strong> <?php echo esc_html( $rules['in'] ); ?></p>
		</div>
	<?php endif; ?>

	<p class="description">
		<?php
		printf(
			esc_html__( 'Palabras actuales: %1$d. Rango: %2$s.', 'minimal-seo-theme' ),
			(int) $words,
			esc_html( mst_get_word_count_range_label( $type ) ?: '—' )
		);
		?>
	</p>

	<details class="mst-arch-brief-preview">
		<summary><?php esc_html_e( 'Vista previa brief JSON', 'minimal-seo-theme' ); ?></summary>
		<pre class="mst-arch-json"><?php echo esc_html( wp_json_encode( $brief, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); ?></pre>
	</details>
	<?php
}

/**
 * Guardar meta de arquitectura.
 *
 * @param int $post_id ID del post.
 */
function mst_save_architecture_meta_box( $post_id ) {
	if ( ! isset( $_POST['mst_arch_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mst_arch_nonce'] ) ), 'mst_save_architecture' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		'mst_arch_id'                    => '_mst_arch_id',
		'mst_arch_parent_id'             => '_mst_arch_parent_id',
		'mst_arch_content_type'          => '_mst_arch_content_type',
		'mst_arch_links_out'             => '_mst_arch_links_out',
		'mst_arch_links_in'              => '_mst_arch_links_in',
		'mst_arch_ecommerce_destination' => '_mst_arch_ecommerce_destination',
		'mst_arch_ecommerce_url'         => '_mst_arch_ecommerce_url',
		'mst_arch_anchor_texts'          => '_mst_arch_anchor_texts',
	);

	foreach ( $fields as $field => $meta_key ) {
		if ( isset( $_POST[ $field ] ) ) {
			$value = wp_unslash( $_POST[ $field ] );
			if ( in_array( $meta_key, array( '_mst_arch_links_out', '_mst_arch_links_in' ), true ) ) {
				$value = wp_json_encode( mst_parse_arch_id_list( $value ) );
			}
			update_post_meta( $post_id, $meta_key, mst_sanitize_architecture_meta( $value, $meta_key ) );
		}
	}

	update_post_meta( $post_id, '_mst_arch_ecommerce_enabled', isset( $_POST['mst_arch_ecommerce_enabled'] ) );

	$brief  = mst_build_arch_brief( $post_id );
	$issues = mst_validate_architecture( $post_id );
	update_post_meta( $post_id, '_mst_arch_brief', wp_json_encode( $brief, JSON_UNESCAPED_UNICODE ) );
	update_post_meta( $post_id, '_mst_arch_validation', wp_json_encode( $issues, JSON_UNESCAPED_UNICODE ) );
	set_transient( 'mst_arch_notice_' . get_current_user_id() . '_' . $post_id, $issues, 60 );
}
add_action( 'save_post', 'mst_save_architecture_meta_box', 15 );

/**
 * Avisos tras guardar.
 */
function mst_architecture_admin_notices() {
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
	$issues = get_transient( 'mst_arch_notice_' . get_current_user_id() . '_' . $post_id );
	if ( false === $issues || ! is_array( $issues ) ) {
		return;
	}
	delete_transient( 'mst_arch_notice_' . get_current_user_id() . '_' . $post_id );
	foreach ( array( 'error' => 'notice-error', 'warning' => 'notice-warning' ) as $level => $class ) {
		$filtered = array_filter( $issues, static function ( $i ) use ( $level ) { return $level === ( $i['level'] ?? '' ); } );
		if ( empty( $filtered ) ) {
			continue;
		}
		echo '<div class="notice ' . esc_attr( $class ) . '"><ul>';
		foreach ( $filtered as $issue ) {
			echo '<li>' . esc_html( $issue['message'] ) . '</li>';
		}
		echo '</ul></div>';
	}
}
add_action( 'admin_notices', 'mst_architecture_admin_notices' );

/**
 * Pagina matriz global.
 */
function mst_register_architecture_admin_page() {
	add_submenu_page(
		'themes.php',
		__( 'Matriz de arquitectura SEO', 'minimal-seo-theme' ),
		__( 'Matriz SEO', 'minimal-seo-theme' ),
		'edit_posts',
		'mst-architecture-matrix',
		'mst_render_architecture_matrix_page'
	);
}
add_action( 'admin_menu', 'mst_register_architecture_admin_page' );

/**
 * Render pagina matriz.
 */
function mst_render_architecture_matrix_page() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}
	$matrix     = mst_export_architecture_matrix();
	$directions = mst_get_interlinking_directions();
	$guide_url  = function_exists( 'mst_get_architecture_beginner_guide_url' ) ? mst_get_architecture_beginner_guide_url() : '';
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Matriz de arquitectura SEO', 'minimal-seo-theme' ); ?></h1>
		<?php if ( $guide_url ) : ?>
			<p><a class="button button-primary" href="<?php echo esc_url( $guide_url ); ?>"><?php esc_html_e( 'Guía fácil para principiantes', 'minimal-seo-theme' ); ?></a></p>
		<?php endif; ?>
		<table class="widefat striped">
			<thead><tr><th><?php esc_html_e( 'Direccion', 'minimal-seo-theme' ); ?></th><th><?php esc_html_e( 'Flujo', 'minimal-seo-theme' ); ?></th><th><?php esc_html_e( 'Funcion', 'minimal-seo-theme' ); ?></th></tr></thead>
			<tbody>
			<?php foreach ( $directions as $row ) : ?>
				<tr><td><?php echo esc_html( $row['label'] ); ?></td><td><?php echo esc_html( $row['flow'] ); ?></td><td><?php echo esc_html( $row['goal'] ); ?></td></tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<h2><?php esc_html_e( 'Export JSON', 'minimal-seo-theme' ); ?></h2>
		<textarea class="large-text code" rows="16" readonly><?php echo esc_textarea( wp_json_encode( $matrix, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); ?></textarea>
	</div>
	<?php
}
