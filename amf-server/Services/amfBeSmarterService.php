<?php
require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Vo/BeSmarterScoreVO.php';
require_once dirname(__FILE__) . '/Features/Reporter.php';

class amfBeSmarterService {
	private $maxPoints = 1000;
	private $incorrect = 10;
	private $correct = 10;
	
	function loadLeadingPlayer($info = false)
	{
		try{
			$pdo = $GLOBALS['database']::getConnection();
			
			$mo = $pdo->prepare("SELECT * FROM besmarter");
			$mo->execute();
			
			$users = $mo->fetchAll();
			
			$maxUser = null;
			$maxUserT = null;
			
			foreach($users as $player => $info) {
				if((int)$info['timestamp'] < strtotime("now")) {
					$a = $pdo->prepare("DELETE FROM `besmarter` WHERE `player_id` = ?");
					$a->bindParam(1,$info['player_id'],PDO::PARAM_INT);
					$a->execute();
				} else {
					switch($maxUserT) {
						case (isset($maxUser->time) && (int)$info['total_points'] == $maxUser->points && (int)$info['correct_answers'] == $maxUser->correctAnswers && (int)$info['incorrect_answers'] == $maxUser->falseAnswers):
							if(ISSET($maxUser->time)) {
								if((int)$info['timing'] == $maxUser->time) {
									if((int)$info['timestamp'] < $maxUserT) {
										$maxUser->correctAnswers = $info['correct_answers'];
										$maxUser->playerName = $info['username'];
										$maxUser->playerId = (int) $info['player_id'];
										$maxUser->points = (int) $info['total_points'];
										$maxUser->falseAnswers = (int) $info['incorrect_answers'];
										$maxUser->time = (int) $info['timing'];
										$maxUserT = (int) $info['timestamp'];
										break;
									} else break;
								} elseif((int)$info['timing'] > $maxUser->time) break;
							}
						case (isset($maxUser->time) && (int)$info['total_points'] > $maxUser->points && (int)$info['correct_answers'] > $maxUser->correctAnswers && (int)$info['incorrect_answers'] < $maxUser->falseAnswers):
						case $maxUser === null:
							$maxUser = new BeSmarterScoreVO();
							$maxUser->correctAnswers = (int)$info['correct_answers'];
							$maxUser->playerName = $info['username'];
							$maxUser->playerId = (int)$info['player_id'];
							$maxUser->points = (int)$info['total_points'];
							$maxUser->falseAnswers = (int)$info['incorrect_answers'];
							$maxUser->time = (int)$info['timing'];
							$maxUserT = (int)$info['timestamp'];
							break;
					}
				}
			}
			
			if($maxUser === NULL) {
				$maxUser = new BeSmarterScoreVO();
				$maxUser->correctAnswers = 99;
				$maxUser->playerName = $_SESSION['playerName'];
				$maxUser->playerId = $_SESSION['playerId'];
				$maxUser->points = 9999999;
				$maxUser->falseAnswers = 55;
				$maxUser->time = 1200000;
				
				$res = new AmfResponse();
				$res->statusCode = 0;
				$res->message = "success";
				$res->valueObject = $maxUser;
			} else {
				$res = new AmfResponse();
				$res->statusCode = 0;
				$res->message = "success";
				$res->valueObject = $maxUser;
			}
			
			return $res;
			
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfBeSmarterService::loadLeadingPlayer\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function putScore($points, $totalCorrects, $totalWrong, $totalTime, $md5Hash)
	{
		
		if(!is_numeric((int)$totalCorrects) || !is_numeric((int)$totalWrong) || !is_numeric((int)$totalTime) || !is_numeric((int)$points) || (int)$totalCorrects > 10 || (int)$totalWrong > 10 || (int)$points > 1000) return null;
		
		$pdo = $GLOBALS['database']::getConnection();
		
		$salt = $pdo::getSalt();
		
		$ma = $pdo->prepare("SELECT * FROM `besmarter` WHERE `player_id` = ? LIMIT 1");
		$ma->bindParam(1,$_SESSION['playerId'],PDO::PARAM_INT);
		$ma->execute();
		
		if($ma->rowCount() > 0) {
			$info = $ma->fetch(PDO::FETCH_ASSOC);
			switch($points) {
				case (int)$info['total_points'] < $points:
				case (int)$info['totalCorrects'] < $totalCorrects:
				case (int)$info['incorrect_answers'] > $totalWrong:
				case (int)$info['timing']>$totalTime:
					$change = true;
					break;
				default:
					$change = false;
			}
			
			
			if($change) {
				$on = $pdo->prepare("UPDATE besmarter SET total_points = ?, correct_answers = ?, incorrect_answers = ?, timing = ? WHERE player_id = ?");
				$on->bindValue(1,$points);
				$on->bindValue(2,$totalCorrects);
				$on->bindValue(3,$totalWrong);
				$on->bindValue(4,$totalTime);
				$on->bindValue(5,$_SESSION['playerId']);
				$on->execute();
			}
		} else {
			$on = $pdo->prepare("INSERT INTO besmarter (total_points,correct_answers,incorrect_answers,timestamp,player_id,username,timing) VALUES (?,?,?,?,?,?,?)");
			$on->bindValue(1,$points);
			$on->bindValue(2,$totalCorrects);
			$on->bindValue(3,$totalWrong);
			$timestamp = strtotime("now +1 month");
			$on->bindValue(4,$timestamp);
			$on->bindValue(5,$_SESSION['playerId']);
			$on->bindValue(6,$_SESSION['playerName']);
			$on->bindValue(7,$totalTime);
			$on->execute();
		}
		
		$ok->statusCode = 0;
		$ok->message = "success";
		return $ok;
	}
	
	function loadBestResult($id)
	{
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			$mo = $pdo->prepare("SELECT * FROM besmarter WHERE player_id = ? LIMIT 1");
			$mo->bindParam(1,$id,PDO::PARAM_INT);
			$mo->execute();
			
			if($mo->rowCount() > 0){
				$info = $mo->fetch(PDO::FETCH_ASSOC);
				
				$maxPoints = new BeSmarterScoreVO();
				$maxPoints->correctAnswers = (int) $info['correct_answers'];
				$maxPoints->playerName = $info['username'];
				$maxPoints->playerId = (int) $info['player_id'];
				$maxPoints->points = (int) $info['total_points'];
				$maxPoints->falseAnswers = (int) $info['incorrect_answers'];
				$maxPoints->time = (int) $info['timing'];
			}else{
				$maxPoints = new BeSmarterScoreVO();
				$maxPoints->correctAnswers = 0;
				$maxPoints->playerName = 0;
				$maxPoints->playerId = $id;
				$maxPoints->points = 0;
				$maxPoints->falseAnswers = 0;
				$maxPoints->time = 0;
			}
			
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = $maxPoints;
			
			return $result;
			
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfBeSmarterService::loadBestResult\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
}