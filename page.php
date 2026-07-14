<?php
/**
 * Plantilla de página estática
 *
 * @package Minimal_SEO_Theme
 */

get_header();

mst_layout_open();
?>

<?php
while ( have_posts() ) :
	the_post();
	?>
	<?php mst_breadcrumbs(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-layout' ); ?>>
		<header class="entry-header">
			<h1<?php echo mst_placeholder_class_attr( get_the_title(), 'entry-title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php the_title(); ?></h1>
			<?php mst_the_entry_intro(); ?>
		</header>

		<?php if ( has_post_thumbnail() && mst_show_featured_image() ) : ?>
			<figure class="entry-featured">
				<?php
				the_post_thumbnail(
					'mst-hero',
					array(
						'loading'       => 'eager',
						'fetchpriority' => 'high',
						'decoding'      => 'async',
					)
				);
				?>
			</figure>
		<?php endif; ?>

		<div class="entry-content">
			<?php the_content(); ?>
		</div>

		<?php
		wp_link_pages(
			array(
				'before' => '<nav class="pagination" aria-label="' . esc_attr__( 'Páginas', 'minimal-seo-theme' ) . '">',
				'after'  => '</nav>',
			)
		);
		?>

		<?php mst_the_entry_cta(); ?>
	</article>
	<?php
endwhile;
?>

<?php
mst_layout_close();
get_footer();
