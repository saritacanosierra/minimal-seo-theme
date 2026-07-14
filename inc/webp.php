<?php
/**
 * Conversión automática de imágenes subidas a WebP
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * ¿El servidor puede generar WebP?
 */
function mst_webp_is_supported() {
	if ( ! function_exists( 'wp_image_editor_supports' ) ) {
		return false;
	}

	$supports = wp_image_editor_supports(
		array(
			'mime_type' => 'image/webp',
		)
	);

	return (bool) $supports;
}

/**
 * Convertir un archivo JPG/PNG a WebP junto al original.
 *
 * @param string $path Ruta absoluta del archivo.
 * @return string|false Ruta del WebP o false si falla.
 */
function mst_convert_file_to_webp( $path ) {
	if ( ! file_exists( $path ) || ! mst_webp_is_supported() ) {
		return false;
	}

	if ( ! preg_match( '/\.(jpe?g|png)$/i', $path ) ) {
		return false;
	}

	$editor = wp_get_image_editor( $path );
	if ( is_wp_error( $editor ) ) {
		return false;
	}

	$editor->set_quality( 82 );

	$dest = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $path );
	$saved = $editor->save( $dest, 'image/webp' );

	if ( is_wp_error( $saved ) || empty( $saved['path'] ) ) {
		return false;
	}

	return $saved['path'];
}

/**
 * Tras generar miniaturas, convertir original y tamaños a WebP.
 *
 * @param array $metadata      Metadatos del adjunto.
 * @param int   $attachment_id ID del adjunto.
 * @return array
 */
function mst_convert_attachment_images_to_webp( $metadata, $attachment_id ) {
	if ( empty( $metadata['file'] ) || ! mst_webp_is_supported() ) {
		return $metadata;
	}

	$mime = get_post_mime_type( $attachment_id );
	if ( ! in_array( $mime, array( 'image/jpeg', 'image/png' ), true ) ) {
		return $metadata;
	}

	$upload_dir = wp_upload_dir();
	if ( ! empty( $upload_dir['error'] ) ) {
		return $metadata;
	}

	$base_dir = trailingslashit( $upload_dir['basedir'] );
	$rel_dir  = dirname( $metadata['file'] );
	$dir      = ( '.' === $rel_dir ) ? $base_dir : trailingslashit( $base_dir . $rel_dir );

	$main_path = $base_dir . $metadata['file'];
	$new_main  = mst_convert_file_to_webp( $main_path );

	if ( $new_main ) {
		wp_delete_file( $main_path );
		$metadata['file'] = ( '.' === $rel_dir ) ? basename( $new_main ) : trailingslashit( $rel_dir ) . basename( $new_main );
		update_attached_file( $attachment_id, $base_dir . $metadata['file'] );
	}

	if ( ! empty( $metadata['sizes'] ) && is_array( $metadata['sizes'] ) ) {
		foreach ( $metadata['sizes'] as $size => $data ) {
			if ( empty( $data['file'] ) ) {
				continue;
			}

			$size_path = $dir . $data['file'];
			$new_size  = mst_convert_file_to_webp( $size_path );

			if ( $new_size ) {
				wp_delete_file( $size_path );
				$metadata['sizes'][ $size ]['file']      = basename( $new_size );
				$metadata['sizes'][ $size ]['mime-type'] = 'image/webp';
			}
		}
	}

	wp_update_post(
		array(
			'ID'             => $attachment_id,
			'post_mime_type' => 'image/webp',
		)
	);

	return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'mst_convert_attachment_images_to_webp', 99, 2 );
