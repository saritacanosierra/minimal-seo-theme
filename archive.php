<?php
/**
 * Plantilla de archivo — categorías, etiquetas, fechas, autores
 *
 * @package Minimal_SEO_Theme
 */

get_header();

mst_layout_open();

global $wp_query;
$archive_data = mst_get_archive_query_data();
$max_pages    = (int) $wp_query->max_num_pages;
?>

<header class="page-header">
	<?php
	the_archive_title( '<h1 class="page-header__title">', '</h1>' );

	$description = get_the_archive_description();
	if ( $description ) :
		?>
		<div class="page-header__desc"><?php echo wp_kses_post( $description ); ?></div>
	<?php endif; ?>
</header>

<?php if ( have_posts() ) : ?>

	<div class="mst-load-more-wrap">
		<div class="post-grid mst-load-more__grid">
			<?php
			while ( have_posts() ) :
				the_post();
				echo mst_get_post_card_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			endwhile;
			?>
		</div>
		<?php
		if ( ! empty( $archive_data ) ) {
			echo mst_load_more_button( 'archive', 1, $max_pages, $archive_data ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
	</div>

<?php else : ?>

	<p><?php esc_html_e( 'No hay contenido en este archivo.', 'minimal-seo-theme' ); ?></p>

<?php endif; ?>

<?php
mst_layout_close();
get_footer();
