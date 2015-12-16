/*
* Author:			Marco Kuiper (http://www.marcofolio.net/)
*							Sylvain Papet (http://www.com-ocean.com/)
*							Toxic Web (http://www.toxic-web.co.uk/) - v1.2 thumbnail edition
*							MNEC - Further modifications, making thumbnail navigation actually work
*/
		
(function($) {
	$.bgimgSlideshow = { version: '1.3' };

	$.fn.bgimgSlideshow = function(options) {
		options = jQuery.extend( {
			slideshowSpeed: 6000,
			method : "fade",
			fadeSpeed : 250,
			contDiv : "#header",
			slideimages : '<div id="headerimg1" class="headerimg"></div>\
										 	<div id="headerimg2" class="headerimg"></div>',
			slidecontrols : '<ul id="featurethumbnails"></ul>\
										<table id="featureinfo">\
											<tr>\
												<td>\
													<div id="screen">\
														<div id="featuretext"></div>\
														<a href="" alt="More" id="featurelink"><img src="' + buttonSrc + '" height="28" width="83"></a>\
													</div>\
												</td>\
											</tr>\
										</table>',
		}, options );

		var interval;
		var activeContainer = 1;	
		var currentImg = 0;
		var started = false;
		var animating = false;
		var currentZindex = -1;
		var imageCache = [];

		$.bgimgSlideshow.preLoadImage = function() {
			var args_len = arguments.length;
			for (var i = args_len; i--;) {
				var cacheImage = document.createElement('img');
				cacheImage.src = arguments[i];
				imageCache.push(cacheImage);
			}
		}

		$.bgimgSlideshow.preLoadPhotoObjects = function(photoObjects) {
			for(i in photoObjects) {
				$.bgimgSlideshow.preLoadImage(photoObjects[i].image, photoObjects[i].image);
				$("#featurethumbnails").append('<li class="featurethumbnail"><a href="' + photoObjects[i].image + '" style="background-image:url(' + photoObjects[i].image + ');filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' + photoObjects[i].image + '\', sizingMethod=\'scale\');-ms-filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' + photoObjects[i].image + '\', sizingMethod=\'scale\');" ></a></li>');				
			}
		}

		$.bgimgSlideshow.initialize = function() {

			$("#header").prepend(options.slideimages);
			$("#home").append(options.slidecontrols);
			$.bgimgSlideshow.preLoadPhotoObjects(options.photos);
			$.bgimgSlideshow.startSlideshow();

		};

		$.bgimgSlideshow.navigate = function(direction) {

			// Check if no animation is running. If it is, prevent the action
			if(animating) {
				return;
			}

			// Check which current image we need to show
			if(direction == "next") {
				currentImg++;
				if(currentImg == options.photos.length + 1) {
					currentImg = 1;
				}
			} else {
				currentImg--;
				if(currentImg == 0) {
					currentImg = options.photos.length;
				}
			}

			// Check which container we need to use
			var currentContainer = activeContainer;
			if(activeContainer == 1) {
				activeContainer = 2;
			} else {
				activeContainer = 1;
			}

			$.bgimgSlideshow.showImage((currentImg - 1), currentContainer, activeContainer);

		};

		$.bgimgSlideshow.showImage = function(numImg, currentContainer, activeContainer) {

			var photoObject = options.photos[numImg];
			animating = true;
			// Make sure the new container is always on the background
			currentZindex--;

			// Set the background image of the new active container
			$("#headerimg" + activeContainer).css({
				"background-image" : "url(" + photoObject.image + ")",
				"display" : "block",
				"z-index" : currentZindex,
				"filter" : "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+ photoObject.image +"', sizingMethod='scale')",
        "-ms-filter" : "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+ photoObject.image +"', sizingMethod='scale')"
			});

			// Set the new header text
			$("#featuretext").html(photoObject.text);
			$("#featurelink").attr("href", photoObject.url);

			// Thumbnail click
			$(".featurethumbnail a").click(function() {
				var clickedThumb = $(this).attr('href');
				var result = $.grep(options.photos, function(item) {
					return item.image == clickedThumb;
				});
				$("#featuretext").html(result[0].text);
				$("#featurelink").attr("href", result[0].url);
				clearInterval(interval);
				interval = setInterval(function() {
					$.bgimgSlideshow.navigate("next");
				}, options.slideshowSpeed);
				$("#headerimg" + activeContainer).css({'background-image': 'url(' + clickedThumb + ')'});
				return false;
			 });

			// Fade out the current container and display the header text
			$("#headerimg" + currentContainer).fadeOut(options.fadeSpeed,function() {
				setTimeout(function() {
					animating = false;
				},
				500);
			});

		}

		$.bgimgSlideshow.stopSlideshow = function() {
			// Clear the interval
			clearInterval(interval);
			started = false;
		};

		$.bgimgSlideshow.startSlideshow = function() {
			$.bgimgSlideshow.navigate("next");
			interval = setInterval(function() {
				$.bgimgSlideshow.navigate("next");
			}, options.slideshowSpeed);
			started = true;
		};		

		$.bgimgSlideshow.initialize();
		return this;

	}

})(jQuery);