<?php
/**
 * Campos personalizados — entradas y páginas (solo admin, 0 JS en frontend)
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registrar meta keys.
 */
function mst_register_post_meta() {
	$keys = array(
		'_mst_subtitle'      => 'string',
		'_mst_lead'          => 'string',
		'_mst_cta_text'      => 'string',
		'_mst_cta_url'       => 'string',
		'_mst_hide_featured'        => 'boolean',
		'_mst_cluster_featured'     => 'boolean',
		'_mst_cluster_description'  => 'string',
	);

	foreach ( array( 'post', 'page' ) as $post_type ) {
		foreach ( $keys as $key => $type ) {
			register_post_meta(
				$post_type,
				$key,
				array(
					'single'            => true,
					'type'              => $type,
					'show_in_rest'      => true,
					'auth_callback'     => function () {
						return current_user_can( 'edit_posts' );
					},
					'sanitize_callback' => 'mst_sanitize_post_meta',
				)
			);
		}
	}
}
add_action( 'init', 'mst_register_post_meta' );

/**
 * Sanitizar meta.
 */
function mst_sanitize_post_meta( $value, $meta_key ) {
	if ( '_mst_hide_featured' === $meta_key || '_mst_cluster_featured' === $meta_key ) {
		return (bool) $value;
	}
	if ( '_mst_cluster_description' === $meta_key ) {
		return sanitize_textarea_field( $value );
	}
	if ( '_mst_cta_url' === $meta_key ) {
		return esc_url_raw( $value );
	}
	return sanitize_text_field( $value );
}

/**
 * Meta box en el editor clásico / sidebar.
 */
