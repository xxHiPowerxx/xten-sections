(function ($) {
	jQuery(document).on('ready', function() {
		var funcs = {},
			readyFuncs = {};
		funcs.executeFuncs = function(obj) {
			for (var func in obj) {
				obj[func]();
			}
		}

		funcs.initFunc = function(slider) {
			// console.log('slider in initFunc', slider);
			// console.log('slider.slick in initFunc', slider.slick);
			$.extend(slider.slick, {
				clonedSlides : $(slider).find('.slick-cloned'),
				clonedSlidesPrepended : (function($slider){
					var $firstClone = $slider.find('.slick-cloned').first(),
						$clones = $firstClone.add($firstClone.nextUntil(':not(.slick-cloned)'));
					// console.log('$slider inside clonedSlidesPrepended', $slider);
					return $clones;
				})($(slider)),
				clonedSlidesAppended : (function($slider){
					var $lastClone = $slider.find('.slick-cloned').last(),
						$clones = $lastClone.add($lastClone.prevUntil(':not(.slick-cloned)'));
					// console.log('$clones', $clones);
					// console.log('$slider inside clonedSlidesAppended', $slider);
					return $clones;
				})($(slider)),
			});
		}

		funcs.beforeChangeFunc = function(slick, nextSlide) {
			// DEBUG ONLY:
			// console.log('beforeChange slick', slick);
			// console.log('beforeChange currentSlide', currentSlide);
			// console.log('beforeChange nextSlide', nextSlide);
			// console.log('beforeChange clonedSlides', slick.clonedSlides);
			var numSlides = slick.$slides.length,
				nextCloneIndex = nextSlide + numSlides,
				prevCloneIndex = nextSlide - numSlides,
				$nextAndPrevClones = slick.$slider.find('[data-slick-index="' + nextCloneIndex + '"], [data-slick-index="' + prevCloneIndex + '"]');
			slick.clonedSlides.removeClass('slick-current-clone');
			$nextAndPrevClones.addClass('slick-current-clone');
			// DEBUG ONLY:
			// console.log('beforeChange $nextAndPrevClones', $nextAndPrevClones);
		}

		funcs.addClassToCurrentClones = function($slider) {
			$slider.each(function(){
				funcs.initFunc(this);
				// console.log('this.slick', this.slick);
				// console.log('this', this);
			}).on('beforeChange', function(event, slick, currentSlide, nextSlide){
				funcs.beforeChangeFunc(slick, nextSlide);
			});
		}

		funcs.bindAddClassToCurrentClones = function() {
			// Bind to un-intiatialized slick sliders.
			$(window).on('init', function(e){
				if ( $(e.target).is('.slick-slider') ) {
					var slider = e.target;
					setTimeout(function(){
						// console.log('slider.slick', slider.slick);
						funcs.addClassToCurrentClones($(slider));
					});
				}
			});

			// Bind to intiatialized slick sliders.
			funcs.addClassToCurrentClones($('.slick-slider.slick-initialized'));
		}
		readyFuncs.bindAddClassToCurrentClones = funcs.bindAddClassToCurrentClones;

		funcs.executeFuncs(readyFuncs);
	});
})(jQuery);
