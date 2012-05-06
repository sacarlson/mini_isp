<?
abstract class socketServer extends socket {
	protected $client_class;

	public function __construct($client_class, $bind_address = 0, $bind_port = 0, $domain = AF_INET, $type = SOCK_STREAM, $protocol = SOL_TCP)
	{
		parent::__construct($bind_address, $bind_port, $domain, $type, $protocol);
		$this->client_class = $client_class;
		$this->listen();
	}

	public function accept()
	{
		$client = new $this->client_class(parent::accept());
		if (!is_subclass_of($client, 'socketServerClient')) {
			throw new socketException("Invalid serverClient class specified! Has to be a subclass of socketServerClient");
		}
		$this->on_accept($client);
		return $client;
	}

	// override if desired
	public function on_accept(socketServerClient $client) {}
}
