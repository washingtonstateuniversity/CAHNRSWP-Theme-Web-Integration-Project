var wpWidgets;
(function($) {

wpWidgets = {

	init : function(){
		var rem, sidebars = $('div.widgets-sortables'), isRTL = !! ( 'undefined' != typeof isRtl && isRtl ),
			margin = ( isRtl ? 'marginRight' : 'marginLeft' ), the_id;

		$('#widgets-right').children('.widgets-holder-wrap').children('.sidebar-name').click(function(){
			var c = $(this).siblings('.widgets-sortables'), p = $(this).parent();
			if ( !p.hasClass('closed') ){
				c.sortable('disable');
				p.addClass('closed');
			} else {
				p.removeClass('closed');
				c.sortable('enable').sortable('refresh');
			}
		});

		$('#widgets-left').children('.widgets-holder-wrap').children('.sidebar-name').click(function(){
			$(this).parent().toggleClass('closed');
		});

		sidebars.each(function(){
			if ( $(this).parent().hasClass('inactive') )
				return true;

			var h = 50, H = $(this).children('.widget').length;
			h = h + parseInt(H * 48, 10);
			$(this).css( 'minHeight', h + 'px' );
		});

		$('a.widget-action').live('click', function(){
			var css = {}, widget = $(this).closest('div.widget'), inside = widget.children('.widget-inside'), w = parseInt( widget.find('input.widget-width').val(), 10 );

			if ( inside.is(':hidden') ) {
				if ( w > 250 && inside.closest('div.widgets-sortables').length ) {
					css['width'] = w + 30 + 'px';
					if ( inside.closest('div.widget-liquid-right').length )
						css[margin] = 235 - w + 'px';
					widget.css(css);
				}
				wpWidgets.fixLabels(widget);
				inside.slideDown('fast');
			} else {
				inside.slideUp('fast', function() {
					widget.css({'width':'', margin:''});
				});
			}
			return false;
		});

		$('input.widget-control-save').live('click', function(){
			wpWidgets.save( $(this).closest('div.widget'), 0, 1, 0 );
			return false;
		});

		$('a.widget-control-remove').live('click', function(){
			wpWidgets.save( $(this).closest('div.widget'), 1, 1, 0 );
			return false;
		});

		$('a.widget-control-close').live('click', function(){
			wpWidgets.close( $(this).closest('div.widget') );
			return false;
		});

		sidebars.children('.widget').each(function() {
			wpWidgets.appendTitle(this);
			if ( $('p.widget-error', this).length )
				$('a.widget-action', this).click();
		});

		$('#widget-list').children('.widget').draggable({
			connectToSortable: 'div.widgets-sortables',
			handle: '> .widget-top > .widget-title',
			distance: 2,
			helper: 'clone',
			zIndex: 5,
			containment: 'document',
			start: function(e,ui) {
				ui.helper.find('div.widget-description').hide();
				the_id = this.id;
			},
			stop: function(e,ui) {
				if ( rem )
					$(rem).hide();

				rem = '';
                                
			}
		});

		sidebars.sortable({
			placeholder: 'widget-placeholder',
			items: '> .widget',
			handle: '> .widget-top > .widget-title',
			cursor: 'move',
			distance: 2,
			containment: 'document',
			start: function(e,ui) {
				ui.item.children('.widget-inside').hide();
				ui.item.css({margin:'', 'width':''});
			},
			stop: function(e,ui){                              
				if ( ui.item.hasClass('ui-draggable') && ui.item.data('draggable') )
					ui.item.draggable('destroy');

				if ( ui.item.hasClass('deleting') ){
					wpWidgets.save( ui.item, 1, 0, 1 ); // delete widget
					ui.item.remove();
					return;
				}
                                
				if( the_id == undefined || the_id == '' ){
					// Moving a widget
					var newParent = $('div#' + ui.item.context.offsetParent.id );
					
					// Add id to array
					$("input#" + newParent.attr('id') + "Array").val(newParent.sortable('toArray').join());
					
					// Remove from old location
					$("input#" + $(this).attr('id') + "Array").val($(this).sortable('toArray').join());
				} else {
					// Widget is new
					var id = the_id,
					counterVal = $("input#" + id.slice(0, -6)).val();

					ui.item.css({margin:'', 'width':''});
					the_id = '';
							
					// This is all to make sure "Page Content" widgets work properly.
					// Without it the IDs get mixed up and you can end up with multiple widgets sharing the same content.
					if ( id.indexOf( 'cTypePage') != -1  ){
						//Get current arrays
						var mainArray = [],
								secondaryArray = [],
								additionalArray = [],
								index = 0,
								widgetArray = [],
								counter = 0;
						
						//Compile all existing page content widgets
						if( $('input#wipMainArray' ).attr('value') != '' && $('input#wipMainArray' ).attr('value') != undefined ){
							mainArray = $('input#wipMainArray' ).attr('value').split( ',' );
							for( index = 0; index < mainArray.length; index++ ) {
								if( mainArray[index].indexOf( 'cTypePage') != -1 ) //{
									widgetArray.push( mainArray[index] );
								//}
							}
						}
						if( $('input#wipSecondayArray' ).attr('value') != '' && $('input#wipSecondayArray' ).attr('value') != undefined ){
							secondaryArray = $('input#wipSecondayArray' ).attr('value').split( ',' );
							for( index = 0; index < secondaryArray.length; index++ ) {
								if( secondaryArray[index].indexOf( 'cTypePage') != -1 ) //{
									widgetArray.push( secondaryArray[index] );
								//}
							}
						}
						if( $('input#wipAdditionalArray' ).attr('value') != '' && $('input#wipAdditionalArray' ).attr('value') != undefined ){
							additionalArray = $('input#wipAdditionalArray' ).attr('value').split( ',' );
							for( index = 0; index < additionalArray.length; index++ ) {
								if( additionalArray[index].indexOf( 'cTypePage') != -1 ) //{
									widgetArray.push( additionalArray[index] );
								//}
							}
						}

						//Sort array
						widgetArray.sort();
						
						//What's the lowest available ID?
						index = 0;
						while( index < widgetArray.length && widgetArray[index].slice(-1) == counter ){
							index++;
							counter++;
						}

						counterVal = counter;                                         
					}
					
					//Insert id value
					ui.item.html( ui.item.html().replace(/<[^<>]+>/g, function(m){ return m.replace(/__i__|%i%/g, counterVal); }) );
					ui.item.attr( 'id', id.replace('__i__', counterVal) );

					//Update base count
					counterVal++;
					$("input#" + id.slice(0, -6)).val(counterVal);
					
					//Save widget to array
					$("input#" + $(this).attr('id') + "Array").val($(this).sortable('toArray').join());
				}

				//Expanding widget
				ui.item.find("a.widget-action").click();
			},
			receive: function(e, ui) {
				var sender = $(ui.sender);

				if ( !$(this).is(':visible') || this.id.indexOf('orphaned_widgets') != -1 )
					sender.sortable('cancel');

				if ( sender.attr('id').indexOf('orphaned_widgets') != -1 && !sender.children('.widget').length ){
					sender.parents('.orphan-sidebar').slideUp(400, function(){ $(this).remove(); });
				}
			}
		}).sortable('option', 'connectWith', 'div.widgets-sortables').parent().filter('.closed').children('.widgets-sortables').sortable('disable');

	},

	saveOrder : function(sb){
		if ( sb )
			$('#' + sb).closest('div.widgets-holder-wrap').find('img.ajax-feedback').css('visibility', 'visible');

		var a = {
			action: 'widgets-order',
			savewidgets: $('#_wpnonce_widgets').val(),
			sidebars: []
		};

		$('div.widgets-sortables').each( function(){
			if ( $(this).sortable )
				a['sidebars[' + $(this).attr('id') + ']'] = $(this).sortable('toArray').join(',');
		});

		$.post( ajaxurl, a, function(){
			$('img.ajax-feedback').css('visibility', 'hidden');
		});

		this.resize();
	},

	save : function(widget){
		widget = $(widget);

		// Added
		wid = widget.attr("id");
		count = $("input#" + wid.slice(0, -2));
                
		// There could well be a better way to do this...
		inp = $("input#" + widget.parent().attr("id") + "Array"); // Get the input for the div we're deleting from
		inpval = inp.val(); // Get the value of above input as it is before the delete
		if (inpval.indexOf(",") >= 0){ // If there's more than one (an array of) widget(s) in this div and its respective input value...
			newval = inpval.replace(wid, ""); // Replace the id of the widget we're deleting with nothing
			arr = newval.split(","); // Split what's left of the value at the commas
			newv = $.grep(arr,function(n){
				return(n);
			}); // Clean up any empty values from our split (i.e. get rid of stray commas) and return the new value
		} else { // Otherwise if there's just one widget...
			newv = ''; // the new value is nothing
		}
		
		widget.slideUp("fast", function(){
	
			// Added
			inp.val(inp.val().replace(inpval, newv)); // replace original input value with the new value
			count.val(count.val() - 1); // subtract 1 from ctype multi counter
				$(this).remove();
		})
	},

	appendTitle : function(widget){
		var title = $('input[id*="-title"]', widget).val() || '';

		if ( title )
			title = ': ' + title.replace(/<[^<>]+>/g, '').replace(/</g, '&lt;').replace(/>/g, '&gt;');

		$(widget).children('.widget-top').children('.widget-title').children()
				.children('.in-widget-title').html(title);

	},

	resize : function() {
		$('div.widgets-sortables').each(function(){
			if ( $(this).parent().hasClass('inactive') )
				return true;

			var h = 50, H = $(this).children('.widget').length;
			h = h + parseInt(H * 48, 10);
			$(this).css( 'minHeight', h + 'px' );
		});
	},

	fixLabels : function(widget) {
		widget.children('.widget-inside').find('label').each(function(){
			var f = $(this).attr('for');
			if ( f && f == $('input', this).attr('id') )
				$(this).removeAttr('for');
		});
	},

	close : function(widget) {
		widget.children('.widget-inside').slideUp('fast', function(){
			widget.css({'width':'', margin:''});
		});
	}
};

$(document).ready(function($){ wpWidgets.init(); });

})(jQuery);