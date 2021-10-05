<?php

require_once dirname(__FILE__) . '/../Services/Vo/DateVO.php';
require_once dirname(__FILE__) . '/../Services/Vo/GameServerVO.php';
require_once dirname(__FILE__) . '/../Services/Vo/ItemVO.php';
require_once dirname(__FILE__) . '/../Services/Vo/PokoPetPropertiesVO.php';
require_once dirname(__FILE__) . '/../Services/Vo/PokoPetVO.php';
require_once dirname(__FILE__) . '/../Services/Features/PetContext.php';
require_once dirname(__FILE__) . '/../Services/Vo/PlayerInfoVO.php';
require_once dirname(__FILE__) . '/../Services/Vo/SmallPlayerInfoVO.php';

class DBConnection {
	static $items = null;
	private static $con;
	
	function __construct() {
		self::$con = new PDO("mysql:dbname=panfu;host=localhost;charset=utf8","PepitoPito","conchatuvieja");
		self::$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
	}
	
	function __destruct() {
		self::$con = null;
	}
	
	static function getConnection() {
		return self::$con;
	}
	
	function daaabconn() {
		return self::$con;
	}
	
	function __parse(array $cloth) {
		$d = Array();
		foreach($cloth as $in => $id) {
			array_push($d,(int) $id['item_id']);
			unset($in);
		}
		return $d;
	}
	
	function activate($key, $user) {
		try {
			$resp = self::$con->prepare("SELECT * FROM `activationcodes` WHERE `code` = ? LIMIT 1");
			$resp->bindParam(1,$key,PDO::PARAM_INT);
			$resp->execute();
			
			$id = false;
			
			if($resp->rowCount() > 0) {
				$data = $resp->fetch(PDO::FETCH_ASSOC);
				$time = (int)$data['expiration'];
				$id = (int)$data['recipent'];
				
				if($time > strtotime("now")) {
				
					if($user != md5($id.$time)) return false;
					
					$resp = self::$con->prepare("UPDATE `users` SET `activated` = 1 WHERE `id` = ?;DELETE FROM `activationcodes` WHERE `code` = ?;");
					$resp->bindParam(1,$id,PDO::PARAM_INT);
					$resp->bindParam(2,$key,PDO::PARAM_INT);
					$resp->execute();
					
					$id = true;
				} else {
					$resp = self::$con->prepare("DELETE FROM `activationcodes` WHERE `code` = ?;DELETE FROM `users` WHERE `id` = ?;DELETE FROM `inventory` WHERE `player_id` = ?;");
					$resp->bindParam(1,$key,PDO::PARAM_INT);
					$resp->bindParam(2,$id,PDO::PARAM_INT);
					$resp->bindParam(1,$id,PDO::PARAM_INT);
					$resp->execute();
					
					$id = false;
				}
				
				if(rand(1,4) === 2) {
					$this->verifyCodes();
				}
				
			}
			
			return $id;
		} catch(PDOException $e) {
			return false;
		}
	}
	
	function verifyCodes() {
		try {
			$resp = self::$con->prepare("SELECT * FROM `activationcodes`");
			$resp->execute();
			
			if($resp->rowCount() > 0) {
				$del = self::$con->prepare("DELETE FROM `users` WHERE `id` = ?;DELETE FROM `activationcodes` WHERE `code` = ?;DELETE FROM `inventory` WHERE `player_id` = ?;");
				$codes = $resp->fetchAll();
				foreach($codes as $index => $obj) {
					if((int)$obj['expiration'] < strtotime("now")) {
						try {
							$args = Array((int)$obj['recipent'],$obj['code'],(int)$obj['recipent']);
							
							$del->execute($args);
						} catch(PDOException $e) {
							return false;
						}
					}
				}
			}
			
			$resp->closeCursor();
			
			return true;
		} catch(PDOException $e) {
			return false;
		}
	}
	
