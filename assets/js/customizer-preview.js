/**
 * Minimal SEO Theme — Customizer live preview
 */
(function (api) {
	'use strict';

	const fontStacks = typeof mstPreview !== 'undefined' ? mstPreview.fontStacks : {};

	const colorMap = {
		mst_color_text: '--mst-color-text',
		mst_color_muted: '--mst-color-muted',
		mst_color_bg: '--mst-color-bg',
		mst_color_accent: '--mst-color-accent',
		mst_color_accent_hover: '--mst-color-accent-hover',
	};

	const cssVarMap = {
		mst_font_size: '--mst-font-size-base',
		mst_line_height: '--mst-line-height',
		mst_max_width: '--mst-max-width',
		mst_content_width: '--mst-content-width',
	};

	function setVar(name, value) {
		document.documentElement.style.setProperty(name, value);
	}

	function bindSetting(settingId, callback) {
		api(settingId, function (setting) {
			setting.bind(callback);
		});
	}

	Object.keys(colorMap).forEach(function (settingId) {
		bindSetting(settingId, function (value) {
			setVar(colorMap[settingId], value);
		});
	});

	bindSetting('mst_font_stack', function (value) {
		const stack = fontStacks[value] || fontStacks.system;
		if (stack) {
			setVar('--mst-font', stack);
		}
	});

	bindSetting('mst_font_size', function (value) {
		setVar('--mst-font-size-base', parseInt(value, 10) + 'px');
	});

	bindSetting('mst_max_width', function (value) {
		const px = parseInt(value, 10) + 'px';
		setVar('--mst-max-width', px);
		setVar('--mst-section-width', px);
	});

	Object.keys(cssVarMap).forEach(function (settingId) {
		if (settingId === 'mst_font_size' || settingId === 'mst_max_width') {
			return;
		}
		bindSetting(settingId, function (value) {
			const varName = cssVarMap[settingId];
			const unit = settingId.indexOf('width') !== -1 ? 'px' : '';
			setVar(varName, value + unit);
		});
	});
})(wp.customize);
