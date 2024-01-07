(function ($) {
	jQuery(document).on('ready', function() {

		var funcs = {},
			readyFuncs = {};
		funcs.executeFuncs = function(obj) {
			for (var func in obj) {
				obj[func]();
			}
		}
		function debug(data) {
			var label = toString(data)
			if (typeof data === 'object' &&  data.type !== undefined) {
				label = data.type;
			}
			return console.log(label, data);
		}

		funcs.openModalWithHash = function() {
			if (window.modalIds === undefined && window.siteWideModalIds === undefined) {
				return;
			}
			// Fire on Load if location hash is found
			// AND that element is a .modal.
			if ( $(location.hash).is('.modal') ) {
				$(location.hash).modal();
			}
		}
		readyFuncs.openModalWithHash = funcs.openModalWithHash;

		funcs.openModalOnHashChange = function() {
			$(window).on('hashchange', function(e){
				debug(e);
				funcs.openModalWithHash();
			});
		}
		readyFuncs.openModalOnHashChange = funcs.openModalOnHashChange;


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
					e.stopImmediatePropagation();
					var id = $(this).attr('href'),
					// TODO: Add options to configure modal/popup ACF Field in addition to OR instead of this anchor tag.
						optionBackDrop = $(this).attr('data-backdrop') || true,
						optionKeyBoard = $(this).attr('data-keyboard') || true,
						options = {
							'backdrop': optionBackDrop,
							'keyboard': optionKeyBoard,
						};
					// push hash to location.
					history.pushState({}, '', id);
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

		funcs.removeHashOnModalHide = function() {
			$(window).on('hidden.bs.modal', function(e){
				// debug(e);
				// debug(this.location.hash);
				var hash = this.location.hash,
					$modal = $(e.target),
					removeHash = function() { 
						history.pushState(
							'',
							document.title,
							window.location.pathname + window.location.search
						)
					};
				debug($modal.attr('id'));
				debug(hash);
				if ('#' + $modal.attr('id') === hash) {
					removeHash();
				}
			});
		}
		readyFuncs.removeHashOnModalHide = funcs.removeHashOnModalHide;

		funcs.executeFuncs(readyFuncs);
	});
})(jQuery);