<?php
require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Vo/PokoPetPropertiesVO.php';
require_once dirname(__FILE__) . '/Vo/DateVO.php';
require_once dirname(__FILE__) . '/Vo/PokoPetVO.php';
require_once dirname(__FILE__) . '/Features/PetContext.php';
require_once dirname(__FILE__) . '/Features/Reporter.php';

class amfPetService {
	
	function buyPet($id, $name)
	{
		if(is_numeric($id) && isset($name)) {
			switch($id) {
				case 7:
				case 8:
				case $id > 9:
					try {
						$info = new Server();
						if($info) {
							try {
								$r = $info::sendMessage('<msg t="sys"><body action="suspicious"><security id="report"><ticket><![CDATA[VfXPfGcMVg64trZRFYXLktTpr2xCZNMnhM3E4cGR7gCAPThr39nEvG3C8]]></ticket><ticketId><![CDATA[oauth:yFZJKzvuZsKJEuRXQ3XQC4wdF2kFtA65PyHYsGPDQ32uyJdLRSFmpg53q]]></tickerId><reportTicket><![CDATA[oauth:LUPUvwXEwPNXg4QUYQwUcCNCAXVCDEYWeqTJmNHa92MxHh2vdxXxrJyMC]]></reportTicket></security><message><reason><![CDATA[Intentó añadir una mascota que no está disponible.]]></reason><user><![CDATA['.$_SESSION['playerId'].']]></user><info function="amfPetService.buyPet" arguments="'.$_SESSION['playerId'].'" actioning="KICK"></info></message></body></msg>');
							} catch(Exception $e) {exit;}
						}
					} catch(Exception $e) {}
					return null;
				case 1:
				case 2:
				case 3:
				case 4:
				case 5:
				case 6:
					if(!$_SESSION['playerPremium']) {
						$rep = new AmfResponse();
						$rep->statusCode = 6;
						$rep->message = "membership needed. :(";
						$rep->valueObject = null;
						return $rep;
					}
					break;
			}
			
			$pdo = $GLOBALS['database']::getConnection();
			$mo = $pdo->prepare("SELECT * FROM pets WHERE pet_id = ? AND owner_id = ? LIMIT 1");
			$mo->bindParam(1,$id,PDO::PARAM_INT);
			$mo->bindParam(2,$_SESSION['playerId'],PDO::PARAM_INT);
			$mo->execute();
				
			if($mo->rowCount() === 0){
				$infous = new PetContext();
				$info = $infous->getPetInfoById($id,"pokopet");
				
				if($info['price'] !== 'VOUCHER_KK_VERIFICATION_NEEDED_PLEASEEEEE_DONT_KILL_ME') {
					$user = $pdo->prepare("SELECT coins FROM users WHERE id = ? LIMIT 1");
					$user->bindParam(1,$_SESSION['playerId'],PDO::PARAM_INT);
					$user->execute();
					
					if($user->rowCount() > 0) {
						$coins = $user->fetch(PDO::FETCH_ASSOC)['coins'];
						
						if($coins < $info['price']) {
							$result = new AmfResponse();
							$result->statusCode = 6;
							$result->message = "Not enough coins";
							$result->valueObject = null;
							return $result;
						} else {
							$user = $pdo->prepare("UPDATE users SET coins = coins - ? WHERE id = ?");
							$user->bindParam(1,$info['price'],PDO::PARAM_INT);
							$user->bindParam(2,$_SESSION['playerId'],PDO::PARAM_INT);
							$user->execute();
						}
					} else {
						return null;
					}
				}
			
				$pete = $pdo->prepare("INSERT INTO pets (owner_id,pet_id,pet_name,pet_price,state,activity,x,y,z,pet_type,lastFed,percentToNextLevel,selected,abilities,properties,status,rescueTime) VALUES (?,?,?,?,'IDLE',?,?,?,?,'pokopet',?,'0','true',':|:|:|:','speed:5|health:5|agility:5|power:5|maxHealth:5|experience:2|level:2','2',?)");
				$pete->bindParam(1,$_SESSION['playerId'],PDO::PARAM_INT);
				$pete->bindParam(2,$info['type'],PDO::PARAM_INT);
				$pete->bindParam(3,$name,PDO::PARAM_INT);
				if($info['price'] === 'VOUCHER_KK_VERIFICATION_NEEDED_PLEASEEEEE_DONT_KILL_ME'){
					if(!$this->userHasVoucher()){
						return null;
					}
					
					$op = $pdo->prepare("DELETE FROM inventory WHERE item_id = 101830 AND player_id = ?");
					$op->bindParam(1,$_SESSION['playerId'],PDO::PARAM_INT);
					$op->execute();
					
					$price = 0;
				}else{
					$price = $info['price'];
				}
				
				$pete->bindParam(4,$price,PDO::PARAM_INT);
				$pete->bindParam(5,$info['idle'],PDO::PARAM_STR);
				$pete->bindParam(6,$info['x'],PDO::PARAM_INT);
				$pete->bindParam(7,$info['y'],PDO::PARAM_INT);
				$pete->bindParam(8,$info['z'],PDO::PARAM_INT);
				$timestamp = time();
				$pete->bindParam(9,$timestamp,PDO::PARAM_INT);
				$pete->bindParam(10,$timestamp,PDO::PARAM_INT);
				$pete->execute();
					
				$link = new AmfResponse();
				$link->statusCode = 0;
				$link->message = "success";
				$link->valueObject = new PokoPetVO();
				$link->valueObject->id = (int) $info['type'];
				$link->valueObject->name = $name;
				$link->valueObject->x = (int) $info['x'];
				$link->valueObject->y = (int) $info['y'];
				$link->valueObject->selected = false;
				$link->valueObject->status = 2;
				$link->valueObject->abilities = Array();
				$link->valueObject->activity = $info['idle'];
				$link->valueObject->type = (int) $info['type'];
				$link->valueObject->state = 'IDLE';
				$link->valueObject->percentToNextLevel = 3;
				$link->valueObject->lastFed = new DateVO();
				$link->valueObject->lastFed->date = $timestamp - 5000;
				$link->valueObject->properties = new PokoPetPropertiesVO();
				$link->valueObject->properties->speed = 5;
				$link->valueObject->properties->health = 5;
				$link->valueObject->properties->agility = 5;
				$link->valueObject->properties->power = 5;
				$link->valueObject->properties->maxHealth = 5;
				$link->valueObject->properties->experience = 2;
				$link->valueObject->properties->level = 2;
					
				return $link;
				
			}
			
			return null;
		}
		
		return null;
	}
	
