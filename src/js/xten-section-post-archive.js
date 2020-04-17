(function ($) {
	$(document).on("ready", function () {
		function checkPostsListWidth() {
			$('.xten-section-post-archive').each(function () {
				var postsList = $(this).find('.posts-list'),
					parent = postsList[0].parentNode,
					parentWidth = parent.getBoundingClientRect().width,
					maxPostsRow = $(this).attr('data-max-posts-per-row'),
					posts = postsList.children(':lt(' + maxPostsRow + ')'),
					singlePostWidth = 0,
					actualPostsPerRow = 0,
					postsCombinedWidth = 0,
					maxWidth;
				posts.each(function (index) {
					var listedPostInner = this.children[0],
						listedPostComputedStyle = getComputedStyle(listedPostInner),
						listedPostInnerWidth = parseFloat(listedPostComputedStyle.width),
						listedPostMargins = parseFloat(listedPostComputedStyle.marginLeft) +
							parseFloat(listedPostComputedStyle.marginRight),
						listedPostOuterWidth = listedPostInnerWidth + listedPostMargins;
					if (index === 0) {
						singlePostWidth = listedPostOuterWidth;
					}
					postsCombinedWidth += listedPostOuterWidth;
				});

				// Check if any the posts-in-question have wrapped.
				if (postsCombinedWidth > parentWidth) {
					// Figure out how many posts actually fit inside the parent container.
					actualPostsPerRow = Math.floor(parentWidth / singlePostWidth);
					maxWidth = (singlePostWidth * actualPostsPerRow) + 'px';
				} else {
					maxWidth = '';
				}
				postsList.css('max-width', maxWidth);
			});
		}
		function readyFuncs() {
			checkPostsListWidth();
		}
		readyFuncs();
		function resizeFuncs() {
			checkPostsListWidth();
		}
		$(window).on('resize', function () {
			resizeFuncs();
		});
	});
})(jQuery);