=== Hotspots Analytics ===
Contributors: dpowney
Donate link: http://www.danielpowney.com/donate
Tags: mouse click, tap, touch, click, usability, heat map, tracker, analytic, analytics, user, track, tracking, impression, funnel, conversion, link, responsive, device, Google
Requires at least: 3.0.1
Tested up to: 4.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The most advanced analytics plugin for WordPress websites including heatmaps, user activity and custom event tracking.

== Description ==

Hotspots Analytics is the most advanced analytics plugin for WordPress websites including heatmaps, user activity and custom event tracking. It can be a great compliment to Google Analytics and provides support for responsive web design and touchscreen devices.

The key features of the plugin are:

*   Heatmaps of mouse clicks and touch screen taps overlayed on your webpage (You can launch the heatmap for a page from wp-admin or simply add ?drawHeatmap=true to the URL)
*   Different heatmaps are drawn to cater for responsive web design
*   Each page on your website has it's own heatmap
*   Track user activity including page views, AJAX actions, mouse clicks, touchscreen taps and custom JavaScript events
*   It's free and there's no sign up or registration required!
*   All data is stored on your own WordPress database
*   All data is viewed in your WordPress admin. No need to go to a third party website.
*   To be able to view the heatmaps, your WordPress theme must be HTML5 compliant and you need to use an Internet browser which supports HTML5 canvas.

Here's a demo in debug mode: http://danielpowney.com/hotspots-analytics?drawHeatmap=true

The plugin should not be used where performance is critical as an additional server request is made for each mouse click, touch screen tap, AJAX action, page view and custom event.

Github Repo: https://github.com/danielpowney/hotspots-analytics

= Heatmaps =
There are two types of heatmaps, a confetti heatmap with spots and heatmap.js. Each heatmap shows the density heat of mouse clicks and or touch screen taps from green which is cool to red which is hot. Heatmap.js is an open source JavaScript library for drawing heatmaps using HTML5 canvas.