function mst_add_meta_boxes() {
	$screens = array( 'post', 'page' );
	foreach ( $screens as $screen ) {
		add_meta_box(
			'mst-content-fields',
			__( 'Campos extra del tema — qué va en cada zona', 'minimal-seo-theme' ),
			'mst_render_meta_box',
			$screen,
			'side',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'mst_add_meta_boxes' );

/**
 * HTML del meta box.
 */
function mst_render_meta_box( $post ) {
	wp_nonce_field( 'mst_save_meta', 'mst_meta_nonce' );

	$subtitle = get_post_meta( $post->ID, '_mst_subtitle', true );
	$lead     = get_post_meta( $post->ID, '_mst_lead', true );
	$cta_text = get_post_meta( $post->ID, '_mst_cta_text', true );
	$cta_url  = get_post_meta( $post->ID, '_mst_cta_url', true );
	$hide     = (bool) get_post_meta( $post->ID, '_mst_hide_featured', true );
	$cluster_featured = (bool) get_post_meta( $post->ID, '_mst_cluster_featured', true );
	$cluster_desc     = get_post_meta( $post->ID, '_mst_cluster_description', true );
	?>
	<p class="description" style="margin-top:0"><?php esc_html_e( 'Completa estos campos para que cada zona de la página tenga su texto. Los textos [EDITAR] son recordatorios que debes reemplazar.', 'minimal-seo-theme' ); ?></p>
	<p>
		<label for="mst_subtitle"><strong><?php esc_html_e( 'Línea bajo el título', 'minimal-seo-theme' ); ?></strong></label><br>
		<input type="text" id="mst_subtitle" name="mst_subtitle" value="<?php echo esc_attr( $subtitle ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'Una frase corta bajo el título principal', 'minimal-seo-theme' ); ?>">
	</p>
	<p>
		<label for="mst_lead"><strong><?php esc_html_e( 'Texto de introducción', 'minimal-seo-theme' ); ?></strong></label><br>
		<textarea id="mst_lead" name="mst_lead" rows="3" class="widefat" placeholder="<?php esc_attr_e( '1 o 2 frases que presenten el artículo o la página', 'minimal-seo-theme' ); ?>"><?php echo esc_textarea( $lead ); ?></textarea>
	</p>
	<p>
		<label for="mst_cta_text"><strong><?php esc_html_e( 'Botón al final — Texto', 'minimal-seo-theme' ); ?></strong></label><br>
		<input type="text" id="mst_cta_text" name="mst_cta_text" value="<?php echo esc_attr( $cta_text ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'Ejemplo: Contactar / Descargar / Ver más', 'minimal-seo-theme' ); ?>">
	</p>
	<p>
		<label for="mst_cta_url"><strong><?php esc_html_e( 'Botón al final — Enlace', 'minimal-seo-theme' ); ?></strong></label><br>
		<input type="url" id="mst_cta_url" name="mst_cta_url" value="<?php echo esc_url( $cta_url ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'Pega aquí la página de destino del botón', 'minimal-seo-theme' ); ?>">
	</p>
	<p>
		<label>
			<input type="checkbox" name="mst_hide_featured" value="1" <?php checked( $hide ); ?>>
			<?php esc_html_e( 'No mostrar la imagen destacada en esta página', 'minimal-seo-theme' ); ?>
		</label>
	</p>
	<hr>
	<p><strong><?php esc_html_e( 'Tarjeta en la cuadrícula de artículos', 'minimal-seo-theme' ); ?></strong></p>
	<p>
		<label>
			<input type="checkbox" name="mst_cluster_featured" value="1" <?php checked( $cluster_featured ); ?>>
			<?php esc_html_e( 'Mostrar esta entrada en la fila superior de tarjetas', 'minimal-seo-theme' ); ?>
		</label>
	</p>
	<p>
		<label for="mst_cluster_description"><strong><?php esc_html_e( 'Texto corto en la tarjeta', 'minimal-seo-theme' ); ?></strong></label><br>
		<textarea id="mst_cluster_description" name="mst_cluster_description" rows="3" class="widefat" placeholder="<?php esc_attr_e( '1 o 2 frases que se ven en la cuadrícula de enlaces', 'minimal-seo-theme' ); ?>"><?php echo esc_textarea( $cluster_desc ); ?></textarea>
	</p>
	<?php
}

/**
 * Guardar meta box.
 */
function mst_save_meta_box( $post_id ) {
	if ( ! isset( $_POST['mst_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mst_meta_nonce'] ) ), 'mst_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$map = array(
		'mst_subtitle' => '_mst_subtitle',
		'mst_lead'     => '_mst_lead',
		'mst_cta_text' => '_mst_cta_text',
		'mst_cta_url'  => '_mst_cta_url',
	);

	foreach ( $map as $field => $meta_key ) {
		if ( isset( $_POST[ $field ] ) ) {
			$value = wp_unslash( $_POST[ $field ] );
			update_post_meta( $post_id, $meta_key, mst_sanitize_post_meta( $value, $meta_key ) );
		}
	}

	update_post_meta( $post_id, '_mst_hide_featured', isset( $_POST['mst_hide_featured'] ) );
	update_post_meta( $post_id, '_mst_cluster_featured', isset( $_POST['mst_cluster_featured'] ) );

	if ( isset( $_POST['mst_cluster_description'] ) ) {
		update_post_meta(
			$post_id,
			'_mst_cluster_description',
			mst_sanitize_post_meta( wp_unslash( $_POST['mst_cluster_description'] ), '_mst_cluster_description' )
		);
	}
}
add_action( 'save_post', 'mst_save_meta_box' );

/**
 * Obtener meta del post actual.
 */
function mst_get_field( $key, $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( ! $post_id ) {
		return '';
	}
	return get_post_meta( $post_id, $key, true );
}

/**
 * Mostrar campos extra bajo el título.
 */
function mst_the_entry_intro() {
	$subtitle = mst_get_field( '_mst_subtitle' );
	$lead     = mst_get_field( '_mst_lead' );

	if ( $subtitle ) {
		echo '<p' . mst_placeholder_class_attr( $subtitle, 'entry-subtitle' ) . '>' . esc_html( $subtitle ) . '</p>';
	}
	if ( $lead ) {
		echo '<p' . mst_placeholder_class_attr( $lead, 'entry-lead' ) . '>' . esc_html( $lead ) . '</p>';
	}
}

/**
 * Mostrar CTA opcional tras el contenido.
 */
function mst_the_entry_cta() {
	$text = mst_get_field( '_mst_cta_text' );
	$url  = mst_get_field( '_mst_cta_url' );

	if ( empty( $text ) || empty( $url ) ) {
		return;
	}
	?>
	<p class="entry-cta">
		<a class="mst-btn" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $text ); ?></a>
	</p>
	<?php
}

/**
 * ¿Mostrar imagen destacada?
 */
function mst_show_featured_image() {
	return ! mst_get_field( '_mst_hide_featured' );
}
