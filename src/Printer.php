<?php

namespace Acgy\FlashforgeApi;

class Printer {
    private $ip_address;
    private $port;
    private $request_messages = [
        'control' => '~M601 S1\r\n',
        'info' => '~M115\r\n',
        'position' => '~M114\r\n',
        'temperature' => '~M105\r\n',
        'progress' => '~M27\r\n',
        'status' => '~M119\r\n'
    ];
    private $request_regexs = [
        'control' => '#(Control Success)#',
        'info' => '',
        'position' => '#X:(-?[0-9\.]+) Y:(-?[0-9\.]+) Z:(-?[0-9\.]+) A:(-?[0-9\.]+)#',
        'temperature' => '#T0:([0-9]+) /([0-9]+)#',
        'progress' => '#printing byte ([0-9]+)/([0-9]+)#',
        'status' => '#MachineStatus: (.+)#'
    ];

    /**
     * Constructor
     *
     * @param string $printer_address The printer IP address
     * @param string $port The printer port, by default 8899
     *
     **/
    function __construct($ip_address, $port = '8899') {
        $this->ip_address = $ip_address;
        $this->port = $port;
    }

    /**
     * Sends a request to the printer a returns a string with the output
     *
     * @param string $message_data The request to send to the printer
     * @return String
     **/
    public function send_and_receive($message_data){
        // instanciating the socket
        $socket = socket_create(AF_INET, SOCK_STREAM, 0);
        if ($socket === false) {
            echo "socket_create() failed: " . socket_strerror(socket_last_error()) . "\n";
            return false;
        }
        // connecting to the printer
        $connection = socket_connect ($socket, $this->ip_address, $this->port);
        if ($connection === false) {
            echo "Connection failed\n";
            return false;
        }
        // send the request to the printer
        $message = $message_data;
        socket_send($socket, $message, strlen($message), 0);
        // receiving the response from the printer
        $response = "";
        socket_recv ($socket, $response , 1024, 0);
        // closing the socket
        socket_close($socket);
        // returning the response
        return $response;
    }

    /**
     * Requests an information to the printer
     *
     * @param string $request_message The information to get
     * @param boolean $verbose True for verbose mode
     * @return string Information
     **/
    public function get($request_message, $verbose = false)
    {
        // checking that the requested information is available in the requests messages
        if (array_key_exists($request_message, $this->request_messages)){
            $output = $this->send_and_receive($this->request_messages[$request_message]);
            if ($verbose){
                return $output;
            }
            // extracting the information from the output
            else if (!empty($this->request_regexs[$request_message]) && preg_match($this->request_regexs[$request_message], $output, $matches)){
                unset($matches[0]);
                return $matches;
            }
            else return $output;
        }
        else {
            echo "Request '".$request_message."' is not available";
            return false;
        }
    }
}
?>