	function getAPInfo($name, $password) {
		try {
			$resp = self::$con->prepare("SELECT DISTINCT `password`,`sheriff`,`username`,`id`,`premium`,`auth_token` FROM `users` WHERE LOWER(`username`) = LOWER(:name) LIMIT 1");
			$resp->bindParam(":name",$name,PDO::PARAM_STR);
			$resp->execute();
			
			$count = $resp->rowCount();
			if($count == 0) return Array('rest'=>false,'message'=>'Incorrect username.');
			$user = $resp->fetch(PDO::FETCH_ASSOC);
			$pw = self::getPasswordHash(base64_decode($password),$user['username']);
			if(password_verify($pw,$user['password'])) {
				unset($user['password']);
				$user['premium'] = $user['premium'];
				$user['sheriff'] = $user['sheriff'] === '0'?false:true;
				$o = Array('rest'=>true,'count'=>$count,'args'=>$user);
				$resp->closeCursor();
				$resp = self::$con->prepare('SELECT `item_id` FROM `inventory` WHERE `player_id` = :id AND `active` = 1');
				$resp->bindParam(":id",$o['args']['id'],PDO::PARAM_INT);
				$resp->execute();
				if($resp->rowCount() == 0) {
					$o['clothes'] = '';
				} else {
					$o['clothes'] = $resp->fetchAll();
				}
			} else {
				return Array('rest'=>false,'message'=>'Incorrect password.');
			}
			return $o;
		} catch(PDOException $e) {
			return Array('rest'=>false);
		}
	}
	
	function getNewSession($id)
	{
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
		
		return substr($holders,0,9);
	}
	
	static function encryptPassword($password, $md5 = true) {
        if($md5 !== false) {
            $password = md5($password);
        }
        $hash = substr($password, 16, 16) . substr($password, 0, 16);
        return $hash;
    }
	
    static function getPasswordHash($password, $username) {  
        $hash = self::encryptPassword($password, false);
		$hash .= $username;
        $hash .= '5jAI1rMhU78IW9kmkK4AI';
		$hash .= base64_encode($username.'sk9AlJk20ms1ksUX16');
        $hash .= 'O(67.>\'vBn}c":E1';
		$hash .= md5($username).'91bfc22105db8759eb697c46d5f34554';
        $hash = self::encryptPassword($hash);
        return $hash;
    }
	
