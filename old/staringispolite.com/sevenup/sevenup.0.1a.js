// Thanks for doing your part to liber(IE)t the world from IE6!
// Original copy written by Jonathan Howard (jon@StaringIsPolite.com)
//
// GNU LGPL License v3
// SevenUp is released into the wild under a GNU LGPL v3
//
// Browser sniffing lovingly ripped from http://www.thefutureoftheweb.com/
// Simple CSS Lightbox technique from http://www.emanueleferonato.com/
// Go read their blogs :)

var showReasons = false;  // Show users some quick reasons why they SHOULD care/upgrade.
var allowSkip = false;    // Allow users to get back to the website without upgrading.
var downloadLink = "http://www.google.com/toolbar/ie7/";
                          // Google has a custom version of IE7 it's pushing. Why not help?
var layerID = "sevenuplbc"; // Change if for some reason this ID exists already.
var needUpgrade = /(MSIE 6|MSIE 5.(\d+))/i.test(navigator.userAgent); // is IE6??
var overlayColor  = "#000000"; // Defaults to black. Change to fit your color scheme.
var lightboxColor = "#ffffff"; // Defaults to white. Change to fit your color scheme.
var borderColor   = "#6699ff";    // Change to fit your color scheme.

document.onload = function() {
  if (needUpgrade) {
    // Hate to define CSS this way, but trying to keep to one file.
    // I'll keep it as pretty as possible.
    var overlayCSS =  
      "display: block; position: absolute; top: 0%; left: 0%;" +
      "width: 100%; height: 100%; background-color: " + overlayColor + "; " +
      "z-index:1001; -moz-opacity: 0.8; opacity:.80; filter: alpha(opacity=80);";
    var lightboxCSS = 
      "display: block; position: absolute; top: 25%; left: 25%; width: 50%; " +
      "height: 50%; padding: 16px; border: 8px solid " + borderColor + "; " +
	  "background-color:" + lightboxColor + "; " +
	  "z-index:1002; overflow: auto;";
  
    // Write layer into the document.
    var layerHTML =
      "<div style='" + overlayCSS + "'>" +
        "<div style='" + lightboxCSS + "'>" +
	      "<h2>Your web browser is outdated and unsupported</h2>" +
	      "<div>" +
	        "You can upgrade to the latest version of IE " +
	        "<a href='" + downloadLink + "'>here</a>" +
	      "</div>" +
	    "</div>" +
	  "</div>";
    document.write(layerHTML);
  }  
}