	function feed($id)
	{
		$pdo = $GLOBALS['database']::getConnection();
		
		$mo = $pdo->prepare("SELECT * FROM pets WHERE pet_id = ? AND owner_id = ? LIMIT 1");
		$mo->bindParam(1,$id,PDO::PARAM_INT);
		$mo->bindParam(2,$_SESSION['playerId'],PDO::PARAM_INT);
		$mo->execute();
		
		if($mo->rowCount() > 0){
			if(($id === 1 || $id === 2 || $id === 3 || $id === 4 || $id === 5 || $id === 6 || $id === 7 || $id === 8 && $_SESSION['playerPremium']) || $id === 9){
				$popo = $pdo->prepare("UPDATE pets SET lastFed = ? WHERE owner_id = ? AND pet_id = ?");
				$timestamp = time();
				$popo->bindParam(1,$timestamp,PDO::PARAM_INT);
				$popo->bindParam(2,$_SESSION['playerId'],PDO::PARAM_INT);
				$popo->bindParam(3,$id,PDO::PARAM_INT);
				$popo->execute();
				
				$result = new AmfResponse();
				$result->statusCode = 0;
				$result->message = "success";
				$result->valueObject = null;
				
				return $result;
			}
			
			return null;
		}
			
		$result = new AmfResponse();
		$result->statusCode = 6;
		$result->message = "User doesn't own that pet";
		$result->valueObject = null;
			
		return $result;
		
	}
	
	function cancelMatchMaking($pokoPetID)
	{
		$res = new AmfResponse();
		$res->statusCode = 0;
		$res->message = "success";
		$res->valueObject = null;
		
		return $res;
	}
	
	function matchMaking($pokoPetID)
	{
		$res = new AmfResponse();
		$res->statusCode = 1;
		$res->message = "failed";
		$res->valueObject = null;
		
		return $res;
	}
	
	function updatePetState($id, $state)
	{
		$pdo = $GLOBALS['database']::getConnection();
		
		$mo = $pdo->prepare("SELECT * FROM pets WHERE pet_id = ? AND owner_id = ? LIMIT 1");
		$mo->bindParam(1,$id,PDO::PARAM_INT);
		$mo->bindParam(2,$_SESSION['playerId'],PDO::PARAM_INT);
		$mo->execute();
		
		if($mo->rowCount() > 0){
			
			$ppee = $pdo->prepare("UPDATE pets SET state = ? WHERE owner_id = ? AND pet_id = ?");
			$ppee->bindParam(1,$state,PDO::PARAM_STR);
			$ppee->bindParam(2,$_SESSION['playerId'],PDO::PARAM_INT);
			$ppee->bindParam(3,$id,PDO::PARAM_INT);
			$ppee->execute();
			
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = null;
			
			return $result;
		}
		
		return null;
	}
	
	function revitalize($id)
	{
		$rsp = new AmfResponse();
		$rsp->statusCode = 0;
		$rsp->message = "success";
		$rsp->valueObject = null;
		
		return $rsp;
	}
	
	function matchMakingWithBot($id)
	{
		$rsp = new AmfResponse();
		$rsp->statusCode = 0;
		$rsp->message = "success";
		$rsp->valueObject = null;
		
		return $rsp;
	}

	function increaseHealth()
	{
		$rsp = new AmfResponse();
		$rsp->statusCode = 0;
		$rsp->message = "success";
		$rsp->valueObject = null;
		
		return $rsp;
	}
	
	function kickPokopet($id)
	{
		try{
			$pdo = $GLOBALS['database']::getConnection();
			$i = $pdo->prepare("DELETE FROM pets WHERE pet_id = ? AND owner_id = ?");
			$i->bindParam(1,$id,PDO::PARAM_INT);
			$i->bindParam(2,$_SESSION['playerId'],PDO::PARAM_INT);
			$i->execute();
			
			$rsp = new AmfResponse();
			$rsp->statusCode = 0;
			$rsp->message = "success";
			$rsp->valueObject = null;
			
			return $rsp;
		} catch(PDOException $e) {
			$rsp = new AmfResponse();
			$rsp->statusCode = 1;
			$rsp->message = "failed";
			$rsp->valueObject = null;
			
			return $rsp;
		}
	}
	
	function userHasVoucher()
	{
		$pdo = $GLOBALS['database']::getConnection();
		$mom = $pdo->prepare("SELECT * FROM inventory WHERE item_id = 101830 AND player_id = ? LIMIT 1");
		$mom->bindParam(1,$_SESSION['playerId'],PDO::PARAM_INT);
		$mom->execute();
		
		if($mom->rowCount() > 0){
			
			return true;
		}
		
		return false;
	}
	
	function switchPet($id)
	{
		$rsp = new AmfResponse();
		$rsp->statusCode = 0;
		$rsp->message = "success";
		$rsp->valueObject = null;
		
		return $rsp;
	}
	
}