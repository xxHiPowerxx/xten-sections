(function ($) {
	jQuery(document).on('ready', function() {
		var funcs = {},
			readyFuncs = {};

		funcs.executeFuncs = function(obj) {
			for (var func in obj) {
				obj[func]();
			}
		}

		funcs.commandYTVideo = function(el,command) {
			console.log(el, 'el');
			console.log(command, 'command');
			el.contentWindow.postMessage('{"event":"command","func":"' + command + '","args":""}', '*');
			// console.log('YT Command = ' + command, el);
		}

		funcs.pauseBackgroundVideo = function($slide) {
			var provider = $slide.attr('data-video-provider');
			switch (provider) {
				case 'https://www.youtube.com/oembed':
					var $yTIFrame = $slide.find('.youtube-iframe').first();
					if ( ! $yTIFrame.is('.loaded') ) {
						// console.log('waiting on $yTIFrame to load');
						$yTIFrame.on('load', function(){
							// console.log('$yTIFrame is now loaded');
							console.log($yTIFrame[0], 'pauseVideo 1');
							funcs.commandYTVideo($yTIFrame[0], 'pauseVideo');
						});
					} else {
						console.log($yTIFrame[0], 'pauseVideo 2');
						funcs.commandYTVideo($yTIFrame[0], 'pauseVideo');
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

		funcs.pauseAllBackgroundVideos = function($slides) {
			$slides.each(function(){
				funcs.pauseBackgroundVideo($(this));
			});
		}

		funcs.playBackgroundVideo = function($slide) {
			var provider = $slide.attr('data-video-provider');
			switch (provider) {
				case 'https://www.youtube.com/oembed':
					var $slideIframe = $slide.find('.youtube-iframe');
					console.log($slideIframe[0], 'playVideo');
					funcs.commandYTVideo($slideIframe[0], 'playVideo');
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

		funcs.pauseYTVideoOnSlideChange = function() {
			var $sliders = $('.xten-component-hero[data-slick]');
			$sliders.on('beforeChange', function(event, slick, currentSlide, nextSlide){
				var $nextSlide = $(slick.$slides[nextSlide]),
					$slides = $(this).find('.slick-slide:not(.slick-cloned)');
					funcs.pauseAllBackgroundVideos($slides);
					funcs.playBackgroundVideo($nextSlide);
				// console.log('event', event);
				// console.log('slick', slick);
				// console.log('currentSlide', currentSlide);
				// console.log('nextSlide', nextSlide);
				// console.log('$nextSlide', $nextSlide);
			});
		}

		funcs.listenForEndOfVideo = function() {
			$videos = $('.xten-hero-slide-background-video');
			$videos.each(function(){
				$loopVideo = $(this).attr('loop-background-video');
				if ( ! $loopVideo ) {
					$(this).on('ended', function(e){
						var $parentSlide = $(this).closest('.xten-hero-slide');
						$parentSlide.addClass('background-video-ended');
					});
				}
			});
		}
		readyFuncs.listenForEndOfVideo = funcs.listenForEndOfVideo;

		funcs.pauseVideosOnSlickInit = function() {
			$(window).on('init', function(e){
				console.log('e', e);
				// Pause Any background videos that may be autoplaying.
				var $slider = $(e.target).filter('.xten-component-hero[data-slick]');
				$slider.each(function(){
					var $slides = $slider.find('.slick-slide'),
						$clones = $slides.filter('.slick-cloned'),
						$slidesSansClones = $slides.not($clones),
						$slidesBesidesCurrent = $slidesSansClones.not('.slick-current'),
						// $backgroundVideos = $slidesBesidesCurrent.find('.xten-hero-slide-background-video-wrapper'),
						$clonesWithSrc = $clones.find('[src]');

					// Remove Source from Clones so they do load iframes unnecessarily.
					$clonesWithSrc.removeAttr('src');
					funcs.pauseAllBackgroundVideos($slidesBesidesCurrent);
					funcs.pauseYTVideoOnSlideChange();
				});
			});
		}
		readyFuncs.pauseVideosOnSlickInit = funcs.pauseVideosOnSlickInit;

		funcs.executeFuncs(readyFuncs);
	});
})(jQuery);