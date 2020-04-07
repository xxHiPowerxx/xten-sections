(function ($) {
	$(document).on("ready", function () {
		function sizeHero() {
			$('.sizeHero').each(function () {
				var section = $(this).closest('.xten-section'),
					minHeight = section.attr('data-minimum-height'),
					viewPortHeight = window.innerHeight,
					headerHeight = window.siteHeaderHeight || $('.site-header')[0].getBoundingClientRect().height,
					headerHeight = parseFloat(headerHeight),
					adminBar = document.getElementById('wpadminbar'),
					adminBarHeight = adminBar ?
						adminBar.getBoundingClientRect().height :
						0,
					spaceAvailable = viewPortHeight - headerHeight - adminBarHeight,
					minHeightNum = parseFloat(minHeight),
					minHeightPercent = minHeightNum / 100,
					calculatedHeight = spaceAvailable * minHeightPercent;
				$(this).css('min-height', calculatedHeight + 'px');
			});
		}
		function readyFuncs() {
			sizeHero();
		}
		readyFuncs();
		function resizeFuncs() {
			sizeHero();
		}
		$(window).on('resize', function () {
			resizeFuncs();
		});
	});
})(jQuery);
