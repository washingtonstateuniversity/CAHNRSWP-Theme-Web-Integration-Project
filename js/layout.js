jQuery(document).ready(function ($) {

  if ($('#wipDynamic').is(':checked')) {
    $("#_dynamic_metabox").show();
		$("#slide-option").show();
  } else {
    $("#_dynamic_metabox").hide();
		$("#slide-option").hide();
  }
	$('#wipDynamic').click(function() {
    $("#_dynamic_metabox").toggle(this.checked);
		$("#slide-option").toggle(this.checked);
  });
	
	if ($('#slideshow').is(':checked')) {
    $("#slide-order").show();
		$("#wip-h").show();
  } else {
    $("#slide-order").hide();
		$("#wip-h").hide();
  }
	$('#slideshow').click(function() {
		$("#slide-order").toggle(this.checked);
		$("#wip-h").toggle(this.checked);
  });

  if ($('#wip-layout-0').is(':checked')) {
		$("#wip-m").css('width','100%');
		 $("#wip-s").hide();
		 $("#wip-a").hide();
		 $("#wip-f").hide();
	}
	$("#wip-layout-0").click(function (event) {
		 $("#wip-m").css('width','100%');
		 $("#wip-s").hide();
		 $("#wip-a").hide();
		 $("#wip-f").hide();
	});

  if ($('#wip-layout-1').is(':checked')) {
		$("#wip-m").css({'display': '','width': '66%'});
    $("#wip-s").css({'display': '','width': '34%'});
		$("#wip-a").hide();
		$("#wip-f").hide();
	}
	$("#wip-layout-1").click(function (event) {
    $("#wip-m").css({'display': '','width': '66%'});
    $("#wip-s").css({'display': '','width': '34%'});
		$("#wip-a").hide();
		$("#wip-f").hide();
	});

  if ($('#wip-layout-2').is(':checked')) {
		$("#wip-m").css('width','50%');
		$("#wip-s").css({'display': '','width' : '50%'});
		$("#wip-a").hide();
	}
	$("#wip-layout-2").click(function (event) {
    $("#wip-m").css('width','50%');
		$("#wip-s").css({'display': '','width' : '50%'});
		$("#wip-a").hide();
		$("#wip-f").hide();
	});

  if ($('#wip-layout-3').is(':checked')) {
		$("#wip-m").css('width','34%')
		$("#wip-s").css({'display': '','width': '66%'});
		$("#wip-a").hide();
		$("#wip-f").hide();
	}
	$("#wip-layout-3").click(function (event) {
    $("#wip-m").css('width','34%')
		$("#wip-s").css({'display': '','width': '66%'});
		$("#wip-a").hide();
		$("#wip-f").hide();
	});

  if ($('#wip-layout-4').is(':checked')) {
	  $("#wip-m").css('width','33%');
		$("#wip-s").css({'display': '','width': '33%'});
		$("#wip-a").css({'display': '','width': '34%'});
		$("#wip-f").hide();
	}
	$("#wip-layout-4").click(function (event) {
    $("#wip-m").css('width','33%');
		$("#wip-s").css({'display': '','width': '33%'});
		$("#wip-a").css({'display': '','width': '34%'});
		$("#wip-f").hide();
	});
	
	if ($('#wip-layout-5').is(':checked')) {
	  $("#wip-m").css('width','25%');
		$("#wip-s").css({'display': '','width': '25%'});
		$("#wip-a").css({'display': '','width': '25%'});
		$("#wip-f").css({'display': '','width': '25%'});
	}
	$("#wip-layout-5").click(function (event) {
    $("#wip-m").css('width','25%');
		$("#wip-s").css({'display': '','width': '25%'});
		$("#wip-a").css({'display': '','width': '25%'});
		$("#wip-f").css({'display': '','width': '25%'});
	});
	
	/*
	$(".ui-sortable").sortable({ revert: true,
		update: function( event, ui ) {
			$("#" + $(this).attr("id") + "order").val($(this).sortable('toArray').toString());
		}
	});
	*/

});