(function ($) {
	jQuery(document).on('ready', function() {
		var funcs = {},
			readyFuncs = {},
			resizeFuncs = {},
			$body = $('body'),
			isMobileChecked = false;

		funcs.executeFuncs = function(obj) {
			for (var func in obj) {
				obj[func]();
			}
		}

		funcs.pauseBackgroundVideo = function($slide) {
			var provider = $slide.attr('data-video-provider');
			switch (provider) {
				case 'https://www.youtube.com/oembed':
					var $yTIFrame = $slide.find('.youtube-iframe').first();
					funcs.stopYTVideo($yTIFrame[0]);
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

		funcs.playYTVideo = function(player) {
			// console.log('player', player);
			// console.log('typeof player', typeof player);
			
			// if player is not a yt player yet, wait until it is.
			if (typeof player.mute !== 'function') {
			// 	var $parentSlide = $(player).closest('.xten-hero-slide');
			// 	$parentSlide.on('ytvideoready', function(e, ytPlayer){
			// 		console.log(e, 'e');
			// 		console.log(ytPlayer, 'ytPlayer');
			// 		ytPlayer.mute().playVideo();
			// 	});
			// 	return player;
			// } else {
				// Video must be muted to autoplay.
				// window.YT.ready(function(e) {
				// window.onPlayerReady = function(e) {
				// 	console.log('e.target inside playYTVideo', e.target);
				// 	console.log('e.target.videoTitle inside playYTVideo', e.target.videoTitle);
				// 	alert();
				// 	console.log('e.target inside playYTVideo', e.target);
				// 	return player.mute().playVideo();
				// }
				player.addEventListener('onReady', function(e) {
					// console.log('e.target inside playYTVideo', e.target);
					// console.log('e.target.videoTitle inside playYTVideo', e.target.videoTitle);
					// console.log('e.target inside playYTVideo', e.target);
					// console.log('e.target.mute inside playYTVideo', e.target.mute);
					// console.log('e.target.playVideo inside playYTVideo', e.target.playVideo);
					return e.target.mute().playVideo();
				});
			} else {
				return player.mute().playVideo();
			}
		}
		funcs.stopYTVideo = function(yTIframe) {
			function coreFunc(){
				var player = YT.get(yTIframe.id);
				if (typeof player.pauseVideo !== 'function') {
					var $parentSlide = $(yTIframe).closest('.xten-hero-slide');
					$parentSlide.on('ytvideoready', function(e, ytPlayer){
						ytPlayer.pauseVideo();
					});
				}else {
					player.pauseVideo();
				}
			}
			if ( typeof YT.get !== 'function' ) {
				window.YT.ready(function(e) {
					coreFunc();
				});
			} else {
				coreFunc();
			}
		}

		funcs.configYTPlayers = function() {
			var $slide = $('.xten-hero-slide:not(.slick-cloned)');
			// console.log('$slide', $slide);
			$slide.each(function(){
				var loopBackGroundVideo = $(this).attr('data-loop-background-video'),
					videoId = $(this).attr('data-background-video-id'),
					$slideIframe = $(this).find('.youtube-iframe');
				var player;
				function coreFunc(ytPlayer) {
					player = new YT.Player(ytPlayer, {
						videoId: videoId,
						controls: 0,
						kb: 0,
						autoplay: 1,
						loop: loopBackGroundVideo,
						playlist: videoId,
						// playerVars: {
							// 'playsinline': 1
						// },
						events: {
							'onReady': function(event) {
								var iframe = event.target.getIframe(),
									$parentSlide = $(iframe).closest('.xten-hero-slide');
								// console.log('$parentSlide', $parentSlide);
								$parentSlide.trigger('ytvideoready', event.target);
							},
							'onStateChange': function(event) {
								var $player = $(event.target.getIframe()),
									playerState = $player.attr('data-player-state');

								function getKeyByValue(obj, value) {
									return Object.keys(obj)[Object.values(obj).indexOf(value)];
								}

								switch (event.data) {
									case YT.PlayerState.ENDED :
										if ( loopBackGroundVideo == 1 ) {
											funcs.playYTVideo(event.target);
										}
									break;
								}
								// console.log('YT.PlayerState', YT.PlayerState);
								// console.log('event', event);
								// console.log('event.data', event.data);
								playerState = String(getKeyByValue(YT.PlayerState, event.data)).toLowerCase();
								$player.attr('data-player-state', playerState);
								
							},
						}
					});
				}
				$slideIframe.each(function(){
					if (typeof YT.Player === 'function') {
						coreFunc(this);
					} else {
						var that = this;
						window.YT.ready(function(e) {
							coreFunc(that);
						});
					}
				});
			});
		}
		readyFuncs.configYTPlayers = funcs.configYTPlayers;

		funcs.playBackgroundVideo = function($slide) {
			var provider = $slide.attr('data-video-provider');
			// console.log('provider', provider);
			switch (provider) {
				case 'https://www.youtube.com/oembed':
					var $slideIframe = $slide.find('.youtube-iframe').first(),
					slideIframeYTPlayer = YT.get($slideIframe[0].id);
					// console.log($slideIframe[0], 'playVideo');
					// funcs.commandYTVideo($slideIframe[0], 'playVideo');
					// funcs.configYTPlayer($slide);
					funcs.playYTVideo(slideIframeYTPlayer);
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

		funcs.handleVideosOnSlickInit = function($slider) {
			// $(window).on('init', function(e){
				// Pause Any background videos that may be autoplaying.
				$slider.on('init', function(){
					var $slides = $(this).find('.slick-slide'),
						$clones = $slides.filter('.slick-cloned'),
						$slidesSansClones = $slides.not($clones),
						$currentSlide = $slidesSansClones.filter('.slick-current'),
						$slidesBesidesCurrent = $slidesSansClones.not('.slick-current'),
						// $backgroundVideos = $slidesBesidesCurrent.find('.xten-hero-slide-background-video-wrapper'),
						$clonesWithSrc = $clones.find('[src]');

					// Remove Source from Clones so they do load iframes unnecessarily.
					$clonesWithSrc.removeAttr('src');
					funcs.pauseAllBackgroundVideos($slidesBesidesCurrent);
					funcs.pauseYTVideoOnSlideChange();
					$currentSlide.each(function(){
						// funcs.configYTPlayer($(this));
						var $that = $(this);
						window.YT.ready(function(e) {
							var $yTIFrame = $that.find('.youtube-iframe').first(),
								YTPlayer = YT.get($yTIFrame[0].id);
							// funcs.playYTVideo(YTPlayer);
						});
						// var $slideIframe = $(this).find('.youtube-iframe');
						// funcs.playYTVideo($slideIframe[0]);
					});
				});
			// });
		}

		funcs.isMobile = function() {
			var isMobile = false; //initiate as false
			// device detection
			if(
				/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) ||
				/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))
			) { 
				isMobile = true;
			}
			isMobileChecked = true;
			return isMobile;
		}

		funcs.addClassToBodyIfIsMoble = function() {
			if ( funcs.isMobile === true ) {
				$body.addClass('is-mobile is-mobile-js');
			}
		}
		readyFuncs.addClassToBodyIfIsMoble = funcs.addClassToBodyIfIsMoble;

		funcs.playVideosOnReady = function() {
			var isMobile = $body.is('.is-mobile') ? $body.is('.is-mobile') : funcs.isMobile();
			if ( isMobile === false ) {
				var $components = $('.xten-component-hero');
				$components.each(function(){
					if ( $(this).is('[data-slick]') ) {
						funcs.handleVideosOnSlickInit($(this));
					} else {
						var $that = $(this);
						window.YT.ready(function(e) {
							var $yTIFrame = $that.find('.youtube-iframe').first(),
								YTPlayer = YT.get($yTIFrame[0].id);
							// console.log('YTPlayer', YTPlayer);
							// console.log('$yTIFrame', $yTIFrame);
							// console.log('$yTIFrame[0].id', $yTIFrame[0].id);
							// console.log('e inside playVideosOnReady', e);
							funcs.playYTVideo(YTPlayer);
						});
					}
				});
			}
		}
		readyFuncs.playVideosOnReady = funcs.playVideosOnReady;

		funcs.preventTabToYTIframe = function(){
			var $parentSlides = $('.xten-hero-slide:not(.slick-clone)');
			$parentSlides.on('ytvideoready', function(e, ytPlayer){
				var yTIFrame = ytPlayer.getIframe();
				$(yTIFrame).attr('tabindex', '-1');
			});
		}
		readyFuncs.preventTabToYTIframe = funcs.preventTabToYTIframe;

		funcs.stretchIframeWidthToFillSlideHeight = function($iFrame){
			var $slide = $iFrame.closest('.xten-hero-slide');
			// console.log($slide);
			if ( ! $slide.length ) {
				return false;
			}
			// get extra height which is added in min-height to keep youtube watermark out of view.
			var minHeight = $iFrame.css('min-height'),
				numbersInMinHeightString = minHeight.match(/\d+/g),
				extraHeight = parseInt( numbersInMinHeightString[numbersInMinHeightString.length - 1] ),
				slideBoundingClientRect = $slide.length ?
					$slide[0].getBoundingClientRect() :
					{
						width: 0,
						height: 0
					},
				slideHeight = slideBoundingClientRect.height,
				slideHeightWithExtraHeight = slideHeight + extraHeight,
				slideWidth = slideBoundingClientRect.width,
				widthRatio = 16,
				heightRatio = 9,
				percentage = widthRatio / heightRatio,
				calcWidth = percentage * slideHeightWithExtraHeight,
				width = calcWidth > slideWidth ?
						calcWidth :
					'';
			// console.log('calcWidth', calcWidth);
			// console.log('slideWidth', slideWidth);
			$iFrame.width(width);
		}
		// readyFuncs.stretchIframeWidthToFillSlideHeight = funcs.stretchIframeWidthToFillSlideHeight;

		funcs.bindStretchIframeWidthToFillSlideHeight = function(){
			function coreFunc() {
				var $iFrames = $('.xten-hero-slide-background-video-wrapper[data-video-type="external_video_type"] iframe');
				// console.log('$iFrames', $iFrames);

				$iFrames.each(function(){
					var $iFrame = $(this);
					funcs.stretchIframeWidthToFillSlideHeight($(this));
					// Listen for resize and fire stretchIframeWidthToFillSlideHeight();
					var $slider = $(this).closest('.slick-slider');
					// console.log('$slider', $slider);
					// console.log('$slider[0].slick.instanceUid', $slider[0].slick.instanceUid);
					// console.log( $slider.length && $slider[0].slick !== undefined);
					if ( $slider.length && $slider[0].slick !== undefined ) {
						
						$(window).on('resize.slick.slick-' + $slider[0].slick.instanceUid, function(){
							funcs.stretchIframeWidthToFillSlideHeight($iFrame);
						});
					} else {
						$(window).on('resize', function(){
							funcs.stretchIframeWidthToFillSlideHeight($iFrame);
						});
					}
				});
			}
			if ( window.YT.loaded !== 1 ) {
				window.YT.ready(function(){
					coreFunc();
				});
			} else {
				coreFunc();
			}
		}
		readyFuncs.bindStretchIframeWidthToFillSlideHeight = funcs.bindStretchIframeWidthToFillSlideHeight;
		// resizeFuncs.stretchIframeWidthToFillSlideHeight = funcs.stretchIframeWidthToFillSlideHeight;

		funcs.executeFuncs(readyFuncs);

		// $(window).on('resize', function(){
		// 	funcs.executeFuncs(resizeFuncs)
		// });
	});
})(jQuery);