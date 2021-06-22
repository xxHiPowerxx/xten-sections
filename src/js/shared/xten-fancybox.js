// TODO: Move this file into a shared folder with it's own repository to keep synced.
// EG: Current: js > shared > xten-fancybox.js. Proposed: shared > js > xten-fancybox.js
(function ($) {
	$(document).on('ready', function () {
		function initFancyBox() {
			$('.fancybox').each(function(){
				var $tar,
				$img = $(this).is('img') ?
					$(this) :
					$(this).find('img').first(),
				args = {
					src: $img.attr('src'),
				};
				if ($(this).is('.is-image-fill')) {
					$tar = $(this).find('.wp-block-media-text__media').first();
				} else {
					$tar = $(this);
				}
				$tar.fancybox(args).addClass('fancybox-target');
			});
		}
		function readyFuncs() {
			initFancyBox();
		}
		readyFuncs();
	});
})(jQuery);