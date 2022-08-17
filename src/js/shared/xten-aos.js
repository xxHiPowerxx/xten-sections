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
		function aosCascade() {
			function getTextNodesIn(node, includeWhitespaceNodes) {
				var textNodes = [], nonWhitespaceMatcher = /\S/;
		
				function getTextNodes(node, level) {
					level = level || 0;
					if (node.nodeType == 3) {
						if (includeWhitespaceNodes || nonWhitespaceMatcher.test(node.nodeValue)) {
							textNodes.push(node);
						}
					} else {
						if ( level === 0 ) {
							for (var i = 0, len = node.childNodes.length; i < len; ++i) {
								getTextNodes(node.childNodes[i], level);
								level ++;
							}
						}
					}
				}
				getTextNodes(node);
				return textNodes;
			}
			/**
			 * Wraps a string around each word
			 *
			 * @param {string} str The string to transform
			 * @param {string} tmpl Template that gets interpolated
			 * @returns {string} The given input splitted by words
			 */
			 function wrapWords(str, tmpl) {
				return str.replace(/\w+/g, tmpl || "<span>$&</span>");
			}
			function wrapWordsInTextNodes(el) {
				var nodes = getTextNodesIn(el);
				$(nodes).each(function(){
					$(this).replaceWith(wrapWords($(this).text()));
				});
			}
			$('.aosCascade').each(function(){
				var interval = parseInt($(this).attr('data-aos-cascade-interval')) || 200,
					delay = parseInt($(this).attr('data-aos-cascade-delay')) || 0,
					offset = delay + interval,
					animation = $(this).attr('data-aos-cascade') || 'fade-up',
					$children;
				wrapWordsInTextNodes(this);
				$children = $(this).children();
				$children.each(function(){
					$(this).attr({
						'data-aos': animation,
						'data-aos-delay': offset,
					});
					offset += interval;
				});
			})
		}

		// Sometimes AOS doesn't on load so we dispatch resize.
		function dispatchResize() {
			var resizeEvent = window.document.createEvent('UIEvents');
			resizeEvent.initUIEvent('resize', true, false, window, 0);
			window.dispatchEvent(resizeEvent);
		}
		function initAOS() {
			var aosConf = {
				startEvent: 'load',
				duration: 500,
				easing: 'ease-out',
			};
			if (waitForClassesToAttributes === true) {
				aosConf.startEvent = 'aosclassesconvertedtoattributes.xten';
				$(window).on('aosclassesconvertedtoattributes.xten', function(e){
					AOS.init(aosConf);
					dispatchResize();
				});
			} else {
				AOS.init(aosConf);
				dispatchResize();
			}
		}
		function readyFuncs() {
			convertAOSClassesToDataAttributes();
			aosCascade();
			initAOS();
		}
		readyFuncs();
	});
})(jQuery);