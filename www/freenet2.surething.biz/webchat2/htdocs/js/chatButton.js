// WebChat2.0 Copyright (C) 2006-2007, Chris Chabot <chabotc@xs4all.nl>
// Licenced under the GPLv2. For more info see http://www.chabotc.com

/************************** chatButton class implimentation ***********************************/
var chatButton = Class.create();
chatButton.prototype = {
	initialize: function(id) {
		this.hasEffectLib = String.prototype.parseColor != null;
		this.options = Object.extend({
			title       : 'button',
			onclick		: Prototype.emptyFunction,
			width       : 80,
			enabled     : true,
			visible     : true,
      		zIndex		: 100
    		}, arguments[1] || {});

    	this.divButton = id+'_button';
		this.divTitle  = id+'_button_title';
		this.createLayout();
		this.element   = $(this.divButton);
		this.element.setStyle({zIndex : this.options.zIndex});
		if (!this.options.visible) {
			this.element.hide();
		}
		if (!this.options.enabled) {
			this.disable();
		}
		this.clickEvent = this.options.onclick.bindAsEventListener(this);
		Event.observe(this.element, "click", this.clickEvent);
	},

	remove: function() {
		Event.stopObserving(this.divButton, "click", this.clickEvent);
		this.element.parentNode.removeChild(this.element);
	},

	shake: function() {
		Effect.shake(this.element);
	},

	fade: function() {
		Effect.Fade(this.element);
		this.visible = false;
	},

	appear: function {
		Effect.Appear(this.element);
		this.visible = true;
	},

	enable: function() {
		this.options.enabled = true;
		this.element.removeClassName('disabled');
	},

	disable: function() {
		this.options.enabled = false;
		this.element.addClassName('disabled');
	},

	show: function() {
		this.options.visible = false;
		Effect.show(this.element);
	},

	hide: function() {
		this.options.visible = true;
		Effect.hide(this.element);
	},

	setTitle: function(title) {
		this.options.title = title;
		$(this.divTitle).update(this.options.title);
	},

	setAction: function(action) {
		Event.stopObserving(this.divButton, "click", this.clickEvent);
		this.options.onclick = action;
		this.clickEvent = this.options.onclick.bindAsEventListener(this);
		Event.observe(this.divButton, "click", this.clickEvent);
	},

	isVisible: function() {
		return this.options.visible;
	},

	createLayout: function() {
		var div1 = document.createElement('DIV');
		div1.setAttribute('id', this.divButton);
		div1.className = 'button';
		var div2=document.createElement('DIV');
		div2.className = 'button_left';
		div1.appendChild(div2);
		var div3=document.createElement('DIV');
		div3.className = 'button_center';
		div1.appendChild(div3);
		var div4=document.createElement('DIV');
		div4.setAttribute('id', this.divTitle);
		div4.className = 'button_text';
		div3.appendChild(div4);
		var txt1 = document.createTextNode('Connect');
		div4.appendChild(txt1);
		var div5 = document.createElement('DIV');
		div5.className = 'button_right';
		div1.appendChild(div5);
	}
}
