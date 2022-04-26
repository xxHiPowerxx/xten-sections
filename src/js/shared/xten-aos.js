// TODO: Move this file into a shared folder with it's own repository to keep synced.

// EG: Current: js > shared > xten-aos.js. Proposed: shared > js > xten-aos.js
(function ($) {
	function ismatch(str){
		var ret = null;
		var tab = [
			'data-aos_',
			'data-aos-delay_',
			'data-aos-duration_',
			'data-aos-easing_',
		];
		Object.values(tab).forEach(function (value) {
			if (String(str).match(value)){
				ret = str.split('_');
				return false;
			}
		});
		return ret;
	}
	$(document).on('ready', function () {
		var waitForClassesToAttributes = false;

		function convertAOSClassesToDataAttributes() {
			var $aosElms = $('[class*="data-aos"]');
			$aosElms.each(function (index) {
				var that = this,
					$that = $(this),
					tab = $that.attr('class').split(' '),
					// Find out if inline style exists
					thisStyleTransitionDuration = this.style['transitionDuration'],
					// store transtion-duration with either the found style or computed style.
					thisTransitionDuration = thisStyleTransitionDuration || $(this).css('transition-duration');

				// Set transition-duration to 0s if not already.
				if ( thisTransitionDuration !== '0s' ) {
					$(this).css('transition-duration', '0s');
					waitForClassesToAttributes = true;
				}

				Object.values(tab).forEach(function (item) {
					var ello = ismatch(item);
					if (ello !== null) {
						$that.attr(ello[0], ello[1]);
					}
				});

				setTimeout(function(){
					// Remove inline style if style found was in a stylesheet.
					if ( thisTransitionDuration !== '0s' ) {
						that.style['transitionDuration'] = '';
					} else {
						// else set to previous found style (could be '' (not set)).
						that.style['transitionDuration'] = thisStyleTransitionDuration;
					}
					if (
						waitForClassesToAttributes === true &&
						index === $aosElms.length - 1
					) {
						$(window).trigger('aosclassesconvertedtoattributes.xten');
					}
				});

			});
		}
		function initAOS() {
			var aosConf = {
				startEvent: 'load',
				duration: 500,
				easing: 'ease-out',
			};
			if (waitForClassesToAttributes === true) {
				$(window).on('aosclassesconvertedtoattributes.xten', function(){
					AOS.init(aosConf);
				});
			} else {
				AOS.init(aosConf);
			}
		}
		function readyFuncs() {
			convertAOSClassesToDataAttributes();
			initAOS();
		}
		readyFuncs();
	});
})(jQuery);