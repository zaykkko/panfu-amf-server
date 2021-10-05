<?php

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Vo/UserActionDailyVO.php';
require_once dirname(__FILE__) . '/Vo/ListVO.php';
require_once dirname(__FILE__) . '/Vo/ItemVO.php';
require_once dirname(__FILE__) . '/Vo/BollyVO.php';
require_once dirname(__FILE__) . '/Vo/RewardVO.php';
require_once dirname(__FILE__) . '/Features/PetContext.php';
require_once dirname(__FILE__) . '/Features/Reporter.php';

class amfActionService {
	
	private $lastDoneActionTime = 0;
	private $doneToday = 0;
	private $doneTimes = 0;
	private $rewards = array(
		"1" => "gold:FALSE|items:FALSE|score:FALSE",
		"2" => "gold:FALSE|items:FALSE|score:FALSE",
		"3" => "gold:FALSE|items:103344|score:FALSE",
		"4" => "gold:FALSE|items:102685|score:FALSE",
		"5" => "gold:TRUE|items:FALSE|score:FALSE",
		"6" => "gold:LOSE|items:FALSE|score:1000",
		"7" => "gold:FALSE|items:100265|score:FALSE",
		"8" => "gold:FALSE|items:FALSE|score:FALSE",
		"9" => "gold:FALSE|items:100264|score:FALSE",
		"10" => "gold:FALSE|items:100263|score:FALSE",
		"11" => "gold:FALSE|items:FALSE|score:FALSE",
		"12" => "gold:FALSE|items:FALSE|score:2000",
		"13" => "gold:FALSE|items:FALSE|pet:100266|score:FALSE",
		"14" => "gold:FALSE|items:FALSE|score:FALSE",
		"15" => "gold:FALSE|items:FALSE|score:FALSE",
		"16" => "gold:FALSE|items:FALSE|score:FALSE",
		"17" => "gold:FALSE|items:103999|score:FALSE",
		"18" => "gold:FALSE|items:FALSE|score:FALSE",
		"19" => "gold:FALSE|items:100352|score:FALSE",
		"20" => "gold:FALSE|items:FALSE|score:FALSE",
		"21" => "gold:FALSE|items:100351|score:FALSE",
		"22" => "gold:FALSE|items:FALSE|score:FALSE",
		"23" => "gold:FALSE|items:FALSE|score:FALSE",
		"24" => "gold:FALSE|items:100393|score:FALSE",
		"25" => "gold:FALSE|items:FALSE|score:FALSE",
		"26" => "gold:FALSE|items:FALSE|score:FALSE",
		"27" => "gold:FALSE|items:FALSE|pet:100392|score:FALSE",
		"28" => "gold:FALSE|items:FALSE|score:FALSE",
		"29" => "gold:FALSE|items:FALSE|score:FALSE",
		"30" => "gold:FALSE|items:FALSE|score:FALSE",
		"31" => "gold:FALSE|items:100398|score:FALSE",
		"32" => "gold:FALSE|items:100397|score:FALSE",
		"33" => "gold:FALSE|items:FALSE|score:FALSE",
		"34" => "gold:FALSE|items:FALSE|score:FALSE",
		"35" => "gold:FALSE|items:FALSE|score:FALSE",
		"36" => "gold:FALSE|items:100356|score:FALSE",
		"37" => "gold:FALSE|items:FALSE|score:FALSE",
		"38" => "gold:FALSE|items:FALSE|score:FALSE",
		"39" => "gold:FALSE|items:100396|score:FALSE",
		"40" => "gold:FALSE|items:103150|score:FALSE",
		"41" => "gold:FALSE|items:FALSE|score:FALSE",
		"42" => "gold:FALSE|items:101658|score:FALSE",
		"43" => "gold:FALSE|items:FALSE|score:5000",
		"44" => "gold:FALSE|items:FALSE|score:FALSE",
		"45" => "gold:FALSE|items:101657|score:FALSE",
		"46" => "gold:FALSE|items:FALSE|score:FALSE",
		"47" => "gold:FALSE|items:103367|score:FALSE",
		"48" => "gold:FALSE|items:FALSE|score:FALSE",
		"49" => "gold:FALSE|items:FALSE|pet:101962|score:FALSE",
		"50" => "gold:FALSE|items:103341|score:FALSE",
		"51" => "gold:FALSE|items:FALSE|score:FALSE",
		"52" => "gold:FALSE|items:103343|score:FALSE",
		"53" => "gold:FALSE|items:103323|score:FALSE",
		"54" => "gold:FALSE|items:FALSE|score:FALSE",
		"55" => "gold:FALSE|items:FALSE|score:FALSE",
		"56" => "gold:FALSE|items:102355|score:FALSE",
		"57" => "gold:FALSE|items:FALSE|score:FALSE",
		"58" => "gold:FALSE|items:103322|score:FALSE",
		"59" => "gold:FALSE|items:FALSE|score:8000",
		"60" => "gold:FALSE|items:FALSE|score:FALSE",
		"61" => "gold:FALSE|items:FALSE|score:FALSE",
		"62" => "gold:FALSE|items:FALSE|score:FALSE",
		"63" => "gold:FALSE|items:100356|score:FALSE",
		"64" => "gold:FALSE|items:FALSE|score:20",
		"65" => "gold:FALSE|items:FALSE|score:FALSE",
		"66" => "gold:FALSE|items:101237|score:FALSE",
	);
	
