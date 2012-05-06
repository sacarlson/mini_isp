// WebChat2.0 Copyright (C) 2006-2007, Chris Chabot <chabotc@xs4all.nl>
// Licenced under the GPLv2. For more info see http://www.chabotc.com

/****************************** chatEditor WYSIWYG input editor class  ***********************************/
var chatEditorResizer = Class.create();
chatEditorResizer.prototype = {
	initialize: function() {
		this.divSend        = $('send');
		this.divEditor      = $('editor_div');
		this.divEdit        = $('editor_edit');
		this.divSizer       = $('editor_resizer');
		this.eventMouseDown = this.initDrag.bindAsEventListener(this);
		this.eventMouseMove = this.updateDrag.bindAsEventListener(this);
		this.eventMouseUp   = this.endDrag.bindAsEventListener(this);
		this.divSizer.observe("mousedown", this.eventMouseDown);
	},

	initDrag: function(event) {
		this.pointer = [Event.pointerX(event), Event.pointerY(event)];
		Event.observe(document, "mouseup",   this.eventMouseUp);
		Event.observe(document, "mousemove", this.eventMouseMove);
	},

	updateDrag: function(event) {
		var pointer   = [Event.pointerX(event), Event.pointerY(event)];
		var dy        = pointer[1] - this.pointer[1];
		this.pointer  = pointer;
		var newHeight = parseFloat($(this.divSend).getStyle('height')) - dy;
		if (newHeight > 45 && newHeight < 400) {
			this.resize(newHeight);
		}
	},

	endDrag: function(event) {
		Event.stopObserving(document, "mouseup",   this.eventMouseUp);
		Event.stopObserving(document, "mousemove", this.eventMouseMove);
		document.body.ondrag        = null;
		document.body.onselectstart = null;
	},

	resize: function(newHeight) {
		this.divSend.setStyle({   height : newHeight + 'px'});
		chat.channels.each(function(channel) {
			channel.onResize();
		});
		this.divEditor.setStyle({ height : parseFloat(newHeight - 21) + 'px'});
		this.divEdit.setStyle({   height : parseFloat(newHeight - 21) + 'px'});
	}
}

