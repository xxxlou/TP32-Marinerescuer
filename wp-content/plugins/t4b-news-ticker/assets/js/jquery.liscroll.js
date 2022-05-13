/*!
 * liScroll 1.1 updated by @davetayls
 * 
 * 2007-2009 Gian Carlo Mingati
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * T4B News Ticker v1.2.5 - 17-12-2021
 * by @realwebcare - https://www.realwebcare.com/
 */
(function($){
	$.fn.liScroll = function(settings) {
	    
		settings = $.extend({
	        travelocity: 0.05
	    }, settings);

		return this.each(function() {
			var strip = this,
				$strip = $(strip);
	
			$strip.addClass("newsticker")
			$stripItems = $strip.find("li");
			
			var stripWidth = 0,
				$mask = $strip.wrap("<div class='ticker-mask'></div>"),
				$tickercontainer = $strip.parent().wrap("<div class='tickercontainer'></div>").parent(),
				paused = false,
				containerWidth = $strip.parent().parent().width(); //a.k.a. 'mask' width
	
			var currentItemIndex = function() {
				var index = 0,
					currentLeft = parseInt($strip.css("left")),
					accumulatedWidth = 0;
					
				if (currentLeft > 0) {
					return 0;
				} else {
					$strip.find("li").each(function(i) {
						if (currentLeft == (0 - accumulatedWidth)) {
							index = i;
							return false;
						}
						accumulatedWidth += $(this).width();
						if (currentLeft > (0 - accumulatedWidth)) {
							index = i;
							return false;
						}
						return true;
					});
				}
				return index;
			};
			
			// calculate full width
			$strip.width(10000); // temporary width to prevent inline elements wrapping to initial width of ul
			$stripItems.each(function(i) {
				stripWidth += $(this).outerWidth()+50;
			});
			$strip.width(stripWidth);

			/*thanks to Scott Waye*/
			var totalTravel = stripWidth + containerWidth,
				defTiming = totalTravel / settings.travelocity;
	
			function scrollnews(spazio, tempo) {
				$strip.animate(
					{ left: '-=' + spazio }, 
					tempo, 
					"linear", 
					function() { 
						$strip.css("left", containerWidth); 
						scrollnews(totalTravel, defTiming); 
					}
				);
			}
			scrollnews(totalTravel, defTiming);			
			$strip.hover(function(){
				$(this).stop();
			},
			function(){
				var offset = $(this).offset();
				var residualSpace = offset.left + stripWidth;
				var residualTime = residualSpace/settings.travelocity;
				scrollnews(residualSpace, residualTime);
			});
		});
	};
})(jQuery);