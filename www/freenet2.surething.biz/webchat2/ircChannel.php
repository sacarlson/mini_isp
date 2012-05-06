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

class ircChannel {
	private $mode;
	private $key;
	private $bans;
	private $topic = '';
	private $channel;
	private $names;
	private $parent;
	private $created;
	private $topic_set_by;
	private $topic_created;

	public function __construct($parent, $channel)
	{
		echo "[IRC] joined: $channel\n";
		$this->parent  = $parent;
		$this->channel = htmlspecialchars($channel, ENT_QUOTES, 'UTF-8');
		$parent->send_script("chat.onJoined('$channel');");
	}

	public function __destruct()
	{
		echo "[IRC] left {$this->channel}\n";
		$this->parent->send_script("chat.onParted('$this->channel');");
	}

	public function on_topic($topic)
	{
		echo "[IRC] {$this->channel} topic:$topic\n";
		$this->topic = $topic;
		$topic       = htmlspecialchars($topic, ENT_QUOTES, 'UTF-8');
		$this->parent->send_script("chat.onTopic('{$this->channel}', '$topic');");
	}

	public function on_join($who)
	{
		echo "[IRC] $who entered {$this->channel}\n";
		if (!isset($this->names[$who])) {
			$this->names[$who] = array('nickname' => $who);
			$who               = htmlspecialchars($who, ENT_QUOTES, 'UTF-8');
			$this->parent->send_script("chat.onJoin('{$this->channel}', '$who');");
		}
	}

	public function on_part($who, $message)
	{
		echo "[IRC] $who left {$this->channel}: $message\n";
		if (isset($this->names[$who])) {
			unset($this->names[$who]);
			$who     = htmlspecialchars($who, ENT_QUOTES, 'UTF-8');
			$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
			$this->parent->send_script("chat.onPart('{$this->channel}', '$who', '$message');");
		}
	}

	public function on_kick($from, $who, $reason)
	{
		echo "[IRC] $who was kicked from {$this->channel}\n";
		unset($this->names[$who]);
		$who    = htmlspecialchars($who,    ENT_QUOTES, 'UTF-8');
		$from   = htmlspecialchars($from,   ENT_QUOTES, 'UTF-8');
		$reason = htmlspecialchars($reason, ENT_QUOTES, 'UTF-8');
		if ($this->parent->nick == $who) {
			$this->parent->send_script("chat.onKicked('{$this->channel}','$from', '$who', '$reason');");
		} else {
			$this->parent->send_script("chat.onKick('{$this->channel}','$from', '$who', '$reason');");
		}
	}

	public function on_mode($mode)
	{
		echo "[IRC] {$this->channel} channel mode set to $mode\n";
		$this->mode = $mode;
		$mode = htmlspecialchars($mode, ENT_QUOTES, 'UTF-8');
		$this->parent->send_script("chat.onChannelMode('{$this->channel}','$mode');");
	}

	public function on_nick($from, $to)
	{
		if (isset($this->names[$from])) {
			$member = $this->names[$from];
			$member['nickname'] = $to;
			unset($this->names[$from]);
			$this->names[$to] = $member;
			$from = str_replace('\\','\\\\', htmlentities($from, ENT_QUOTES, 'UTF-8'));
			$to   = str_replace('\\','\\\\', htmlentities($to,   ENT_QUOTES, 'UTF-8'));
			$this->parent->send_script("chat.onNick('{$this->channel}', '$from', '$to');");
		}
	}

	public function on_quit($who)
	{
		if (isset($this->names[$who])) {
			unset($this->names[$who]);
			$who = htmlspecialchars($who, ENT_QUOTES, 'UTF-8');
			$this->parent->send_script("chat.onPart('{$this->channel}', '$who', 'Quit');");
		}
	}

	public function add_names($names)
	{
		foreach ($names as $name) {
			$operator = $voice = 'false';
			if (substr($name, 0, 1) == '@') {
				$name = substr($name, 1);
				$operator = 'true';
			} elseif (substr($name, 0, 1) == '+') {
				$name  = substr($name, 1);
				$voice = 'true';
			}
			$this->names[$name] = array('nickname' => $name, 'operator' => $operator, 'voice' => $voice);
			$name = str_replace('\\','\\\\', htmlspecialchars($name, ENT_QUOTES, 'UTF-8'));
			$this->parent->send_script("chat.addMember('{$this->channel}' ,'$name', $operator, $voice);");
		}
	}

