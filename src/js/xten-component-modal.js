(function ($) {
	jQuery(document).on('ready', function() {

		var funcs = {},
			readyFuncs = {};
		funcs.executeFuncs = function(obj) {
			for (var func in obj) {
				obj[func]();
			}
		}

		funcs.openModalOnClick = function() {
			// modalIds is defined through wp_localize_script.
			// Bail early if no modalIds are found.
			if (window.modalIds === undefined && window.siteWideModalIds === undefined) {
				return;
			}

			function assignClickHandler(modalId) {
				if (! modalId ) {
					return;
				}
				var selector = 'a[href="#' + modalId + '"]';
				$(selector).on('click', function(e){
					e.preventDefault();
					e.stopPropagation();
					var id = $(this).attr('href'),
					options = {
						'backdrop': $(this).attr('data-backdrop'),
						'keyboard': $(this).attr('data-keyboard'),
					};
					$(id).modal(options);
				});
			}

			if ( window.modalIds ) {
				for (var modalId in window.modalIds) {
					assignClickHandler(window.modalIds[modalId]);
				}
			}
			if ( window.siteWideModalIds ) {
				for (var modalId in window.siteWideModalIds) {
					assignClickHandler(window.siteWideModalIds[modalId]);
				}
			}
		}

		readyFuncs.openModalOnClick = funcs.openModalOnClick;
		funcs.executeFuncs(readyFuncs);
	});
})(jQuery);