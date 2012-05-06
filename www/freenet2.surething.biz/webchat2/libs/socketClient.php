<?
abstract class socketClient extends socket {
	public $remote_address = null;
	public $remote_port    = null;
	public $connecting     = false;
	public $disconnected   = false;
	public $read_buffer    = '';
	public $write_buffer   = '';

	public function connect($remote_address, $remote_port)
	{
		$this->connecting = true;
		try {
			parent::connect($remote_address, $remote_port);
		} catch (socketException $e) {
			echo "Caught exception: ".$e->getMessage()."\n";
		}
	}

	public function write($buffer, $length = 4096)
	{
		$this->write_buffer .= $buffer;
		$this->do_write();
	}

	public function do_write()
	{
		$length = strlen($this->write_buffer);
		try {
			$written = parent::write($this->write_buffer, $length);
			if ($written < $length) {
				$this->write_buffer = substr($this->write_buffer, $written);
			} else {
				$this->write_buffer = '';
			}
			$this->on_write();
			return true;
		} catch (socketException $e) {
			$old_socket         = (int)$this->socket;
			$this->close();
			$this->socket       = $old_socket;
			$this->disconnected = true;
			$this->on_disconnect();
			return false;
		}
		return false;
	}

	public function read($length = 4096)
	{
		try {
			$this->read_buffer .= parent::read($length);
			$this->on_read();
		} catch (socketException $e) {
			$old_socket         = (int)$this->socket;
			$this->close();
			$this->socket       = $old_socket;
			$this->disconnected = true;
			$this->on_disconnect();
		}
	}

	public function on_connect() {}
	public function on_disconnect() {}
	public function on_read() {}
	public function on_write() {}
	public function on_timer() {}
}
