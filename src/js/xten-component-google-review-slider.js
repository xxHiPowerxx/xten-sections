(function ($) {
	$(document).on("ready", function () {
		var $sliders = $('.component-google-review-slider .wp-google-reviews, .rplg-reviews');

		function reviewsPluginFreeOrPaid() {
			$sliders.each(function(){
				this.reviewsPlugin = $(this).is('.wp-google-reviews') ?
				'free' :
				'paid';
			});
		}
		reviewsPluginFreeOrPaid();

		function moveAvatarToName() {
			$sliders.each(function(){
				var sliderReviewPlugin = this.reviewsPlugin,
					reviewsSelector = sliderReviewPlugin === 'free' ?
						'.wp-google-review' :
						'.rplg-reviews',
					$reviews = $(this).find(reviewsSelector);
				$reviews.each(function(){
					var $img = $(this).find('.rplg-review-avatar'),
						$imgParent = $img.parent(),
						nameSelector = sliderReviewPlugin === 'free' ?
							'.wp-google-name' :
							'.rplg-review-name',
						$name = $(this).find(nameSelector).first(),
						$imgElm = $img.detach();
					$imgParent.remove();
					$name.prepend($imgElm);
				});
			});
		}
		function slickExtras($slider) {
			var $dots = $slider.find('.slick-dots').first().detach(),
				moreToggleSelector = this.reviewsPlugin	=== 'free' ?
				'.wp-more-toggle' :
				'.rplg-more-toggle';
			$slider.
				after($dots).
				find(moreToggleSelector);
			if ( ! this.animateClickHandlerBound ) {
				$(this).on('click', function(){
					this.animateClickHandlerBound = true;
					$slider[0].slick.animateHeight();
					$slider.slick('slickSetOption', '', null, true);
				});
			}
		}
		function initSlick() {
			moveAvatarToName();
			$sliders.each(function(){
				$(this).on('init reInit breakpoint', function(){
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
					responsive: [
						{
							breakpoint: 768,
							settings: {
								dots: false
							}
						}
					],
				});
			});
		}
		function readyFuncs() {
			initSlick();
		}
		readyFuncs();
	});
})(jQuery);