<?php

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Features/ServerManager.php';

class amfActivateGoldPackageService {
	private $valid = false;
	function activateGoldPackage($code) {
		$pdo = $GLOBALS['database']::getConnection();
		
		$ver = $pdo->prepare("SELECT * FROM `membershipcodes` WHERE LOWER(`mem_code`) = LOWER(?) AND `used` = 0 LIMIT 1");
		$ver->bindParam(1,$code,PDO::PARAM_STR);
		$ver->execute();
		
		if($ver->rowCount() > 0) {
			$args = $ver->fetch(PDO::FETCH_ASSOC);
			$op = $pdo->prepare("UPDATE membershipcodes SET used = 1 WHERE mem_code = ?");
			$op->bindParam(1,$args['mem_code'],PDO::PARAM_STR);
			$op->execute();
			
			$ap = $pdo->prepare("UPDATE `users` SET `premium` = 1, `memExpiration` = ? WHERE `id` = ? AND `memExpiration` = 0");
			$_b = strtotime("now +".$args['mem_duration']);
			$ap->bindParam(1,$_b,PDO::PARAM_INT);
			$ap->bindParam(2,$_SESSION['playerId'],PDO::PARAM_INT);
			$ap->execute();
			
			$ep = $pdo->prepare("INSERT INTO membershipcodes (mem_code,mem_duration,used) VALUES (?,?,0)");
			$owpwpoe = $this->generateMembershipCode();
			$owpwpoa = rand(2,15)." Days";
			$ep->bindParam(1,$owpwpoe,PDO::PARAM_STR);
			$ep->bindParam(2,$owpwpoa,PDO::PARAM_STR);
			$ep->execute();
			
			$amf = new AmfResponse();
			$amf->statusCode = 0;
			$amf->message = "success";
			
			try {
				$info = new Server();
				if($info) {
					try {
						$info::sendMessage("<msg t='sys'><body action='gold'><security><ticket><![CDATA[oauth:LBtx4s7SFXXacMVg64kjZNMnhK3PfGcMVeEmPqdRmJAiwI48oehNEKurP]]></ticket></security><membership><user><![CDATA[".$_SESSION['playerId']."]]></user></membership></body></msg>");
					} catch(Exception $e) {}
				}
			} catch(Exception $e) {}
			
			return $amf;
		}else{
			$amf = new AmfResponse();
			$amf->statusCode = 13;
			$amf->message = "failed";
			
		}
		
		return $amf;
	}
	
	function sendMessage($message)
	{
		try {
			$info = new Server($serverId);
			
			return $info;
		} catch(Exception $e) {
			return false;
		}
	}
	
	function generateMembershipCode()
	{
		$codeFormats = Array(
			1 => "ABCD-EFGH-IJKLM-NÑOP",
			2 => "ABCDE-FGHIJ-KLMNÑ-OPQRST",
			3 => "ABCDEF-GHIJKL-MNÑOPQ-RSTUVXYZ",
			4 => "ABCDE-FGHIJ-KLMNÑ",
			5 => "ABCDE-FGHIJ-KLMNÑ-OPQRST-UVXYZ",
		);
		$con = $codeFormats[rand(1,5)];
		$cor = Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','Ñ','O','P','Q','R','S','T','U','V','X','Y','Z');
		$code = "";
		
		$letters = Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','X','Y','Z');
		$numbers = Array(0,1,2,3,4,5,6,7,8,9);
		
		for($i=0;$i<sizeof($cor);$i++) {
			$p = rand(0,1);
			
			if(strpos($con,$cor[$i]) !== false) {
				if($p === 1) {
					$con = str_replace($cor[$i],$numbers[rand(0,9)],$con);
				} else {
					$con = str_replace($cor[$i],$letters[rand(0,22)],$con);
				}
			}
			
		}
		
		return $con;
	}
	
}