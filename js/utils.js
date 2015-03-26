/**
 * Contains utility functions
 */
var utils = new function() {
	
	/**
	 * Retrieves an array of URL query string parameters in order
	 * @param url
	 * @returns params
	 */
	this.getUrlParams = function(url) {
		
		// ignore hash # in URL when retrieving params
	    var hashIndex = url.indexOf('#');
	    if (hashIndex > 0) {
	    	url = url.substring(0, hashIndex);
	    }
		
		var params = [], hash;
		if (url.indexOf("?") !== -1) {
			var hashes = url.slice(url.indexOf('?') + 1).split('&');
			for ( var i = 0; i < hashes.length; i++) {
				hash = hashes[i].split('=');
				params.push(hash[0]);
				params[hash[0]] = hash[1];
			}
		}
		return params;
	};
	
	/**
	 * Gets a URL query string parameter by name
	 * 
	 * @param url
	 * @param name
	 * @returns url query string parameter
	 */
	this.getUrlParamByName = function(url, name) {
		return this.getUrlParams(url)[name];
	};
	

	/**
	 * Returns the x and y coordinates of the event
	 * @param event
	 * @returns x and y coordinates
	 */
	this.getEventXYCoords = function(event) {
		var xCoord = '';
		var yCoord = '';
			if ((event.clientX || event.clientY) && document.body
				&& document.body.scrollLeft != null) {
			xCoord = event.clientX + document.body.scrollLeft;
			yCoord = event.clientY + document.body.scrollTop;
		}
		if ((event.clientX || event.clientY) && document.compatMode == 'CSS1Compat'
				&& document.documentElement
				&& document.documentElement.scrollLeft != null) {
			xCoord = event.clientX + document.documentElement.scrollLeft;
			yCoord = event.clientY + document.documentElement.scrollTop;
		}
		if (event.pageX || event.pageY) {
			xCoord = event.pageX;
			yCoord = event.pageY;
		}
		
		if (yCoord != '') {
			if (jQuery('#wpadminbar').length > 0) {
				yCoord -= jQuery('#wpadminbar').height();
			}
		}
		return { xCoord : xCoord, yCoord : yCoord };
	};


	/**
	 * Returns the remaining horizontal scroll width available. It does not include
	 * the actual scrollbar.
	 * 
	 * @returns remaining scrolling width
	 */
	this.getRemainingScrollWidth = function() {
		if ('scrollMaxX' in window) { // only supported by Firefox
			return window.scrollMaxX;
		} else {
			return (document.documentElement.scrollWidth - document.documentElement.clientWidth);
		}
	};


	/**
	 * Calculates the vertical scrollbar width
	 * 
	 * @returns scrollbar width
	 */
	this.getVerticalScrollbarWidth = function() {
	
		var scrollDiv = document.createElement("div");
		scrollDiv.className = "scrollbar-measure";
		scrollDiv.style.width = "100px";
		scrollDiv.style.height = "100px";
		scrollDiv.style.overflow = "scroll";
		scrollDiv.style.position = "absolute";
		scrollDiv.style.top = "-9999px";
		document.body.appendChild(scrollDiv);
	
		// Get the scrollbar width
		var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth;
	
		// Delete the DIV 
		document.body.removeChild(scrollDiv);
		
		return scrollbarWidth;
	};


	/**
	 * Returns the inner width of the window, then subtracts vertical 
	 * scrollbar width and adds any remaining horizontal scroll.
	 * 
	 * @returns width of the page
	 */
	this.getPageWidth = function() {
		var pageWidth = 0;
		//if ("ontouchstart" in window) { // Mobiles
		//	// FIXME iOS does not flip dimensions when orientation is changed
		if (window.innerWidth) {
			if (typeof window.chrome === "object") { // hack for Chrome browser
				pageWidth = self.outerWidth;
			} else {
				pageWidth = window.innerWidth;
			}
		} else if (document.documentElement
				&& document.documentElement.clientWidth != 0) {
			pageWidth = document.documentElement.clientWidth;
		} else if (document.body) {
			pageWidth = document.body.clientWidth;
		}
		
		// Exclude vertical scrollbar width and add any remaining horizontal scroll
		if (pageWidth > 0) {
			pageWidth += this.getRemainingScrollWidth();
			// do not add vertical scrollbar width for Firefox??????/
			if (this.hasVerticalScrollbar()) { // && !jQuery.browser.mozilla) {
				pageWidth -= this.getVerticalScrollbarWidth();
			}
		}
		return pageWidth;
	};

	/**
	 * Checks if a vertical scrollbar exists
	 * 
	 * @returns true if a vertical scrollbar exists, otherwise, false
	 */
	this.hasVerticalScrollbar = function() {
		// Check if body height is higher than window height
		if (jQuery(document).height() > jQuery(window).height()) { 
			return true;
		}
		return false;
	};
	
	/**
	 * Shows the loading dialog
	 * 
	 * @param create the DIV element
	 */
	this.showLoadingDialog = function(createDiv) {
		if (createDiv) {
			jQuery("<div id='loadingDialog' title=\"Loading...\">" +
					"<p>Loading heat map...</p>" +
					"</div>").appendTo("body");
		}
		jQuery("#loadingDialog").dialog( { autoOpen: false });
		
	};
	
	/**
	 * Closes the loading dialog
	 */
	this.closeLoadingDialog = function() {
		jQuery("#loadingDialog").dialog('close');
	};

};