<?php
/**
 * tcp_ping.class.php
 * Connectivity Ping, sending NOOP Command to Common TCP Service.
 * 
 * @package TcpPing
 * @author Andronicus Riyono <nick@riyono.com> 
 * @copyright Copyright (c) 2005 Andronicus Riyono <nick@riyono.com>
 * @version 1.0.0
 * @license LGPL
 */

/**
 * TcpPing
 * Connectivity Ping, sending NOOP Command to Common TCP Service. 
 * 
 * This class can be used to check connectivity with TCP service ping. Sending NOOP Command
 * to widely used TCP service (http, telnet, ftp, mail)
 * 
 * @package TcpPing
 * @author Andronicus Riyono <nick@riyono.com> 
 * @copyright Copyright (c) 2005 Andronicus Riyono <nick@riyono.com>
 * @version 1.0.0
 * @access public 
 * @license LGPL
 */
class TcpPing
{
    /**
     * Error Message Container
     * 
     * @var string 
     */
    var $mErrorMessage;

    /**
     * Ping Response Time in seconds
     * 
     * @var float 
     */
    var $mTime;

    /**
     * Ping Start Time in seconds
     * 
     * @var float 
     */
    var $mStartTime;

    /**
     * NOOP command to use
     * 
     * @var string 
     */
    var $mNoopCommand;

    /**
     * Response received from pinged remote host
     * 
     * @var string 
     */
    var $mResponse;

    /**
     * Remote host IP Address
     * 
     * @var float 
     */
    var $mTargetAddr;

    /**
     * Remote host name or IP Address
     * 
     * @var float 
     */
    var $mTargetHost;

    /**
     * Remote host port to ping
     * 
     * @var float 
     */
    var $mTargetPort;

    /**
     * Target TCP Service
     * 
     * @var float 
     */
    var $mTargetService;

