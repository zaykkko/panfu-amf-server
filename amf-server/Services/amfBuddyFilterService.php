<?php
require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Vo/ListVO.php';

class amfBuddyFilterService {
	
	function listFilteredBuddies()
	{
		$db = $GLOBALS['database']::getConnection();
		$id = $db->prepare("SELECT ignored FROM users WHERE id = ? LIMIT 1");
		$id->bindParam(1,$_SESSION['playerId'],PDO::PARAM_INT);
		$id->execute();
		
		$holder = Array();
		
		//this ignore system is weird af
		
		//BUDDY1 => ignored by THIS user
		//BUDDY2 => ignored by OTHER user
		
		if($id->rowCount() > 0){
			$info = $id->fetchAll();
			
			$ipbm = new AmfResponse();
			$ipbm->statusCode = 0;
			$ipbm->message = "users ignored by ".$_SESSION['playerName'].", oh there's my name.";
			$ipbm->valueObject = new ListVO();
			
			$ignored = $info[0]['ignored'];
			
			if($ignored === null){
				$ipbm->message = "No one was ignored, lol";
				$ipbm->valueObject->list = Array();
			}else{
				$arr = explode('|',$ignored)[0];
				
				foreach($arr as $player){
					$mo = new stdClass();
					$mo->buddy1 = $player;
					$mo->buddy2 = -1;
					array_push($holder,$mo);
				}
				
				$arr = explode('|',$ignored)[1];
				
				foreach($arr as $player){
					$mo = new stdClass();
					$mo->buddy1 = -1;
					$mo->buddy2 = $player;
					array_push($holder,$mo);
				}

				$ipbm->valueObject->list = $holder;
			}
		}else{
			$ipbm = new AmfResponse();
			$ipbm->statusCode = 0;
			$ipbm->message = "No one was ignored, lol";
			$ipbm->valueObject = new ListVO();
			$ipbm->valueObject->list = Array();
		}
		
		return $ipbm;
	}
	
}