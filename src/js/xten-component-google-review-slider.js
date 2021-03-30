(function ($) {
	$(document).on("ready", function () {
		var $sliders = $('.component-google-review-slider .wp-google-reviews');

		function moveAvatarToName() {
			var $reviews = $sliders.find('.wp-google-review');
			$reviews.each(function(){
				var $img = $(this).find('.rplg-review-avatar'),
					$imgParent = $img.parent(),
					$name = $(this).find('.wp-google-name').first(),
					$imgElm = $img.detach();
				$imgParent.remove();
				$name.prepend($imgElm);
			});
		}
		function slickExtras($slider) {
			var $dots = $slider.find('.slick-dots').first().detach();
			$slider.
					after($dots).
					find('.wp-more-toggle').
					on('click', function(){
						$slider[0].slick.animateHeight();
						$slider.slick('slickSetOption', '', null, true);
					});
		}
		function initSlick() {
			moveAvatarToName();
			$sliders.each(function(){
				$(this).on('init reInit', function(){
					slickExtras($(this));
				}).slick({
					autoplay: true,
					autoplaySpeed: 7000,
					speed: 350,
					adaptiveHeight: true,
					dots: true,
					cssEase: 'cubic-bezier(0.22, 0.61, 0.36, 1)',
					swipeToSlide: true,
					touchMove: true,
					touchMove: true,
				});
			});
		}
		function readyFuncs() {
			initSlick();
		}
		readyFuncs();
	});
})(jQuery);