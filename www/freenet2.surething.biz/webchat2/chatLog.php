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

error_reporting(E_ALL | E_STRICT);
set_error_handler('chat_log_php_error',E_ALL | E_STRICT);
set_exception_handler('chat_log_php_exception');

function chat_dump_log($file, $line)
{
	$time = date('m-d-Y_H:i:s');
	$fp   = "/tmp/chat_{$file}:{$line}_{$time}.backtrace";
	if (@fopen($fp,"w+")) {
		fwrite($fp, print_r(debug_backtrace(),true));
		fclose($fp);
	}
}

function chat_log_php_error($error_code, $error_msg, $error_file, $error_line)
{
	echo "[ERROR] {$error_file}:{$error_line} $error_msg\n";
	chat_dump_log($error_file, $error_line);
}

function chat_log_php_exception($exception)
{
	$error_msg  = $exception->getMessage();
	$error_file = $exception->getFile();
	$error_line = $exception->getLine();
	echo "[ERROR] {$error_file}:{$error_line} $error_msg\n";
	chat_dump_log($error_file, $error_line);
}
