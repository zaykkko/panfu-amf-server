<?php 

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Features/PetContext.php';
require_once dirname(__FILE__) . '/Vo/BollyVO.php';

class amfBollyService {
	
	function purchaseBolly($bollyId)
	{
		if($bollyId > 19999 && $bollyId < 20006 && $_SESSION['playerPremium']) {
			$pdo = $GLOBALS['database']::getConnection();
			
			$pet = new PetContext();
			$pets = $pet->getPetInfoById($bollyId,"bolly");
			$timestamp = strtotime("now");
			
			if($pets != NULL){
				$player = $pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
				$player->bindParam(1,$_SESSION['playerId'],PDO::PARAM_INT);
				$player->execute();
				
				$info = $player->fetch(PDO::FETCH_ASSOC);
				
				if($info['coins'] >= 2500){
					$player->closeCursor();
					
					$player = $pdo->prepare("UPDATE users SET coins = coins - 2500 WHERE id = ?");
					$player->bindParam(1,$_SESSION['playerId'],PDO::PARAM_INT);
					$player->execute();
				
					$ko = $pdo->prepare("SELECT * FROM pets WHERE pet_id = ? AND owner_id = ?");
					$ko->bindParam(1,$bollyId,PDO::PARAM_INT);
					$ko->bindParam(2,$_SESSION['playerId'],PDO::PARAM_INT);
					$ko->execute();
					
					if($ko->rowCount() === 0){
						$s = $pdo->prepare("INSERT INTO pets (owner_id,pet_id,pet_name,pet_price,activity,health,rest,energy,rescueTime,x,y,z,colour,style,pet_type,state) VALUES (?,?,?,'2500',?,'100','100','100',?,?,?,?,?,?,'bolly','normal')");
						
						$s->bindValue(1,$_SESSION['playerId']);
						$s->bindValue(2,$bollyId);
						$s->bindValue(3,$pets['name']);
						$s->bindValue(4,$pets['idle']);
						$s->bindValue(5,$timestamp);
						$s->bindValue(6,$pets['x']);
						$s->bindValue(7,$pets['y']);
						$s->bindValue(8,$pets['z']);
						$s->bindValue(9,$pets['colour']);
						$s->bindValue(10,$pets['style']);
						$s->execute();
						
						$mo = new AmfResponse();
						$mo->statusCode = 0;
						$mo->message = "success";
						$mo->valueObject = new BollyVO();
						$mo->valueObject->id = $bollyId;
						$mo->valueObject->name = $pets['name'];
						$mo->valueObject->type = (int) $pets['colour'];
						$mo->valueObject->price = 2500;
						$mo->valueObject->state = "normal";
						$mo->valueObject->activity = $pets['idle'];
						$mo->valueObject->health = 100;
						$mo->valueObject->rest = 100;
						$mo->valueObject->energy = 100;
						$mo->valueObject->rescueTime = -1;
						$mo->valueObject->x = (int) $pets['x'];
						$mo->valueObject->y = (int) $pets['y'];
						$mo->valueObject->z = (int) $pets['z'];
						$mo->valueObject->colour = rand(0,50);
						$mo->valueObject->style = "standard";
						
						return $mo;
					}else{
						$mo = new AmfResponse();
						$mo->statusCode = 6;
						$mo->message = "failed - user already bought this pet";
						$mo->valueObject = null;
					
						return $mo;
					}
				}else{
					$mo = new AmfResponse();
					$mo->statusCode = 6;
					$mo->message = "failed - poca money :(";
					$mo->valueObject = null;
					
					return $mo;
				}
			}else{
				$mo = new AmfResponse();
				$mo->statusCode = 6;
				$mo->message = "failed - pet doesn't exists dude";
				$mo->valueObject = null;
				
				return $mo;
			}
		}else{
			return null;
		}
	}
	
	function removeBolly($bollyId)
	{
		$pdo = $GLOBALS['database']::getConnection();
		$mo = $pdo->prepare("SELECT * FROM pets WHERE pet_id = ? AND owner_id = ? LIMIT 1");
		$mo->bindParam(1,$bollyId,PDO::PARAM_INT);
		$mo->bindParam(2,$_SESSION['playerId'],PDO::PARAM_INT);
		$mo->execute();
		
		if($mo->rowCount() > 0 && (int) $_SESSION['playerId'] != 1013){
			$op = $pdo->prepare("DELETE FROM pets WHERE pet_id = ? AND owner_id = ?");
			$op->bindParam(1,$bollyId,PDO::PARAM_INT);
			$op->bindParam(2,$_SESSION['playerId'],PDO::PARAM_INT);
			$op->execute();
			
			$link = new AmfResponse();
			$link->statusCode = 0;
			$link->message = "success";
			$link->valueObject = null;
			
			return $link;
		} else {
			$link = new AmfResponse();
			$link->statusCode = 6;
			$link->message = "failed";
			$link->valueObject = null;
			
			return $link;
		}
	}
	
	function updateBolly($data = array())
	{
		$pdo = $GLOBALS['database']::getConnection();
		
		$ko = $pdo->prepare("SELECT * FROM pets WHERE pet_id = ? AND owner_id = ?");
		$ko->bindParam(1,$data->id,PDO::PARAM_INT);
		$ko->bindParam(2,$_SESSION['playerId'],PDO::PARAM_INT);
		$ko->execute();
		
		if($ko->rowCount() > 0){
			$ok = $pdo->prepare("UPDATE pets SET state = ?, activity = ?, health = ?, rest = ?, energy = ?, x = ?, y = ?, z = ?, style = ? WHERE pet_id = ? AND owner_id = ?");
			$ok->bindParam(1,$data->state,PDO::PARAM_STR);
			$ok->bindParam(2,$data->activity,PDO::PARAM_STR);
			$ok->bindParam(3,$data->health,PDO::PARAM_INT);
			$ok->bindParam(4,$data->rest,PDO::PARAM_INT);
			$ok->bindParam(5,$data->energy,PDO::PARAM_INT);
			$ok->bindParam(6,$data->x,PDO::PARAM_INT);
			$ok->bindParam(7,$data->y,PDO::PARAM_INT);
			$ok->bindParam(8,$data->z,PDO::PARAM_INT);
			$ok->bindParam(9,$data->style,PDO::PARAM_INT);
			$ok->bindParam(10,$data->id,PDO::PARAM_INT);
			$ok->bindParam(11,$_SESSION['playerId'],PDO::PARAM_INT);
			$ok->execute();
			
			$rsp = new AmfResponse();
			$rsp->statusCode = 0;
			$rsp->message = "success";
			$rsp->valueObject = new BollyVO();
			$rsp->valueObject->id = $data->id;
			$rsp->valueObject->name = $data->name;
			$rsp->valueObject->type = $data->type;
			$rsp->valueObject->price = $data->price;
			$rsp->valueObject->state = $data->state;
			$rsp->valueObject->activity = $data->activity;
			$rsp->valueObject->health = $data->health;
			$rsp->valueObject->rest = $data->rest;
			$rsp->valueObject->energy = $data->energy;
			$rsp->valueObject->rescueTime = $data->rescueTime;
			$rsp->valueObject->x = $data->x;
			$rsp->valueObject->y = $data->y;
			$rsp->valueObject->z = $data->z;
			$rsp->valueObject->colour = $data->colour;
			$rsp->valueObject->style = $data->style;
			
			return $rsp;
		}else{
			$rsp = new AmfResponse();
			$rsp->statusCode = 6;
			$rsp->message = "failed - User doesn't own that bolly";
			$rsp->valueObject = null;
			
			return $rsp;
		}
	}
	
}