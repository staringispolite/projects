// Thanks for doing your part to liber(IE)t the world from IE6!
// Original copy written by Jonathan Howard (jon@StaringIsPolite.com)
//
// GNU LGPL License v3
// SevenUp is released into the wild under a GNU LGPL v3
//
// Browser sniffing technique lovingly adapted from http://www.thefutureoftheweb.com/
// Simple CSS Lightbox technique adapted equally lovingly from http://www.emanueleferonato.com/
// Go read their blogs :)

// Constructor technique advocated by Doug Crockford (of LSlint, JSON) in his recent Google tech talk.
var sevenUp = function () {
  // Define 'private vars' here.
	var osSupportsUpgrade = /(Windows NT 5.1|Windows NT 6.0|Windows NT 6.1|)/i.test(navigator.userAgent); // XP, Vista, Win7
  function enableClosing(custom) {
    return (custom !== undefined && custom !== null) ? custom : true;
  }
  function enableQuitBuggingMe(custom) {
    return (custom !== undefined && custom !== null) ? custom : true;
  }
  // Change these to fit your color scheme via the 'options' arg for test().
	function overlayColor(custom) {
    return custom ? custom : "#000000";
  }
	function lightboxColor(custom) {
    return custom ? custom : "#ffffff";
	}
  function borderColor(custom) {
    return custom ? custom : "#6699ff";
  }
	function downloadLink(custom) {
    if (!custom) {
      custom = osSupportsUpgrade ? 
               "http://www.google.com/toolbar/ie7/" : "http://getfirefox.com";
    }
    return custom;
  }
  // Hate to define CSS this way, but trying to keep to one file.
  // I'll keep it as pretty as possible.
  function overlayCSS(color) {
    return "display: block; position: absolute; top: 0%; left: 0%;" +
    "width: 100%; height: 100%; background-color: " + overlayColor(color) + "; " +
    "filter: alpha(opacity: 80); z-index:1001;";
  }
  function lightboxCSS(bColor, lbColor) { 
    return "display: block; position: absolute; top: 25%; left: 25%; width: 50%; " +
    "height: 50%; padding: 16px; border: 8px solid " + borderColor(bColor) + "; " +
    "background-color:" + lightboxColor(lbColor) + "; " +
    "z-index:1002; overflow: hidden;";
  }
  function lightboxContents(html, customDownloadLink, customEnableClosing,
                            customEnableQuitBuggingMe) {
    if (!html) {
      html =
      "<div style='width: 100%; height: 95%'>" +
        "<h2>Your web browser is outdated and unsupported</h2>" +
        "<div style='text-align: center;'>" +
          "You can easily upgrade to the latest version at<br> " +
          "<a style='color: #0000EE' href='" + downloadLink(customDownloadLink) + "'>" +
            downloadLink(customDownloadLink) +
          "</a>" +
        "</div>" +
        "<h3 style='margin-top: 40px'>Why should I upgrade?</h3>" +
        "<ul>" +
          "<li><b>Websites load faster</b>, often double the speed of this older version</li>" +
          "<li><b>Websites look better</b> with more web standards compliance</li>" +
          "<li><b>Tabs</b> let you view multiple sites in one window</li>" +
          "<li><b>Safer browsing</b> with phishing protection</li>" +
          "<li><b>Convenient Printing</b> with fit-to-page capability</li>" +
        "</ul>" +
      "</div>";
      if (enableClosing(customEnableClosing)) {
        html += "<div style='font-size: 11px; text-align: right;'>";
        html += (enableQuitBuggingMe(customEnableQuitBuggingMe)) ?
        ("<a href='#' onclick='sevenUp.quitBuggingMe();' " +
            "style='color: #0000EE'>" +
            "Quit bugging me" +
        "</a>")
        :
        ("<a href='#' onclick='sevenUp.close();' " +
            "style='color: #0000EE'>" +
            "close" +
          "</a>");
        html += "</div>";
      }
    }
    return html;
  }
  function isCookieSet() {
    if (document.cookie.length > 0) {
      var i = document.cookie.indexOf("sevenup=");
      return (i != -1);
    }
    return false;
  }
  
  // Return object literal and public methods here.
  return {
  	test: function(options, callback) {
  	  if (!isCookieSet()) {
  	    // Write layer into the document.
  	    var layerHTML =
  	      "<!--[if lt IE 7]>" +
          "<div id='sevenUpOverlay' style='" + overlayCSS(options.overlayCSS) + "'>" +
  	        "<div style='" + lightboxCSS(options.borderColor, options.lightboxCSS) + "'>" +
              lightboxContents(options.html, options.downloadLink,
                               options.enableClosing,
                               options.enableQuitBuggingMe) +
            "</div>" +
  		    "</div>" +
          "<script type='text/javascript'>sevenUp.callback()</script>" +
          "<![endif]-->";
        var layer = document.createElement('div');
        layer.innerHTML = layerHTML;
  	    document.body.appendChild(layer);
        if (callback) {
          this.callback = callback;
        }
  	  }  
  	},
    quitBuggingMe: function() {
      var exp = new Date();
      exp.setTime(exp.getTime()+(7*24*3600000));
      document.cookie = "sevenup=dontbugme; expires="+exp.toUTCString();
      this.close();
    },
    close: function() {
      var overlay = document.getElementById('sevenUpOverlay');
      if (overlay) {
        overlay.style.display = 'none';
      }
    },
    callback: function() { }
  };
}();

