// WebChat2.0 Copyright (C) 2006-2007, Chris Chabot <chabotc@xs4all.nl>
// Licenced under the GPLv2. For more info see http://www.chabotc.com

chatConnectWindow = Class.create();
Object.extend(Object.extend(chatConnectWindow.prototype, chatWindow.prototype), {
	initialize: function(id) {
		this.windowInitialize(id, arguments[1] || {});
		this.divNickname = this.id+'_nickname_input';
		this.divNetwork  = this.id+'_network_select';
		this.localServers    = ['192.168.2.250'];
		this.freenodeServers = ['208.71.169.36','216.165.191.52','213.92.8.4','82.96.64.4','130.239.18.172','213.219.249.66','85.188.1.26','163.25.104.18','194.24.188.100','212.204.214.114'];
		this.magnetServers   = ['66.180.175.14', '209.218.54.66', '209.2.32.37', '216.75.41.2', '62.121.16.101', '80.68.80.162' ];
		this.efnetServers    = ['207.45.69.69', '209.249.249.126', '217.17.33.10', '194.159.164.195', '198.51.77.22', '69.16.172.2', '66.225.225.225'];
		$(this.divContent).update('<div class="list_content">'+
		                               'Enter your nickname:<br />'+
		                               '<div class="nickname_input" id="'+this.divNickname+'"><input type="text" name="input_nickname" id="input_nickname" /></div><br />'+
		                               'And select a network (local is unlimited, other networks will not accept to many connections from the same IP):<br />'+
		                               '<div class="network_select" id="'+this.divNetwork+'"><select name="select_network" id="select_network"><option value="local">local</option><option value="freenode">freenode</option><option value="magnet">MAGnet</option><option value="efnet">efnet</option></select></div><br />'+
		                               '<div class="button" id="connect_button"><div class="button_left"></div><div class="button_center"><div class="button_text">Connect</div></div><div class="button_right"></div></div>'+
		                               '</div>');
		this.setTitle('Connect');
		$('connect_button').observe('click', this.onConnect);
	},

	onConnect: function(event) {
		if (event != undefined && event && event.stopPropagation != undefined) {
			event.stopPropagation();
		}
		var server   = '';
		var nickname = $F('input_nickname');
		if ($F('select_network') == 'local') {
			server = $A(chat.connectWindow.localServers).random();
		} else if ($F('select_network') == 'efnet') {
			server = $A(chat.connectWindow.efnetServers).random();
		} else if ($F('select_network') == 'magnet') {
            server = $A(chat.connectWindow.magnetServers).random();
        } else if ($F('select_network') == 'freenode') {
			server = $A(chat.connectWindow.freenodeServers).random();
        }
		if (server && nickname != '' && nickname != undefined) {
			chat.connect(nickname, server);
		}
	}
});