var chatEditor = Class.create();
chatEditor.prototype = {
	initialize: function() {
		// define mIRC layout codes
		this.sizer     = new chatEditorResizer();
		this.menus     = [];
		this.END       = String.fromCharCode(15);
		this.BOLD      = String.fromCharCode(2);
		this.ITALIC    = String.fromCharCode(4);
		this.UNDERLINE = String.fromCharCode(31);
		// and create html editor..
		this.doc = $('editor_edit').contentWindow.document;
		this.doc.designMode = 'on';
		this.createMenu();
		// listen for Return key, which triggers the 'send' event
		Event.observe(this.doc, 'keydown', function(event) {
			if (event.keyCode == Event.KEY_RETURN && !event.shiftKey) {
				this.send();
				return false;
			} else if (event.keyCode == Event.KEY_RETURN && parseFloat($(this.sizer.divSend).getStyle('height')) < 60) {
				this.sizer.resize(80);
			}
		}.bind(this));
		this.focus();
	},

	send: function() {
		// Remove returns and translate WYSIWYG html code to mIRC compatible control codes (see chatChannel.js colorize function for the recieving end)
		var msg = this.translateTags(this.doc.body.innerHTML.toString().replace(/<br \/>/g,"\n").replace(/<br>/g,"\n").replace(/&nbsp;/g,' '));
		var msgs = msg.split("\n");
		msgs.each(function(msg) {
			msg = msg.replace(/(<([^>]+)>)/ig,"").replace(/\n/g,'').replace(/\r/g,'');
			msg = decodeURI(msg);
			msg = msg.trim();
			if (msg && msg != '') {
				chat.message(msg);
			}
		});
		setTimeout("chat.editor.clear();",10);
	},

	focus: function() {
		$('editor_edit').scrollTop = 0;
		$('editor_edit').contentWindow.focus();
	},

	clear: function() {
		this.doc.body.innerHTML = '&nbsp;';
		this.focus();
	},

	closeMenus: function() {
		this.menus.each(function(menu) {
			menu.hide();
		});
	},

	createMenu: function() {
		var cmds = ['bold', 'italic', 'underline', 'forecolor', 'smile'];
		cmds.each(function(cmd) {
			var menu = document.createElement('LI');
			menu.className = 'editor_'+cmd;
			menu.setAttribute('id', 'editor_button_'+cmd);
			$('editor_menu').appendChild(menu);
			if (cmd == 'smile') {
				this.menus.push(new chatSmiliePopup('editor_button_'+cmd, cmd));
				return;
			} else if (cmd == 'forecolor') {
				this.menus.push(new chatColorPopup('editor_button_'+cmd, cmd));
				return;
			} else {
				new chatEditorButton('editor_button_'+cmd, cmd);
			}
		}.bind(this));
	},

	execCommand: function(cmd, opt) {
		var option = opt != undefined ? opt : false;
		try {
			this.doc.execCommand(cmd, false, option);
		} catch(e) {
			alert('Error executing command');
		}
	},

	translateTags: function(str) {
		//<span style="color: red;">
		var code    = new RegExp('<span style="color: (.*?);">(.*?)<\/span>', 'igm');
		var newStr  = str;
		while (matches = code.exec(str)) {
			if (matches != undefined && matches.length > 2) {
				var toReplace = str.substring(matches.index, code.lastIndex);
				var out       = String.fromCharCode(3)+this.getColor(matches[1].trim().toLowerCase())+matches[2]+String.fromCharCode(15);
				newStr = newStr.replace(toReplace, out, 'igm');
			}
		}
		str = newStr;
		var RegExpCode = [
		['<(\/?)P>|</DIV>|&nbsp;',                                                                         ''],
		['<STRONG>(.*?)<\/STRONG>',                                                                        this.BOLD + '$1' + this.END],
		['<EM>(.*?)<\/EM>',                                                                                this.ITALIC + '$1' + this.END],
		['<U>(.*?)</\U>',                                                                                  this.UNDERLINE + '$1' + this.END],
		['<I>(.*?)</\I>',                                                                                  this.ITALIC + '$1' + this.END],
		['<span style="font-weight: bold;">(.*?)<\/span>',                                                 this.BOLD + '$1' + this.END],
		['<span style="font-style: italic;">(.*?)<\/span>',                                                this.ITALIC + '$1' + this.END],
		['<span style="text-decoration: underline;">(.*?)<\/span>',                                        this.UNDERLINE + '$1' + this.END],
		['<span style="font-weight: bold; text-decoration: underline;">(.*?)<\/span>',                     this.BOLD + this.UNDERLINE + '$1' + this.END + this.END],
		['<span style="text-decoration: underline; font-weight: bold;">(.*?)<\/span>',                     this.UNDERLINE + this.BOLD + '$1' + this.END + this.END],
		['<span style="font-weight: bold; font-style: italic;">(.*?)<\/span>',                             this.BOLD + this.ITALIC + '$1' + this.END + this.END],
		['<span style="font-style: italic; font-weight: bold;">(.*?)<\/span>',                             this.BOLD + this.ITALIC + '$1' + this.END + this.END],
		['<span style="text-decoration: underline; font-style: italic;">(.*?)<\/span>',                    this.UNDERLINE + this.ITALIC + '$1' + this.END + this.END],
		['<span style="font-style: italic; text-decoration: underline;">(.*?)<\/span>',                    this.UNDERLINE + this.ITALIC + '$1' + this.END + this.END],
		['<span style="font-style: italic; text-decoration: underline; font-weight: bold;">(.*?)<\/span>', this.UNDERLINE + this.BOLD + this.ITALIC + '$1' + this.END + this.END + this.END],
		['<span style="font-style: italic; font-weight: bold; text-decoration: underline;">(.*?)<\/span>', this.UNDERLINE + this.BOLD + this.ITALIC + '$1' + this.END + this.END + this.END],
		['<span style="font-weight: bold; font-style: italic; text-decoration: underline;">(.*?)<\/span>', this.UNDERLINE + this.BOLD + this.ITALIC + '$1' + this.END + this.END + this.END],
		['<span style="font-weight: bold; text-decoration: underline; font-style: italic;">(.*?)<\/span>', this.UNDERLINE + this.BOLD + this.ITALIC + '$1' + this.END + this.END + this.END],
		['<span style="text-decoration: underline; font-style: italic; font-weight: bold;">(.*?)<\/span>', this.UNDERLINE + this.BOLD + this.ITALIC + '$1' + this.END + this.END + this.END],
		['<span style="text-decoration: underline; font-weight: bold; font-style: italic;">(.*?)<\/span>', this.UNDERLINE + this.BOLD + this.ITALIC + '$1' + this.END + this.END + this.END],
		['<DIV>|<BR(\/?)>', '']
		];
		RegExpCode.each(function(e) {
			var code = new RegExp(e[0], 'igm');
			str = str.replace(code, e[1]);
		});
		return str;
	},


	getColor: function(color)
	{
		switch (color) {
			case 'white':   return 0;
			case 'black':   return 1;
			case 'navy':    return 2;
			case 'green':   return 3;
			case 'red':     return 4;
			case 'maroon':  return 5;
			case 'purple':  return 6;
			case 'olive':   return 7;
			case 'yellow':  return 8;
			case 'lime':    return 9;
			case 'teal':    return 10;
			case 'aqua':    return 11;
			case 'blue':    return 12;
			case 'fuchsia': return 13;
			case 'gray':    return 14;
			default: 	    return 1;
		}
	}
}


