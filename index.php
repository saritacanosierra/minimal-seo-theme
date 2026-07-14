<?php
/**
 * Plantilla principal — listado de entradas / portada con posts
 *
 * @package Minimal_SEO_Theme
 */

get_header();

$is_blog_home = is_front_page() && is_home();
?>

<?php if ( $is_blog_home ) : ?>
	<?php mst_render_home_top_sections(); ?>
	<?php mst_render_home_posts_heading(); ?>
<?php else : ?>
	<header class="page-header">
		<?php if ( is_home() && ! is_front_page() ) : ?>
			<h1 class="page-header__title"><?php esc_html_e( 'Últimas publicaciones', 'minimal-seo-theme' ); ?></h1>
		<?php else : ?>
			<p class="page-header__title"><?php esc_html_e( 'Últimas publicaciones', 'minimal-seo-theme' ); ?></p>
		<?php endif; ?>
	</header>
<?php endif; ?>

<?php if ( have_posts() ) : ?>

	<div class="post-grid">
		<?php
		while ( have_posts() ) :
			the_post();
			echo mst_get_post_card_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		endwhile;
		?>
	</div>

	<nav class="pagination" aria-label="<?php esc_attr_e( 'Paginación', 'minimal-seo-theme' ); ?>">
		<?php
		echo paginate_links(
			array(
				'prev_text' => '&laquo; ' . esc_html__( 'Anterior', 'minimal-seo-theme' ),
				'next_text' => esc_html__( 'Siguiente', 'minimal-seo-theme' ) . ' &raquo;',
				'type'      => 'plain',
			)
		);
		?>
	</nav>

<?php else : ?>

	<p><?php esc_html_e( 'No se encontraron publicaciones.', 'minimal-seo-theme' ); ?></p>

<?php endif; ?>

<?php if ( $is_blog_home ) : ?>
	<?php mst_render_home_bottom_sections(); ?>
<?php endif; ?>

<?php
get_footer();
