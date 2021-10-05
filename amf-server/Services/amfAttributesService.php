<?php

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Vo/ListVO.php';

class amfAttributesService {
	private $target = 0;
	private $isCMod = null;
	private $savednames = ['ng','nc','sc','sg','sf','pf','pg','st'];
	private $savedMysql = ['select *','update ','insert into '];
	private $aaa = ['IF MONEY CAN\'T BUY A PENIS THEN WHY IT IS SO FABULOUS? :v','THE BEST PLP ARE','ALL THE BEST PLP R CRAZY','ALL DE BEST PLP AREEE','|113;-2,9999;13;this.getAvatarByName(&name,false).getUID();hitlightningtransformation;animation','u can\'t hurry love','wut can i say its complicated'];
	private $requestSeparator = '@€~';
	private $serverSeparator = '#$%';
	private $requestInternal = "%%";
	
	function mysqlInjection($args)
	{
		for($i=0; $i < count($args); $i++){
			for($u=0; $u < count($this->savedMysql); $u++){
				if(strrpos(strtolower($args[$i]),$this->savedMysql[$u]) !== false){
					echo $this->aaa[rand(0,count($this->aaa))];
					header($_SERVER["SERVER_PROTOCOL"].' 401 Unauthorized');
					exit;
				}
			}
		}
	}
	
	function requestRefereces($type)
	{
		$m = is_numeric($this->getIndexByLol($type,true)) === true?false:true;
		return $m;
	}
	
	function getIndexByLol($str, $need = false)
	{
		$ch=true;
		switch(strtolower($str))
		{
			case 'st':
			case 'statustext':
			case 'statusinfo':
			case 'statusline':
				if($need){
					return $ch;
				}
				else{
					return 6;
				}
			case 'sg':
			case 'statusglow':
			case 'statusbright':
				if($need){
					return $ch;
				}
				else{
					return 7;
				}
			case 'sc':
			case 'statuscolor':
			case 'statustextcolor':
			case 'statusinfocolor':
				if($need){
					return $ch;
				}
				else{
					return 8;
				}
			case 'sf':
			case 'statusfont':
			case 'statusfonttype':
			case 'statusfontype':
			case 'statusfontupdate':
				if($need){
					return $ch;
				}
				else{
					return 9;
				}
			case 'pg':
			case 'pandaglow':
			case 'pandabright':
			case 'playerglow':
				if($need){
					return $ch;
				}
				else{
					return 4;
				}
			case 'nc':
			case 'namecolor':
			case 'pandanamecolor':
				return 2;
			case 'nameglow':
			case 'ng':
			case 'pandanameglow':
				return 1;
			case 'pf':
			case 'pandapreferences':
			case 'userpreferences':
			case 'userutilities':
				if($need){
					return $ch;
				}
				else{
					return 11;
				}
		}
	}

	static function isBlackListed($id)
	{
		try{
		}
		catch(PDOException $e){
		}
	}

	static function unblackUser($id, $reason)
	{
		try{
		}
		catch(PDOException $e){
		}
	}

	static function blackListUser($id, $hash, $clientId = 0)
	{
		try{
		}
		catch(PDOException $aaaa){
		}
	}

	function changeByIndexAndConvertStr($strc, $imploder, $index, $replacement, $str)
	{
		$moup = explode($strc,$str);
		$moup[$index] = $replacement;
		//$newer = array_replace($moup,$align);
		$align2 = implode($imploder,$moup);
		return $align2;
	}
	
	function changeShits($mani, $str)
	{
		$index = $this->getIndexByLol($mani) - 1;
		$monekye = $this->changeByIndexAndConvertStr($this->serverSeparator,$this->serverSeparator,$index,true,$str);
		return $monekye;
	}
	
