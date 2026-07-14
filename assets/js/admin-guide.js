/**
 * Aviso de bienvenida — descartar y guardar preferencia.
 */
(function ($) {
	$(document).on('click', '.mst-admin-notice.is-dismissible .notice-dismiss', function () {
		var $notice = $(this).closest('.mst-admin-notice');
		if (!$notice.length || typeof mstAdminGuide === 'undefined') {
			return;
		}

		$.post(mstAdminGuide.ajaxUrl, {
			action: 'mst_dismiss_admin_notice',
			nonce: mstAdminGuide.nonce
		});
	});
})(jQuery);
