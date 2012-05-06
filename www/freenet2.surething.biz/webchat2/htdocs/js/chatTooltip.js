// WebChat2.0 Copyright (C) 2006-2007, Chris Chabot <chabotc@xs4all.nl>
// Licenced under the GPLv2. For more info see http://www.chabotc.com

/************************** chatchatTooltip class implimentation ***********************************/
/* Based on "chatTooltip-0.1" by Jonathan Weiss <jw@innerewut.de>                                  */
var chatTooltip = Class.create();
chatTooltip.prototype = {
  initialize: function(element, tool_tip) {
    var options = Object.extend({
      default_css     : false,
      margin          : "0px",
	  padding         : "5px",
	  backgroundColor : "#d6d6fc",
	  delta_x         : 5,
	  delta_y         : 5,
      zindex          : 1000
    }, arguments[1] || {});
    this.element      = $(element);
    this.tool_tip     = $(tool_tip);
    this.options      = options;
    this.tool_tip.hide();
    this.eventMouseOver = this.showchatTooltip.bindAsEventListener(this);
    this.eventMouseOut   = this.hidechatTooltip.bindAsEventListener(this);
    this.registerEvents();
  },

  destroy: function() {
    Event.stopObserving(this.element, "mouseover", this.eventMouseOver);
    Event.stopObserving(this.element, "mouseout", this.eventMouseOut);
  },

  registerEvents: function() {
    Event.observe(this.element, "mouseover", this.eventMouseOver);
    Event.observe(this.element, "mouseout", this.eventMouseOut);
  },

  showchatTooltip: function(event) {
	Event.stop(event);
    var mouse_x = Event.pointerX(event);
	var mouse_y = Event.pointerY(event);
	var dimensions = Element.getDimensions( this.tool_tip );
	var element_width = dimensions.width;
	var element_height = dimensions.height;
	if ( (element_width + mouse_x) >= ( this.getWindowWidth() - this.options.delta_x) ) { // too big for X
		mouse_x = mouse_x - element_width;
		mouse_x = mouse_x - this.options.delta_x;
	} else {
		mouse_x = mouse_x + this.options.delta_x;
	}
	if ( (element_height + mouse_y) >= ( this.getWindowHeight() - this.options.delta_y) ) { // too big for Y
		mouse_y = mouse_y - element_height;
		mouse_y = mouse_y - this.options.delta_y;
	} else {
		mouse_y = mouse_y + this.options.delta_y;
	}
	this.setStyles(mouse_x, mouse_y);
	new Effect.Appear(this.tool_tip, { duration: 0.2 });
	//new Element.show (this.tool_tip);
  },

  setStyles: function(x, y) {
	Element.setStyle(this.tool_tip, { position:'absolute',
	 								  top:y + "px",
	 								  left:x + "px",
									  zindex:this.options.zindex
	 								});
	if (this.options.default_css) {
	  	Element.setStyle(this.tool_tip, { margin:this.options.margin,
		 								  padding:this.options.padding,
		                                  backgroundColor:this.options.backgroundColor,
										  zindex:this.options.zindex
		 								});
	}
  },

  hidechatTooltip: function(event) {
	new Effect.Fade(this.tool_tip, {duration : 0.2});
	//new Element.hide(this.tool_tip);
  },

  getWindowHeight: function() {
    var innerHeight = (document.body.clientHeight || window.innerHeight);
    return innerHeight;
  },

  getWindowWidth: function() {
    var innerWidth = (document.body.clientWidth || window.innerWidth);
    return innerWidth;
  }
}