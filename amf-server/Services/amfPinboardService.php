<?php

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Vo/PinboardVO.php';
require_once dirname(__FILE__) . '/Vo/MessageVO.php';
require_once dirname(__FILE__) . '/Vo/SenderVO.php';
require_once dirname(__FILE__) . '/Vo/DateVO.php';
require_once dirname(__FILE__) . '/Vo/AddedMessageVO.php';
require_once dirname(__FILE__) . '/Vo/SmallPlayerInfoVO.php';
require_once dirname(__FILE__) . '/Features/ServerManager.php';

class amfPinboardService {
	
	function addMessage($obj) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			$typeId = $obj->typeId;
			$parentId = $obj->parentMessageId;
			$receivers = $obj->receivers;
			$content = $obj->content;
			$timestamp = floor(microtime(true) * 1000) - (3600000 * 3);
			$reference_id = $this->getReferenceId();
			$user = $_SESSION["playerName"] . "|" . $_SESSION["playerId"];
			
			if($parentId > 0) {
				$b = $pdo->prepare("UPDATE `messageboard` SET `replied` = 1 WHERE `receiver` = ? AND `readed` = 1 AND `typeId` = ?");
				$b->bindParam(1,$_SESSION['playerId'],\PDO::PARAM_INT);
				$b->bindParam(2,$typeId,\PDO::PARAM_INT);
				$b->execute();
			}
			
			for($z = 0;$z < count($receivers);$z++) {
				if(is_numeric($receivers[$z])) {
					$x = $pdo->prepare("SELECT * FROM `users` WHERE `id` = ? LIMIT 1");
					$x->bindParam(1,$receivers[$z],\PDO::PARAM_INT);
					$x->execute();
					
					if($x->rowCount() > 0) {
						$b = $pdo->prepare("INSERT INTO `messageboard` (`typeId`,`parentMessageId`,`sender`,`content`,`createdAt`,`receiver`,`reference_id`) VALUES (?,?,?,?,?,?,?)");
						$b->bindParam(1,$typeId,\PDO::PARAM_INT);
						$b->bindParam(2,$parentId,\PDO::PARAM_INT);
						$b->bindParam(3,$user,\PDO::PARAM_STR);
						$b->bindParam(4,$content,\PDO::PARAM_STR);
						$b->bindParam(5,$timestamp,\PDO::PARAM_INT);
						$b->bindParam(6,$receivers[$z],\PDO::PARAM_INT);
						$b->bindParam(7,$reference_id,\PDO::PARAM_STR);
						$b->execute();
						
						$reference_id = $this->getReferenceId();
					}
				}
			}
			
			$z = $pdo->prepare("SELECT * FROM `messageboard` WHERE `reference_id` = ? LIMIT 1");
			$z->bindParam(1,$reference_id,\PDO::PARAM_STR);
			$z->execute();
			
			if($z->rowCount() > 0) {
				$messageId = $z->fetch(\PDO::FETCH_ASSOC)["message_id"];
			} else {
				$messageId = 0;
			}
			
			$added = new AddedMessageVO();
			$added->receivers = $receivers;
			$added->createdMessageVO = new MessageVO();
			$added->createdMessageVO->sender = new SenderVO();
			$added->createdMessageVO->sender->senderId = $_SESSION["playerId"];
			$added->createdMessageVO->sender->senderName = $_SESSION["playerName"];
			$added->createdMessageVO->messageId = $messageId;
			$added->createdMessageVO->parentMessageId = $parentId;
			$added->createdMessageVO->typeId = $typeId;
			$added->createdMessageVO->content = $content;
			$added->createdMessageVO->read = false;
			$added->createdMessageVO->createdAt = new DateVO();
			$added->createdMessageVO->createdAt->date = $timestamp;
			$added->createdMessageVO->replied = false;
			
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = $added;
			
			$z = $pdo->prepare("SELECT * FROM `messageboard` WHERE `receiver` = ? AND `readed` = 0");
			$z->bindParam(1,$receivers[0],\PDO::PARAM_STR);
			$z->execute();
		
