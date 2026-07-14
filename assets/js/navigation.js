/**
 * Minimal SEO Theme — Navigation Module
 * Menú hamburguesa (desktop/tablet) + Menú Órbita radial (móvil)
 *
 * @package Minimal_SEO_Theme
 */

(function () {
	'use strict';

	const MOBILE_BREAKPOINT = 768;

	/**
	 * Toggle del menú principal en móvil.
	 */
	function initPrimaryNav() {
		const toggle = document.querySelector('[data-nav-toggle]');
		const nav = document.getElementById('primary-navigation');

		if (!toggle || !nav) {
			return;
		}

		toggle.addEventListener('click', () => {
			const isOpen = nav.classList.toggle('is-open');
			toggle.setAttribute('aria-expanded', String(isOpen));
		});

		document.addEventListener('click', (event) => {
			if (window.innerWidth >= MOBILE_BREAKPOINT) {
				return;
			}
			if (!nav.contains(event.target) && !toggle.contains(event.target)) {
				nav.classList.remove('is-open');
				toggle.setAttribute('aria-expanded', 'false');
			}
		});

		window.addEventListener('resize', () => {
			if (window.innerWidth >= MOBILE_BREAKPOINT) {
				nav.classList.remove('is-open');
				toggle.setAttribute('aria-expanded', 'false');
			}
		});
	}

	/**
	 * Menú Órbita — despliegue radial semicircular.
	 */
	function initOrbitalMenu() {
		const menu = document.querySelector('[data-orbital-menu]');
		const toggle = document.querySelector('[data-orbital-toggle]');

		if (!menu || !toggle) {
			return;
		}

		const closeMenu = () => {
			menu.classList.remove('is-open');
			toggle.setAttribute('aria-expanded', 'false');
		};

		const openMenu = () => {
			menu.classList.add('is-open');
			toggle.setAttribute('aria-expanded', 'true');
		};

		toggle.addEventListener('click', (event) => {
			event.stopPropagation();
			const isOpen = menu.classList.contains('is-open');
			if (isOpen) {
				closeMenu();
			} else {
				openMenu();
			}
		});

		document.addEventListener('click', (event) => {
			if (!menu.contains(event.target)) {
				closeMenu();
			}
		});

		document.addEventListener('keydown', (event) => {
			if (event.key === 'Escape') {
				closeMenu();
			}
		});

		window.addEventListener('resize', () => {
			if (window.innerWidth >= MOBILE_BREAKPOINT) {
				closeMenu();
			}
		});
	}

	/**
	 * Enlaces ofuscados — decodificación Base64 al clic.
	 */
	function initObfuscatedLinks() {
		const decodeUrl = (encoded) => {
			try {
				return atob(encoded);
			} catch {
				return '';
			}
		};

		const navigate = (encoded) => {
			const url = decodeUrl(encoded);
			if (url) {
				window.location.href = url;
			}
		};

		document.addEventListener('click', (event) => {
			const link = event.target.closest('.obfuscated-link');
			if (!link) {
				return;
			}
			event.preventDefault();
			navigate(link.dataset.link);
		});

		document.addEventListener('keydown', (event) => {
			const link = event.target.closest('.obfuscated-link');
			if (!link || (event.key !== 'Enter' && event.key !== ' ')) {
				return;
			}
			event.preventDefault();
			navigate(link.dataset.link);
		});
	}

	/**
	 * Tabla de contenidos — colapsar / desplegar.
	 */
	function initToc() {
		document.addEventListener('click', (event) => {
			const toggle = event.target.closest('.mst-toc__toggle');
			if (!toggle) {
				return;
			}
			const toc = toggle.closest('.mst-toc');
			if (!toc) {
				return;
			}
			const isCollapsed = toc.classList.toggle('mst-toc--collapsed');
			toggle.setAttribute('aria-expanded', String(!isCollapsed));
		});
	}

	/**
	 * Inicialización cuando el DOM está listo.
	 */
	function init() {
		initPrimaryNav();
		initOrbitalMenu();
		initObfuscatedLinks();
		initToc();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
