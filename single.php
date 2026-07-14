<?php
/**
 * Plantilla de entrada individual
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
			<p class="entry-meta">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				<?php if ( get_the_modified_date() !== get_the_date() ) : ?>
					&middot;
					<?php esc_html_e( 'Actualizado:', 'minimal-seo-theme' ); ?>
					<time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>"><?php echo esc_html( get_the_modified_date() ); ?></time>
				<?php endif; ?>
				&middot;
				<span><?php the_author(); ?></span>
			</p>
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
				'before' => '<nav class="pagination" aria-label="' . esc_attr__( 'Páginas del artículo', 'minimal-seo-theme' ) . '">',
				'after'  => '</nav>',
			)
		);
		?>

		<?php mst_the_entry_cta(); ?>

		<?php
		if ( mst_get_mod( 'mst_adsense_enabled' ) && ! empty( trim( mst_get_mod( 'mst_adsense_code' ) ) ) ) {
			mst_render_affiliate_zone();
		} else {
			mst_render_ad_placeholder();
		}
		?>
	</article>

	<?php mst_render_related_posts(); ?>
	<?php
endwhile;
?>

<?php
mst_layout_close();
get_footer();