			try {
				$info = new Server();
				if($info) {
					try {
						$r = $info::sendMessage("<msg t='sys'><body action='mail'><security><ticket><![CDATA[oauth:XfYEq7EJLh5EbC6cwHHGv35rBGvCyngYfCBZLxtsZ4FDGmrc4yRdfezAn]]></ticket></security><mailservice><sender><![CDATA[". $_SESSION['playerId'] ."]]></sender><recipent><![CDATA[". implode(",",$receivers) ."]]></recipent><read><![CDATA[". $z->rowCount() ."]]></read><type><![CDATA[mail]]></type></mailservice></body></msg>");
					} catch(Exception $e) {}
				}
			} catch(Exception $e) {}
		
			return $result;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPinboardService::addMessage\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function deleteMessage($id) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			$a = $pdo->prepare("SELECT * FROM `messageboard` WHERE `receiver` = ? AND `message_id` = ? LIMIT 1");
			$a->bindParam(1,$_SESSION["playerId"],\PDO::PARAM_INT);
			$a->bindParam(2,$id,\PDO::PARAM_INT);
			$a->execute();
			
			if($a->rowCount() > 0) {
				$b = $pdo->prepare("DELETE FROM `messageboard` WHERE `receiver` = ? AND `message_id` = ?");
				$b->bindParam(1,$_SESSION["playerId"],\PDO::PARAM_INT);
				$b->bindParam(2,$id,\PDO::PARAM_INT);
				$b->execute();
				
				$result = new AmfResponse();
				$result->statusCode = 0;
				$result->message = "success";
				$result->valueObject = $id;
				
				return $result;
			}
			
			return null;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPinboardService::deleteMessage\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
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
	
	function loadPinboardedBuddies($id) {
		try{
			$pdo = $GLOBALS['database']::getConnection();
				
			$a = $pdo->prepare("SELECT DISTINCT * FROM `buddies` WHERE `player_id` = ?");
			$a->bindParam(1,$_SESSION["playerId"],\PDO::PARAM_INT);
			$a->execute();
			if($a->rowCount() > 0) {
				$b = $a->fetchAll();
			}
			
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = Array();
			
			return $result;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPinboardService::loadPinboardedBuddies\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function viewPinboard() {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			$a = $pdo->prepare("UPDATE `messageboard` SET `readed` = 1 WHERE `receiver` = ?");
			$a->bindParam(1,$_SESSION["playerId"],\PDO::PARAM_INT);
			$a->execute();
			
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = null;
				
			return $result;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPinboardService::viewPinboard\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function loadPinboardPaginated($id, $start, $amount) {
		try{
			$pdo = $GLOBALS['database']::getConnection();
			
			if((int) $id === (int) $_SESSION['playerId']) {
				$get = $pdo->prepare("SELECT * FROM `messageboard` WHERE `receiver` = ?");
			} else {
				$get = $pdo->prepare("SELECT * FROM `messageboard` WHERE `receiver` = ? AND `typeId` != 1100");
			}
			
			$get->bindParam(1,$id,\PDO::PARAM_INT);
			$get->execute();
			
			$pinboard = new PinboardVO();
			
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$pinboard->undeletedMessagesCount = $get->rowCount();
			$pinboard->offset = $start;
			$pinboard->limit = $start;
			$pinboard->messages = Array();
			if($get->rowCount() > 0) {
				$po = $get->fetchAll();
				for($c = (0 + $start);$c < count($po);$c++) {
					if(count($pinboard->messages) >= $amount) break;
					$p = explode("|",$po[$c]["sender"]);
					$uw = new MessageVO();
					$uw->sender = new SenderVO();
					$uw->sender->senderName = $p[0];
					$uw->sender->senderId = (int) $p[1];
					$uw->parentMessageId = $po[$c]["parentMessageId"];
					$uw->typeId = $po[$c]["typeId"];
					$uw->content = $po[$c]["content"];
					$uw->messageId = $po[$c]["message_id"];
					$uw->read = $po[$c]["readed"] === "0"?false:true;
					$uw->createdAt = new DateVO();
					$uw->createdAt->date = $po[$c]["createdAt"];
					$uw->replied = $po[$c]["replied"] === "0"?false:true;
							

					array_unshift($pinboard->messages,$uw);
				}
			}
			
			if($id !== $_SESSION['playerId']) {
				$pinboard->undeletedMessagesCount = $pinboard->undeletedMessagesCount - 1;
			}
			
			$result->valueObject = $pinboard;
			return $result;
		}catch(PDOException $e){
			$error = date("d.m.Y H:i:s") . "\amfPinboardService::loadPinboardPaginated\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
}