    /**
     * Connectivity Ping Timeout in seconds
     * 
     * @var integer 
     */
    var $mTimeout;
    /**
     * SocketPing::_GetMicroTime()
     * 
     * @return float Current microtime in seconds as float
     */
    function _GetMicroTime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    } 

    /**
     * SocketPing::TimerStart()
     * Starts the timer
     */
    function TimerStart()
    {
        $this->mStartTime = $this->_GetMicroTime();
    } 

    /**
     * SocketPing::TimerStop()
     * Stop the timer and assign the value to mTime property
     */
    function TimerStop()
    {
        $timer_stop = $this->_GetMicroTime();
        $this->mTime = $timer_stop - $this->mStartTime;
    } 

    /**
     * TcpPing::GetTargetAddress()
     * Get value of mTargetAddr property. mTargetAddr contains target's IP address
     * 
     * @return string target IP address
     */
    function GetTargetAddress()
    {
        return $this->mTargetAddr;
    } 

    /**
     * SocketPing::GetTime()
     * Get the value of mTime property
     * 
     * mTime property contains the ping execution time (set by
     * 
     * @param integer $roundPrecision 
     * @return float Ping Response Time
     */
    function GetTime($roundPrecision = 3)
    {
        $time = round($this->mTime * 1000, $roundPrecision);
        $retval = $time > 1 ? "{$time}ms" : "<1ms";
        return $retval;
    } 

    /**
     * TcpPing::GetErrorMessage()
     * Get the value of mErrorMessage property
     * 
     * mErrorMessage property contains error message if the ping failed 
     * [TcpPing::Ping() returns boolean false]
     * 
     * @return string ErrorMessage
     */
    function GetErrorMessage()
    {
        return $this->mErrorMessage;
    } 

    /**
     * TcpPing::GetResponse()
     * Get the value of mResponse property
     * 
     * mResponse property contains the response from remote host 
     * [ if there are any response of course ;) ]
     * 
     * @return string Tcp Ping Response
     */
    function GetResponse()
    {
        return $this->mResponse;
    } 

    /**
     * TcpPing::_SetNoopCommand()
     * Setting NOOP Command to use
     * the NOOP Command list should be written as an array
     * even when it only contains single NOOP Command.
     * 
     * @access private 
     */
    function _SetNoopCommand()
    {
        switch ($this->mTargetPort)
        {
            case 21:
                $this->mNoopCommand = array("", "NOOP\r\n", "QUIT\r\n");
                break;
            case 25:
                $this->mNoopCommand = array("", "EHLO\r\n", "NOOP\r\n", "QUIT\r\n");
                break;
            case 80:
                $this->mNoopCommand = array("HEAD / HTTP/1.0\r\nConnection: Close\r\n\r\n");
                break;
            case 110:
                $this->mNoopCommand = array("", "QUIT\r\n");
                break;
            default:
                $this->mNoopCommand = array("NOOP\r\n\r\n");
        } // switch
    } 

    /**
     * TcpPing::TcpPing()
     * Constructor of TcpPing class
     * 
     * @param string $host target hostname or ip address
     * @param string $service tcp service to ping (http, ftp, etc.)
     * @param integer $timeout in seconds
     * @access public 
     */
    function TcpPing($host, $service = 'http', $timeout = 30)
    {
        $this->mTargetHost = trim($host);
        $this->mTargetService = trim($service);
        $this->mTimeout = ($timeout > 0) ? $timeout : 30;
    } 

    /**
     * TcpPing::Ping()
     * Do the ping
     * 
     * @return boolean true on successful ping, and false on failed ping.
     */
    function Ping()
    {
        /**
         * Disabling script timeout
         */
        set_time_limit(0);

        /**
         * Setting target port
         */
        if (!($this->mTargetPort = getservbyname($this->mTargetService, 'tcp')))
        {
            $this->mErrorMessage = "No port number associated with the specified Internet service '{$this->mTargetService}'.";
            return false;
        } 

        /**
         * Setting target IP address
         */
        if (preg_match('/^(?:[0-9]{1,3}.){3}[0-9]{1,3}$/', $this->mTargetHost))
        {
            $this->mTargetAddr = $this->mTargetHost;
        } 
        else
        {
            $this->mTargetAddr = gethostbyname($this->mTargetHost);
            /**
             * gethostbyname() returns a string containing the unmodified hostname on failure
             */
            if ($this->mTargetAddr == $this->mTargetHost)
            {
                $this->mErrorMessage = "Could not find host {$this->mTargetHost}.";
                return false;
            } 
        } 

        /**
         * Setting no op command
         * 
         * @see TcpPing::_SetNoopCommand()
         */
        $this->_SetNoopCommand();

        /**
         * Create a TCP/IP socket.
         */
        $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (false === $socket)
        {
            $this->mErrorMessage = "Could not open socket, reason: " . socket_strerror(socket_last_error());
            return false;
        } 

        /**
         * Setting timeout
         */
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => $this->mTimeout, 'usec' => 0));

        /**
         * Try to connect to the specified TCP Service
         */
        $result = @socket_connect($socket, $this->mTargetAddr, $this->mTargetPort);
        if (false === $result)
        {
		    $this->mErrorMessage = trim(socket_strerror(socket_last_error()));
            return false;
        } 

        /**
         * When the script reach this point, it means the connection is already established. 
         * Preparing $reply to receive any reply from the TCP Service and starting the timer.
         */
        $reply = '';
        $this->TimerStart();

        foreach($this->mNoopCommand as $command)
        {
            socket_write($socket, $command, strlen($command));
            $buffer = @socket_read($socket, 2048);
            if (false !== $buffer)
            {
                $reply .= $buffer;
            } 
            else
            {
                $this->mErrorMessage = trim(socket_strerror(socket_last_error()));
                return false;
            } 
        } 
        /**
         * When the script reach this point, it means the TCP Service is responding to the NOOP Command
         * Stopping the timer, closing the connection, setting the mResponse property, returning true.
         */
        $this->TimerStop();
        socket_close($socket);
        $this->mResponse = $reply;
        return true;
    } 
} 

/**
 * TcpPingWrapper
 * Ping wrapper for processing multiple ping request
 * 
 * @package TcpPing
 * @author Andronicus Riyono 
 * @copyright Copyright (c) 2004 Andronicus Riyono <nick@riyono.com>
 * @version 1.0.0
 * @access public 
 */
class TcpPingWrapper
{
    var $mInfo;
    var $mInput;
    var $mOutput;
    var $mDebug;

    /**
     * TcpPingWrapper::TcpPingWrapper()
     * Constructor of TcpPingWrapper clas
     * 
     * @access public 
     */
    function TcpPingWrapper()
    {
        $this->mDebug = false;
        $this->mOutput = array();
    } 

