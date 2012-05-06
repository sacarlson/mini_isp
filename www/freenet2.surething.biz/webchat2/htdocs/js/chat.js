// WebChat2.0 Copyright (C) 2006-2007, Chris Chabot <chabotc@xs4all.nl>
// Licenced under the GPLv2. For more info see http://www.chabotc.com

/****************************** main chat application  ***********************************/
var chat = {
	nickname     : '',
	server       : '',
	version      : '0.2',
	key          : false,
	connection   : false,
	iframeDiv    : false,
	timer        : false,
	editor       : false,
	channels     : [],
	current      : false,
	disconnected : false,
	listWindow   : false,
	connectWindow: false,

	initialize: function() {
		chat.disconnected = false;
		$('new_channel').observe("mousedown", chat.showList);
		chat.editor = new chatEditor;
		chat.addChannel('info');
		chat.channel('info').show();
		chat.onResize();
		chat.showConnect();
	},

	showConnect: function() {
		$('overlay').setOpacity(0.8);
		$('overlay').show();
		chat.connectWindow = new chatConnectWindow('connect', {allowResize : false, allowClose : false, allowDrag : true, width: 304, height: 232, zIndex:1001});
		chat.connectWindow.show();
	},

	connect: function(nickname, server) {
		chat.connectWindow.destroy();
		chat.connectWindow = false;
		chat.nickname      = nickname;
		chat.server        = server;
		chat.initializeIframe();
	},

	showList: function() {
		if (!chat.listWindow) {
			chat.listWindow = new chatListWindow('channel_list', {height : 440, width : 600, allowResize : false});
		}
		if (!chat.listWindow.visible()) {
			chat.listWindow.show();
		}
	},

	addChannel: function(channel) {
		chat.channels.push(new chatChannel(channel));
		chat.onResize();
		chat.editor.focus();
	},

	removeChannel: function(channel) {
		if (chat.channel(channel) != undefined) {
			chat.channel(channel).destroy();
		}
		chat.editor.focus();
	},

	channel: function(channel) {
	    for (i = 0; i < chat.channels.length; i++) {
			if (chat.channels[i].channel == channel) {
				return chat.channels[i];
			}
	    }
	    return undefined;
	},

	createSortable: function() {
		Sortable.create('toolbar', {tag: 'div', only : 'channel_button', ghosting : false, constraint : 'horizontal', overlap : 'horizontal', scroll : window });
	},

	add: function(channel, message) {
		if (chat.channel(channel) != undefined) {
			chat.channel(channel).add(message);
		}
	},

	sortNames: function(channel) {
		if (chat.channel(channel) != undefined) {
			chat.channel(channel).sortNames();
		}
	},

	message: function(msg) {
		new Ajax.Request('/message?key='+chat.key+'&msg='+encodeURIComponent(msg)+'&channel='+encodeURIComponent(chat.current), { asynchronous : true, method : 'get'});
	},

	onConnecting: function() {
		chat.add('info', '<span class="notice">Connecting to server</span>');
	},

	onServerInfo: function(what, info) {
		if ($('overlay').visible()) {
			$('overlay').hide();
		}
		chat.add('info', '<span class="notice">'+info+'</span>');
		if (chat.current != 'info') {
			chat.add(chat.current, '<span class="notice">'+info+'</span>');
		}
	},

	onMotd: function(motd) {
		if ($('overlay').visible()) {
			$('overlay').hide();
		}
		if (chat.connectWindow != false) {
			chat.connectWindow.destroy();
			chat.connectWindow = false;
		}
		chat.add('info', '<span class="notice">'+motd+'</span>');
	},

	onVersion: function(from) {
		chat.add('info', '<span class="notice">Recieved ctpcp version request from '+from+'</span>');
		if (chat.current != 'info') {
			chat.add(chat.current, '<span class="notice">Recieved ctpcp version request from '+from+'</span>');
		}
	},

	onTime: function(from) {
		chat.add('info', '<span class="notice">Recieved ctpcp time request from '+from+'</span>');
		if (chat.current != 'info') {
			chat.add(chat.current, '<span class="notice">Recieved ctpcp time request from '+from+'</span>');
		}
	},

	onPing: function(from) {
		chat.add('info', '<span class="notice">Recieved ctpcp ping request from '+from+'</span>');
		if (chat.current != 'info') {
			chat.add(chat.current, '<span class="notice">Recieved ctpcp ping request from '+from+'</span>');
		}
	},

	onMessage: function(from, channel, msg) {
		chat.add(channel, '<div class="from">'+from+':</div> <span class="message">'+msg+'</span>');
	},

	onNotice: function(from, msg) {
		chat.add('info', '<span class="notice">Notice from '+from+': '+msg+'</span>');
		if (chat.current != 'info') {
			chat.add(chat.current, '<span class="notice">Notice from '+from+': '+msg+'</span>');
		}
	},

	onWhois: function(msg) {
		chat.add('info','<span class="notice">'+msg+'</span></span>')
	},

	onWhowas: function(msg) {
		chat.add('info','<span class="notice">'+msg+'</span></span>')
	},

	onAction: function(channel, from, msg) {
		chat.add(channel, '<span class="notice">'+from+' <span class="message">'+msg+'</span></span>')
	},

	onPrivateMessage: function(from, msg) {
		chat.add('info', '<span class="privmsg">Message from '+from+': <span class="message">'+msg+'</span></span>')
		if (chat.current != 'info') {
			chat.add(chat.current, '<span class="privmsg">Message from '+from+': <span class="message">'+msg+'</span></span>')
		}
	},

	onServerNotice: function(notice) {
		chat.add('info', '<span class="notice">Server notice: '+notice+'</span>');
	},

	onKick: function(channel, from, who, reason) {
		if (chat.channel(channel) != undefined) {
			chat.channel(channel).members.remove(who);
			chat.channel(channel).members.render();
		}
		var reason = (reason != undefined && reason != '') ? ' ('+reason+')' : '';
		chat.add(channel, '<span class="kick">'+from+' kicked '+who+' from '+channel+reason+'</span>');

	},

	onKicked: function(channel, from, who, reason) {
		chat.removeChannel(channel);
		var reason = (reason != undefined && reason != '') ? ' ('+reason+')' : '';
		chat.add('info',  '<span class="kick">You were kicked from '+channel+' by '+from+reason+'</span>');
	},

	onError: function(error) {
		chat.add('info', '<span class="kick">Error: '+error+'</span>');
		if (chat.current != 'info') {
			chat.add(chat.current, '<span class="kick">Error: '+error+'</span>');
		}
	},

	onPart: function(channel, who, message) {
		if (chat.channel(channel) != undefined) {
			chat.add(channel, '<span class="part">'+who+' left</span>');
			chat.channel(channel).members.remove(who);
			chat.channel(channel).members.render();
		}
	},

	onParted: function(channel) {
		chat.removeChannel(channel);
		chat.add('info', '<span class="part">Left '+channel+'</span>');
	},

	onJoin: function(channel, who) {
		if (chat.channel(channel) != undefined) {
			chat.channel(channel).members.add(who, false, false);
			chat.channel(channel).members.render();
			chat.add(channel, '<span class="join">'+who+' joined '+channel+'</span>');
		}
	},

	onJoined: function(channel) {
		chat.addChannel(channel);
		chat.add(channel, '<span class="join">Entered '+channel+'</span>');
		if (chat.channel(channel) != undefined) {
			chat.channel(channel).show();
		}
	},

	onTopic: function(channel, topic) {
		if (chat.channel(channel) != undefined) {
			chat.channel(channel).setTopic(topic);
		}
	},

	onNick: function(channel, from, to) {
		if (from == chat.nickname) {
			chat.nickname = to;
		}
		if (chat.channel(channel) != undefined) {
			chat.add(channel, '<span class="notice">'+from+' changes nickname to '+to+'</span>');
			chat.channel(channel).members.nick(from, to);
		}
	},

	onWho: function(nick, ident, host, server, full_name) {
		chat.add('info', '<span class="notice"> * '+nick+' ('+ident+'), host: '+host+', server: '+server+', full name: '+full_name+'</span>');
	},

	onEndOfWho: function() {
		chat.add('info', '<span class="notice">End of who</span>');
	},

	onChannelMode: function(channel, mode) {
		chat.add(channel, '<span class="notice">channel mode set to '+mode+'</span>')
	},

	addMember: function(channel, who, operator, voice) {
		if (chat.channel(channel) != undefined) {
			chat.channel(channel).members.add(who, operator, voice);
		}
	},

	opMember: function(channel, who, from) {
		if (chat.channel(channel) != undefined) {
			chat.add(channel, '<span class="notice">'+from+' gives operator status to '+who+'</span>')
			chat.channel(channel).members.op(who, from);
		}
	},

	deopMember: function(channel, who, from) {
		if (chat.channel(channel) != undefined) {
			chat.add(channel, '<span class="notice">'+from+' removes operator status from '+who+'</span>')
			chat.channel(channel).members.deop(who, from);
		}
	},

	voiceMember: function(channel, who, from) {
		if (chat.channel(channel) != undefined) {
			chat.add(channel, '<span class="notice">'+from+' gives voice to '+who+'</span>')
			chat.channel(channel).members.voice(who, from);
		}
	},

	devoiceMember: function(channel, who, from) {
		if (chat.channel(channel) != undefined) {
			chat.add(channel, '<span class="notice">'+from+' removes voice from '+who+'</span>')
			chat.channel(channel).members.devoice(who, from);
		}
	},

	addBan: function(channel, mask, from) {
		if (chat.channel(channel) != undefined) {
			chat.add(channel, '<span class="notice">'+from+' bans: '+mask+'</span>')
			chat.channel(channel).addBan(mask, from);
		}
	},

	removeBan: function(channel, mask, from) {
		if (chat.channel(channel) != undefined) {
			chat.add(channel, '<span class="notice">'+from+' removes ban: '+mask+'</span>')
			chat.channel(channel).removeBan(mask, from);
		}
	},

	setKey: function(channel, key, from) {
		if (chat.channel(channel) != undefined) {
			chat.add(channel, '<span class="notice">'+from+' sets channel key to '+key+'</span>')
			chat.channel(channel).setKey(key, from);
		}
	},

	renderMembers: function(channel) {
		if (chat.channel(channel) != undefined) {
			chat.channel(channel).members.render();
		}
	},

	onResize: function() {
		var pageWidth     = (document.documentElement.clientWidth  || window.document.body.clientWidth);
		var pageHeight    = (document.documentElement.clientHeight || window.document.body.clientHeight);
		$('send').setStyle({        width : (pageWidth - 10)+'px'});
		$('editor_edit').setStyle({ width : (pageWidth - 10)+'px'});
		$('menu_div').setStyle({    width : (pageWidth - 8)+'px'});
		$('editor_menu').setStyle({ width : (pageWidth - 8)+'px'});
		chat.channels.each(function(channel) {
			channel.onResize();
		});
		window.scrollTo(0, 0);
	},

	initializeIframe: function() {
		if (navigator.appVersion.indexOf("MSIE") != -1) {
			// use the "htmlfile hack" in ie to prevent the background click sounds
			chat.connection = new ActiveXObject("htmlfile");
			chat.connection.open();
			chat.connection.write("<html>");
			chat.connection.write("<script>document.domain = '"+document.domain+"'");
			chat.connection.write("</html>");
			chat.connection.close();
			chat.iframeDiv = chat.connection.createElement("div");
			chat.connection.appendChild(chat.iframeDiv);
			chat.connection.parentWindow.chat = chat;
			chat.iframeDiv.innerHTML = "<iframe name='comet_iframe' id='comet_iframe' src='/get?nickname="+chat.nickname+"&server="+chat.server+"' onload='chat.frameDisconnected();'></iframe>";
			//chat.timer = setTimeout('chat.frameCheck()', 500);
		} else {
			chat.connection = document.createElement('iframe');
			chat.connection.setAttribute('id',     'comet_iframe');
			chat.connection.setAttribute('name',   'comet_iframe_name');
			with (chat.connection.style) {
				left       = top   = "-100px";
				height     = width = "1px";
				visibility = "hidden";
				display    = 'none';
			}
			chat.iframeDiv = document.createElement('iframe');
			chat.iframeDiv.setAttribute('onLoad', 'chat.frameDisconnected()');
			chat.iframeDiv.setAttribute('src',    '/get?nickname='+chat.nickname+'&server='+chat.server);
			chat.connection.appendChild(chat.iframeDiv);
			document.body.appendChild(chat.connection);

		}
	},

	frameCheck: function() {
		if ($('comet_iframe').readyState == "complete") {
			chat.frameDisconnected();
		} else {
			chat.timer = setTimeout('chat.frameCheck()', 500);
		}
	},

	frameDisconnected: function() {
		$A(chat.channels).each(function(channel) {
			if (channel.channel != 'info') {
				channel.destroy();
			}
		});
		chat.connection = false;
		$('comet_iframe').remove();
		setTimeout("chat.showConnect();",100);
	},

	onUnload: function() {
		if (chat.connection) {
			// release the iframe or htmlfile object, prevents bugs on reloading in IE
			chat.connection = false;
		}
	}
}

// Used in chatConnectionWindow, array.random(), returns a random element from the array
Array.prototype.random = function(r) {
	var i = 0, l = this.length;
	if( !r ) { r = this.length; }
	else if( r > 0 ) { r = r % l; }
	else { i = r; r = l + r % l; }
	return this[ Math.floor( r * Math.random() - i ) ];
};

// String.trim prototype, used in chatEdtitor.js (and others)
String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g, "");
};

// Hook up the chat object to the onLoad and onResize events
Event.observe(window, "load",   chat.initialize);
Event.observe(window, "resize", chat.onResize);
Event.observe(window, "unload", chat.onUnload);