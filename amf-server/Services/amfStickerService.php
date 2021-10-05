<?php

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Vo/StickerVO.php';
require_once dirname(__FILE__) . '/Vo/StickerRestrictionsVO.php';
require_once dirname(__FILE__) . '/Vo/StickerDefinitionVO.php';
require_once dirname(__FILE__) . '/Features/ServerManager.php';

class amfStickerService {
	//const $STICKER_MESSAGE = 1100;
	
	function loadStickers($target)
	{
		$pdo = $GLOBALS['database']::getConnection();
		$holder = Array();
		
		$w = $pdo->prepare("SELECT * FROM `stickers` WHERE `player_id` = ?");
		$w->bindParam(1,$target,\PDO::PARAM_INT);
		$w->execute();
		
		if($w->rowCount() > 0) {
			$list = $w->fetchAll();
			for($a = 0;$a < count($list);$a++) {
				$b = new StickerVO();
				$b->amount = $list[$a]["amount"];
				$b->definitionId = $list[$a]["definition"];
				array_push($holder,$b);
			}
		}
		
		$result = new AmfResponse();
		$result->statusCode = 0;
		$result->message = "success";
		$result->valueObject = $holder;
		
		return $result;
	}
	
	function getReferenceId()
	{
		$keys = Array(0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $result = Array();
        $max = rand(6,12);
		
        for($i=0;$i<$max;$i++) {
            $num = rand(0,count($keys)-1);
			array_push($result,$keys[$num]);
        }
		
		return implode('',$result);
	}
	
	function newDefinition($points = 5, $id, $min = 15, $coins = 520, $prem = true)
	{
		$mo = new StickerDefinitionVO();
		$mo->id = $id;
		$mo->points = $points;
		$mo->restrictions = new StickerRestrictionsVO();
		$mo->restrictions->minLevel = $min;
		$mo->restrictions->coins = $coins;
		$mo->restrictions->premium = $prem;
		//return $mo::getInstance();
		return $mo;
	}
	
	function addNewSticker($values, $npc = false, $npcName = "Robin")
	{
		$target = $values->receiverId;
		$definition = $values->definitionId;
		$content = $values->content;
		$timestamp = floor(microtime(true) * 1000) - (3600000 * 3);
		
		$pdo = $GLOBALS['database']::getConnection();
		
		$stick = $pdo->prepare("SELECT * FROM `stickers` WHERE `player_id` = ? AND `definition` = ? LIMIT 1");
		$stick->bindValue(1,$target);
		$stick->bindValue(2,$definition);
		$stick->execute();
		
		if($stick->rowCount() > 0) {
			$sticker = $stick->fetch(PDO::FETCH_ASSOC);
			
			$guilt = $pdo->prepare("UPDATE `stickers` SET `amount` = `amount` + 1, `content` = :co, `timestamp` = :ts WHERE `player_id` = :id AND `definition` = :di");
			$guilt->bindValue(":co",$content);
			$guilt->bindValue(":id",$target);
			$guilt->bindValue(":di",$definition);
			$guilt->bindValue(":ts",$timestamp);
			$guilt->execute();
			
		} else {
			$timestamp = floor(microtime(true) * 1000);
			
			$guilt = $pdo->prepare("INSERT INTO stickers (`player_id`,`definition`,`content`,`amount`,`timestamp`) VALUES (?,?,?,1,?)");
			$guilt->bindValue(1,$target);
			$guilt->bindValue(2,$definition);
			$guilt->bindValue(3,$content);
			$guilt->bindValue(4,$timestamp);
			$guilt->execute();
			
		}
		
		$reference_id = $this->getReferenceId();
		$user = $_SESSION["playerName"] . "|" . $_SESSION["playerId"];
		$content = $_SESSION["playerId"] . "|" . $definition;
		
		$t = $pdo->prepare("INSERT INTO `messageboard` (`reference_id`,`typeId`,`parentMessageId`,`sender`,`content`,`createdAt`,`receiver`) VALUES (?,1100,-1,?,?,?,?)");
		$t->bindParam(1,$reference_id,\PDO::PARAM_STR);
		$t->bindParam(2,$user,\PDO::PARAM_STR);
		$t->bindParam(3,$content,\PDO::PARAM_STR);
		$t->bindParam(4,$timestamp,\PDO::PARAM_INT);
		$t->bindParam(5,$target,\PDO::PARAM_INT);
		$t->execute();
		
		$result = new AmfResponse();
		$result->statusCode = 0;
		$result->message = "success";
		$result->valueObject = null;
		
		$z = $pdo->prepare("SELECT * FROM `messageboard` WHERE `receiver` = ? AND `readed` = 0");
		$z->bindParam(1,$target,\PDO::PARAM_STR);
		$z->execute();
		
		try {
			$info = new Server();
			if($info) {
				try {
					$r = $info::sendMessage("<msg t='sys'><body action='mail'><security><ticket><![CDATA[oauth:XfYEq7EJLh5EbC6cwHHGv35rBGvCyngYfCBZLxtsZ4FDGmrc4yRdfezAn]]></ticket></security><mailservice><sender><![CDATA[". $_SESSION['playerId'] ."]]></sender><recipent><![CDATA[". $target ."]]></recipent><read><![CDATA[". $z->rowCount() ."]]></read><type><![CDATA[sticker]]></type></mailservice></body></msg>");
				} catch(Exception $e) {}
			}
		} catch(Exception $e) {}
		
		return $result;
	}
	
	function loadStickerDefinitions()
	{
		$stickers = Array($this->newDefinition(2,1,4,120,false),$this->newDefinition(5,2,5,125,true),$this->newDefinition(10,3,5,130,true),$this->newDefinition(2,4,15,50,true),$this->newDefinition(9,5,20,140,false),$this->newDefinition(15,6,23,160,true),$this->newDefinition(2,7,5,68,false),$this->newDefinition(4,8,17,162,true),$this->newDefinition(26,9,33,341,true),$this->newDefinition(19,10,33,215,true),$this->newDefinition(6,11,35,120,true),$this->newDefinition(9,12,35,129,true),$this->newDefinition(28,13,37,410,true),$this->newDefinition(28,14,37,429,true),$this->newDefinition(62,15,37,1728,false),$this->newDefinition(15,16,23,163,false),$this->newDefinition(18,17,37,59,true),$this->newDefinition(8,18,29,37,false),$this->newDefinition(12,19,25,129,true),$this->newDefinition(7,20,42,127,true),$this->newDefinition(26,21,44,459,true),$this->newDefinition(26,22,46,528,true),$this->newDefinition(11,23,46,235,false),$this->newDefinition(11,24,46,256,true),$this->newDefinition(34,25,46,975,true),$this->newDefinition(53,26,49,1026,true),$this->newDefinition(2,27,3,67,false),$this->newDefinition(41,28,51,1101,true),$this->newDefinition(41,29,51,1101,true),$this->newDefinition(2695,30,53,3941,true),$this->newDefinition(10,31,55,210,false));
		
		$result = new AmfResponse();
		$result->statusCode = 0;
		$result->message = "success";
		$result->valueObject = $stickers;
		
		return $result;
	}
	
}