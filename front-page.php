<?php

/**

 * Plantilla de página de inicio

 *

 * En modo blog (últimas entradas) delega en index.php.

 * En modo estático muestra la página asignada + constructor de inicio.

 *

 * @package Minimal_SEO_Theme

 */



if ( 'posts' === get_option( 'show_on_front' ) ) {

	locate_template( 'index.php', true );

	return;

}



get_header();

?>



<?php mst_render_home_top_sections(); ?>



<?php

while ( have_posts() ) :

	the_post();

	?>

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



		<?php mst_the_entry_cta(); ?>

	</article>

	<?php

endwhile;

?>



<?php

if ( mst_get_home_mod( 'mst_home_posts_enable' ) && mst_get_home_mod( 'mst_home_posts_title' ) ) {

	mst_render_home_posts_heading();

}

?>



<?php

if ( mst_get_home_mod( 'mst_home_posts_enable' ) ) {

	$query = new WP_Query(

		array(

			'post_type'      => 'post',

			'posts_per_page' => get_option( 'posts_per_page' ),

			'no_found_rows'  => true,

		)

	);

	if ( $query->have_posts() ) {

		echo '<div class="post-grid">';

		while ( $query->have_posts() ) {

			$query->the_post();

			echo mst_get_post_card_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		}

		echo '</div>';

		wp_reset_postdata();

	}

}

?>



<?php mst_render_home_bottom_sections(); ?>



<?php

get_footer();


