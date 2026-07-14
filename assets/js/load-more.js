/**
 * Minimal SEO Theme — Cargar más (fetch API)
 */
(function () {
	'use strict';

	if (typeof mstLoadMore === 'undefined') {
		return;
	}

	const { endpoint, nonce } = mstLoadMore;

	function getWrap(button) {
		return button.closest('.mst-load-more-wrap');
	}

	function getGrid(wrap) {
		return wrap.querySelector('.mst-load-more__grid');
	}

	async function loadMore(button) {
		const wrap = getWrap(button);
		const grid = getWrap(button) ? getGrid(wrap) : null;

		if (!grid || button.disabled) {
			return;
		}

		const nextPage = parseInt(button.dataset.page, 10) + 1;
		const params = new URLSearchParams({
			type: button.dataset.type,
			page: String(nextPage),
			query: button.dataset.query || '{}',
			nonce: nonce,
		});

		button.disabled = true;
		button.classList.add('is-loading');
		const originalText = button.textContent;
		button.textContent = button.dataset.loadingText || '…';

		try {
			const response = await fetch(`${endpoint}?${params.toString()}`, {
				method: 'GET',
				credentials: 'same-origin',
				headers: { Accept: 'application/json' },
			});

			if (!response.ok) {
				throw new Error('Request failed');
			}

			const data = await response.json();

			if (data.html) {
				grid.insertAdjacentHTML('beforeend', data.html);
			}

			button.dataset.page = String(data.page);

			if (!data.hasMore) {
				button.remove();
			}
		} catch {
			button.textContent = originalText;
		} finally {
			if (button.isConnected) {
				button.disabled = false;
				button.classList.remove('is-loading');
				if (button.textContent === '…') {
					button.textContent = originalText;
				}
			}
		}
	}

	document.addEventListener('click', (event) => {
		const button = event.target.closest('.mst-load-more');
		if (button) {
			event.preventDefault();
			loadMore(button);
		}
	});
})();