    /**
     * TcpPingWrapper::ReadInputFile()
     * Reads the input from file
     * 
     * @param string $filename path to input file
     * @return boolean true when file read succeed, false when failed
     */
    function ReadInputFile($filename)
    {
        if (file_exists($filename))
        {
            $this->mInput = array();
            foreach(file($filename) as $value)
            {
                $this->mInput[] = rtrim($value);
            } 
            if (false !== $this->mDebug)
            {
                echo "Success: reading $filename as input.";
            } 
            return true;
        } 
        else
        {
            if (false !== $this->mDebug)
            {
                echo "Error: file $filename doesn't exists.";
            } 
            return false;
        } 
    } 

    /**
     * TcpPingWrapper::ReadFormInput()
     * Reads the input from form variable
     * 
     * @param string $varname input variable name. Defaults to "hosts" which means $_REQUEST['hosts'] will be used.
     * @return boolean true when file read succeed, false when failed
     */
    function ReadFormInput($varname = "hosts")
    {
        $input = $_REQUEST[$varname];
        if (is_array($input))
        {
            foreach($input as $value)
            {
                $this->mInput[] = rtrim($value);
            } 
        } 
        else
        {
            $this->mInput = rtrim($input);
        } 
    } 

    /**
     * TcpPingWrapper::SetInput()
     * Set the mInput property
     * 
     * mInput contains target hostname or IP Address.
     * mInput may be string or array of string.
     * 
     * @param mixed $input string or array of string. target hostname or IP Address.
     */
    function SetInput($input)
    {
        $this->mInput = $input;
    } 

    /**
     * SocketPingWrapper::Ping()
     * 
     * @return boolean true when ping is executed false when ping failed to execute.
     */
    function Ping($try = array('http', 'ftp', 'telnet', 'mail'))
    {
        if (empty($try))
        {
            $try = array('http', 'ftp', 'telnet', 'mail');
        } 
        else if (!is_array($try))
        {
            $try = array($try);
        } 
        if (empty($this->mInput))
        {
            if (false !== $this->mDebug)
            {
                echo "Error: no ping target specified.";
            } 
            return false;
        } 
        if (!is_array($this->mInput))
        {
            $this->mInput = array($this->mInput);
        } 
        foreach($this->mInput as $key => $host)
        {
            $this->mInfo = array();
            $up = false;
            foreach($try as $service)
            {
                $tping = new TcpPing($host, $service);
                if ($result = $tping->Ping())
                {
                    $up = true;
                    $this->mInfo[] = "service=$service time=" . $tping->GetTime();
                    break;
                } 
                else
                {
				    /**
					* If current error message is different from the last one, take it.
					*/
                    if (!sizeof($this->mInfo) || $tping->GetErrorMessage() != $this->mInfo[sizeof($this->mInfo)-1])
                    {
                        $this->mInfo[] = $tping->GetErrorMessage();
                    } 
                } 
            } 
            $this->mOutput[] = "$host [" . $tping->GetTargetAddress() . "] is  " . ($up ? 'Alive.' : 'Down!!') . ' ' . implode(" ", $this->mInfo);
        } 
        return true;
    } 

    /**
     * SocketPingWrapper::GetOutputAsString()
     * Ping result(s) as a string
     * 
     * @return string Ping result(s)
     * @access public 
     */
    function GetOutputAsString()
    {
        return implode("\r\n", $this->mOutput);
    } 

    /**
     * SocketPingWrapper::DisplayOutputAsText()
     * Display Ping Result(s) as plain text
     * 
     * @access public 
     */
    function DisplayOutputAsText()
    {
        echo $this->GetOutputAsString();
    } 

    /**
     * SocketPingWrapper::DisplayOutputAsHtml()
     * Display Ping Result(s) as html with line breaks (snippet only, no html nor head nor body tags)
     * 
     * @access public 
     */
    function DisplayOutputAsHtml()
    {
        echo nl2br(htmlentities($this->GetOutputAsString()));
    } 

	/**
     * SocketPingWrapper::DisplayOutputAsHtmlTable()
     * Display Ping Result(s) as html with table (snippet only, no html nor head nor body tags)
     * 
     * @access public 
     */
    function DisplayOutputAsHtmlTable()
    {
        echo $this->GetOutputAsHtmlTable();
    } 