Heatmaps can be launched from the WordPress admin plugin page by clicking on the "View Heatmap" button or by adding the query string parameter drawHeatmap=true to your website URL ( i.e. http://danielpowney.com?drawHeatmap=true). There are different heatmaps for each page, page widht, browser, operating system and device to cater for responsive web design and for filtering. 

When the heatmap is displayed, a HTML5 canvas drawing is overlayed on the website. An additional information panel is also provided at the bottom right of the screen which provides the current page width, browser, device etc... If you resize the page width, a different heatmap will be displayed. A width allowance setting can be changed to allow for some overlap in page widths.

= User Activity =

A comprehensive summary of all user activity on your website can be viewed including a sequence list of mouse clicks, touch screen taps, page views, AJAX actions and custom defined events. Additional information is provided such as time elapsed since previous event and the individual mouse clicks and or touch screen taps can viewed overlayed on the web page to show you exactly what was clicked or tapped.

= Reports =
There are various reports which provide statistics and graphs of all user activity on your website. Each report can be filtered for different devices, browsers, operating systems, page widths and within a period of time (today, yesterday, last week, last 30 days or last 60 days). The following reports are available:

*   Event comparison line graph which compares selected events over time
*   Event line graph which details events over time
*   Event statistics table which shows the total counts, averages per user and averages per page for all event types
*   Events total bar graph which shows the total counts of events

= Custom Events =

Custom events can be added for mouse clicks, touch screen taps or form submits on any HTML element selected using a jQuery selector. Alternatively you can use JavaScript to save custom events using the saveEvent function of the global hotspots object 

i.e. hotspots.saveEvent('my_event','My description', 'My misc data');

== Installation ==

1. Download the plugin and put it in the WordPress plugins directory
1. Activate the plugin through the Plugins page in WordPress
1. Go to the Hotspots Analytics plugin page

== Screenshots ==

1. Confetti heatmap with spots
2. heatmap.hs
3. Heatmaps table
4. User activity summary
5. User activity sequence table
6. Users table
7. General settings
8. Heatmap settings
9. Report 1
10. Report 2
11. Report 3
12. Report 4

== Frequently Asked Questions ==

**Why can't I see the heatmap when adding URL query parameter drawHeatmap=true.**

You cannot view the heatmaps if your theme is not HTML5 compliant and you need to use an Internet browser which supports HTML5 canvas. Most modern browsers support HTML5 canvas now. Your theme also needs to be HTML5 compliant. Make sure the *Enable drawing heatmap* option is checked on in the General options tab. An information panel should be displayed at the bottom right overlayed on your website when the heatmap is displayed. Try resizing your page widths as per the page widths in the heatmaps table in the plugin page.

**I just updated the plugin and it does not seem to be working**

If you're using a caching plugin such as W3TC then empty all page cache. Also empty your browser cache to ensure the latest JavaScript files are loaded. Also try to deactivate and then reactivate the plugin.

**Does the plugin impact performance?**

The free plugin may have a small impact on your website performance as an additional server request is made for each mouse click, touch screen tap, AJAX action, page view and custom event. For websites with a low usage, this performance impact is negligable to the user. However you can purchase two plugins which allow you to direct all user activity events to be saved on a remote host and database to reduce load on your server. For more information, visit http://danielpowney.com/downloads/hotspots-analytics-remote-bundle/

**Can I make the plugin only work on certain pages?**

Yes. There are settings to setup URL filters for enabling or disabling saving events on different pages.

**Can I clear all of the data?**

Yes, there is an option to clear all data from the database

**Why aren't all of my mouse clicks or touchscreen taps being saved?**

Occasionally mouse clicks and touchscreen taps are not saved as the browser event can take over the AJAX call (cancel it) when navigating to a different page for example. So it can be a little hit and miss and it's up to the browser. Howeber this does not seem to occur very often. Also, if the JavaScript on the page has not finished loading and you quickly click on a link, then the mouse click or touchscreen tap may not be saved.

**Why is the page width not the same as when I view the heatmap via the WordPress admin**

Sometimes the browser adds a vertical scrollbar which is subtracted from the page width. E.g. 17px for Firefox. So if you wanted to view 1600px and when viewing the hea map the expanded browser window is 1583px with a vertical scrollbar of 17px, change the width allowance option in the heatmap settings tab to allow the mouse clicks and touchscreen taps to be displayed for 17px difference.

**Do I have to resize the window to the exact page width?**

No. There is an setting called width allowance which allows up to 20 pixels each side of your target page width to display the heatmap. This amount can be changed and is defaulted to 6 pixels.

**How do I add a custom event?**

You will need to understand jQuery selectors first here: http://api.jquery.com/category/selectors/. You can bind a mouse click, touchscreen tap, or form submit event to any HTML elements on the pahe by using jQuery selectors. 

== Changelog ==

= 4.0.12 (16/07/2016) =
* Tweak: Added filters to ignore custom URL query parameters and also to modify the normalized URL
* Bug: Fixed activation error in dbdelta when creating indexes

= 4.0.11 (05/04/2016) =
* Bug: Fixed extra space in relative root path when loading admin assets which is causing some issues
* Bug: Removed usage of mysql_real_escape_string() in admin tables

= 4.0.10 (07/07/2015) =
* Bug: Fixed unable to create custom events db table due to stray comma in SQL statement

= 4.0.9 (15/06/2015) =
* Removed modernizer check in JS
* Optimized db index performance
* Make jQuery UI calls protocol agnostic

= 4.0.8 (11/04/2014) =
* Replaced deprecated jQuery live with on 

= 4.0.7 (25/01/2014) =
* Added event_type filters to users table

= 4.0.6 (25/01/2014) =
* Fixed report bug with query filters

= 4.0.5 (22/01/2014) =
* Fixed page width retrieve events bug

= 4.0.4 (22/01/2014) =
* Improved reports
* Added event types as a query filter

= 4.0.3 (14/01/2014) =
* Improved and refactored calls to data services
* Removed remote settings view

= 4.0.2 (12/01/2014) =
* Fixed plugin prefix bug

= 4.0.1 (11/01/2014) =
* Fixed custom event bug for binding form submits

= 4.0 (08/01/2014) =
* Major release
* Refactored code to use a new data model. Old data will not be migrated to the new data model.
* Removed device pixel ratio and zoom level logic due to compatibility issues
* Improved admin UI
* Added more reports
* Added function to manually create custom events in JavaScript
* Added remote client settings
