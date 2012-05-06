// WebChat2.0 Copyright (C) 2006-2007, Chris Chabot <chabotc@xs4all.nl>
// Licenced under the GPLv2. For more info see http://www.chabotc.com

chatListWindow = Class.create();
Object.extend(Object.extend(chatListWindow.prototype, chatWindow.prototype), {
	initialize: function(id) {
		this.windowInitialize(id, arguments[1] || {});
		this.divList  = this.id+'_channel_list';
		this.divInput = this.id+'_channel_input';
		this.channels = [];
		$(this.divContent).update('<div class="list_content">'+
		                               '<div class="list_input" id='+this.divInput+'">Enter channel name, starting with a # (and hit enter to join)<br /><input type="text" id="entered_channel" name="entered_channel" value="#" /><br />Or select a channmel from the list (click to join)</div>'+
		                               '<div class="list_list" id="'+this.divList+'"></div>'+
		                               '</div>');
		$('entered_channel').observe('keypress', this.onKeyPress);
		this.setTitle('Select a channel');
		chat.message('/list');
	},

	onKeyPress: function(e) {
		if (e.keyCode == Event.KEY_RETURN) {
			chat.message('/join '+$('entered_channel').getValue());
			$('entered_channel').clear();
			chat.listWindow.hide(); // aka hide me
			return true;
		}
	},

	refresh: function() {
		chat.message('/list');
	},

	join: function(event) {
		var channel = this.id.replace(/channel_list_/,'');
		chat.message('/join '+channel);
		chat.listWindow.hide(); // aka hide me
	},

	start: function() {
		//$('entered_channel').focus();
		$(this.divList).update('<img src="/images/loading.gif" alt="Loading.." /> Loading channel list...');
		this.channels = [];
	},

	add: function(channel, members) {
		// to preserve bandwidth, memory &speed the topics is skipped
		this.channels.push({channel: channel, members: members, toString : function() { return this.channel.toLowerCase()} });
	},

	done: function() {
		$(this.divList).update('');
		var sorted = this.channels.sort();
		var length = sorted.length;
		var cnt    = 0;
		for (var i = 0 ; i < length ; i++) {
			cnt++;
			if (sorted[i].members > 2) {
				new Insertion.Bottom(this.divList, '<div id="channel_list_'+sorted[i].channel+'" class="channel_list_entry">'+
			                                       '<div class="channel_list_members">'+sorted[i].members+'</div>'+
			                                       sorted[i].channel+'</div>');
			  	if ($('channel_list_'+sorted[i].channel) != undefined) {
					Event.observe('channel_list_'+sorted[i].channel, "click", this.join);
			  	}
			}
		}
		if (!cnt) {
			new Insertion.Bottom(this.divList, ' No channels found');
		}
	}
});
