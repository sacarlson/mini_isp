// WebChat2.0 Copyright (C) 2006-2007, Chris Chabot <chabotc@xs4all.nl>
// Licenced under the GPLv2. For more info see http://www.chabotc.com

/************************** chatMembers class implimentation ***********************************/
var chatMembers = Class.create();
chatMembers.prototype = {
	initialize: function(channel, content, header, parent) {
		this.channel  = channel;
		this.content  = content;
		this.header   = header;
		this.tooltips = [];
		this.menus    = [];
		this.members  = [];
		this.parent   = parent;
	},

	destroy: function() {
		this.clear();
	},

	clear: function() {
		while ($(this.content).firstChild) {
			$(this.content).removeChild($(this.content).firstChild);
		}
		$(this.content).update('');
	},

	render: function() {
		var sorted = this.members.sort();
		var ops     = 0;
		var length = sorted.length;
		var operator = '';
		var voice    = '';
		var member   = false;
		$(this.header).update(length+' members');
		this.clear();
		for (var i = 0 ; i < length ; i++) {
			member = sorted[i];
			operator = member.operator != undefined && member.operator ? 'operator' : '';
			if (operator == 'operator') {
				ops ++;
			}
			voice = member.voice != undefined && member.voice ? 'voice' : '';
			new Insertion.Bottom($(this.content), '<li class="member '+operator+voice+'" id="'+member.content+'">'+member.who+'</li>');
		}
		if (ops) {
			$(this.header).update($(this.header).innerHTML + ', '+ops+' operator(s)');
		}
	},

	add: function(who, operator, voice) {
		if (this.indexOf(who) == -1) {
			this.members.push({who : who, operator : operator, voice: voice, content : this.channel + '_member_' + who, toString : function() {return (this.operator ? ' @' : '')+(this.voice ? '+' : '')+this.who.toLowerCase()} });
		}
	},

	remove: function(who) {
		this.members.splice(this.indexOf(who), 1);
	},

	op: function(who, from) {
		if (this.indexOf(who) != -1) {
			this.members[this.indexOf(who)].operator = true;
		}
		this.render();
	},

	deop: function(who, from) {
		if (this.indexOf(who) != -1) {
			this.members[this.indexOf(who)].operator = false;
		}
		this.render();
	},

	voice: function(who, from) {
		if (this.indexOf(who) != -1) {
			this.members[this.indexOf(who)].voice = true;
		}
		this.render();
	},

	devoice: function(who, from) {
		if (this.indexOf(who) != -1) {
			this.members[this.indexOf(who)].voice = false;
		}
		this.render();
	},

	nick: function(from, to) {
		if (this.indexOf(from) != -1) {
			this.members[this.indexOf(from)].who = to;
			this.render();
		}
	},

	indexOf: function(who) {
	    for (i = 0; i < this.members.length; i++) {
			if (this.members[i].who == who) {
				return i;
			}
	    }
	    return -1;
	},

	member: function(who) {
		var index = this.indexOf(who);
		return (index != -1) ? this.members[index] : undefined;
	}
}