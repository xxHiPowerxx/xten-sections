(function ($) {
	$(document).on("ready", function () {
		var $document = $(this),
		$sliders = $('.xten-component-image-gallery .slickSlider'),
		$mainSliders = $sliders.filter('.main-slider');

		function fancyBoxIgnoreSlickClones () {
			// Skip cloned elements
			$('.main-slider').each(function(){
				var cId = $(this).closest('.xten-component-image-gallery').attr('data-c-id'),
					selector = '[data-c-id="' + cId + '"] .main-slider .slick-slide:not(.slick-cloned)';
				$().fancybox({
					selector : selector,
					backFocus : false
				});
			});

			// Attach custom click event on cloned elements, 
			// trigger click event on corresponding link
			$document.on('click', '.main-slider .slick-cloned', function(e) {
				$(selector)
					.eq( ( $(e.currentTarget).attr("data-slick-index") || 0) % $(selector).length )
					.trigger("click.fb-start", {
						$trigger: $(this)
					});

				return false;
			});
		}
		function initSlick() {
			$sliders.each(function(){
				$(this).slick();
			});
		}
		function slideSlickOnFancyClick() {
			$document.on('afterLoad.fb', function(e, instance) {
				var $mainSlider = instance.$trigger.closest('.main-slider'),
					currIndex = instance.currIndex;
				if ( $mainSlider.slick('slickCurrentSlide') !== currIndex ) {
					$mainSlider.slick('slickGoTo', currIndex);
				}
			});
		}
		function pauseSlickAutoPlayOnFancy() {
			$document.on('afterClose.fb', function(e,instance){
				var $mainSlider = instance.$trigger.closest('.main-slider'),
					autoPlay = $mainSlider.slick('slickGetOption', 'autoplay');
				if ( ! autoPlay ) {
					return;
				};
				$mainSlider.slick('slickPlay');
			});
			$mainSliders.each(function(){
				var $mainSlider = $(this),
					autoPlay = $mainSlider.slick('slickGetOption', 'autoplay');
				if ( ! autoPlay ) {
					return;
				}
				var $fancyboxes = $(this).find('[data-fancybox]');
				$fancyboxes.on('click', function(){
					$mainSlider.slick('slickPause');
				});
			});
		}
		function isCenterOfElementInViewport(el) {
			// Special bonus for those using jQuery
			if (typeof jQuery === "function" && el instanceof jQuery) {
				el = el[0];
			}
			var rect = el.getBoundingClientRect(),
				vCenter = rect.top + rect.height/2;
			return (
				rect.top >= 0 &&
				rect.left >= 0 &&
				vCenter <= (window.innerHeight || document.documentElement.clientHeight) && /* or $(window).height() */
				rect.right <= (window.innerWidth || document.documentElement.clientWidth) /* or $(window).width() */
			);
		}
		// Hide notification when Slider is in Viewport or if Slider has interaction.
		function hideNotificationOnScroll() {
			var coreFunc = function() {
				$mainSliders.each(function() {
					// Bail if no Title,
					// the Notification is entirely dependant on the Title Attribute
					if ( $(this).attr('title') === undefined ) {
						return;
					}
					if (isCenterOfElementInViewport($(this))) {
						var $that = $(this);
						setTimeout(function(){
							$that.removeClass('show-notification');
							$(window).off('scroll', coreFunc);
						}, 2000);
					}
				});
			}
			coreFunc();
			$(window).on('scroll', coreFunc);
		}
		function hideNotificationOnClick() {
			$mainSliders.each(function(){
				// Bail if no Title,
				// the Notification is entirely dependant on the Title Attribute
				if ( $(this).attr('title') === undefined ) {
					return;
				}
				// Remove Slick's native focusin event handler and cache it.
				var that = this,
					focusInHandler,
					prop;
				// Find prop that starts with jQuery && has 'events' prop.
				Object.keys(that).forEach(function(p){
					if ( ~p.indexOf("jQuery") && that[p].hasOwnProperty('events') ) {
						// cache prop and focusin handler and set to null.
						prop = p;
						focusInHandler = that[p]['events']['focusin'];
						that[p]['events']['focusin'] = null;
					}
				});
				$(this).one('focusin', function(){
					$(this).removeClass('show-notification');
					// Re-Bind focusInHandler to focusin event.
					if ( prop && focusInHandler ) {
						this[prop]['events']['focusin'] = focusInHandler;
					}
				});

				$(this).addClass('show-notification').
					one('click mouseover', function(){
						$(this).removeClass('show-notification');
					});
			});
		}
		function readyFuncs() {
			fancyBoxIgnoreSlickClones();
			initSlick();
			slideSlickOnFancyClick();
			pauseSlickAutoPlayOnFancy();
			hideNotificationOnClick();
			hideNotificationOnScroll();
		}
		readyFuncs();
	});
})(jQuery);
