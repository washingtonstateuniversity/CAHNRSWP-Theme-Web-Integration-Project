jQuery(document).ready(function($){

	// Toggle nav and search for mobile devices
	$(function() {
		$(window).on('resize', function(){
			var width = document.width;
			if( width > 768 ) {
				$('#sitenav').show();
			} else {
				if( ! $('#mobilenav a').hasClass('active') ) {
					$('#compoundsearch').hide();
					$('#sitenav').hide();
				}
			}
		}).trigger('resize');
		var $anchorLinks = $('#mobilenav').find('a');
		$anchorLinks.click(function(e){
			e.preventDefault();
			var $this = $(this), thisHref = $this.attr('href');
			$('.reveal').hide();
			if($this.hasClass('active')) {
				$this.removeClass('active');
				$(thisHref).hide();
			} else {
				$anchorLinks.removeClass('active');
				$this.addClass('active');
				$(thisHref).show();
			}
		});
	});

	// Accordian function
	$(".toggle_container").hide();
	$(".trigger").click(function(){
		$(this).toggleClass("active").next().slideToggle("slow");
	});

	// Responsive videos
	$(function() {
		var $allVideos = $("iframe[src^='http://player.vimeo.com'], iframe[src^='http://www.youtube.com'], object, embed"),
		    $fluidEl = $("p");		
		$allVideos.each(function() {
			$(this).attr('data-aspectRatio', this.height / this.width).removeAttr('height').removeAttr('width');
		});
		$(window).resize(function() {
			var newWidth = $fluidEl.width();
			$allVideos.each(function() {
				var $el = $(this);
				$el.width(newWidth).height(newWidth * $el.attr('data-aspectRatio'));
			});
		}).resize();
	});

});

// Using the placeholder attribute would be preferable
function checktextboxwsu() {
	if (document.wsusearchm.q.value.replace(/\s+/g, '') == '') { document.wsusearchm.q.value = 'Search WSU Web/People' }
	if (document.wsusearch.q.value.replace(/\s+/g, '') == '') { document.wsusearch.q.value = 'Search WSU Web/People' }
	return false
}
function erasetextboxwsu() {
	if (document.wsusearchm.q.value.toUpperCase() == 'SEARCH WSU WEB/PEOPLE') { document.wsusearchm.q.value = '' }
	if (document.wsusearch.q.value.toUpperCase() == 'SEARCH WSU WEB/PEOPLE') { document.wsusearch.q.value = '' }
	return false
}
function checktextboxlocal() {
	if (document.sitesearch.s.value.replace(/\s+/g, '') == '') { document.sitesearch.s.value = 'Search' }
	return false
}
function erasetextboxlocal() {
	if (document.sitesearch.s.value.toUpperCase() == 'SEARCH') { document.sitesearch.s.value = '' }
	return false
}
function checktextboxlocalm() {
	if (document.sitesearchm.s.value.replace(/\s+/g, '') == '') { document.sitesearchm.s.value = 'Search' }
	return false
}
function erasetextboxlocalm() {
	if (document.sitesearchm.s.value.toUpperCase() == 'SEARCH') { document.sitesearchm.s.value = '' }
	return false
}