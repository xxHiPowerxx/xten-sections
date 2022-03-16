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
			if (! modalIds) {
				console.error('no modalIds');
				return;
			}
			for (var modalId in modalIds) {
				var selector = 'a[href="#' + modalIds[modalId] + '"]';
				$(selector).on('click', function(e){
					e.preventDefault();
					e.stopPropagation();
					var id = $(this).attr('href');
					$(id).modal('show');
				});
			}
		}
		readyFuncs.openModalOnClick = funcs.openModalOnClick;
		funcs.executeFuncs(readyFuncs);
	});
})(jQuery);