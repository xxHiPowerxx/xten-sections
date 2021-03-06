(function ($) {
	$(document).on("ready", function () {
		var $body = $('body');
		function startWork(elem) {
			window.workStarted = window.workStarted || {};
			window.workStarted[elem] = true;
		}
		function finishWork(elem) {
			window.dispatchEvent(new CustomEvent('finishWork'));
			delete window.workStarted[elem];
		}
		function sizeHero() {
			$('.sizeHero').each(function () {
				startWork(this);
				var component = $(this).closest('.xten-component'),
					minHeight = component.attr('data-minimum-height'),
					viewPortHeight = window.innerHeight,
					headerHeight = window.siteHeaderHeight || $('.site-header')[0].getBoundingClientRect().height,
					headerHeight = parseFloat(headerHeight),
					adminBar = document.getElementById('wpadminbar'),
					adminBarHeight = adminBar ?
						adminBar.getBoundingClientRect().height :
						0,
					componentComputedStyle = getComputedStyle(component[0]),
					componentPaddings = parseFloat(componentComputedStyle.paddingTop) + parseFloat(componentComputedStyle.paddingBottom),
					spaceAvailable = viewPortHeight - headerHeight - adminBarHeight - componentPaddings,
					minHeightNum = parseFloat(minHeight),
					minHeightPercent = minHeightNum / 100,
					calculatedHeight = spaceAvailable * minHeightPercent;
				$(this).css('min-height', calculatedHeight + 'px');
				// Depenendancy: Xten Theme
				if ($body.is('.browser-ie')) {
					$(this).css('height', calculatedHeight + 'px');
				}
				finishWork(this);
			});
		}
		function isInViewport(elem) {
			if (elem instanceof jQuery) {
				elem = elem[0];
			}
			var bounding = elem.getBoundingClientRect();
			return (
				bounding.top >= 0 &&
				bounding.left >= 0 &&
				bounding.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
				bounding.right <= (window.innerWidth || document.documentElement.clientWidth)
			);
		};
		// Polyfill for startsWith.
		if (!String.prototype.startsWith) {
			String.prototype.startsWith = function (searchString, position) {
				position = position || 0;
				return this.indexOf(searchString, position) === position;
			};
		}
		function listenForImageLoad() {
			$('.xten-component-hero').each(function () {
				if (isInViewport(this)) {
					var backgroundImage = getComputedStyle(this).backgroundImage;
					if (backgroundImage.startsWith('url')) {
						startWork(this);
						var src = backgroundImage.replace('url(', ''),
							src = src.replace('"', ''),
							src = src.replace(')', ''),
							src = src.replace('"', ''),
							that = this;
						$('<img/>').attr('src', src).on('load', function () {
							$(this).remove();
							finishWork(that);
						});
					} // endif (backgroundImage.startsWith('url'))
				}// endif ( isInViewport(this) )
			});
		}
		function readyFuncs() {
			sizeHero();
			listenForImageLoad();
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
