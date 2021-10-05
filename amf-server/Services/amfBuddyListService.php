<?php

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Vo/BuddyVO.php';
require_once dirname(__FILE__) . '/Vo/ListVO.php';

class amfBuddyListService {
	
	function getCompleteBuddyList($playerId) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			$stmt = $pdo->prepare("SELECT DISTINCT * FROM buddies WHERE player_id = ?");
			$stmt->bindParam(1, $playerId, PDO::PARAM_INT);
			$stmt->execute();
			$lisa = $stmt->fetchAll();
			
			$storage = array();
			
			foreach($lisa as $buddy) {
				$buddyStmt = $pdo->prepare("SELECT DISTINCT * FROM users WHERE id = ? LIMIT 1");
				$buddyStmt->bindParam(1,$buddy['buddy_id'],PDO::PARAM_INT);
				$buddyStmt->execute();
				$buddyData = $buddyStmt->fetch(PDO::FETCH_ASSOC);
				
				$tBuddy = new BuddyVO();
				$tBuddy->id = (int) $buddy['buddy_id'];
				$tBuddy->name = $buddyData['username'];
				$tBuddy->premium = boolval($buddyData['premium']);
				$tBuddy->bestfriend = boolval($buddy['best_friend']);
				$tBuddy->currentGameServer = $buddyData['current_gameserver'];
				$tBuddy->socialLevel = (int) $buddyData['social_level'];
				
				array_push($storage, $tBuddy);
			}
			
			$tList = new ListVO();
			$tList->list = $storage;
			
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = $tList;
			return $result;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfBuddyListService::getCompleteBuddyList\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function changeBestFriend($oldBff, $newBff) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			if($newBff == "0" || $newBff == 0) {		
				if($oldBff != 0) {
					$bff = $pdo->prepare("UPDATE `users`, `buddies` SET users.best_friend = '', buddies.best_friend = 0 WHERE users.id = ? AND buddies.player_id = ? AND buddies.buddy_id = ?");
					$bff->bindParam(1,$_SESSION['playerId'],PDO::PARAM_INT);
					$bff->bindParam(2,$_SESSION['playerId'],PDO::PARAM_INT);
					$bff->bindParam(3,$oldBff,PDO::PARAM_STR);
					$bff->execute();
				}
			} else {
				$name = $pdo->prepare("SELECT `username` FROM `users` WHERE `id` = :id LIMIT 1");
				$name->bindParam(':id',$newBff,PDO::PARAM_INT);
				$name->execute();
				
				if($name->rowCount() > 0) {
					$name = $name->fetch(PDO::FETCH_ASSOC)['username'];
					$bff = $pdo->prepare("UPDATE `users`, `buddies` SET users.best_friend = :bff, buddies.best_friend = 1 WHERE users.id = :id AND buddies.player_id = :id AND buddies.buddy_id = :ida");
					$bff->bindParam(":bff",$name,PDO::PARAM_STR);
					$bff->bindParam(":id",$_SESSION['playerId'],PDO::PARAM_INT);
					$bff->bindParam(":ida",$newBff,PDO::PARAM_STR);
					$bff->execute();
					
					if($oldBff != 0 && $oldBff != 0) {
						$bff = $pdo->prepare("UPDATE `buddies` SET `best_friend` = '' WHERE `player_id` = ? AND `buddy_id` = ?");
						$bff->bindParam(1,$_SESSION['playerId'],PDO::PARAM_INT);
						$bff->bindParam(1,$oldBff,PDO::PARAM_INT);
						$bff->execute();
					}
				}
			}
			
			$amf = new AmfResponse();
			$amf->statusCode = 0;
			$amf->valueObject = null;
			$amf->message = $newBff == 0?'N/A':$name;
			
			return $amf;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfBuddyListService::changeBestFriend\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}

	
}