<?php
/**
 * Entradas relacionadas — Minimal SEO Theme
 *
 * @package Minimal_SEO_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Consulta de entradas relacionadas por categoría.
 */
function mst_get_related_posts_query( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( ! $post_id ) {
		return null;
	}

	if ( function_exists( 'mst_get_arch_related_query' ) ) {
		$arch_query = mst_get_arch_related_query( $post_id );
		if ( $arch_query && $arch_query->have_posts() ) {
			return $arch_query;
		}
	}

	$categories = wp_get_post_categories( $post_id );
	if ( empty( $categories ) ) {
		return null;
	}

	return new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => max( 1, min( 12, absint( mst_get_mod( 'mst_related_count' ) ) ) ),
			'post__not_in'        => array( $post_id ),
			'category__in'        => $categories,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'orderby'             => 'rand',
		)
	);
}

/**
 * Mostrar grid de entradas relacionadas.
 */
function mst_render_related_posts() {
	if ( ! is_singular( 'post' ) || ! mst_get_mod( 'mst_related_enable' ) ) {
		return;
	}

	$query = mst_get_related_posts_query();
	if ( ! $query || ! $query->have_posts() ) {
		return;
	}

	$title = mst_get_mod( 'mst_related_title' );
	?>
	<section class="mst-section mst-related" aria-label="<?php esc_attr_e( 'Artículos relacionados', 'minimal-seo-theme' ); ?>">
		<?php if ( $title ) : ?>
			<h2 class="mst-section__title"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>
		<div class="post-grid post-grid--related">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				echo mst_get_post_card_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</section>
	<?php
}
