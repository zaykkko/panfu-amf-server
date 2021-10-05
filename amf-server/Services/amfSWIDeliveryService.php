<?php

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Vo/SmallPlayerInfoVO.php';

class amfSWIDeliveryService {
	
	function getPlayerBySwid($swid) {
		try {
			$res = new AmfResponse();
			$res->statusCode = 1;
			$res->message = "failed";
			
			$pdo = $GLOBALS['database']::getConnection();
			
			if(!isset($swid)) {
				return $res;
			}
			
			$smh = $pdo->prepare("SELECT `id`,`username`,`current_gameserver` FROM `users` WHERE `swid` = ? LIMIT 1");
			$smh->bindParam(1,$swid,PDO::PARAM_STR);
			$smh->execute();
			
			if($smh->rowCount() > 0) {
				$args = $smh->fetch(PDO::FETCH_ASSOC);
				$o = new SmallPlayerInfoVO();
				$o->playerId = $args['id'];
				$o->playerName = $args['username'];
				$o->currentGameServer = $args['current_gameserver'];
				
				$res->message = "success";
				$res->valueObject = $o;
			}
			
			return $res;
			
		} catch(PDOException $e) {
            throw new Exception(date("d.m.Y H:i:s") . "\amfSWIDeliveryService\tError: (" . $e->getCode . ") " . $e->getMessage);
		}
	}
}