	function getLastDoneActionToday($playerId, $action, $timeBetweenUse = 360000) {
		switch($action) {
			case 'cake':
			case 'waterbomb':
				$tUserAction = new UserActionDailyVO();
				$tUserAction->playerId = (int) $_SESSION['playerId'];
				$tUserAction->actionId = $action;
				$tUserAction->doneToday = 0;
				$tUserAction->time = time();
				$tUserAction->doneInTime = 0;
				$tUserAction->lastDoneActionTime = time();
				
				$result = new AmfResponse();
				$result->statusCode = 0;
				$result->message = $action;
				$result->valueObject = $tUserAction;
				return $result;
		}
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			$stmt = $pdo->prepare("select * from actions where player_id = ? and action = ?");
			$stmt->bindParam(1, $_SESSION['playerId'], PDO::PARAM_INT);
			$stmt->bindParam(2, $action, PDO::PARAM_STR);
			$stmt->execute();
			
			$timestamp = floor(microtime(true) * 1000);
			
			if ($stmt->rowCount() > 0) {
				$actionData = $stmt->fetch(PDO::FETCH_ASSOC);
				
				$this->lastDoneActionTime = $timestamp - (int) $actionData['timestamp'];
				$this->doneToday = 1;
				$this->doneTimes = (int) $actionData['done_times'];
				
				
				if($this->lastDoneActionTime > 360000){
					$update = $pdo->prepare("DELETE FROM `actions` WHERE `action` = ? AND `player_id` = ?");
					$update->bindParam(1, $action,\PDO::PARAM_STR);
					$update->bindParam(2, $_SESSION['playerId'],\PDO::PARAM_INT);
					$update->execute();
					$this->doneTimes = 0;
					$this->lastDoneActionTime = 0;
				}
				/* VALENTINE'S EVENT -> ICE,SLIME,LIGHTNING
				} else {
					if($this->lastDoneActionTime > 3600000){
						$update = $pdo->prepare("DELETE FROM `actions` WHERE `action` = ? AND `player_id` = ?");
						$update->bindParam(1, $action,\PDO::PARAM_STR);
						$update->bindParam(2, $_SESSION['playerId'],\PDO::PARAM_INT);
						$update->execute();
						$this->doneTimes = 0;
						$this->lastDoneActionTime = 0;
					}
				}
				*/
			}
			
			$tUserAction = new UserActionDailyVO();
			$tUserAction->playerId = (int) $_SESSION['playerId'];
			$tUserAction->actionId = $action;
			$tUserAction->doneToday = $this->doneToday;
			$tUserAction->time = $timestamp;
			$tUserAction->doneInTime = $this->doneTimes;
			$tUserAction->lastDoneActionTime = $this->lastDoneActionTime - 5 * 60;
			
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = $action;
			$result->valueObject = $tUserAction;
			return $result;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfActionService::getLastDoneActionToday\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function getItemInfo($id)
	{
		
		try{
			$pdo = $GLOBALS['database']::getConnection();
			$pl = $pdo->prepare("SELECT * FROM items WHERE id = ?");
			$pl->bindValue(1,$id);
			$pl->execute();
			
			if($pl->rowCount() > 0){
				return $pl->fetch(PDO::FETCH_ASSOC);
			}else{
				return NULL;
			}
		}catch(\PDOException $e){
			$error = date("d.m.Y H:i:s") . "\amfActionService::getLastDoneActionToday\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function addItem($id, $itemId) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			$stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
			$stmt->bindParam(1,$itemId,PDO::PARAM_INT);
			$stmt->execute();
			
			if($stmt->rowCount() > 0){
				$stmt2 = $pdo->prepare("SELECT * FROM inventory WHERE item_id = ? AND player_id = ? LIMIT 1");
				$stmt2->bindParam(1,$itemId,PDO::PARAM_INT);
				$stmt2->bindParam(2,$_SESSION['playerId'],PDO::PARAM_INT);
				$stmt2->execute();
				
				if($stmt2->rowCount() > 0){
					return true;
				}
				
				$item = $stmt->fetch(PDO::FETCH_ASSOC);
				
				$timestamp = time();
				
				if($item['type'] === "00" || $item['type'] === "13" || $item['type'] === "14" || $item['type'] === "17"){
					$itemType = "FURNITURE";
				}else{
					$itemType = "CLOTHES";
				}
				
				$insert = $pdo->prepare("INSERT INTO inventory (player_id, item_id, timestamp, bought, active, parameters, x, y, rot, ele_type) VALUES (:pid, :id, :time, '1', '0', '', '0', '0', '0', :type)");
				$insert->bindParam(":pid",$_SESSION['playerId'],PDO::PARAM_INT);
				$insert->bindParam(":id",$itemId,PDO::PARAM_INT);
				$insert->bindParam(":time",$timestamp,PDO::PARAM_INT);
				$insert->bindParam(":type",$itemType,PDO::PARAM_STR);
				$insert->execute();
			}
			
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\$GLOBALS['database']::addItems\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function getUser($id)
	{
		try{
			$pdo = $GLOBALS['database']::getConnection();
			$pl = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id AND `current_gameserver` > 0 LIMIT 1");
			$pl->bindParam(":id",$_SESSION['playerId'],PDO::PARAM_INT);
			$pl->execute();
			
			if($pl->rowCount() > 0){
				return $pl->fetch(PDO::FETCH_ASSOC);
			}
			
			return null;
		}catch(\PDOException $e){
			$error = date("d.m.Y H:i:s") . "\amfActionService::getLastDoneActionToday\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function performAction($playerId, $action, $same = false) {
		switch($action) {
			case 'played10':
				if($playerId != $_SESSION['playerId']) return null;
				
				$info = $this->getUser($playerId);
				
				$lastScore = isset($_SESSION['updated'])?$_SESSION['updated']:false;
				
				if($info != null && (time() - $lastScore > 9 * 60 || $lastScore === false || $same)) {
					if($info != NULL){
						$lvl = (int)$info['social_level'];
						$slvl = (int)$info['social_score'];
						
						if($lvl >= 66 && $slvl >= 100){
							return null;
						}
						
						$result = new ListVO();
						$rewards = array();
						
						if($lvl == 66) $lvl = $lvl - 1;
						
						$lvlInfo = $this->rewards[$lvl + 1];
						
						$slvle = $slvl + 25;
						
						switch((int)$lvl) {
							case $same === true:
								$slvle = 100;
								break;
							case 1:
							case 2:
							case 3:
								$slvle = 100;
								break;
							case 4:
								$slvle = $slvle + 22;
								break;
							case 5:
								$slvle = $slvle - 7;
								break;
							case $lvl <= 13:
								$slvle = $slvle - 10;
								break;
							case $lvl > 13 && $lvl <= 31:
								$slvle = $slvle - 13;
								break;
							case $lvl > 31 && $lvl <= 50:
								$slvle = $slvle - 18;
								break;
							case $lvl > 50 && $lvl < 59:
								$slvle = $slvle - 20;
								break;
							case $lvl >= 59:
								$slvle = $slvle - 21;
								break;
							case $slvl > 100:
								$slvle = 100;
								break;
							default:
								$slvle = $slvle - 1;
						}
						
						if($slvle >= 100 && $lvl < 66) {
							$momo = new RewardVO();
							$momo->type = "sp";
							$momo->levelStatus = 1;
							$momo->number = 0;
							array_push($rewards,$momo);
							$lvlcoo = explode('|',$lvlInfo);
							for($i = 0;$i < count($lvlcoo);$i++){
								$momo = explode(":",$lvlcoo[$i]);
								switch($momo[0]) {
									case "pet":
										$pet = new PetContext();
										$mum = $pet->getPetInfoById($momo[1],"bolly");
										if($mum != null){
											if((int)$momo[1] === 100266 || (int)$momo[1] === 100392){
													$pdo = $GLOBALS['database']::getConnection();
													$mow = $pdo->prepare("INSERT INTO pets (owner_id,pet_id,pet_name,pet_price,state,activity,health,rest,energy,rescueTime,x,y,z,colour,style,pet_type) VALUES (?,?,?,'0','normal',?,'100','100','100','-1',?,?,?,?,'standard','bolly')");
													
													$mow->bindParam(1,$_SESSION['playerId'],PDO::PARAM_INT);
													$mow->bindParam(2,$mum['id'],PDO::PARAM_INT);
													$mow->bindParam(3,$mum['name'],PDO::PARAM_STR);
													$mow->bindParam(4,$mum['idle'],PDO::PARAM_STR);
													$mow->bindParam(5,$mum['x'],PDO::PARAM_INT);
													$mow->bindParam(6,$mum['y'],PDO::PARAM_INT);
													$mow->bindParam(7,$mum['z'],PDO::PARAM_INT);
													$mow->bindParam(8,$mum['colour'],PDO::PARAM_INT);
													
													$mow->execute();
											}elseif((int)$momo[1] === 101962){
													$pdo = $GLOBALS['database']::getConnection();
													$mow = $pdo->prepare("INSERT INTO pets (owner_id,pet_id,pet_name,pet_price,state,activity,health,rest,energy,rescueTime,x,y,z,colour,style,pet_type) VALUES (?,'101962','BLUE_WOOBY','0','normal','woobyBlueClean','100','100','100','-1','600','35','5','-101962','standard','bolly')");
													$mow->bindParam(1,$_SESSION['playerId'],PDO::PARAM_INT);
													
													$mow->execute();
											}
												
											$bolly2 = new BollyVO();
											$bolly2->id = $mum['id'];
											$bolly2->name = $mum['name'];
											$bolly2->type = $mum['colour'];
											$bolly2->price = 0;
											$bolly2->state = 'normal';
											$bolly2->activity = $mum['idle'];
											$bolly2->health = 100;
											$bolly2->rest = 100;
											$bolly2->energy = 100;
											$bolly2->rescueTime = -1;
											$bolly2->x = $mum['x'];
											$bolly2->y = $mum['y'];
											$bolly2->z = $mum['z'];
											$bolly2->colour = rand(0,80);
											$bolly2->style = 'standard';
										}
										break;
									case "gold":
										$resp = new RewardVO();
										if((bool)$momo[1] === TRUE){
											$resp->type = "getgp";
											$GLOBALS['database']::premium("a");
											array_push($rewards,$resp);
										}
										elseif($momo[1] === "LOSE"){
											$resp->type = "losegp";
											$GLOBALS['database']::premium("b");
											array_push($rewards,$resp);
										}
										break;
									case "items":
										$resp = new RewardVO();
										if((bool)$momo[1] != FALSE) {
											$resp->type = "item";
											$item = $this->getItemInfo($momo[1]);
											$tItem = new ItemVO();
											$tItem->id = $item['id'];
											$tItem->name = $item['name'];
											$tItem->type = $item['type'];
											$tItem->price = $item['price'];
											$tItem->zettSort = $item['zett_sort'];
											$tItem->premium = boolval($item['premium']);
											$tItem->bought = true;
											$tItem->active = false;
											$tItem->movementType = $item['movement_type'];
											$resp->item = $tItem;
											
											if(boolval($item['premium']) && $_SESSION['playerPremium']){
												$suc = $this->addItem(null,$momo[1]);
												array_push($rewards,$resp);
											}elseif(!boolval($item['premium'])){
												$suc = $this->addItem(null,$momo[1]);
												array_push($rewards,$resp);
											}
										}
										break;
									case "score":
										$resp = new RewardVO();
										if($momo[1] != "FALSE"){
											$resp->type = "score";
											$resp->number = "".$momo[1]."";
											$GLOBALS['database']::updateScore($info['coins'] + $momo[1]);
											array_push($rewards,$resp);
										}
										break;
								}
							}
							$slvle = 0;
							$GLOBALS['database']::updateSocialScore($slvle,"new",$lvl + 1);
						} else {
							$momo = new RewardVO();
							$momo->type = "sp";
							$momo->levelStatus = 0;
							$momo->number = $slvle;
							array_push($rewards,$momo);
							$GLOBALS['database']::updateSocialScore($slvle);
						}
						
						if(!$same) {
							$_SESSION['updated'] = time();
						}
						
						$resulta = new ListVO();
						$resulta->list = $rewards;
						
						$result = new AmfResponse();
						$result->statusCode = 0;
						$result->message = $action;
						$result->valueObject = $resulta;
						return $result;
					}
				} else return null;
				break;
			case "slimebomb":
			case "masterOfSlime":
			case "icecubeSpell":
			case "fireworks":
			case "heartFireworks":
			case "frogSpell":
			case "snailSpell":
			case "pikachuSpell":
			case "chickSpell":
			case "rabbitSpell":
			case "rainbowRay":
			case "flowerPower":
			case "flyingHeart":
			case "masterOfIce":
			case "mousetransformation":
			case "spidertransformation":
			case "fogtransformation":
			case "robotertransformation":
			case "invisible":
			case "rabbittransformation":
			case "monsterFart":
			case "hole":
			case "teleportation":
			case "flyingPillow":
			case "kiss":
			case "megaphone":
			case "underwaterPump":
			case "sealFish":
			case "lightningPlayerSpell":
				try{
					$pdo = $GLOBALS['database']::getConnection();
					
					$oko = $pdo->prepare("select * from actions where player_id = :id and action = :ac limit 1");
					$oko->bindValue(":id",$_SESSION['playerId']);
					$oko->bindValue(":ac",$action);
					$oko->execute();
					if($oko->rowCount() === 0){
						$insert = $pdo->prepare("insert into actions (player_id, action, timestamp, done_times) values (:id,:ac,:time,'1')");
						$insert->bindParam(':id',$_SESSION['playerId'],PDO::PARAM_INT);
						$insert->bindParam(':ac', $action, PDO::PARAM_STR);
						$timestamp = floor(microtime(true) * 1000);
						$insert->bindParam(':time', $timestamp, PDO::PARAM_INT);
						$insert->execute();
						
					}else{
						$insert = $pdo->prepare("update actions set done_times = done_times + 1, timestamp = :time where player_id = :id and action = :ac");
						$timestamp = floor(microtime(true) * 1000);
						$insert->bindParam(':time', $timestamp, PDO::PARAM_INT);
						$insert->bindParam(':id', $_SESSION['playerId'], PDO::PARAM_INT);
						$insert->bindParam(':ac', $action, PDO::PARAM_STR);
						$insert->execute();
						
					}
					
					$result = new AmfResponse();
					$result->statusCode = 0;
					$result->message = $action;
					$result->valueObject = null;
					return $result;
				}catch(PDOException $e){
					$error = date("d.m.Y H:i:s") . "\amfActionService::getLastDoneActionToday\tError: (" . $e->getCode . ") " . $e->getMessage;
					throw new Exception($error);
				}
				break;
			case 'levelboost':
				try {
					$pdo = $GLOBALS['database']::getConnection();
					$ok = $pdo->prepare("SELECT * FROM `states` WHERE `player_id` = ? AND `category_id` = 5 AND `name_id` = 0 LIMIT 1");
					$ok->bindParam(1,$_SESSION['playerId'],PDO::PARAM_INT);
					$ok->execute();
					
					if($ok->rowCount() > 0) {
						$info = $ok->fetch(PDO::FETCH_ASSOC);
						
						if($info['value'] === 10) {
							return $this->performAction("played10",$_SESSION['playerId'],true);
						}
						
						$result = new AmfResponse();
						$result->statusCode = 1;
						$result->message = null;
						$result->valueObject = null;
							
						return $result;
					}
					
					$result = new AmfResponse();
					$result->statusCode = 1;
					$result->message = null;
					$result->valueObject = null;
							
					return $result;
				} catch(PDOException $e) {
					$error = date("d.m.Y H:i:s") . "\amfActionService::performAction\tError: (" . $e->getCode . ") " . $e->getMessage;
					throw new Exception($error);
				}
				break;
			default:
				return null;
		}
	}
	
}