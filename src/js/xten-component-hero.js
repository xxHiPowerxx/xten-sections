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
				var $component = $(this),
					minHeight = $component.attr('data-minimum-height'),
					viewPortHeight = window.innerHeight,
					headerHeight = window.siteHeaderHeight || $('.site-header')[0].getBoundingClientRect().height,
					headerHeight = parseFloat(headerHeight),
					adminBar = document.getElementById('wpadminbar'),
					adminBarHeight = adminBar ?
						adminBar.getBoundingClientRect().height :
						0,
					componentComputedStyle = getComputedStyle($component[0]),
					componentPaddings = parseFloat(componentComputedStyle.paddingTop) + parseFloat(componentComputedStyle.paddingBottom),
					spaceAvailable = viewPortHeight - headerHeight - adminBarHeight - componentPaddings,
					minHeightNum = parseFloat(minHeight),
					minHeightPercent = minHeightNum / 100,
					calculatedHeight = spaceAvailable * minHeightPercent,
					$slides = $component.find('.xten-hero-slide:not(.slick-cloned)'),
					$inners = $slides.find('.sizeHeroInner'),
					tallestInnerHeight = 0,
					componentOffsetTop = $component.offset().top;
				// Find Tallest Inner Height.
				$inners.each(function(){
					// Inner Height can be offset from actual component which would Padding
					// between top of component and Inner.
					var thisOuterHeight = $(this).outerHeight(true),
						thisOffsetTop = $(this).offset().top,
						// Calculate the Padding Found.
						paddingTop = thisOffsetTop - componentOffsetTop,
						thisHeightFromTop = paddingTop + thisOuterHeight;
					if ( thisHeightFromTop > tallestInnerHeight ) {
						tallestInnerHeight = thisHeightFromTop;
					}
				});
				// If tallest Inner Height found is be taller than sizeHero,
				// Remove Height and Class.
				if ( tallestInnerHeight > calculatedHeight ) {
					$component.css({
						'height': ''
					}).removeClass('heroSized');
				} else {
					$component.css({
						'height': calculatedHeight + 'px'
					}).addClass('heroSized');
				}

				finishWork(this);
			});
		}
		// For resizing, there is a split second needed
		//to adjust for repaint, and recalculation of elements.
		function doSizeHero() {
			setTimeout(function(){
				sizeHero();
			}, 100);
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
		function setLoadedIframeClass() {
			$('iframe').on('load', function(){
				$(this).addClass('loaded');
			});
		}
		function commandYTVideo(el,command) {
			el.contentWindow.postMessage('{"event":"command","func":"' + command + '","args":""}', '*');
			// console.log('YT Command = ' + command, el);
		}
		function pauseBackgroundVideo($slide) {
			var provider = $slide.attr('data-video-provider');
			switch (provider) {
				case 'https://www.youtube.com/oembed':
					var $yTIFrame = $slide.find('.youtube-iframe').first();
					if ( ! $yTIFrame.is('.loaded') ) {
						// console.log('waiting on $yTIFrame to load');
						$yTIFrame.on('load', function(){
							// console.log('$yTIFrame is now loaded');
							commandYTVideo($yTIFrame[0], 'pauseVideo');
						});
					} else {
						commandYTVideo($yTIFrame[0], 'pauseVideo');
					}
				break;
				case 'internal-video':
					var $internalVideo = $slide.find('.xten-hero-slide-background-video');
					if ($internalVideo.length) {
						$internalVideo[0].pause();
					}
					// console.log('internal-video Paused');
				break;
			}
		}
		function pauseAllBackgroundVideos($slides) {
			$slides.each(function(){
				pauseBackgroundVideo($(this));
			});
		}
		function playBackgroundVideo($slide) {
			var provider = $slide.attr('data-video-provider');
			switch (provider) {
				case 'https://www.youtube.com/oembed':
					var $slideIframe = $slide.find('.youtube-iframe');
					commandYTVideo($slideIframe[0], 'playVideo');
				break;
				case 'internal-video':
					var $internalVideo = $slide.find('.xten-hero-slide-background-video');
					if ($internalVideo.length) {
						$internalVideo[0].play();
					}
					// console.log('internal-video Played');
				break;
			}
		}
		function pauseYTVideoOnSlideChange() {
			var $sliders = $('.xten-component-hero[data-slick]');
			$sliders.on('beforeChange', function(event, slick, currentSlide, nextSlide){
				var $nextSlide = $(slick.$slides[nextSlide]),
					$slides = $(this).find('.slick-slide:not(.slick-cloned)');
					pauseAllBackgroundVideos($slides);
					playBackgroundVideo($nextSlide);
				// console.log('event', event);
				// console.log('slick', slick);
				// console.log('currentSlide', currentSlide);
				// console.log('nextSlide', nextSlide);
				// console.log('$nextSlide', $nextSlide);
			});
		}
		function initSlick() {
			var $sliders = $('.xten-component-hero[data-slick]:not(.slick-initialized)');
			$sliders.each(function(){
				if (typeof $(this).slick === 'function') {
					$(this).slick();

					// Pause Any background videos that may be autoplaying.
					var $slides = $(this).find('.slick-slide'),
						$clones = $slides.filter('.slick-cloned'),
						$slidesSansClones = $slides.not($clones),
						$slidesBesidesCurrent = $slidesSansClones.not('.slick-current'),
						// $backgroundVideos = $slidesBesidesCurrent.find('.xten-hero-slide-background-video-wrapper'),
						$clonesWithSrc = $clones.find('[src]');

					// Remove Source from Clones so they do load iframes unnecessarily.
					$clonesWithSrc.removeAttr('src');
					pauseAllBackgroundVideos($slidesBesidesCurrent);
					pauseYTVideoOnSlideChange();
				}
			});
		}

		function readyFuncs() {
			sizeHero();
			listenForImageLoad();
			setLoadedIframeClass();
			initSlick();
		}
		readyFuncs();
		function resizeFuncs() {
			doSizeHero();
		}
		$(window).on('resize', function (){
			resizeFuncs();
		});
		$(this).on('wpcf7submit wpcf7invalid wpcf7mailsent', doSizeHero);
	});
})(jQuery);