	public function who($ident, $host, $server, $nick, $full_name)
	{
		if (isset($this->names[$nick]) && !isset($this->names[$nick]['ident'])) {
			$operator = isset($this->names[$nick]['operator']) ? $this->names[$nick]['operator'] : 'false';
			$voice    = isset($this->names[$nick]['voice'])    ? $this->names[$nick]['voice']    : 'false';
			$this->names[$nick] = array('nickname' => $nick, 'ident' => $ident, 'server' => $server, 'full_name' => $full_name, 'operator' => $operator, 'voice' => $voice);
		}
	}

	public function end_of_names()
	{
		$this->parent->send_script("chat.renderMembers('{$this->channel}');");
	}

	public function end_of_who() {}


	public function channel_created($timestamp)
	{
		$this->created = $timestamp;
	}

	public function topic_set_by($who, $when)
	{
		echo "[IRC] {$this->channel} topic set by $who at ".date("d/m/Y H:i", $when)."\n";
		$this->topic_created = $when;
		$this->topic_set_by  = $who;
	}

	public function op($nick, $from)
	{
		if (isset($this->names[$nick])) {
			$this->names[$nick]['operator'] = true;
			$nick = htmlspecialchars($nick, ENT_QUOTES, 'UTF-8');
			$from = htmlspecialchars($from, ENT_QUOTES, 'UTF-8');
			$this->parent->send_script("chat.opMember('{$this->channel}', '$nick', '$from');");
		}
	}

	public function deop($nick, $from)
	{
		if (isset($this->names[$nick])) {
			$this->names[$nick]['operator'] = false;
			$nick = htmlspecialchars($nick, ENT_QUOTES, 'UTF-8');
			$from = htmlspecialchars($from, ENT_QUOTES, 'UTF-8');
			$this->parent->send_script("chat.deopMember('{$this->channel}', '$nick', '$from');");
		}
	}

	public function voice($nick, $from)
	{
		if (isset($this->names[$nick])) {
			$this->names[$nick]['voice'] = true;
			$nick = htmlspecialchars($nick, ENT_QUOTES, 'UTF-8');
			$from = htmlspecialchars($from, ENT_QUOTES, 'UTF-8');
			$this->parent->send_script("chat.voiceMember('{$this->channel}', '$nick', '$from');");
		}
	}

	public function devoice($nick, $from)
	{
		if (isset($this->names[$nick])) {
			$this->names[$nick]['voice'] = false;
			$nick = htmlspecialchars($nick, ENT_QUOTES, 'UTF-8');
			$from = htmlspecialchars($from, ENT_QUOTES, 'UTF-8');
			$this->parent->send_script("chat.devoiceMember('{$this->channel}', '$nick', '$from');");
		}
	}

	public function set_key($key = false, $from)
	{
		$this->key = $key;
		$key  = htmlspecialchars($key, ENT_QUOTES, 'UTF-8');
		$from = htmlspecialchars($from, ENT_QUOTES, 'UTF-8');
		$this->parent->send_script("chat.setKey('{$this->channel}', '$key', '$from');");
	}

	public function add_ban($hostmask, $from)
	{
		$this->bans[$hostmask] = $hostmask;
		$from = htmlspecialchars($from, ENT_QUOTES, 'UTF-8');
		$hostmask = htmlspecialchars($hostmask, ENT_QUOTES, 'UTF-8');
		$this->parent->send_script("chat.addBan('{$this->channel}', '$hostmask', '$from');");
	}

	public function remove_ban($hostmask, $from)
	{
		if (isset($this->bans[$hostmask])) {
			unset($this->bans[$hostmask]);
			$from = htmlspecialchars($from, ENT_QUOTES, 'UTF-8');
			$hostmask = htmlspecialchars($hostmask, ENT_QUOTES, 'UTF-8');
			$this->parent->send_script("chat.removeBan('{$this->channel}', '$hostmask', '$from');");
		}
	}
}