	function updatePlayerAttributes($userid, $hash, $arguments)
	{
		$this->mysqlInjection([$userid,$hash,$arguments]);
		if((int($userid) || is_numeric($userid)) && (int($hash) || is_numeric($hash))){
			try{
				$con = $GLOBALS['database']::getConnection();
				
				$user = $con->prepare("select * from maaboiiis where Player_ID = ? and Client_Token = ? limit 1");
				$user->bindParam(1,$userid,PDO::PARAM_INT);
				$user->bindParam(2,$hash,PDO::PARAM_INT);
				$user->execute();
				
				if($user->rowCount() > 0){
					$aaa = $user->fetch(PDO::FETCH_ASSOC);
					$aaa = $aaa['Attributes'];
					if(strrpos($arguments,$this->requestSeparator) !== false){
						if($user->execute()){
							$newAttributes = $aaa;
							$counter = explode($this->requestSeparator,$arguments);
							$mani = NULL;
							
							for($i=0; $i < count($counter); $i++){
								$mani = $counter[$i];
								$newAttributes = $this->changeByIndexAndConvertStr($this->serverSeparator,$this->serverSeparator,$this->getIndexByLol($mani[0]),$mani[1],$Attributes);
								$needBooleanChanges = $this->requestRefereces($mani[0]);
								if($needBooleanChanges){
									$newAttributes = $this->changeShits($mani[0],$newAttributes);
								}
							}
						}else{
							$response->message = "unexpected error";
							$response->statusCode = 13;
							$response->valueObject = $result;
							return $response;
						}
					}else{
						$mani = explode($this->requestInternal,$arguments);
						$newAttributes = $this->changeByIndexAndConvertStr($this->serverSeparator,$this->serverSeparator,$this->getIndexByLol($mani[0]),$mani[1],$aaa);
						$needBooleanChanges = $this->requestRefereces($mani[0]);
						if($needBooleanChanges){
							$newAttributes = $this->changeShits($mani[0],$newAttributes);
						}
					}
					$user->closeCursor();
					$user->prepare("update maaboiiis set Attributes = :aa where Player_ID = :bb and Client_Token = :cc");
					$user->bindValue(':aa',$newAttributes);
					$user->bindValue(':bb',$userid);
					$user->bindValue(':cc',$hash);
					$user->execute();
					
					$resultaa = new stdClass();
					$resultaa->atts = $newAttributes;
					$response->message = "success";
					$response->statusCode = 0;
					$response->valueObject = $resultaa;
				}
				else{
					$response->message = "user not found";
					$response->statusCode = 4;
					$response->valueObject = $result;
				}
				return $response;
			}
			catch(PDOException $e){
				$error = date("d.m.Y H:i:s") . "\amfAttributesService::getPlayerAttributes\tError: (" . $e->getCode . ") " . $e->getMessage;
				echo $error;
				header($_SERVER["SERVER_PROTOCOL"].' 400 Internal Server Error');
				exit;
			}
		}
		else{
			$error = date("d.m.Y H:i:s") . "\amfAttributesService::getPlayerAttributes\tError: (0x8am290) \"UserId\" or \"token\" isn\'t a fucking number.";
			echo $error;
			header($_SERVER["SERVER_PROTOCOL"].' 400 Internal Server Error');
			exit;
		}
	}
	
	function getPlayerExtendedAttributes($players = array(), $detailed = false) {
		try {
			$storage = array();
			
			foreach($players as $player) {
				$playerInfo = $this->getPlayerAttributes($player,"test","esp");
				array_push($storage, $playerInfo);
			}
			
			$tList = new ListVO();
			$tList->list = $storage;
				
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success|attributes_service";
			$result->valueObject = $tList;
			return $result;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfAttributesService::getPlayerInfoList\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function getPlayerAttributes($userid, $name, $type = "none")
	{
		if(is_numeric($userid)){
			try{
				$con = $GLOBALS['database']::getConnection();
				
				$user = $con->prepare("select * from maaboiiis where Player_ID = ? limit 1");
				$user->bindParam(1,$userid,PDO::PARAM_INT);
				$user->execute();
				
				$response = new AmfResponse();
				$result = new stdClass();
				if($user->rowCount() > 0){
					$att = $user->fetch(PDO::FETCH_ASSOC);
					$ext = $att['Player_Name'];
					$att = $att['Attributes'];
					$att = explode($this->serverSeparator,$att);
					
					$result->name = $ext;
					$result->message = "user found";
					$result->playerId = $userid;
					$result->updated = $att[0];
					if($result->updated){
						$result->nameglow = $att[1];
						$result->namecolor = $att[2];
						$result->hasglowactive = $att[3];
						if($result->hasglowactive){
							$result->glowcolor = $att[4];
						}
						$result->hastatus = $att[5];
						if($result->hastatus){
							$result->status = $att[6];
							$result->statusg = $att[7];
							$result->statusc = $att[8];
							$result->statusf = $att[9];
						}
						$result->haspreferences = $att[10];
						if($result->haspreferences){
							$result->preferences = $att[11];
						}
					}
					$response->message = "success";
					$response->statusCode = 0;
					$response->valueObject = $result;
				}
				else{
					$result->name = $name;
					$result->playerId = $userid;
					$result->updated = false;
					$result->message = "user not found";
					$response->message = "error";
					$response->statusCode = 4;
					$response->valueObject = $result;
				}
				if($type === "esp"){
					return $result;
				}else{
					return $response;
				}
			}
			catch(PDOException $e){
				$error = date("d.m.Y H:i:s") . "\amfAttributesService::getPlayerAttributes\tError: (" . $e->getCode . ") " . $e->getMessage;
				echo $error;
				header($_SERVER["SERVER_PROTOCOL"].' 500 Internal Server Error');
				exit;
			}
		}
		else{
			$error = date("d.m.Y H:i:s") . "\amfAttributesService::getPlayerAttributes\tError: (0x8am290) \"UserId\" or \"token\" isn\'t a fucking number.";
			echo $error;
			header($_SERVER["SERVER_PROTOCOL"].' 500 Internal Server Error');
			exit;
		}
	}
}