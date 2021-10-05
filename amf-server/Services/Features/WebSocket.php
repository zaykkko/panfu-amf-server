<?php

class WebSocketError extends Exception {}

class WebsocketClient {

    private $_Socket = null;
	private $_origin = '';

    function __construct($host, $port, $origin, $messenger, $report) {
		$this->_origin = $origin;
		
		try {
			$p = $this->_connect($host, $port, $messenger, $report);
			
			return $p;
		} catch(Exception $e) {
			throw $e;
		}
    }

    function __destruct() {
        $this->_disconnect();
    }

    function sendXML($data) {
        if(fwrite($this->_Socket, "\x00" . $data . "\xff")) {
			$wsData = fread($this->_Socket, 2000);
			$retData = trim($wsData, "\x00\xff");
			return $retData;
		} else {
			throw new Exception('Error:' . $errno . ':' . $errstr);
		}
    }

    function _connect($host, $port, $msg, $reporting) {
        $key1 = $this->_generateRandKey(32);
        $key2 = $this->_generateRandKey(32);
        $key3 = $this->_generateRandKey(8,false,true);

		if(!$reporting) {
			$header = "GET /echo HTTP/1.1\r\n";
			$header.= "Upgrade: WebSocket\r\n";
			$header.= "Connection: Upgrade\r\n";
			$header.= "Host: " . $host . ":" . $port . "\r\n";
			$header.= "Origin: " . $this->_origin . "\r\n";
			$header.= "Sec-WebSocket-Key1: " . $key1 . "\r\n";
			$header.= "Sec-WebSocket-Key2: " . $key2 . "\r\n";
			$header.= "Authentication-Ticket: a7Q1mozDczvic5701966715a976cd9219253.77746469\r\n";
			$header.= "X-Control-Command: $" . $msg . "$\r\n";
			$header.= "sec-websocket-version: 13\r\n";
			$header.= "Upgrape-loop: -1\r\n";
			$header.= "\r\n";
			$header.= $key3;
		} else {
			$header = $msg;
		}


        $this->_Socket = fsockopen($host, $port, $errno, $errstr, 2);
        if(fwrite($this->_Socket, $header)) {

			return true;
		} else {
			throw new Exception('Error: ' . $errno . ':' . $errstr);
		}
    }

    function _disconnect() {
        fclose($this->_Socket);
    }

    function _generateRandKey($length = 10, $addSpaces = true, $addNumbers = true) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $useChars = array();
        for ($i = 0; $i < $length; $i++) {
            $useChars[] = $characters[mt_rand(0, strlen($characters) - 1)];
        }
        if ($addSpaces === true) {
            array_push($useChars, ' ', ' ', ' ', ' ', ' ', ' ');
        }
        if ($addNumbers === true) {
            array_push($useChars, rand(0, 9), rand(0, 9), rand(0, 9));
        }
        shuffle($useChars);
        $randomString = trim(implode('', $useChars));
        $randomString = substr($randomString, 0, $length);
        return $randomString;
    }

}

?>