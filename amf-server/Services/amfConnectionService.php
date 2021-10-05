<?php

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Vo/LoginResultVO.php';
require_once dirname(__FILE__) . '/Vo/BollyVO.php';

class amfConnectionService {
	
	static function getNewSession($id) {
		$holders = "x1x2x3x4x5x6x7x8x9";
		
		$rand = rand(0,3);
		$holders = str_replace("x1",$rand,$holders);
		$rand = rand(0,9);
		$holders = str_replace("x2",$rand,$holders);
		$rand = rand(0,9);
		$holders = str_replace("x3",$rand,$holders);
		$rand = rand(0,9);
		$holders = str_replace("x4",$rand,$holders);
		$rand = rand(0,9);
		$holders = str_replace("x5",$rand,$holders);
		$rand = rand(0,9);
		$holders = str_replace("x6",$rand,$holders);
		$rand = rand(0,9);
		$holders = str_replace("x7",$rand,$holders);
		$rand = rand(0,9);
		$holders = str_replace("x8",$rand,$holders);
		$rand = rand(0,9);
		$holders = str_replace("x9",$rand,$holders);
		
		$holders = $holders * rand(2,500) + rand(1,9) * $id;
		$holders .= rand(1,9);
		
		return floor(substr($holders,0,9));
	}
	