var chatEditorButton = Class.create();
chatEditorButton.prototype = {
	initialize: function(element, command) {
		this.initEvents(element, command);
		this.clickEvent = this.onClick.bindAsEventListener(this);
		Event.observe(this.element, "mousedown", this.clickEvent);
	},

	initEvents: function(element, command) {
		this.element    = $(element);
		this.command    = command;
		this.mouseUp    = this.onMouseUp.bindAsEventListener(this);
		this.mouseOver  = this.onMouseOver.bindAsEventListener(this);
		this.mouseOut   = this.onMouseOut.bindAsEventListener(this);
		Event.observe(this.element, "mouseup",   this.mouseUp);
		Event.observe(this.element, "mouseover", this.mouseOver);
		Event.observe(this.element, "mouseout",  this.mouseOut);
	},

	onMouseUp: function() {
		this.element.removeClassName('down');
	},

	onMouseOver: function() {
		this.element.addClassName('over');
	},

	onMouseOut: function() {
		this.element.removeClassName('over');
	},

	onClick: function(event) {
		this.element.addClassName('down');
		chat.editor.focus();
		chat.editor.execCommand(this.command);

	}
}


chatEditorPopup = Class.create();
Object.extend(Object.extend(chatEditorPopup.prototype, chatEditorButton.prototype), {
	initialize: function(element, command) {
		this.initEvents(element, command);
		this.divContent = 'editor_popup_'+element;
		this.createLayout();
		$(this.divContent).setStyle({opacity : 0.85});
		$(this.divContent).hide();
		this.clickEvent  = this.onClick.bindAsEventListener(this);
		this.selectEvent = this.onSelect.bindAsEventListener(this);
		var sel = this.selectEvent;
		Event.observe(this.element,  "click", this.clickEvent);
		if (this.populate != undefined) {
			this.populate();
			$$('#'+this.divContent+' div').each(function(element) {
				Event.observe(element.id, "click", sel);
			});
			$$('#'+this.divContent+' img').each(function(element) {
				Event.observe(element.id, "click", sel);
			});
		}
	},

	createLayout: function() {
		var div1 = document.createElement('DIV');
		div1.setAttribute('id', this.divContent);
		div1.className = 'editor_popup';
		document.body.appendChild(div1);
	},

	onClick: function() {
		if (!$(this.divContent).visible()) {
			this.show();
			chat.editor.focus();
		} else {
			this.hide();
			chat.editor.focus();
		}
	},

	show: function() {
		var dimensions = $(this.divContent).getDimensions();
		$(this.divContent).setStyle({top : (this.element.offsetTop - dimensions.height) - 2 + 'px', left : (this.element.offsetLeft + 1) + 'px'});
		this.element.removeClassName('over');
		this.element.addClassName('down');
		Effect.Appear(this.divContent, {duration:0.2, to : 0.85});
	},

	hide: function() {
		this.element.removeClassName('down');
		Effect.Fade(this.divContent, {duration:0.2});
	},

	onSelect: function(event) {
		var element = Event.element(event);
		var option = this.command == 'smile' ? element.src : element.style.backgroundColor;
		chat.editor.execCommand(this.command, option);
		this.hide();
		chat.editor.focus();
	}
});


chatColorPopup = Class.create();
Object.extend(Object.extend(chatColorPopup.prototype, chatEditorPopup.prototype), {
	populate: function() {
		var colors = [
		'white',
		'black',
		'navy',
		'green',
		'red',
		'maroon',
		'purple',
		'olive',
		'yellow',
		'lime',
		'teal',
		'aqua',
		'blue',
		'fuchsia',
		'gray'
		];
		var menuDiv = $(this.divContent);
		var cmd     = this.command;
		colors.each(function(color) {
			var div1 = document.createElement('DIV');
			div1.className = 'editor_color';
			div1.setAttribute('id', 'editor_'+cmd+'_'+color);
			menuDiv.appendChild(div1);
			$('editor_'+cmd+'_'+color).setStyle({backgroundColor: color});
		});
	}
});


chatSmiliePopup = Class.create();
Object.extend(Object.extend(chatSmiliePopup.prototype, chatEditorPopup.prototype), {
	populate: function() {
		var smilies = [
		'biggrin.gif',
		'smile.gif',
		'sad.gif',
		'surprised.gif',
		'shock.gif',
		'confused.gif',
		'cool.gif',
		'lol.gif',
		'mad.gif',
		'razz.gif',
		'redface.gif',
		'cry.gif',
		'evil.gif',
		'badgrin.gif',
		'rolleyes.gif',
		'wink.gif',
		'exclaim.gif',
		'question.gif',
		'idea.gif',
		'arrow.gif',
		'neutral.gif',
		'doubt.gif'
		];
		menuDiv = $(this.divContent);
		smilies.each(function(smile) {
			var img1 = document.createElement('IMG');
			img1.className = 'editor_smilie';
			img1.setAttribute('src', '/images/smilies/'+smile);
			img1.setAttribute('id','editor_smile_'+smile);
			menuDiv.appendChild(img1);
		});
	}
});