	static function addItems($id, $items) {
		try {
			$pdo = self::getConnection();
			
			$timestamp = floor(microtime(true) * 1000);
			$list = explode(',', $items);
			
			foreach($list as $item) {
				$insert = $pdo->prepare("insert into inventory (player_id, item_id, timestamp) values (?, ?, ?)");
				$insert->bindParam(1, $id, PDO::PARAM_INT);
				$insert->bindParam(2, $item, PDO::PARAM_INT);
				$insert->bindParam(3, $timestamp, PDO::PARAM_INT);
				$insert->execute();
			}
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\Database::addItems\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function getAttributes($_txt)
	{
		$str = explode(':',$_txt);
		
		$holder = new \stdClass();
		$holder->nameGlow = $str[0] === NULL?"INDEFINIDO":$str[0];
		$holder->nameColor = $str[1] === NULL?"0x000000":$str[1];
		$holder->nameFont = $str[2] === NULL?"HirukoFont":$str[2];
		$holder->appearEffect = $str[3] === NULL?false:true;
		$holder->headEffect = $str[4] === NULL?"INDEFINIDO":$str[4];
		$holder->statusText = $str[5] === NULL?"":$str[5];
		$holder->statusColor = $str[6] === NULL?"0x000000":$str[6];
		$holder->statusGlow = $str[7] === NULL?"INDEFINIDO":$str[7];
		$holder->statusFont = $str[8] === NULL?"HirukoFont":$str[8];
		$holder->nameAlias = $str[9] === NULL?"INDEFINIDO":$str[9];
		$holder->walkEffect = $str[10] === NULL?false:true;
		
		return json_decode(json_encode($holder),true);
	}
	
	static function addScore($gameId, $amount)
	{
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			if($gameId == 11 || $gameId === 41 || $gameId == 51 || $gameId == 45){
				$money = $pdo->prepare("SELECT * FROM highscores WHERE player_id = :pi AND game_id = :gi LIMIT 1");
				$money->bindValue(":pi",$_SESSION['playerId']);
				$money->bindValue(":gi",$gameId);
				$money->execute();
				
				if($money->rowCount() > 0){
					$timestamp = time();
					
					$maxscore = $money->fetch(PDO::FETCH_ASSOC)['highscore'];
					
					if($maxscore < $amount){
						$money2 = $pdo->prepare("UPDATE highscores SET highscore = :hi, timestamp = :ts WHERE player_id = :pi AND game_id = :gi");
						$money2->bindValue(":hi",$amount);
						$money2->bindValue(":ts",$timestamp);
						$money2->bindValue(":pi",$_SESSION['playerId']);
						$money2->bindValue(":gi",$gameId);
						
						$money2->execute();
					}
				}else{
					$timestamp = time();
					
					$money = $pdo->prepare("INSERT INTO highscores (player_id,game_id,highscore,timestamp) VALUES (:pi,:gi,:hi,:ts)");
					$money->bindValue(":pi",$_SESSION['playerId']);
					$money->bindValue(":gi",$gameId);
					$money->bindValue(":hi",$amount);
					$money->bindValue(":ts",$timestamp);
					
					$money->execute();
				}
				
				return '3';
			}else{
				return '2';
			}
			
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::getPlayerHome\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	static function updateScore($amount)
	{
		try {
			$pdo = self::getConnection();
			
			$money = $pdo->prepare("UPDATE users SET coins = :amount WHERE id = :id");
			$amount = $amount - 1;
			$money->bindValue(":amount",$amount);
			$money->bindValue(":id",$_SESSION['playerId']);
			$money->execute();
			
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::getPlayerHome\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	static function updateSocialScore($value, $isnew = "no", $mo = 0)
	{
		try {
			$pdo = self::getConnection();
			
			$money = $pdo->prepare("UPDATE users SET social_score = :amount WHERE id = :id");
			$money->bindValue(":amount",$value);
			$money->bindValue(":id",$_SESSION['playerId']);
			$money->execute();
			
			if($isnew === "new"){
				$money = $pdo->prepare("UPDATE users SET social_level = :amount WHERE id = :id");
				$money->bindValue(":amount",$mo);
				$money->bindValue(":id",$_SESSION['playerId']);
				$money->execute();
			}
			
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::getPlayerHome\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	static function getPlayerInfo($id) {
		try {
			$pdo = self::getConnection();
			
			$stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id LIMIT 1");
			$stmt->bindParam(':id',$id,PDO::PARAM_INT);
			$stmt->execute();
			
			if ($stmt->rowCount() > 0) {
				$player = $stmt->fetch(PDO::FETCH_ASSOC);
				
				$diff = time() - $player['account_created'];
				
				$activeInventory = $GLOBALS['database']::getPlayerInventory($player['id']);
				$inactiveInventory = $GLOBALS['database']::getPlayerInventory($player['id'], false);
				$buddies = $GLOBALS['database']::getPlayerBuddies($player['id']);
				
				$tPlayerInfo = new PlayerInfoVO();
				$tPlayerInfo->id = (int) $player['id'];
				$tPlayerInfo->name = $player['display_name'];
				$tPlayerInfo->sex = $player['gender'];
				
				if(($player['birthday'] != null && (int)$player['birthday'] != 0 && (int)$id != (int)$_SESSION['playerId'] && $_SESSION['playerPremium']) || ((int)$id == (int)$_SESSION['playerId'])) {
					$tDate = new DateVO();
					$tDate->date = (int)$player['birthday'];
					$tPlayerInfo->birthday = $tDate;
				} else {
					$tPlayerInfo->birthday = null;
				}
				
				$tPlayerInfo->coins = (int) $player['coins'];
				$tPlayerInfo->chatId = (int) $player['chat_id'];
				$tPlayerInfo->isPremium = boolval($player['premium']);
				$tPlayerInfo->currentGameServer = $player['current_gameserver'];
				$tPlayerInfo->socialLevel = (int) $player['social_level'];
				$tPlayerInfo->socialScore = (int) $player['social_score'];
				$tPlayerInfo->lastLogin = (int) $player['last_login'];
				$tPlayerInfo->signupDate = (int) $player['account_created'];
				$tPlayerInfo->daysOnPanfu = floor($diff / (60*60*24));
				$tPlayerInfo->helperStatus = boolval($player['helper_status']);
				$tPlayerInfo->isSheriff = boolval($player['sheriff']);
				$tPlayerInfo->isTourFinished = boolval($player['tour_finished']);
				$tPlayerInfo->state = $player['state'];
				$tPlayerInfo->membershipStatus = (int) $player['premium'];
				$tPlayerInfo->activeInventory = $activeInventory;
				$tPlayerInfo->inactiveInventory = $inactiveInventory;
				$tPlayerInfo->buddies = $buddies;
				$tPlayerInfo->blocked = null;
				
				$mod = $pdo->prepare("SELECT * FROM pets WHERE pet_type = 'bolly' AND owner_id = ?");
				$mod->bindParam(1,$id,PDO::PARAM_INT);
				$mod->execute();
				
				$bollies2 = Array();
				
				if($mod->rowCount() > 0){
					$bollies = $mod->fetchAll();
					
					foreach($bollies as $bolly){
						$bolly2 = new BollyVO();
						$bolly2->id = $bolly['pet_id'];
						$bolly2->name = $bolly['pet_name'];
						$bolly2->type = (int) $bolly['colour'];
						$bolly2->price = (int) $bolly['pet_price'];
						$bolly2->state = $bolly['state'];
						$bolly2->activity = $bolly['activity'];
						$bolly2->health = (int) $bolly['health'];
						$bolly2->rest = (int) $bolly['rest'];
						$bolly2->energy = (int) $bolly['energy'];
						$bolly2->rescueTime = (int) $bolly['rescueTime'];
						$bolly2->x = (int) $bolly['x'];
						$bolly2->y = (int) $bolly['y'];
						$bolly2->z = (int) $bolly['z'];
						$bolly2->colour = $bolly['colour'];
						$bolly2->style = $bolly['style'];
						
						array_push($bollies2,$bolly2);
					}
				}
				
				$tPlayerInfo->bollies = $bollies2;
				
				$mod->closeCursor();
				
				$mod = $pdo->prepare("SELECT * FROM pets WHERE pet_type = 'pokopet' AND owner_id = ?");
				$mod->bindParam(1,$id,PDO::PARAM_INT);
				$mod->execute();
				
				$helper = new PetContext();
				
				$pokopets = Array();
								
				if($mod->rowCount() > 0){
					$fofo = $mod->fetchAll();

					foreach($fofo as $info){
						$pokoHolder = new PokoPetVO();
						
						$pokoHolder->id = (int) $info['pet_id'];
						$pokoHolder->name = $info['pet_name'];
						$pokoHolder->x = (int) $info['x'];
						$pokoHolder->y = (int) $info['y'];
						$pokoHolder->state = $info['state'];
						$pokoHolder->selected = $info['selected'];
						$pokoHolder->status = $info['status'];
						$pokoHolder->abilities = $helper->getAbilitiesByString($info['abilities']);
						$pokoHolder->activity = $info['activity'];
						$pokoHolder->type = (int) $info['pet_id'];
						$pokoHolder->percentToNextLevel = (int) $info['percentToNextLevel'];
						$pokoHolder->lastFed = new DateVO();
						$pokoHolder->lastFed->date = (int) $info['lastFed'];
						$pokoHolder->properties = $helper->getPokoProperties($info['properties']);
						$pokoHolder->z = (int) $info['z'];
						
						array_push($pokopets,$pokoHolder);
					}
				}
				
				$tPlayerInfo->pokoPets = $pokopets;
				$tPlayerInfo->pokoPetsWithNoHealth = Array();
				$tPlayerInfo->attributes = $player['SWID'];
				
				return $tPlayerInfo;
			}
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\Database::getPlayerInfo\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	static function getItems() {
		if(self::$items == null) {
			self::$items = json_decode(file_get_contents(dirname(__FILE__).'/../Services/Features/panfu_items.pIOP.json'), true);
		}
		
		return self::$items;
	}
	
	static function getItemInfoById($id) {
		$dato = self::getItems();
		
		foreach($dato as $item) {
			if($item['id'] == $id) {
				
				return $item;
			}
		}
		
		return null;
	}
	
	static function premium($a) {
		try{
			$pdo = $GLOBALS['database']::getConnection();
			$b = $pdo->prepare("SELECT `premium` FROM `users` WHERE `id` = ?");
			$b->bindParam(1,$_SESSION['playerId'],\PDO::PARAM_INT);
			$b->execute();
			$c = $b->fetch(PDO::FETCH_ASSOC)['premium'];
			
			return (int)$c>0?true:false;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\Database::changer\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	static function getPlayerInventory($id, $active = true, $getArr = true) {
		if($id === 1010) {
			$storage = array();
			$tItem = new ItemVO();
			$tItem->id = 102853;
			$tItem->name = "ADVENT_SANTA_SHOES";
			$tItem->type = '09';
			$tItem->price = 0;
			$tItem->zettSort = 1050;
			$tItem->premium = boolval(0);
			$tItem->bought = true;
			$tItem->active = 1;
			$tItem->movementType = 0;
			array_push($storage, $tItem);
			$tItem = new ItemVO();
			$tItem->id = 102851;
			$tItem->name = "ADVENT_SANTA_JACKET";
			$tItem->type = '30';
			$tItem->price = 0;
			$tItem->zettSort = 1091;
			$tItem->premium = boolval(1);
			$tItem->bought = true;
			$tItem->active = 1;
			$tItem->movementType = 0;
			array_push($storage, $tItem);
			$tItem = new ItemVO();
			$tItem->id = 102850;
			$tItem->name = "ADVENT_SANTA_HAT";
			$tItem->type = '03';
			$tItem->price = 0;
			$tItem->zettSort = 1140;
			$tItem->premium = boolval(1);
			$tItem->bought = true;
			$tItem->active = 1;
			$tItem->movementType = 0;
			array_push($storage, $tItem);
			return $storage;
		}
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			if($getArr) {
				$stmt = $pdo->prepare("select DISTINCT * from `inventory` where `player_id` = :a and `active` = :b");
				$stmt->bindParam(':a', $id, PDO::PARAM_INT);
				$stmt->bindParam(':b', $active, PDO::PARAM_INT);
				$stmt->execute();
			
				$storage = array();
				
				foreach($stmt as $row) {
					$item = self::getItemInfoById($row['item_id']);
					
					if($item != null) {
					
						$tItem = new ItemVO();
						$tItem->id = $row['item_id'];
						$tItem->name = $item['name'];
						$tItem->type = $item['type'];
						$tItem->price = $item['price'];
						$tItem->zettSort = $item['zett_sort'];
						$tItem->premium = boolval($item['premium']);
						$tItem->bought = false;
						$tItem->active = boolval($row['active']);
						$tItem->movementType = $item['movement_type'];
						
						array_push($storage, $tItem);
					}
				}
				
				return $storage;
			} else {
				$str = "";
				$stmt = $pdo->prepare("SELECT DISTINCT * FROM inventory WHERE player_id = ? AND ele_type = 'CLOTHES'");
				$stmt->bindParam(1,$_SESSION['playerId'],PDO::PARAM_INT);
				$stmt->execute();
				
				$stmt = $stmt->fetchAll();
				
				foreach($stmt as $row) {
					$str = $str.(int) $row['item_id'].",";
				}
				
				return $str;
			}
			
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\Database::getPlayerInventory\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	static function getGameServers($locale) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			$stmt = $pdo->prepare("select * from servers where locale = ?");
			$stmt->bindParam(1, $locale, PDO::PARAM_STR);
			$stmt->execute();
			
			$storage = array();
			
			foreach($stmt as $row) {
				$tGameServer = new GameServerVO();
				$tGameServer->id = (int) $row['id'];
				$tGameServer->name = $row['name'];
				$tGameServer->playercount = (int) $row['playercount'];
				$tGameServer->url = $row['url'];
				$tGameServer->port = (int) $row['port'];
				$tGameServer->ageFrom = (int) $row['agefrom'];
				$tGameServer->ageTo = (int) $row['ageto'];
				$tGameServer->premiumonly = boolval($row['premium_only']);
				$tGameServer->availableFor = 0;
				
				array_push($storage, $tGameServer);
			}
			
			return $storage;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\Database::getGameServers\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	static function getSalt()
	{
		if(!isset($_SESSION['playerSalt'])) {
			try {
				$pdo = self::getConnection();
				
				$aa = $pdo->prepare("SELECT `salt`,`id`,`username`,`premium` FROM `users` WHERE `id` = ? LIMIT 1");
				$aa->bindParam(1,$_SESSION['playerId'],PDO::PARAM_INT);
				$aa->execute();
				
				$rsp = $aa->fetch(PDO::FETCH_ASSOC)['salt'];
				
				$aa->closeCursor();
				
				$_SESSION['playerSalt'] = $rsp;
				
				return $_SESSION['playerSalt'];
			} catch (PDOException $e) {
			}
		}
		
		return $_SESSION['playerSalt'];
	}
	
	static function getPlayerBuddies($id) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			$stmt = $pdo->prepare("SELECT * FROM `buddies` WHERE `player_id` = ?");
			$stmt->bindParam(1, $id, PDO::PARAM_INT);
			$stmt->execute();
			
			$storage = array();
			
			foreach($stmt->fetchAll() as $row) {
				
				$tPlayerInfo = new SmallPlayerInfoVO();
				$tPlayerInfo->playerId = $row['buddy_id'];
				$tPlayerInfo->playerName = $row['username'];
				$tPlayerInfo->currentGameServer = $row['currentgs'];
				
				array_push($storage, $tPlayerInfo);
			}
			
			return $storage;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\Database::getPlayerBuddies\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
}