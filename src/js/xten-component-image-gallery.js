(function ($) {
	$(document).on("ready", function () {
		var $document = $(this),
		$sliders = $('.xten-component-image-gallery .slickSlider');

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
		function readyFuncs() {
			fancyBoxIgnoreSlickClones();
			initSlick();
			slideSlickOnFancyClick();
		}
		readyFuncs();
		function resizeFuncs() {
			
		}
		$(window).on('resize', function () {
			resizeFuncs();
		});
	});
})(jQuery);
