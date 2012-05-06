#!/usr/bin/php -Cq
<?
/*
WebChat2.0 Copyright (C) 2006-2007, Chris Chabot <chabotc@xs4all.nl>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

include("libs/socket.php");
include("httpServer.php");
include("ircClient.php");
include("chatLog.php");

ini_set('max_execution_time', '0');
ini_set('assert.bail', false);
ini_set('max_input_time', '0');
ini_set('mbstring.func_overload', '0');
ini_set('output_handler', '');
ini_set('default_socket_timeout','10');
ini_set('memory_limit','512M');
set_time_limit(0);


$daemon = new socketDaemon();
$server = $daemon->create_server('httpdServer', 'httpdServerClient', 0, 2001);
$daemon->process();
