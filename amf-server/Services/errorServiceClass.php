<?php
require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Vo/AMFServiceErrorVO.php';
require_once dirname(__FILE__) . '/Features/ServerManager.php';

final class errorServiceClass {
	
	function showEverythingIsFine($errorReason, $result, $restart)
	{
		header($_SERVER['SERVER_PROTOCOL']." 500 Internal Server Error");

		$timestamp = floor(microtime(true)*1000);

		if(isset($_SESSION['playerId'])) {
			try {
				$pdo = $GLOBALS['database']::getConnection();
				
				$_r = $pdo->prepare("INSERT INTO `amferrors` (`timestamp`, `reason`, `result`, `target_id`, `level`) VALUES (?,?,?,?,?)");
				$_r->bindParam(1,$timestamp,PDO::PARAM_INT);
				$_r->bindParam(2,$errorReason,PDO::PARAM_STR);
				$_r->bindParam(3,$result,PDO::PARAM_STR);
				$_r->bindParam(4,$_SESSION['playerId'],PDO::PARAM_INT);
				$_r->bindParam(5,$restart,PDO::PARAM_STR);
				$_r->execute();
				
			} catch(PDOException $e) {
				$errorReason = $errorReason . "|" . $e->getMessage;
			}
			
			try {
				$info = new Server();
				if($info) {
					try {
						$r = $info::sendMessage('<msg t="sys"><body action="suspicious"><security id="report"><ticket><![CDATA[oauth:VfXPfGcMVg64trZRFYXLktTpr2xCZNMnhM3E4cGR7gCAPThr39nEvG3C8]]></ticket><ticketId><![CDATA[oauth:yFZJKzvuZsKJEuRXQ3XQC4wdF2kFtA65PyHYsGPDQ32uyJdLRSFmpg53q]]></tickerId><reportTicket><![CDATA[oauth:LUPUvwXEwPNXg4QUYQwUcCNCAXVCDEYWeqTJmNHa92MxHh2vdxXxrJyMC]]></reportTicket></security><message><reason><![CDATA[Utilización de programas para añadirse a sí mismo en la lista de amigos.]]></reason><user><![CDATA['.$_SESSION['playerId'].']]></user><info function="amfPlayerService.addToBuddyList" arguments="'.$_SESSION['playerId'].'" actioning="KICK"></info></message></body></msg>');
					} catch(Exception $e) {exit;}
				}
			} catch(Exception $e) {}
		}

		$resp = new AmfResponse();
		$resp->statusCode = 1;
		$resp->message = "failure";
		$resp->valueObject = new AMFServiceErrorVO();
		if(isset($_SESSION['playerModerator']) && $_SESSION['playerModerator']) {
			$resp->valueObject->reason = " #".$errorReason;
			$resp->valueObject->result = " #".$result;
			$resp->valueObject->level = " #".$restart;
			$resp->valueObject->userRecurred = $_SESSION['playerId'];
			$resp->valueObject->service = 'Información recopilada y reportada.';
		} else {
			$resp->valueObject->reason = "???";
			$resp->valueObject->result = "???";
			$resp->valueObject->level = "???";
			$resp->valueObject->userRecurred = "???";
			$resp->valueObject->service = "???";
		}
		
		return $resp;
	}
}