	function iniciarSesiónATravésDeNumeritosUnTantoPeculiares($sessionKey, $lang = null) {
		if($lang != 'ES') return null;
		
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			$stmt = $pdo->prepare("SELECT * FROM `users` WHERE `auth_token` = :ID LIMIT 1");
			$stmt->bindParam(':ID',$sessionKey,PDO::PARAM_INT);
			$stmt->execute();
			
			if ($stmt->rowCount() > 0) {
				$player = $stmt->fetch(PDO::FETCH_ASSOC); 
				$ld = date("Y-m-d",strtotime('sunday this week'));  
				
				$_christmas = $pdo->prepare("SELECT `value` FROM `states` WHERE `player_id` = ? AND `category_id` = 143 AND `value` = 1000 LIMIT 1");
				$_christmas->bindParam(1, $player['id'], PDO::PARAM_INT);
				$_christmas->execute();
				
				if($_christmas->rowCount() > 0) {
					$_christmas->closeCursor();
					$_christmas = $pdo->prepare("UPDATE `states` SET `value` = 0, `last_changed` = ? WHERE `category_id` = 143 AND `player_id` = ?");
					$ts = floor(microtime(true) * 1000);
					$_christmas->bindParam(1, $ts, PDO::PARAM_INT);
					$_christmas->bindParam(2, $player['id'], PDO::PARAM_INT);
					$_christmas->execute();
					$_christmas->closeCursor();
				}
				
				/*
				$_booster = $pdo->prepare("SELECT `value`,`last_changed` FROM `states` WHERE `player_id` = ? AND `name_id` = 0 AND `category_id` = 5 LIMIT 1");
				$_booster->bindParam(1,$player['id'],PDO::PARAM_INT);
				$_booster->execute();
				$_boosterd = $pdo->prepare("SELECT * FROM `boosting`");
				$_boosterd->execute();
				$_bs = $_boosterd->fetch(PDO::FETCH_ASSOC);
				$_bsu = $_booster->fetch(PDO::FETCH_ASSOC);
				if($_bs['date'] < date("Y-m-d",strtotime("now"))) {
					$_booster->closeCursor();
					$_bs['date'] = $nd = (string) date("Y-m-d",strtotime("+2 weeks") - (rand(1,7) * 86400000));
					$_booster = $pdo->prepare("UPDATE `boosting` SET `date` = ?");
					$_booster->bindValue(1,$nd);
					$_booster->execute();																	
					// (date("Y-m-d",strtotime("-1 week",strtotime('sunday last week'))) > date('Y-m-d',$_b['date']) && date("Y-m-d",strtotime('sunday last week')) <= date('Y-m-d',$_b['date']))
				}
				if($_bs['date'] == date("Y-m-d",strtotime("now")) && (int) $_bsu['value'] === 20 && $_bs['date'] <= date("Y-m-d",strtotime('sunday last week'))) {
					$_booster->closeCursor();
					$_booster = $pdo->prepare("UPDATE `states` SET `value` = 10 WHERE `player_id` = ? AND `category_id` = 5 AND `name_id` = 0");
					$_booster->bindParam(1,$player['id'],PDO::PARAM_INT);
					$_booster->execute();
				} elseif($_bs['date'] > date("Y-m-d",strtotime("now")) && (int) $_bsu['value'] < 20) {
					$_booster->closeCursor();
					$_booster = $pdo->prepare("UPDATE `states` SET `value` = 20 WHERE `player_id` = ? AND `category_id` = 5 AND `name_id` = 0");
					$_booster->bindParam(1,$player['id'],PDO::PARAM_INT);
					$_booster->execute();
				}*/
				
				if ($player['tour_finished']) {
					$update = $pdo->prepare("update users set login_count = login_count + 1 where id = ?");
					$update->bindParam(1, $player['id'], PDO::PARAM_INT);
					$update->execute();
				}
				
				$day = $this->getCurrentDayName();
				
				$_SESSION['playerId'] = $player['id'];
				$_SESSION['playerName'] = $player['display_name'];
				$_SESSION['playerPremium'] = $player['premium'] === "0"?false:true;
				$_SESSION['playerModerator'] = $player['sheriff'] === "0"?false:true;
				$_SESSION['playerCoins'] = $player['coins'];
				
				if(isset($_SESSION['gameserver'])) unset($_SESSION['gameserver']);
				
				unset($_SESSION['playerSalt']);
				/**
				$mo = isset($_SESSION['updated'])?true:false;
				if($mo){
					unset($_SESSION['updated']);
				}
				**/
				
				$playerInfo = $GLOBALS['database']::getPlayerInfo($player['id']);
				$gameServers = $GLOBALS['database']::getGameServers($lang);
				
				$tLoginResult = new LoginResultVO();
				$tLoginResult->playerInfo = $playerInfo;
				$tLoginResult->playerInfo->membershipStatus = $player['premium'];
				$tLoginResult->playerInfo->isPremium = boolval($player['premium']);
				$nses = self::getNewSession($player['id']);
				try{
					$update = $pdo->prepare("UPDATE `users` SET `auth_token` = :tock WHERE `id` = :pi");
					$update->bindParam(':tock',$nses,PDO::PARAM_INT);
					$update->bindParam(':pi',$player['id'],PDO::PARAM_INT);
					$update->execute();
				} catch(PDOException $e) {
					die();
				}
				$tLoginResult->ticketId = $nses;
				$tLoginResult->showNewsletterScreen = false;
				$tLoginResult->gameplayPanfu = 100;
				$tLoginResult->gameServers = $gameServers;
				$tLoginResult->date = floor(microtime(true) * 1000);
				$tLoginResult->loginCount = (int) $player['login_count'];
				$tLoginResult->goldPandaDay = false;
				$tLoginResult->blockedUser = boolval($player['muted']);
				$tLoginResult->membershipStatus = $player['premium'];
				$tLoginResult->email = $player['email'];
				if(($day === 'Sábado' || $day === 'Viernes') && !$_SESSION['playerPremium']) {
					$_SESSION['playerPremium'] = true;
					$tLoginResult->playerInfo->isPremium = true;
					$tLoginResult->playerInfo->membershipStatus = 1;
					$tLoginResult->goldPandaDay = true;
					$tLoginResult->promoMessageKey = $player['premium'] === "0"?"GOLDPACKAGEDAY_MESSAGE":null;
				} else if((int) $player['premium'] > 0 && $player['memExpiration'] != "0") {
					if(strtotime("now") > (int) $player['memExpiration']) {
						$pdo = $GLOBALS['database']::getConnection();
						$_a = $pdo->prepare("UPDATE `users` SET `premium` = 0, `memExpiration` = 0 WHERE `id` = ?");
						$_a->bindParam(1,$player['id'],PDO::PARAM_INT);
						$_a->execute();
						$tLoginResult->promoMessageKey = "EXPIRATION_ON_ACTION";
						$tLoginResult->membershipStatus = 0;
						$tLoginResult->playerInfo->isPremium = false;
						$_SESSION['playerPremium'] = false;
					}
				}
				
				$o = $pdo->prepare("SELECT * FROM `messageboard` WHERE `readed` = 0 AND `receiver` = ?");
				$o->bindParam(1,$player['id'],\PDO::PARAM_INT);
				$o->execute();
				$tLoginResult->unreadMessagesCount = $o->rowCount();
				
				$o->closeCursor();
				
				$o = $pdo->prepare("SELECT * FROM `messageboard` WHERE `receiver` = ?");
				$o->bindParam(1,$player['id'],\PDO::PARAM_INT);
				$o->execute();
				$tLoginResult->undeletedMessagesCount = $o->rowCount();
				
				$result = new AmfResponse();
				$result->statusCode = 0;
				$result->message = "success";
				$result->valueObject = $tLoginResult;
				return $result;
			}
			
			return null;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfConnectionService::doLoginSession\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function checkUserName($str)
	{
		$res = new AmfResponse();
		$res->statusCode = 6;
		$res->message = "FAILED";
		$res->valueObject = null;
		return $res;
	}
	
	function getCurrentDayName()
	{
		$dat = Array(
			"Mon" => "Lunes",
			"Tue" => "Martes",
			"Wed" => "Miércoles",
			"Thu" => "Jueves",
			"Fri" => "Viernes",
			"Sat" => "Sábado",
			"Sun" => "Domingo"
		);
		$str = date('D');
		
		return $dat[$str];
	}
	
	function checkEmailAddress($str)
	{
		$res = new AmfResponse();
		$res->statusCode = 6;
		$res->message = "mail not valid";
		$res->valueObject = null;
		return $res;
	}
	
	function doLogout() {
		unset($_SESSION['playerId']);
		unset($_SESSION['playerName']);
		unset($_SESSION['playerPremium']);
		
		session_destroy();
		
		$result = new AmfResponse();
		$result->statusCode = 0;
		$result->message = "success";
		$result->valueObject = null;
		return $result;
	}
	
	function setBirthday($birthday) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			$update = $pdo->prepare("update users set birthday = ? where id = ?");
			$update->bindParam(1, $birthday->date, PDO::PARAM_INT);
			$update->bindParam(2, $_SESSION['playerId'], PDO::PARAM_INT);
			$update->execute();
			
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = null;
			return $result;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfConnectionService::setBirthday\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function ping() {
		$_SESSION['playerId'] = $_SESSION['playerId'];
		$GLOBALS['session_check']();
		if ($_SESSION['playerId'] !== null) {
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "pong";
			$result->valueObject = null;
			return $result;
		} else {
			$result = new AmfResponse();
			$result->statusCode = 412;
			$result->message = "Session has expired";
			$result->valueObject = null;
			return $result;
		}
	}
	
}