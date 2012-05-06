<?
abstract class socketServerClient extends socketClient {
	public $socket;
	public $remote_address;
	public $remote_port;
	public $local_addr;
	public $local_port;

	public function __construct($socket)
	{
		$this->socket         = $socket;
		if (!is_resource($this->socket)) {
			throw new socketException("Invalid socket or resource");
		} elseif (!socket_getsockname($this->socket, $this->local_addr, $this->local_port)) {
			throw new socketException("Could not retrieve local address & port: ".socket_strerror(socket_last_error($this->socket)));
		} elseif (!socket_getpeername($this->socket, $this->remote_address, $this->remote_port)) {
			throw new socketException("Could not retrieve remote address & port: ".socket_strerror(socket_last_error($this->socket)));
		}
		$this->set_non_block();
		$this->on_connect();
	}
}
