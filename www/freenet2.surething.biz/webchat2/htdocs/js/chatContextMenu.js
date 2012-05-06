// WebChat2.0 Copyright (C) 2006-2007, Chris Chabot <chabotc@xs4all.nl>
// Licenced under the GPLv2. For more info see http://www.chabotc.com

/************************** chatChannel class implimentation ***********************************/
/* Based on "ContextMenu" by 2005 Nicolas Schmitt (MIT-style license).                         */
var chatContextMenu = Class.create();
chatContextMenu.prototype = {
    _currentMenu : null,

	initialize: function(element, menu) {
        this.options = Object.extend({
            allowed: null,
            duration: 0.3
        }, arguments[2] || {});
		this.element = $(element);
        this.menu    = $(menu);
        this.index   = 0;
        var list = this.menu.getElementsByTagName("LI");
        for (var i = 0; i < list.length; i++) {
            list[i].menuIndex = i;
            Element.addClassName(list[i], "menuitem");
            if (this.options.allowed != null && !this.options.allowed[i]) {
                Element.hide(list[i]);
            }
        }
        this.entryCount       = list.length;
        Event.observe(this.element, "chatContextMenu", this.onrightclick.bindAsEventListener(this));
        Event.observe(this.menu,    "mouseover",       this.onhover.bindAsEventListener(this));
	},

	destroy: function() {
		this.hide();
		Event.stopObserving(document, "click", this.onclicklistener);
	    Event.stopObserving(this.element, "chatContextMenu", this.onrightclick);
    	Event.stopObserving(this.menu,    "mouseover",       this.onhover);
    	Event.stopObserving(document, "click", this.onclicklistener);
	},

    hide: function() {
        chatContextMenu._currentMenu = null;
        Element.hide(this.menu);
    },

    onrightclick: function(event) {
        if (chatContextMenu._currentMenu != null) {
            chatContextMenu._currentMenu.hide();
        }
        this.index = 0;
        chatContextMenu._currentMenu = this;
        this.menu.setStyle({ left : Event.pointerX(event) + 'px', top : Event.pointerY(event) + 'px'});
        Effect.Appear(this.menu, {duration : this.options.duration});
        Event.observe(document, "click", this.onclicklistener);
        Event.stop(event);
    },

    onhover: function(event) {
        var element = Event.findElement(event, 'LI');
        if (this.index != element.menuIndex) {
            this.index = element.menuIndex;
            this.render();
        }
        Event.stop(event);
    },

    onclick: function(event) {
        Event.stopObserving(document, "click", this.onclicklistener);
        this.hide();
    },

    render: function() {
        var list = this.menu.getElementsByTagName("LI");
        for (var i = 0; i < list.length; i++) {
            if (this.index == i) {
                Element.addClassName(list[i], "selected");
            } else {
                Element.removeClassName(list[i], "selected");
            }
        }
    }
}
