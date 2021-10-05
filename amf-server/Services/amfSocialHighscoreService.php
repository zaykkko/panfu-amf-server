<?php

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Vo/ListVO.php';
require_once dirname(__FILE__) . '/Vo/SocialHighscoreVO.php';
require_once dirname(__FILE__) . '/Vo/NamesVO.php';

class amfSocialHighscoreService {
	private $games = Array(11,45,41);
	
	function getSocialHighscore($sender, $target)
	{
		$root = new ListVO();
		$raam = Array();
		
		if($target === -1){
			$pdo = $GLOBALS['database']::getConnection();
				
			for($i=0;$i<sizeof($this->games);$i++){
				$mo = $pdo->prepare("SELECT * FROM highscores WHERE player_id = :id AND game_id = :gi LIMIT 1");
				$mo->bindValue(':id',$_SESSION['playerId']);
				$mo->bindValue(':gi',$this->games[$i]);
				$mo->execute();
						
				if($mo->rowCount() > 0){
					$ok = $mo->fetch(PDO::FETCH_ASSOC);
						
					$games = new SocialHighscoreVO();
					$games->playerID = (int) $sender;
					$games->playerScore = (int) $ok['highscore'];
					$games->gameID = (int) $ok['game_id'];
					$games->otherPlayerID = -1;
						
					array_push($raam,$games);
				}else{
					$games = new SocialHighscoreVO();
					$games->playerID = (int) $sender;
					$games->playerScore = 0;
					$games->gameID = $this->games[$i];
					$games->otherPlayerID = -1;
					
					array_push($raam,$games);
				}
			}

			/**
			foreach($game as $item => $a){
				$games = new SocialHighscoreVO();
				$games->playerID = $sender;
				$games->playerScore = $a['highscore'];
				$games->gameID = $a['game_id'];
				$games->otherPlayerID = -1;
				$games->otherPlayerScore = 0;
				array_push($raam,$games);
			}
			**/

			$root->list = $raam;
			
			$rta = new AmfResponse();
			$rta->statusCode = 0;
			$rta->message = "success";
			$rta->valueObject = $root;
			
			return $rta;
		}else{
			$pdo = $GLOBALS['database']::getConnection();
			
			for($i=0;$i<sizeof($this->games);$i++){
				$games = new SocialHighscoreVO();
				$games->gameID = $this->games[$i];
				
				$me = $pdo->prepare("SELECT * FROM highscores WHERE player_id = :id AND game_id = :gi LIMIT 1");
				$me->bindParam(":id",$_SESSION['playerId'],PDO::PARAM_INT);
				$me->bindParam(":gi",$this->games[$i],PDO::PARAM_INT);
				$me->execute();
				
				if($me->rowCount() > 0){
					$info = $me->fetch(PDO::FETCH_ASSOC);
					
					$games->playerID = $info['player_id'];
					$games->playerScore = $info['highscore'];
					
				}else{
					$games->playerID = $_SESSION['playerId'];
					$games->playerScore = 0;
				}
				
				$other = $pdo->prepare("SELECT * FROM highscores WHERE player_id = :id AND game_id = :gi LIMIT 1");
				$other->bindParam(":id",$target,PDO::PARAM_INT);
				$other->bindParam(":gi",$this->games[$i],PDO::PARAM_INT);
				$other->execute();
				
				if($other->rowCount() > 0){
					$info2 = $other->fetch(PDO::FETCH_ASSOC);
					
					$games->otherPlayerID = $info2['player_id'];
					$games->otherPlayerScore = $info2['highscore'];
				}else{
					$games->otherPlayerID = $target;
					$games->otherPlayerScore = 0;
				}
				
				array_push($raam,$games);
			}
		}
		
		$root->list = $raam;
		
		$rta = new AmfResponse();
		$rta->statusCode = 0;
		$rta->message = "success";
		$rta->valueObject = $root;
		
		return $rta;
	}
	
}