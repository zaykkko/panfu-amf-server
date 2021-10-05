<?php

require_once dirname(__FILE__) . '/WebSocket.php';

class Server {
	private static $servers = Array(
		0 => Array(
			"ip" => "127.0.0.1",
			"port" => "8080"
		),
		1 => Array(
			"ip" => "127.0.0.1",
			"port" => "6112"
		)
	);
	
	public static $result = "FAILED";
	public static $ip;
	public static $port;
	public static $id;
	
	function __construct() {
		if(!isset($_SESSION['gameserver'])) {
			try {
				$pdo = $GLOBALS['database']::getConnection();
				
				$user = $pdo->prepare("SELECT `current_gameserver` FROM `users` WHERE `ID` = :id LIMIT 1");
				$user->bindParam(':id',$_SESSION['playerId'],PDO::PARAM_INT);
				$user->execute();
				
				if($user->rowCount() > 0) {
					self::$id = $user->fetch(PDO::FETCH_ASSOC)['current_gameserver'];
					
					$_SESSION['gameserver'] = self::$id;
					
				} else {
					self::$id = 0;
					
				}
				
			} catch(PDOException $e) {
				exit;
				throw $e;
			}
		} else {
			self::$id = $_SESSION['gameserver'];
		}
		
		$info = self::$servers[self::$id];
		
		self::$ip = $info['ip'];
		self::$port = $info['port'];
		
		return true;
	}
	
	static function sendMessage($message, $report = false) {
		try {
			if($report) {
				$outdata = "POST / HTTP/1.1\r\n";
				$outdata .= "Host: 127.0.0.1::".self::$id."\r\n";
				$outdata .= "Connection: Close\r\n";
				$outdata .= "Content-Type: discordhook\r\n";
				$outdata .= "Message: **[ Servicio AMF | ".self::$id." ] -->** `$message`\r\n";
				$outdata .= "Username: AMF Info Bot\r\n";
				$outdata .= "Authorization: OP ovCHaC9ZVbFjmexlLt4W1tFR9VV7IovOUTYUo4fDLdu8r7GSk9L06fP\r\n";
				$outdata .= "X-Security-Key: OP 8GbGTxtLmc5ISK6Kw0ySX4tdADANHxiSsui95T9j4t952myZbWBHWCRPiaRS\r\n";
				$message = $outdata;
				self::$port = 6892;
			}
			
			$_listen = new WebsocketClient(self::$ip,self::$port,'https://localhost',$message,$report);
			
			return $_listen;
		} catch(Exception $e) {
			throw $e;
		}
	}
}

?>