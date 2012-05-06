// WebChat2.0 Copyright (C) 2006-2007, Chris Chabot <chabotc@xs4all.nl>
// Licenced under the GPLv2. For more info see http://www.chabotc.com

/************************** chatChannel class implimentation ***********************************/
var chatChannel = Class.create();
chatChannel.prototype = {
	initialize: function(channel) {
		this.closing            = false;
		this.channel            = channel;
		this.topic              = '';
		this.key                = false;
		this.bans               = [];
		this.messageCounter     = 0;
		this.divMain            = 'channel_'+this.channel;
		this.divNames           = 'names_'+this.channel;
		this.divWhoHeader       = 'who_header_' + this.channel;
		this.divWhoSizer        = 'who_sizer_'+this.channel;
		this.divWhoContent      = 'who_content_' + this.channel;
		this.ulWhoContent       = 'who_content_ul_' + this.channel;
		this.divWhoTitle        = 'who_title_' + this.channel;
		this.divMessages        = 'messages_' + this.channel;
		this.divMessagesHeader  = 'messages_header_' + this.channel;
		this.divHeaderClose     = 'messages_close_' + this.channel;
		this.divMessagesContent = 'messages_content_' + this.channel;
		this.divButton          = 'channel_button_'+this.channel;
		this.divSizer           = 'sizer_'+this.channel;
		this.divTopic           = 'messages_topic_' + this.channel;
		this.createLayout();
		this.members            = new chatMembers(this.channel, this.ulWhoContent, this.divWhoTitle, this);
		$(this.divMessagesHeader).update(this.channel+'<span id="'+this.divTopic+'"></span>');
		var close = this.channel != 'info' ? '<div class="tab_close" id="'+this.divHeaderClose+'"></div>' : '';
		new Insertion.Bottom('toolbar', '<div class="channel_button" id="'+this.divButton+'"><div class="tab_left"></div><div class="tab_center">'+close+this.channel+'</div><div class="tab_right"></div></div>');
		$(this.divWhoSizer).onclick = this.collapseWho.bindAsEventListener(this);
		$(this.divButton).onclick   = this.show.bindAsEventListener(this);
		this.eventMouseDown = this.initDrag.bindAsEventListener(this);
		this.eventMouseMove = this.updateDrag.bindAsEventListener(this);
		this.eventMouseUp   = this.endDrag.bindAsEventListener(this);
		$(this.divSizer).observe("mousedown", this.eventMouseDown);
		this.hide();
		if (this.channel != 'info') {
			$(this.divButton).hide();
			new Effect.Appear($(this.divButton), { duration : 0.4 });
			$(this.divHeaderClose).onclick = this.close.bindAsEventListener(this);
		} else {
			$(this.divNames).setStyle({width : '0px'});
			$(this.divSizer).hide();
			$(this.divNames).hide();
		}
		setTimeout('chat.createSortable();', 10);
	},

	destroy: function() {
		new Effect.Fade(this.divButton, {duration : 0.4, afterFinish: function(effect) { Element.remove(effect.element)} });
		this.members.destroy();
		if (this.channel != 'info') {
			$(this.divHeaderClose).stopObserving('click');
		}
		$(this.divButton).stopObserving('click');
		$('main').removeChild($(this.divMain));
		chat.channels.splice(chat.channels.indexOf(this), 1);
		if (chat.current == this.channel) {
			chat.channel('info').show();
		}
		setTimeout('chat.createSortable();', 10);
	},

	close: function() {
		this.closing = true;
		chat.message('/part '+this.channel);
		return true;
	},

	createLayout: function() {
		var div1 = document.createElement('DIV');
		div1.setAttribute('id', this.divMain);
		div1.className     = 'channel';
		var div2 = document.createElement('DIV');
		div2.setAttribute('id', this.divNames);
		div2.className     = 'names';
		div1.appendChild(div2);
		var div3 = document.createElement('DIV');
		div3.setAttribute('id', this.divWhoHeader);
		div3.className     = 'header';
		div2.appendChild(div3);
		var div10 = document.createElement('DIV');
		div10.setAttribute('id', this.divWhoSizer);
		div10.className     = 'who_sizer';
		div3.appendChild(div10);
		var span1 = document.createElement('SPAN');
		div10.appendChild(span1);
		var div9 = document.createElement('DIV');
		div9.setAttribute('id', this.divWhoTitle);
		div9.className     = 'who_title';
		div3.appendChild(div9);
		var div4 = document.createElement('DIV');
		div4.setAttribute('id', this.divWhoContent);
		div4.className     = 'who_content';
		div2.appendChild(div4);
		var ul1 = document.createElement('UL');
		ul1.setAttribute('id', this.ulWhoContent);
		ul1.className     = 'who_ul';
		div4.appendChild(ul1);
		var div8 = document.createElement('DIV');
		div8.setAttribute('id', this.divSizer);
		div8.className     = 'sizer';
		div1.appendChild(div8);
		var span1 = document.createElement('SPAN');
		div8.appendChild(span1);
		var div5 = document.createElement('DIV');
		div5.setAttribute('id', this.divMessages);
		div5.className     = 'messages';
		div1.appendChild(div5);
		var div6 = document.createElement('DIV');
		div6.setAttribute('id', this.divMessagesHeader);
		div6.className     = 'header';
		div5.appendChild(div6);
		var div7 = document.createElement('DIV');
		div7.setAttribute('id', this.divMessagesContent);
		div7.className     = 'messages_content';
		div5.appendChild(div7);
		$('main').appendChild(div1);
	},

	onResize: function() {
		var pageWidth  = (document.documentElement.clientWidth  || window.document.body.clientWidth);
		var pageHeight = (document.documentElement.clientHeight || window.document.body.clientHeight);
		var namesWidth = $(this.divNames).getDimensions().width;
		var sendHeight = $('send').getDimensions().height;
		if (this.channel == 'info') {
			namesWidth = -4;
		}

		$(this.divMessages).setStyle({ height : (pageHeight - 63 - sendHeight)+'px', width : (pageWidth - namesWidth - 14)+'px' });
		$(this.divMessagesContent).setStyle({ height : (pageHeight - 86 - sendHeight)+'px', width : (pageWidth - namesWidth - 14)+'px' });
		$(this.divSizer).setStyle({ height : (pageHeight - 63 - sendHeight)+'px'});
		$(this.divNames).setStyle({ height : (pageHeight - 63 - sendHeight)+'px' });
		$(this.divWhoContent).setStyle({ height : (pageHeight - 86 - sendHeight)+'px' });
		if ($(this.divMessagesContent).lastChild != undefined) {
			$(this.divMessagesContent).scrollTop = $(this.divMessagesContent).lastChild.offsetTop;
		}
	},

	initDrag: function(event) {
		this.pointer = [Event.pointerX(event), Event.pointerY(event)];
		Event.observe(document, "mouseup",   this.eventMouseUp);
		Event.observe(document, "mousemove", this.eventMouseMove);
	},

	updateDrag: function(event) {
		var pointer  = [Event.pointerX(event), Event.pointerY(event)];
		var dx       = pointer[0] - this.pointer[0];
		this.pointer = pointer;
		var newWidth = parseFloat($(this.divNames).getStyle('width')) - dx;
		if (newWidth > 50 && newWidth < 400) {
			$(this.divNames).setStyle({ width : newWidth + 'px'});
		}
		this.onResize();
	},

	endDrag: function(event) {
		Event.stopObserving(document, "mouseup",   this.eventMouseUp);
		Event.stopObserving(document, "mousemove", this.eventMouseMove);
		document.body.ondrag        = null;
		document.body.onselectstart = null;
	},

	collapseWho: function() {
		$(this.divSizer).hide();
		$(this.divWhoContent).hide();
		$(this.divWhoTitle).hide();
		chatChannelResizing = this;
		Event.stopObserving(this.divWhoSizer, 'click');
		$(this.divWhoSizer).onclick = this.expandWho.bindAsEventListener(this);
		var elementDimensions = $(this.divNames).getDimensions();
		this.whoOriginalHeight = elementDimensions.height;
		this.whoOriginalWidth  = elementDimensions.width - 2;
		new Effect.Scale(this.divNames, 10, { duration: 0.6, scaleContent: false, scaleY: false, afterFinish: function(effect) { $(chatChannelResizing.divWhoSizer).setStyle({backgroundImage : "url('/images/expand.gif')"}); $(chatChannelResizing.divNames).setStyle({ backgroundColor : '#deecfd'}); chat.onResize();} });
	},

	expandWho: function() {
		$(this.divNames).setStyle({ backgroundColor : '#ffffff'});
		Event.stopObserving(this.divWhoSizer, 'click');
		$(this.divWhoSizer).onclick = this.collapseWho.bindAsEventListener(this);
		chatChannelResizing = this;
		var pageWidth  = (document.documentElement.clientWidth  || window.document.body.clientWidth);
		$(this.divMessages).setStyle({ width : (pageWidth - this.whoOriginalWidth - 16) +'px' });
		$(this.divMessagesContent).setStyle({ width : (pageWidth - this.whoOriginalWidth - 16) +'px' });
		new Effect.Scale(this.divNames, 100, { duration: 0.6, scaleFrom: 10, scaleContent: false, scaleY: false, scaleMode: { originalHeight: this.whoOriginalHeight, originalWidth: this.whoOriginalWidth }, afterFinish: function(effect) { $(chatChannelResizing.divWhoSizer).setStyle({backgroundImage : "url('/images/collapse.gif')"}); $(chatChannelResizing.divSizer).show(); $(chatChannelResizing.divWhoContent).show(); $(chatChannelResizing.divWhoTitle).show(); chat.onResize(); }});
	},

	show: function() {
		if (!this.closing) {
			chat.channels.each(function(channel) {
				if (channel.channel != this.channel) {
					channel.hide();
				}
			});
			$(this.divMain).show();
			$(this.divButton).setStyle({fontWeight : 'normal', color: '#ffffff'});
			$(this.divButton).addClassName('on');
			this.onResize();
			chat.current = this.channel;
		}
	},

	hide: function() {
		$(this.divButton).removeClassName('on');
		$(this.divButton).setStyle({color: '#15428B'});
		$(this.divMain).hide();
	},

	visible: function() {
		return $(this.divMain).style.display != 'none';
	},

	setTopic: function(topic) {
		if (topic == undefined) {
			this.divTopic.update('');
		} else {
			$(this.divTopic).update(': '+topic);
		}
	},

	addBan: function(mask, from) {
		this.bans.push(mask);
	},

	removeBan: function(mask, from) {
		if (this.bans.indexOf(mask) != -1) {
			this.bans.splice(this.bans.indexOf(mask), 1);
		}
	},

	setKey: function(key, from) {
		this.key = key;
	},

	add: function(message) {
		if (chat.current != this.channel) {
			$(this.divButton).setStyle({fontWeight : 'bold'});
		}
		if (this.messageCounter >= 500) {
			for (var i = 0 ; i < 10 ; i++) {
				$(this.divMessagesContent).removeChild($(this.divMessagesContent).firstChild);
			}
			this.messageCounter -= 10;
		}
		var div1        = document.createElement('DIV');
		div1.className  = 'message';
		div1.innerHTML  = this.smilify(this.colorize(this.linkify(message)));
		$(this.divMessagesContent).appendChild(div1);
		$(this.divMessagesContent).scrollTop = $(this.divMessagesContent).lastChild.offsetTop;
		this.messageCounter++;
	},

	smilify: function(message) {
		var smiles = [
			['biggrin.gif', ':D'],
			['biggrin.gif', ':-D'],
			['biggrin.gif', ':grin:'],
			['biggrin.gif', ':biggrin:'],
			['smile.gif', ':)'],
			['smile.gif', ':-)'],
			['smile.gif', ':smile:'],
			['sad.gif', ':('],
			['sad.gif', ':-('],
			['sad.gif', ':sad:'],
			['surprised.gif', ':o'],
			['surprised.gif', ':-o'],
			['surprised.gif', ':eek:'],
			['shock.gif', ':shock:'],
			['confused.gif', ':?'],
			['confused.gif', ':-?'],
			['confused.gif', ':???:'],
			['cool.gif', '8)'],
			['cool.gif', '8-)'],
			['cool.gif', ':cool:'],
			['lol.gif', ':lol:'],
			['mad.gif', ':x'],
			['mad.gif', ':-X'],
			['mad.gif', ':mad:'],
			['razz.gif', ':p'],
			['razz.gif', ':-p'],
			['razz.gif', ':razz:'],
			['redface.gif', '::oops:'],
			['cry.gif', ':cry:'],
			['evil.gif', ':evil:'],
			['badgrin.gif', ':badgrin:'],
			['rolleyes.gif', ':roll:'],
			['wink.gif', ';)'],
			['wink.gif', ';-)'],
			['wink.gif', ':wink:'],
			['exclaim.gif', ':!:'],
			['question.gif', ':?:'],
			['idea.gif', ':idea:'],
			['arrow.gif', ':arrow:'],
			['neutral.gif', ':|'],
			['neutral.gif', ':-|'],
			['neutral.gif', ':neutral:'],
			['doubt.gif', ':doubt:']
		];
		smiles.each(function(e) {
			message = message.replace(e[1], '<img src="/images/smilies/'+e[0]+'" />', 'igm');
		});
		return message;
	},

	linkify: function(message) {
		var urlRegex = /\b(https?:\/\/[^\s+\"\<\>]+)/igm;
		if (urlRegex.test(message)) {
			return message.replace(urlRegex, "<a href=\"$1\" title=\"$1\" target=\"_blank\">$1</a>");
		}
		return message;
	},

	colorize: function(message) {
		var pageBack  = 'white';
		var pageFront = 'black';
		var length    = message.length;
		var newText   = '';
		var bold      = false;
		var color     = false;
		var reverse   = false;
		var underline = false;
		var italic    = false;
		var foreColor = '';
		var backColor = '';
		for (var i = 0 ; i < length ; i++) {
			switch (message.charAt(i)) {
				case String.fromCharCode(2):
					if (bold) {
						newText += '</b>';
						bold     = false;
					} else {
						newText += '<b>';
						bold    = true;
					}
					break;
				case String.fromCharCode(3):
					if (color)	{
						newText += '</span>';
						color = false;
					}
					foreColor = '';
					backColor = '';
					if ((parseInt(message.charAt(i+1)) >= 0) && (parseInt(message.charAt(i+1)) <= 9)) {
						color = true;
						if ((parseInt(message.charAt(++i+1)) >= 0) && (parseInt(message.charAt(i+1)) <= 9)) {
							foreColor = this.getColor(parseInt(message.charAt(i)) * 10 + parseInt(message.charAt(++i)));
						} else {
							foreColor = this.getColor(parseInt(message.charAt(i)));
						}
						if ((message.charAt(i+1) == ',') && (parseInt(message.charAt(++i+1)) >= 0) && (parseInt(message.charAt(i+1)) <= 9)) {
							if ((parseInt(message.charAt(++i+1)) >= 0) && (parseInt(message.charAt(i+1)) <= 9)) {
								backColor = this.getColor(parseInt(message.charAt(i)) * 10 + parseInt(message.charAt(++i)));
							} else {
								backColor = this.getColor(parseInt(message.charAt(i)));
							}
						}
					}
					if (foreColor) {
						newText += '<span style="color:'+foreColor;
						if (backColor) {
							newText += ';background-color:'+backColor;
						}
						newText += '">';
					}
					break;
				case String.fromCharCode(4):
					if (italic) {
						newText += '</i>';
						italic     = false;
					} else {
						newText += '<i>';
						italic    = true;
					}
					break;
				case String.fromCharCode(15):
					if (bold) {
						newText += '</b>';
						bold     = false;
					}
					if (color) {
						newText += '</span>';
						color    = false;
					}
					if (reverse) {
						newText += '</span>';
						reverse  = false;
					}
					if (underline) {
						newText  += '</u>';
						underline = false;
					}
					if (italic) {
						newText += '</i>';
						italic   = false;
					}
					break;
				case String.fromCharCode(22):
					if (reverse) {
						newText += '</span>';
						reverse  = false;
					} else {
						newText += '<span style="color:'+pageBack+';background-color:'+pageFront+'">';
						reverse  = true;
					}
					break;
				case String.fromCharCode(31):
					if (underline) {
						newText  += '</u>';
						underline = false;
					} else {
						newText  += '<u>';
						underline = true;
					}
					break;
				default:
					newText += message.charAt(i);
					break;
			}

		}
		if (bold)      newText += '</b>';
		if (color)     newText += '</span>';
		if (reverse)   newText += '</span>'
		if (underline) newText += '</u>';
		if (italic)    newText += '</i>';
		return newText;
	},

	getColor: function(numeric)
	{
		var num = parseInt(numeric);
		switch (num) {
			case 0:  return 'white';
			case 1:  return 'black';
			case 2:  return 'navy';
			case 3:  return 'green';
			case 4:  return 'red';
			case 5:  return 'maroon';
			case 6:  return 'purple';
			case 7:  return 'olive';
			case 8:  return 'yellow';
			case 9:  return 'lime';
			case 10: return 'teal';
			case 11: return 'aqua';
			case 12: return 'blue';
			case 13: return 'fuchsia';
			case 14: return 'gray';
			default: return 'silver';
		}
	}
}
