<?php

require_once './includes/Database.php';

class Sessioner implements SessionHandlerInterface {
    private $conn;
    
    function open($savePath, $sessionName) {
        if($this->conn = new DBConnection()) {
            return true;
        } else {
            return false;
        }
    }
	
    function close() {
        $this->conn->__destruct();
		$this->conn = null;
        return true;
    }
	
    function read($id) {
        $result = $this->conn->daaabconn()->prepare("SELECT `Session_Data` FROM `Session` WHERE `Session_id` = :id  AND `Session_Type` = 'AMF' LIMIT 1");
		$result->bindValue(':id',$id);
		$result->execute();
		
        if($result->rowCount() > 0){
			$row = $result->fetch(PDO::FETCH_ASSOC);
            return !isset($row['Session_Data'])||$row['Session_Data']===''?"":$row['Session_Data'];
        }else{
            return "";
        }
    }
	
	function get_client_ip() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	
	function checkExistense($id) {
		$ip = $this->get_client_ip();
		$o = $this->conn->daaabconn()->prepare("SELECT `Session_Data` FROM `Session` WHERE `Session_Id` = :id AND `Session_IP` = :ip AND `Session_Type` = 'AMF' LIMIT 1");
		$o->bindValue(":id",$id);
		$o->bindValue(":ip",$ip);
		$o->execute();
		return $o->rowCount();
	}
	
    function write($id, $data) {
        $NewDateTime = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' + 1 hour'));
		$ip = $this->get_client_ip();
		
        if($this->checkExistense($id) > 0) {
			$result = $this->conn->daaabconn()->prepare("UPDATE `Session` SET `Session_Expires` = :ed, `Session_Data` = :data WHERE `Session_id` = :id AND `Session_IP` = :ip AND `Session_Type` = 'AMF'");
			$result->bindValue(":ed",$NewDateTime);
			$result->bindValue(":data",$data);
			$result->bindValue(":id",$id);
			$result->bindValue(":ip",$ip);
            return $result->execute();
        } else {
			$result = $this->conn->daaabconn()->prepare("INSERT INTO `Session` (`Session_Id`,`Session_Expires`,`Session_IP`,`Session_Data`,`Session_Type`) VALUES (:id,:time,:ip,:data,'AMF')");
			$result->bindValue(":id",$id);
			$result->bindValue(":time",$NewDateTime);
			$result->bindValue(":ip",$ip);
			$result->bindValue(":data",$data);
            return $result->execute();
        }
    }
	
    function destroy($id) {
		$ip = $this->get_client_ip();
        $result = $this->conn->daaabconn()->prepare("DELETE FROM `Session` WHERE `Session_Id` = :id AND `Session_IP` = :ip AND `Session_Type` = 'AMF'");
		$result->bindValue(":id",$id);
		$result->bindValue(":ip",$ip);
        if($result->execute()){
            return true;
        }else{
            return false;
        }
    }
	
    function gc($maxlifetime) {
        $result = $this->conn->daaabconn()->prepare("DELETE FROM `Session` WHERE ((UNIX_TIMESTAMP(`Session_Expires`) + :max) < :max)");
		$result->bindValue(':max',$maxlifetime);
        if($result->execute()){
            return true;
        }else{
            return false;
        }
    }
	
	function checkSessions() {
		$result = $this->conn->daaabconn()->prepare("SELECT * FROM `Session`");
		$result->execute();
		$arr = $result->fetchAll();
		foreach($arr as $in => $on) {
			if(strtotime($on['Session_Expires']) <= strtotime("now")) {
				$a = $this->conn->daaabconn()->prepare("DELETE FROM `Session` WHERE `Session_Id` = :id AND `Session_IP` = :ip");
				$a->bindParam(':id',$on['Session_Id']);
				$a->bindParam(':ip',$on['Session_IP']);
				$a->execute();
			}
		}
	}
}
$GLOBALS['handler'] = new Sessioner();
session_set_save_handler($GLOBALS['handler'],true);
$sessionChecker = function() {
	return $GLOBALS['handler']->checkSessions();
};
$GLOBALS['session_check'] = $sessionChecker;
?>