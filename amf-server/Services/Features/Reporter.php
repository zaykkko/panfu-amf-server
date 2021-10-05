<?php
//namespace AMFController\Report;
require_once dirname(__FILE__) . '/WebSocket.php';
require_once dirname(__FILE__) . '/ServerManager.php';

class AMFReport {
	private $reason;
	private $targetId;
	private $function;
	private $container;
	private $message;
	private $server;
	private $securityTicket = 'oauth:yFZJKzvuZsKJEuRXQ3XQC4wdF2kFtA65PyHYsGPDQ32uyJdLRSFmpg53q';
	private $reporterTicket = 'oauth:LUPUvwXEwPNXg4QUYQwUcCNCAXVCDEYWeqTJmNHa92MxHh2vdxXxrJyMC';
	private $playerServiceTicket = 'oauth:yFZJKzvuZsKJEuRXQ3XQC4wdF2kFtA65PyHYsGPDQ32uyJdLRSFmpg53q';
	private $cryptationID = 0;
	
	function __construct($reason, $targetId, $functionName, $args = 'NONE', $message = 'WARN_USER') {
		$this->reason = $reason;
		$this->targetId = $targetId;
		$this->func = $functionName;
		$this->container = $args;
		$this->message = $message;
		try {
			$this->server = new Server();
		} catch(Exception $e) {}
	}
	
	function sendReport() {
		$message = "[INTENTO DE MANIPULACIÓN] ->\r\nInformacion => Nombre del usuario: ". $_SESSION['playerName']." e ID de usuario: ". $_SESSION['playerId'].".\r\nRazón => {$this->reason}\r\nFunción => {$this->func}\r\nArgumentos dados => {$this->container}\r\nTipo de accionar => {$this->message}.";
		if($this->server) {
			try {
				$this->server->sendMessage($this->message,true);
			} catch(Exception $e) {}
		}
		
		return session_destroy();
	}
}

?>