    /**
     * SocketPingWrapper::DisplayOutputAsHtmlFancy()
     * Display Ping result(s) as a fancy html table (WARNING: using deprecated style attribute)
     * 
     * @access public 
     */
    function DisplayOutputAsHtmlFancy($tableSummary = "Ping Result(s)")
    {
        echo $this->GetOutputAsHtmlFancy();
    } 	
	
    /**
     * SocketPingWrapper::GetOutputAsHtmlTable()
     * Ping result(s) as a html table (snippet only, no html nor head nor body tags)
     * 
     * @return string Ping result(s) as a html table
     * @access public 
     */
    function GetOutputAsHtmlTable($tableSummary = "Ping Result(s)")
    {
        $string = $this->GetOutputAsString();
        preg_match_all('/^([^\[]*?)\[(.*?)\] is (.*?)(\.|!!)(.*)$/mi', $string, $matches);
        $buffer = '<table summary="' . $tableSummary . '">' . "\r\n";
        $buffer .= '<tr><th scope="col">Host</th><th scope="col">IP Address</th><th scope="col">Status</th><th scope="col">Additional Info</th></tr>' . "\r\n";
        foreach($matches[0] as $key => $value)
        {
            $buffer .= '<tr><td>' . htmlentities($matches[1][$key]) . '</td><td>' . htmlentities($matches[2][$key]) . '</td><td>' . htmlentities($matches[3][$key]) . '</td><td>' . $matches[5][$key] . '</td></tr>' . "\r\n";
        } 
        $buffer .= '</table>';
        return $buffer;
    } 

    /**
     * SocketPingWrapper::GetOutputAsHtmlFancy()
     * Ping result(s) as a html table (snippet only, no html nor head nor body tags)
     * 
     * @return string Ping result(s) as a fancy html table (WARNING: using deprecated style attribute)
     * @access public 
     */
    function GetOutputAsHtmlFancy($tableSummary = "Ping Result(s)")
    {
        $string = $this->GetOutputAsString();
        preg_match_all('/^([^\[]*?)\[(.*?)\] is (.*?)(\.|!!)(.*)$/mi', $string, $matches);
        $buffer = '<table summary="' . $tableSummary . '">' . "\r\n";
        $buffer .= '<tr style="background-color:#ddf;"><th scope="col">Host</th><th scope="col">IP Address</th><th scope="col">Status</th><th scope="col">Additional Info</th></tr>' . "\r\n";
        foreach($matches[0] as $key => $value)
        {
            $bg = ($matches[3][$key] == ' Alive') ? '#dfd' : '#fdd';
            $buffer .= '<tr style="background-color:' . $bg . ';"><td>' . htmlentities($matches[1][$key]) . '</td><td>' . htmlentities($matches[2][$key]) . '</td><td>' . htmlentities($matches[3][$key]) . '</td><td>' . $matches[5][$key] . '</td></tr>' . "\r\n";
        } 
        $buffer .= '</table>';
        return $buffer;
    } 

    /**
     * SocketPingWrapper::WriteOutputTextFile()
     * Write the ping result(s) to file as a plain text
     * 
     * @param string $filename File to write output to.
     */
    function WriteOutputTextFile($filename)
    {
        $fh = fopen($filename, "w");
        fwrite($fh, $this->GetOutputAsString());
        fclose($fh);
    } 

    /**
     * SocketPingWrapper::WriteOutputHtmlFile()
     * Write the ping result(s) to file as a html with line breaks (snippet only, no html nor head nor body tags)
     * 
     * @param string $filename File to write output to.
     */
    function WriteOutputHtmlFile($filename)
    {
        $fh = fopen($filename, "w");
        fwrite($fh, nl2br($this->GetOutputAsString()));
        fclose($fh);
    } 

    /**
     * SocketPingWrapper::WriteOutputHtmlFileFancy()
     * Write the ping result(s) to file as a html table (snippet only, no html nor head nor body tags)
     * 
     * @param string $filename File to write output to.
     */
    function WriteOutputHtmlFileFancy($filename)
    {
        $fh = fopen($filename, "w");
        fwrite($fh, $this->GetOutputAsHtmlFancy());
        fclose($fh);
    } 
} 

?>