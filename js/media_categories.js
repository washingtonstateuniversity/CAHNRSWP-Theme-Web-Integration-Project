/*
jQuery for Media Categories plugin

@author Eddie Moya
*/

jQuery(document).ready(function($){
	// This modifies the settings for the AttachmentCompat object in media-views.js.
	// This is needed because saving on change of checkboxes is glitchy when a user clicks multiple checkboxes in a row.
	delete wp.media.view.AttachmentCompat.prototype.events['change input'];
	wp.media.view.AttachmentCompat.prototype.events['change input[type!="checkbox"]'] = 'save';

	// Overriding saveCompat to add the trigger.
	wp.media.model.Attachment.prototype.saveCompat = 
		function( data, options ) {
			var model = this;

			// If we do not have the necessary nonce, fail immeditately.
			if ( ! this.get('nonces') || ! this.get('nonces').update )
				return $.Deferred().rejectWith( this ).promise();

			return wp.media.post( 'save-attachment-compat', _.defaults({
				id:			this.id,
				nonce:	 this.get('nonces').update,
				post_id: wp.media.model.settings.post.id
			}, data ) ).done( function( resp, status, xhr ) {
				model.set( model.parse( resp, xhr ), options );
				// Trigger to detect the refreshing of the sidebar.
				$('.compat-attachment-fields').trigger('modal-refreshed');
			});
		}

	$.each(taxonomy, function(index, tax){

		var input_class = '.compat-field-'+tax;
		var metabox_class = input_class+'_metabox';

		$('.compat-attachment-fields').live('modal-refreshed', function(){
			$(input_class).hide();
			$(metabox_class).addClass('metabox_container');
		})

		$('.attachments').live('click', function(){
			$(input_class).hide();
			$(metabox_class).addClass('metabox_container');
		})

		// Trigger the 'change' event when the mouse leaves the metabox.
		$(metabox_class).live('mouseleave', function() { $('.compat-item input:first').trigger('change');});

		// Sync the checkboxes to comma delimited list in the hidden text field for the taxonomy.
		$('.media-sidebar input').live('click', function(){

			var form_fields = $(this).closest("tbody");
			var checked = form_fields.find(".compat-field-" + tax + "_metabox input:checked");
			var slug_list = '';

			checked.each(function(index){

				if(slug_list.length > 0) 
						slug_list += ',' + $(this).val();
				else 
						slug_list += $(this).val();

			});

			form_fields.find("tr.compat-field-"+ tax +" > td.field > input.text").val(slug_list);
		})

		$.extend($.expr[":"], {
			"icontains": function(elem, i, match, array) {
				return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
			}
		});

	});
})