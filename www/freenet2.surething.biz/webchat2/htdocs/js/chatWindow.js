// WebChat2.0 Copyright (C) 2006-2007, Chris Chabot <chabotc@xs4all.nl>
// Licenced under the GPLv2. For more info see http://www.chabotc.com

/************************** chatWindow class implimentation ***********************************/
var chatWindow = Class.create();
chatWindow.prototype = {
	initialize: function(id) {
		this.windowInitialize(id, arguments[1] || {});
	},

	windowInitialize: function(id) {
		this.options = Object.extend({
			allowResize	: true,
			allowClose  : true,
			allowDrag   : true,
			showCenter  : true,
			minWidth    : 220,
			minHeight   : 120,
      		width		: 400,
      		height		: 240,
      		top         : 100,
      		left        : 100,
      		zIndex		: 100
    		}, arguments[1] || {});
		this.id         = 'window_' + id;
		this.divSizer   = this.id+'_sizer';
		this.divHandle  = this.id+'_handle';
		this.divClose   = this.id+'_close';
		this.divTitle   = this.id+'_title';
		this.divContent = this.id+'_content';
		this.createLayout();
		this.element    = $(this.id);
		$(this.id).setStyle({ width  : this.options.width+'px',
		                      height : this.options.height+'px',
		                      top    : this.options.top+'px',
		                      left   : this.options.left+'px',
		                      zIndex : this.options.zIndex});
		if (this.options.allowResize) {
			this.eventMouseDown = this.initDrag.bindAsEventListener(this);
			this.eventMouseMove = this.updateDrag.bindAsEventListener(this);
			this.eventMouseUp   = this.endDrag.bindAsEventListener(this);
			$(this.divSizer).observe('mousedown', this.eventMouseDown);
		}
		if (this.options.allowClose) {
			this.eventClose     = this.hide.bindAsEventListener(this);
			$(this.divClose).observe('mousedown', this.eventClose);
		}
		if (this.options.allowDrag) {
			this.draggable      = new Draggable(this.id, { handle : this.divHandle});
			$(this.divHandle).setStyle({ cursor : 'move' });
		}
		this.resizeContent();
		$(this.id).hide();
	},

	setTitle: function(title) {
		$(this.divTitle).update(title);
	},

	createLayout: function() {
		var div1       = document.createElement('DIV');
		div1.setAttribute('id', this.id);
		div1.className = 'window';
		var div2       = document.createElement('DIV');
		div2.setAttribute('id', this.divHandle);
		div2.className = 'window_handle';
		div1.appendChild(div2);
		var div7        = document.createElement('DIV');
		div7.className = 'window_handle_right';
		div2.appendChild(div7);
		var div5        = document.createElement('DIV');
		div5.className = 'window_handle_left';
		div2.appendChild(div5);
		var div6        = document.createElement('DIV');
		div6.className = 'window_handle_center';
		div2.appendChild(div6);
		if (this.options.allowClose) {
			div8          = document.createElement('DIV');
			div8.setAttribute('id', this.divClose);
			div8.className = 'window_close';
			div6.appendChild(div8);
		}
		var span1      = document.createElement('SPAN');
		span1.setAttribute('id', this.divTitle);
		span1.className = "window_title";
		div6.appendChild(span1);
		var div3       = document.createElement('DIV');
		div3.setAttribute('id', this.divContent);
		div3.className = 'window_content';
		div1.appendChild(div3);
		if (this.options.allowResize) {
			var div4       = document.createElement('DIV');
			div4.setAttribute('id', this.divSizer);
			div4.className = 'window_sizer';
			div1.appendChild(div4);
		}
		$('main').appendChild(div1);
	},

	destroy: function(event) {
		if (this.options.allowResize) {
			$(this.divSizer).stopObserving('mousedown', this.eventMouseDown);
		}
		if (this.options.allowClose) {
			$(this.divClose).stopObserving("mousedown", this.eventClose);
		}
		if (this.options.allowDrag) {
			this.draggable.destroy();
		}
		this.hide();
		$('main').removeChild(this.element);
	},

	shake: function() {
		Effect.shake(this.element);
	},

	hide: function(event) {
		if (event != undefined && event && event.stopPropagation != undefined) {
			event.stopPropagation();
		}
		//new Effect.Fade(this.element, {duration: 0.4});
		this.element.hide();
	},

	show: function(event) {
		if (this.options.showCenter) {
			this.center();
		}
		//new Effect.Appear(this.element, {duration: 0.4});
		this.element.show();
	},

	visible: function() {
		return this.element.visible();
	},

	center: function() {
		var pageWidth     = (document.documentElement.clientWidth  || window.document.body.clientWidth);
		var pageHeight    = (document.documentElement.clientHeight || window.document.body.clientHeight);
		var dimensions    = $(this.id).getDimensions();
		this.options.top  = (pageHeight - dimensions.height) / 2;
		this.options.left = (pageWidth  - dimensions.width)  / 2;
		if (this.options.top  < 0) this.options.top  = 0;
		if (this.options.left < 0) this.options.left = 0;
		$(this.id).setStyle({ top    : this.options.top+'px',
		                      left   : this.options.left+'px' });

	},

	initDrag: function(event) {
		this.pointer = [Event.pointerX(event), Event.pointerY(event)];
		Event.observe(document, "mouseup",   this.eventMouseUp);
		Event.observe(document, "mousemove", this.eventMouseMove);
		document.body.ondrag        = function () { return false; };
		document.body.onselectstart = function () { return false; };
	},

	updateDrag: function(event) {
		var pointer  = [Event.pointerX(event), Event.pointerY(event)];
		var dx       = pointer[0] - this.pointer[0];
		var dy       = pointer[1] - this.pointer[1];
		this.pointer = pointer;
		dx = parseFloat($(this.id).getStyle('width'))  + dx > parseFloat(this.options.minWidth)  ? dx : 0;
		dy = parseFloat($(this.id).getStyle('height')) + dy > parseFloat(this.options.minHeight) ? dy : 0;
		$(this.id).setStyle({ width  : parseFloat($(this.id).getStyle('width'))  + dx + 'px',
		                      height : parseFloat($(this.id).getStyle('height')) + dy + 'px'});
		this.resizeContent();
	},

	endDrag: function(event) {
		Event.stopObserving(document, "mouseup",   this.eventMouseUp);
		Event.stopObserving(document, "mousemove", this.eventMouseMove);
		document.body.ondrag        = null;
		document.body.onselectstart = null;
	},

	resizeContent: function() {
		$(this.divContent).setStyle({ width  : parseFloat($(this.id).getStyle('width')) - 2 + 'px',
		                              height : (parseFloat($(this.id).getStyle('height')) - 28) + 'px' });
	}
}
