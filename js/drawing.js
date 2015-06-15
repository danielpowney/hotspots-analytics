// constants
var MAX_COLOUR = 255;
var MIN_COLOUR = 0;
var zIndex = 1000;
var heatmap = null; // for heatmap.js


/**
 * Draws the heatmap canvas overlay and information panel
 */
var drawing = new function() {
	/**
	 * Sets up and initialises the drawing
	 */
	this.setupCanvas = function() {

		var docWidth = jQuery(document).width();
		var docHeight = jQuery(document).height();
		
		// Create a blank div where we are going to put the canvas into.
		var canvasContainer = document.createElement('div');
		document.body.appendChild(canvasContainer);
		canvasContainer.setAttribute("id", "canvasContainer");
		canvasContainer.style.left = "0px";
		canvasContainer.style.top = "0px";
		canvasContainer.style.zIndex = zIndex;
		
		if (isHeatmap) {
			canvasContainer.style.width = docWidth + "px"; // "100%";
			canvasContainer.style.height = docHeight + "px"; //"100%";
			canvasContainer.style.position = "absolute";
			 // heatmap configuration
			var config = {
				element : document.getElementById("canvasContainer"),
				radius : spotRadius,
				opacity : opacity * 100
			};
			// creates and initializes the heatmap
			heatmap = h337.create(config);
			heatmap.store.setDataSet({ max: hot, data : [] });
		} else {
			canvasContainer.style.position = "absolute";
			canvasContainer.style.width = "100%";
			canvasContainer.style.height = "100%";

			
			// create the canvas
			var canvas = document.createElement("canvas");
			canvas.setAttribute("id", "canvas");
			canvas.style.width = docWidth;
			canvas.style.height = docHeight;
			canvas.width = docWidth;
			canvas.height = docHeight;
			canvas.style.overflow = 'visible';
			canvas.style.position = 'absolute';
			
			canvasContainer.appendChild(canvas);		
		}
		
		// set opacity for all elements so that hot spots are visible
		jQuery("body *").each(function() {
			// if current element already already has opacity < 1, leave as is
			var opacity = jQuery(this).css("opacity");
			if (opacity !== undefined && opacity === 1) {
				jQuery(this).css({
					opacity : 0.99
				});
			}
			// check z-index to ensure heat map is overlayed on top of any element
			var tempZIndex = jQuery(this).css("z-index");
			if (tempZIndex != "auto" && parseInt(tempZIndex) > parseInt(zIndex)) {
				zIndex = parseInt(tempZIndex) + 1;
				var canvasContainer = jQuery("#canvasContainer");
				canvasContainer.css("z-index", zIndex);
			}
		});
	};
	
	/**
	 * Initilises the drawing of the heatmap
	 */
	this.init = function(isHeatmap) {
		
		// Remove the WordPress admin bar and margin style
		jQuery('#wpadminbar').remove();
		var css = 'html { margin-top: 0px !important; } * html body { margin-top: 0px !important; }';
		var head = document.head || document.getElementsByTagName('head')[0];
		style = document.createElement('style');
		style.type = 'text/css';
		if (style.styleSheet){
			style.styleSheet.cssText = css;
		} else {
			style.appendChild(document.createTextNode(css));
		}
		head.appendChild(style);

		// Overlay canvas
		this.setupCanvas();
	};
	
	/**
	 * Draws a mouse click or touchscreen tap on the canvas in a colour based on
	 * the heat value
	 * 
	 * @param xCoord
	 * @param yCoord
	 * @param heatValue
	 */
	this.drawArc = function(xCoord, yCoord, heatValue) {
		
		var canvas = jQuery("#canvas").get(0);
		var context = canvas.getContext("2d");
		context.beginPath();
		context.arc(xCoord, yCoord, spotRadius, 0, 2 * Math.PI);

		/* 
		 * Calculates RGB colour for corresponding heat value. From Green to Red, 
		 * therefore Blue is always 0.
		 * Green is cold, Orange is warm and Red is hot
		 * Green is 0, 255, 0 and Red is 255, 0, 0. In between is 255, 255, 0
		 */
		var fillStyle = null;
		if (heatValue === 0) { // green
			fillStyle = "rgba(" + MIN_COLOUR + ", " + MAX_COLOUR + ", "
					+ MIN_COLOUR + ", " + opacity + ")";
		} else if (heatValue === warm) { // orange
			fillStyle = "rgba(" + MAX_COLOUR + ", " + MAX_COLOUR + ", "
					+ MIN_COLOUR + ", " + opacity + ")";
		} else if (heatValue >= hot) { // red
			fillStyle = "rgba(" + MAX_COLOUR + ", " + MIN_COLOUR + ", "
					+ MIN_COLOUR + ", " + opacity + ")";
		} else { // in between
			if (heatValue > warm) { // more red
				var someGreen = MAX_COLOUR
						- (MAX_COLOUR * ((heatValue - warm) / warm));
				fillStyle = "rgba(" + MAX_COLOUR + ", " + Math.round(someGreen)
						+ ", " + MIN_COLOUR + ", " + opacity + ")";
			} else { // more green
				var someRed = MAX_COLOUR * (heatValue / warm);
				fillStyle = "rgba(" + Math.round(someRed) + ", " + MAX_COLOUR
						+ ", " + MIN_COLOUR + ", " + opacity + ")";
			}
		}

		context.fillStyle = fillStyle;
		context.fill();
	};
	
	/** 
	 * Draws the heatmap or plots the events on the canvas
	 */
	this.doDrawing = function(events) {
		
		for (var index in events) {
			var eventData = events[index];
			if (eventData.event_type == "mouse_click" || eventData.event_type == "touchscreen_tap") {
				if (isHeatmap) {
					heatmap.store.addDataPoint(eventData.x_coord, eventData.y_coord, 1);
				} else {
					// draw the mouse click or touch screen tap on the canvas
					this.drawArc(eventData.x_coord, eventData.y_coord, eventData.heat_value);
				}
			}
		}
	};
	
	/**
	 * Draws heatmap
	 * 
	 */
	this.drawHeatmap = function() {	
		utils.showLoadingDialog(false);

		// remove hash tags from URL
		var url = window.location.href;
		var hashIndex = url.indexOf('#');
		if (hashIndex > 0) {
			url = url.substring(0, hashIndex);
		}
		
		var userEventId = utils.getUrlParamByName(url, "userEventId");
		var device = utils.getUrlParamByName(url, "device");
		var browser = utils.getUrlParamByName(url, "browser");
		var os = utils.getUrlParamByName(url, "os");
		
		var data = {
			action : "retrieve_user_events",
			nonce : config_data.ajax_nonce,
			pluginVersion : config_data.plugin_version,
			
			url : url,
			pageWidth : utils.getPageWidth(),
			userEventId : (userEventId !== undefined && userEventId !== "") ? userEventId : null,
			device : (device !== undefined && device !== "") ? device : null,
			os : (os !== undefined && os !== "") ? os : null,
			browser : (browser !== undefined && browser !== "") ? browser : null,
			userId : config_data.user_id,
			userEnvironmentId : config_data.user_environment_id,
			eventTypes : [ "mouse_click", "touchscreen_tap" ],
			ipAddress : config_data.ip_address,
			sessionId : config_data.session_id,
			debug : debug,
			drawHeatMapEnabled : drawHeatmapEnabled,
			spotRadius : spotRadius,
			ignoreWidth: config_data.ignore_width,
			widthAllowance : config_data.width_allowance,
			ignoreDevice : config_data.ignore_device,
			ignoreOs : config_data.ignore_os,
			ignoreBrowser : config_data.ignore_browser,
			hideRoles : config_data.hide_roles,
		};
		
		
		jQuery.get(config_data.ajax_url, data, function(response) {
			var events = jQuery.parseJSON(response);
			drawing.doDrawing(events);
			utils.closeLoadingDialog();
		});
	};
	
	this.destroy = function() {
		jQuery("#canvasContainer").remove();
		//jQuery("#infoPanel").remove();
	};
	
	/**
	 * Adds a small information panel to the bottom right corner with width, 
	 * height, zoom level and device pixel ratio
	 */
	this.setupInfoPanel = function(refresh) {
		
		jQuery("<div id='infoPanel'>" +
				"Page Width: <div id='infoPageWidth' style='display: inline-block' />px, " +
				"Browser: <div id='infoBrowser' style='display: inline-block'/>, " +
				"OS: <div id='infoOS' style='display: inline-block'/>, " +
				"Device: <div id='infoDevice' style='display: inline-block'/>. " +
				"<a href='#' id='infoCloseLink'>Close</a> | " +
				"<a href='#' id='infoClearLink'>Clear</a>" +
				"</div>").appendTo("body");
		
		jQuery("#infoCloseLink").on("click", function() {
			jQuery("#infoPanel").remove();
		});
		
		jQuery("#infoClearLink").on("click", function() {
			jQuery("#infoPanel").remove();
			jQuery("#canvasContainer").remove();
			drawHeatmapEnabled = false;
		});
	
		if (refresh) {
			this.refreshInfoPanel();
		}
		
		// Add 1 to zIndex so it shows on top of the canvas
		jQuery("#infoPanel").css("z-index", zIndex + 1);
		jQuery("#infoPanel *").css("z-index", zIndex + 1);
		
		// get query params
		var url = window.location.href;
		var widthQueryParam = utils.getUrlParamByName(url, "pageWidth");
			// current data
		var pageWidth = utils.getPageWidth();
		
		var osQueryParam =  utils.getUrlParamByName(url, "os");
		var browserQueryParam =  utils.getUrlParamByName(url, "browser");
		var deviceQueryParam =  utils.getUrlParamByName(url, "device");
		
		var message = "";
		if (widthQueryParam !== undefined && widthQueryParam !== "" && pageWidth != widthQueryParam) {
			message += "Target page width is " + widthQueryParam + "px. ";
		}
		if (osQueryParam !== undefined && osQueryParam !== "" && config_data.os != osQueryParam) {
			message += "Target operating system is " + osQueryParam + ". ";
		}
		if (browserQueryParam !== undefined && browserQueryParam !== "" && config_data.browser != browserQueryParam) {
			message += "Target browser is " + browserQueryParam + ". ";
		}
		if (deviceQueryParam !== undefined && deviceQueryParam !== "" && config_data.device != deviceQueryParam) {
			message += "Target device is " + deviceQueryParam + ".";
		}
		
		if (message.length > 0)
			message = "<p style='color: Orange;'>" + message + "</p>";
			jQuery(message).appendTo("#infoPanel");
	};
	
	/**
	 * Refreshes the information panel with current width, zoom level and device
	 * pixel ration data
	 */
	this.refreshInfoPanel = function() {
		var pageWidth = utils.getPageWidth();
		
		jQuery("#infoPageWidth").html(pageWidth);		
		jQuery("#infoBrowser").html(config_data.browser);
		jQuery("#infoOS").html(config_data.os);
		jQuery("#infoDevice").html(config_data.device);
		
	};
};