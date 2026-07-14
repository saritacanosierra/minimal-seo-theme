<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main-content"><?php esc_html_e( 'Saltar al contenido', 'minimal-seo-theme' ); ?></a>

<header class="site-header" role="banner">
	<div class="site-header__inner">
		<?php mst_site_branding(); ?>

		<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation" data-nav-toggle>
			<span class="nav-toggle__icon" aria-hidden="true"></span>
			<span class="nav-toggle__label"><?php esc_html_e( 'Menú', 'minimal-seo-theme' ); ?></span>
		</button>

		<nav id="primary-navigation" class="primary-nav" role="navigation" aria-label="<?php esc_attr_e( 'Navegación principal', 'minimal-seo-theme' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'primary-nav__list',
					'fallback_cb'    => 'mst_fallback_menu',
					'depth'          => 2,
				)
			);
			?>
		</nav>
	</div>
</header>

<main id="main-content" class="site-main" role="main">
