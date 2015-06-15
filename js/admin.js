jQuery(document).ready(function() {	
	// Date pickers
	jQuery('.date-field').datepicker({
        dateFormat : 'yy-mm-dd'
    });
	
	jQuery('#dashboard-widgets .show-filters').css('display', 'inline-block');
	
	jQuery('#dashboard-widgets .show-filters').live('click', function(e) {
		e.preventDefault();
		jQuery('#dashboard-widgets .hide-filters').css('display', 'inline-block');
		jQuery('#dashboard-widgets .ha-filters').css('display', 'inline');
		jQuery('#dashboard-widgets .show-filters').css('display', 'none');
	});
	
	jQuery('.hide-filters').live('click', function(e) {
		e.preventDefault();
		jQuery('#dashboard-widgets .hide-filters').css('display', 'none');
		jQuery('#dashboard-widgets .ha-filters').css('display', 'none');
		jQuery('#dashboard-widgets .show-filters').css('display', 'inline-block');
	});
});

// Metabox toggle
jQuery(document).ready(function($){$(".if-js-closed").removeClass("if-js-closed").addClass("closed");
	postboxes.add_postbox_toggles( 'apmbt');
});

// Secondary button flags
jQuery(document).on('click', '#clear-database', function(e) {
	jQuery("#clear-database-flag").val("true");
});
jQuery(document).on('click', '#test-connection', function(e) {
	jQuery("#test-connection-flag").val("true");
});

// view heat map buttons
jQuery(document).on('click', '.view-heat-map-button', function(e) {
	e.preventDefault();
	var btnId = this.id; 
	
	// now we can lookup the url from a hidden input field given we have the rowId
	var url = jQuery("#" + btnId + "-url").val();
	
	var queryParams = "drawHeatmap=true";
	if (url.indexOf("?") >= 0) {
		url += "&";
	} else {
		url += "?";
	}
	url += queryParams;
	
	var pageWidth = jQuery("#" + btnId + "-page_width");
	var os = jQuery("#" + btnId + "-os");
	var browser = jQuery("#" + btnId + "-browser");
	var device = jQuery("#" + btnId + "-device");
	var userEventId = jQuery("#" + btnId + "-user_event_id");	
	
	if (userEventId && userEventId.val())
		url += "&userEventId=" + userEventId.val();	
	if (pageWidth && pageWidth.val())
		url += "&pageWidth=" + pageWidth.val();
	if (os && os.val())
		url += "&os=" + os.val();
	if (browser && browser.val())
		url += "&browser=" + browser.val();
	if (device && device.val())
		url += "&device=" + device.val();
	
	window.open(url, "_blank", 'scrollbars=yes, resizable=yes, location=yes, toolbar=yes');
});