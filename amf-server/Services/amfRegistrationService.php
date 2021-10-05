<?php

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';

class PasswordStrength {
	const STRENGTH_VERY_WEAK   = 0;
	const STRENGTH_WEAK        = 1;
	const STRENGTH_FAIR        = 2;
	const STRENGTH_STRONG      = 3;
	const STRENGTH_VERY_STRONG = 4;

	public function classifyScore($score) {
		if ($score <  0) return self::STRENGTH_VERY_WEAK;
		if ($score < 60) return self::STRENGTH_WEAK;
		if ($score < 70) return self::STRENGTH_FAIR;
		if ($score < 90) return self::STRENGTH_STRONG;

		return self::STRENGTH_VERY_STRONG;
	}

	public function classify($pw) {
		return $this->classifyScore($this->calculate($pw));
	}

	/**
	 * Calculate score for a password
	 *
	 * @param  string $pw  the password to work on
	 * @return int         score
	 */
	public function calculate($pw) {
		$length    = strlen($pw);
		$score     = $length * 4;
		$nUpper    = 0;
		$nLower    = 0;
		$nNum      = 0;
		$nSymbol   = 0;
		$locUpper  = array();
		$locLower  = array();
		$locNum    = array();
		$locSymbol = array();
		$charDict  = array();

		// count character classes
		for ($i = 0; $i < $length; ++$i) {
			$ch   = $pw[$i];
			$code = ord($ch);

			/* [0-9] */ if     ($code >= 48 && $code <= 57)  { $nNum++;    $locNum[]    = $i; }
			/* [A-Z] */ elseif ($code >= 65 && $code <= 90)  { $nUpper++;  $locUpper[]  = $i; }
			/* [a-z] */ elseif ($code >= 97 && $code <= 122) { $nLower++;  $locLower[]  = $i; }
			/* .     */ else                                 { $nSymbol++; $locSymbol[] = $i; }

			if (!isset($charDict[$ch])) {
				$charDict[$ch] = 1;
			}
			else {
				$charDict[$ch]++;
			}
		}

		// reward upper/lower characters if pw is not made up of only either one
		if ($nUpper !== $length && $nLower !== $length) {
			if ($nUpper !== 0) {
				$score += ($length - $nUpper) * 2;
			}

			if ($nLower !== 0) {
				$score += ($length - $nLower) * 2;
			}
		}

		// reward numbers if pw is not made up of only numbers
		if ($nNum !== $length) {
			$score += $nNum * 4;
		}

		// reward symbols
		$score += $nSymbol * 6;

		// middle number or symbol
		foreach (array($locNum, $locSymbol) as $list) {
			$reward = 0;

			foreach ($list as $i) {
				$reward += ($i !== 0 && $i !== $length -1) ? 1 : 0;
			}

			$score += $reward * 2;
		}

		// chars only
		if ($nUpper + $nLower === $length) {
			$score -= $length;
		}

		// numbers only
		if ($nNum === $length) {
			$score -= $length;
		}

		// repeating chars
		$repeats = 0;

		foreach ($charDict as $count) {
			if ($count > 1) {
				$repeats += $count - 1;
			}
		}

		if ($repeats > 0) {
			$score -= (int) (floor($repeats / ($length-$repeats)) + 1);
		}

		if ($length > 2) {
			// consecutive letters and numbers
			foreach (array('/[a-z]{2,}/', '/[A-Z]{2,}/', '/[0-9]{2,}/') as $re) {
				preg_match_all($re, $pw, $matches, PREG_SET_ORDER);

				if (!empty($matches)) {
					foreach ($matches as $match) {
						$score -= (strlen($match[0]) - 1) * 2;
					}
				}
			}

			// sequential letters
			$locLetters = array_merge($locUpper, $locLower);
			sort($locLetters);

			foreach ($this->findSequence($locLetters, mb_strtolower($pw)) as $seq) {
				if (count($seq) > 2) {
					$score -= (count($seq) - 2) * 2;
				}
			}

			// sequential numbers
			foreach ($this->findSequence($locNum, mb_strtolower($pw)) as $seq) {
				if (count($seq) > 2) {
					$score -= (count($seq) - 2) * 2;
				}
			}
		}

		return $score;
	}

	/**
	 * Find all sequential chars in string $src
	 *
	 * Only chars in $charLocs are considered. $charLocs is a list of numbers.
	 * For example if $charLocs is [0,2,3], then only $src[2:3] is a possible
	 * substring with sequential chars.
	 *
	 * @param  array  $charLocs
	 * @param  string $src
	 * @return array             [[c,c,c,c], [a,a,a], ...]
	 */
	private function findSequence($charLocs, $src) {
		$sequences = array();
		$sequence  = array();

		for ($i = 0; $i < count($charLocs)-1; ++$i) {
			$here         = $charLocs[$i];
			$next         = $charLocs[$i+1];
			$charHere     = $src[$charLocs[$i]];
			$charNext     = $src[$charLocs[$i+1]];
			$distance     = $next - $here;
			$charDistance = ord($charNext) - ord($charHere);

			if ($distance === 1 && $charDistance === 1) {
				// We find a pair of sequential chars!
				if (empty($sequence)) {
					$sequence = array($charHere, $charNext);
				}
				else {
					$sequence[] = $charNext;
				}
			}
			elseif (!empty($sequence)) {
				$sequences[] = $sequence;
				$sequence    = array();
			}
		}

		if (!empty($sequence)) {
			$sequences[] = $sequence;
		}

		return $sequences;
	}
}

class amfRegistrationService {
	private $availableClothes = Array(102435,102736,102747,102825,102438,102374,102508,102765,102607,102372,102749,102766,102750,102757,102737,102827,102472,102783,102760,102743,101617,102369,102762,102784,102753,102745,102763,102745,102746,102809,102754,101616);
	private static $blackListedES = "pedofil,menstruaciom,menstruacion,menstrvacion,ano,puto,maraca,pito,maricon,conchudo,conchatumadre,conchadetumadre,hijode,hijodeput,chingon,chinga,puta,trolo,trola,escoria,pendejo,pendeja,mierda,mierder,fuck,wank,frikki,polla,miconcha,vagina,pene,silly,stupid,vagine,vaginu,chupala,chupamela,bitch,nigg,dosser,weon,aweonao,weonao,pendeji,kys,kill,stfu,shutup,sutup,hell,shit,cock,penis,dick,connard,satan,joto,boludo,boluda,pelotudo,pelotuda,subnormal,nopor,autista,huevon,wevon,pvto,pvta,webon,cagar,chingada,palancaaltecho,forro,jilipolla,pete,cajeta,cajetud,sexo,sexual,nepe,pinche,marica,conchita,panochon,panocha,panoch,turra,ramera,cortesana,buscona,hetaira,golfa,milonga,milonguera,cualquiera,trola,zurrona,bagasa,prostituta,barragana,ramera,grofa,gamberra,puta,meretriza,autism,anormal,penco,conche,reputi,puti,pndjo,pndja,pendjo,pendja,pndejo,pndeja,gilipoa,gilipoia,gili,pto,pta,elvaginon";
	public static $_suggestion = ["froggy","snaily","picture","squirtel","doodle","poggers","lul","ale","mane","magikarp","raichu","fluffy","puff","moggo","ditto","picto","grammar","linear","magg_","meggy__","__lol__"];
	
	function loadUsernameSuggestions($str, $gender, $op)
	{
		$res = new AmfResponse();
		$res->statusCode = 1;
		$res->message = "service unavailable";
		$res->valueObject = null;
		return $res;
		
		$ns = substr(self::$_suggestion[rand(0,count(self::$_suggestion)-1)].rand(6000, 10000),0,12);
		
		$res = new AmfResponse();
		$res->statusCode = 1;
		$res->message = "success";
		$res->valueObject = [$ns];
		return $res;
	}
	
	function checkPWS($a) {
		$b = new PasswordStrength();
		$c = $b->classify($a);
		
		if($c === 0 || $c === 1) return false;
		
		return true;
	}
	
	static function removeExtraCharactersAndTransform($worssd)
	{
		$none = strtolower($worssd);
		$arr1 = Array('7',' ','4','1','5','3','0','v','V');
		$arr2 = Array('t','','a','i','s','e','o','u','u');
		$preg = Array('/[á]/'=>'[a]?','/[é]/'=>'[e]?','/[í]/'=>'[i]?','/[ó]/'=>'[o]?','/[ú]/'=>'[u]?');
		
		foreach($arr1 as $in => $volwe) {
			if(strpos($none,$volwe) !== false) {
				$none = str_replace($volwe,$arr2[$in],$none);
			}
		}
		
		foreach($preg as $in => $va) {
			$none = preg_replace($in,$va,$none);
		}
		
		$none = preg_replace("/[^a-zA-Z]+/","",$none);
		return $none;
	}
	
	function generateUniqueId() {
		mt_srand((double)microtime() * 10000);
		
		$charid = md5(uniqid(rand(), true));
		$hyphen = chr(45);
		$uuid = chr(123)
			. substr($charid, 0, 8) . $hyphen
			. substr($charid, 8, 4) . $hyphen
			. substr($charid, 12, 4) . $hyphen
			. substr($charid, 16, 4) . $hyphen
			. substr($charid, 20, 12)
			. chr(125);
		
		return $uuid;
	}
	
	function checkEmailAddress($str) {
		$res = new AmfResponse();
		$res->statusCode = 1;
		$res->message = "service unavailable";
		$res->valueObject = null;
		return $res;
		
		if(strrpos($str,"@gmail") !== false || strrpos($str,"@yahoo") !== false || strrpos($str,"@hotmail") !== false){
			try{
				$pdo = $GLOBALS['database']::getConnection();
				
				$ok = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
				$ok->bindParam(1,$str,PDO::PARAM_STR);
				$ok->execute();
				
				if($ok->rowCount() > 0){
					$res = new AmfResponse();
					$res->statusCode = 1;
					$res->message = false;
					$res->valueObject = null;
					return $res;
				}
				
				$res = new AmfResponse();
				$res->statusCode = 0;
				$res->message = "valid";
				$res->valueObject = true;
				return $res;
					
			}catch(PDOException $e){
				$error = date("d.m.Y H:i:s") . "\amfConnectionService::checkEmailAddress\tError: (" . $e->getCode . ") " . $e->getMessage;
				throw new Exception($error);
			}
		}else{
			$res = new AmfResponse();
			$res->statusCode = 1;
			$res->message = "mail not valid";
			$res->valueObject = false;
		}
		return $res;
	}
	
	function getClientIP()
	{
	  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	  } else {
		$ip = $_SERVER['REMOTE_ADDR'];
	  }
	  
	  return $ip;
	}
	
	function register($obj)
	{
		$res = new AmfResponse();
		$res->statusCode = 1;
		$res->message = "service unavailable";
		$res->valueObject = null;
		return $res;
		
		$timestamp = time();
		
		$name = $this->checkUserName($obj->name,true);
		
		if(!$name){
			$res = new AmfResponse();
			$res->statusCode = 1;
			$res->message = "ATTEMPTED_GAME_MANIPULATION_USEREPORTED-4";
			$res->valueObject = false;
			
			return $res;
		}
		
		if($obj->chatId !== "0"){
			$res = new AmfResponse();
			$res->statusCode = 1;
			$res->message = "ATTEMPTED_GAME_MANIPULATION_USEREPORTED-3";
			$res->valueObject = false;
			
			return $res;
		}
		
		if(strlen($obj->pw) < 6){
			$res = new AmfResponse();
			$res->statusCode = 1;
			$res->message = "ATTEMPTED_GAME_MANIPULATION_USEREPORTED-2";
			$res->valueObject = false;
			
			return $res;
		}
		
		if($this->getClientIP() === "127.0.0.1"){
			$ipDetails = $this->ipValidate($this->getClientIP());
			$ipDetails2 = Array("ip"=>"127.0.0.1","phone"=>72);
		}else{
			$ipDetails2 = $this->ipValidate($this->getClientIP());
			
			if(!isset($ipDetails['phone'])) {
				
				$res = new AmfResponse();
				$res->statusCode = 1;
				$res->message = "PROXY_SERVER_DETECTED";
				$res->valueObject = false;
				
				return $res;
			}
		}
		
		if(!$this->ipValidate($ipDetails) && $ipDetails2 != '127.0.0.1'){
			$res = new AmfResponse();
			$res->statusCode = 1;
			$res->message = "IP_REGISTRATION_LIMITED";
			$res->valueObject = false;
			
			return $res;
		}
		
		if($obj->country !== "US") {
			$res = new AmfResponse();
			$res->statusCode = 1;
			$res->message = "ATTEMPTED_GAME_MANIPULATION_USEREPORTED1";
			$res->valueObject = false;
			
			return $res;
		}
		
		if($obj->sex !== "MALE" && $obj->sex !== "FEMALE"){
			$res = new AmfResponse();
			$res->statusCode = 1;
			$res->message = "ATTEMPTED_GAME_MANIPULATION_USEREPORTED2";
			$res->valueObject = false;
			
			return $res;
		}
		
		if(!$this->verifyCloth($obj->itemIds)) {
			$res = new AmfResponse();
			$res->statusCode = 1;
			$res->message = "ATTEMPTED_GAME_MANIPULATION_USEREPORTED3";
			$res->valueObject = false;
			
			return $res;
		}
		
		$password = self::getPasswordHash($obj->pw,$obj->name);
		
		$pdo = $GLOBALS['database']::getConnection();
		
		$swid = $this->generateUniqueId();
		
		$user = $pdo->prepare("INSERT INTO users (username,password,SWID,email,gender,auth_token,last_login,login_count,current_ip,registered_ip,account_created,sheriff,premium,tour_finished,current_gameserver,coins,birthday,home_locked,social_level,social_score,chat_id,helper_status,muted,state,best_friend,attributes,loginIP) VALUES (?,?,?,?,?,?,'0','0',?,?,?,'0','0','0','0','100','0','0','1','0','1','0','0','','','NULL:NULL:NULL:NULL:NULL:NULL:NULL:NULL:NULL:NULL:NULL','???')");
		$user->bindParam(1,$obj->name,PDO::PARAM_STR);
		$user->bindParam(2,$password,PDO::PARAM_STR);
		$user->bindParam(3,$swid,PDO::PARAM_STR);
		$user->bindParam(4,$obj->emailParents,PDO::PARAM_STR);
		$gender = $obj->sex === "MALE"?"boy":"girl";
		$user->bindParam(5,$gender,PDO::PARAM_STR);
		$sess = $pdo->getNewSession(round(rand(9,99) * rand(8,20)) + strlen($obj->name) - 1);
		$user->bindParam(6,$sess,PDO::PARAM_STR);
		$user->bindParam(7,$ipDetails2['ip'],PDO::PARAM_STR);
		$user->bindParam(8,$ipDetails2['ip'],PDO::PARAM_STR);
		$user->bindParam(9,$timestamp,PDO::PARAM_INT);
		
		$user->execute();
		
		$user->closeCursor();
		
		$mom = $pdo->prepare("SELECT * FROM users WHERE registered_ip = ? AND auth_token = ? LIMIT 1");
		$mom->bindParam(1,$ipDetails2['ip'],PDO::PARAM_STR);
		$mom->bindParam(2,$sess,PDO::PARAM_STR);
		$mom->execute();
		
		$userIds = $mom->fetch(PDO::FETCH_ASSOC);
		$userId = $userIds['id'];
		
		foreach($obj->itemIds as $item => $id){
		
			$user = $pdo->prepare("INSERT INTO inventory (player_id,item_id,active,bought,timestamp,parameters,ele_type,x,y,rot) VALUES (?,?,'1','1',?,'WASNT_DECLARED','CLOTHES','0','0','0')");
			$user->bindParam(1,$userId,PDO::PARAM_INT);
			$user->bindParam(2,$id,PDO::PARAM_INT);
			$user->bindParam(3,$timestamp,PDO::PARAM_INT);
			$user->execute();
		}
		
		$user = $pdo->prepare("INSERT INTO inventory (player_id,item_id,active,bought,timestamp,parameters,ele_type,x,y,rot) VALUES (?,'100','1','0',?,'','FURNITURE','0','0','0')");
		$user->bindValue(1,$id);
		$user->bindValue(2,$timestamp);
		$user->execute();
		
		$user->closeCursor();
		
		$user = $pdo->prepare("INSERT INTO inventory (player_id,item_id,active,bought,timestamp,parameters,ele_type,x,y,rot) VALUES (?,'103199','0','0',?,'','FURNITURE','0','0','0')");
		$user->bindValue(1,$id);
		$user->bindValue(2,$timestamp);
		$user->execute();
		
		$result = new AmfResponse();
		$result->statusCode = 0;
		$result->message = "success baeeeee";
		$result->valueObject = null;
		
		return $result;
	}
	
	function verifyCloth($arr = array())
	{
		foreach($arr as $item => $id){
			if(array_search($id,$this->availableClothes) === FALSE){
				return false;
			}
		}
		
		return true;
	}
	
	function ipValidate($phone)
	{
		$phone = $this->getIpInfo($phone);
		if($phone['geoplugin_status'] != 200) return false;
		switch($phone['geoplugin_countryCode']){
			case "FK":
			case "BZ":
			case "GT":
			case "SV":
			case "HN":
			case "NI":
			case "CR":
			case "PA":
			case "PM":
			case "HT":
			case "PE":
			case "MX":
			case "CU":
			case "AR":
			case "BR":
			case "CL":
			case "CO":
			case "VE":
			case "GP":
			case "BO":
			case "GY":
			case "EC":
			case "GF":
			case "PY":
			case "MQ":
			case "SR":
			case "UY":
			case "DO":
			case "ES":
				$valid = true;
				break;
			default:
				$valid = false;
		}
		
		$pdo = $GLOBALS['database']::getConnection();
		
		$ip = $pdo->prepare("SELECT * FROM users WHERE registered_ip = ? LIMIT 1");
		$ip->bindParam(1,$phone['ip'],PDO::PARAM_STR);
		$ip->execute();
		
		if($ip->rowCount() > 0){
			$valid = false;
		}
		
		return $valid;
	}
	
	function getIpInfo($num)
	{
		$details = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip={$num}"),true);
		
		return $details;
	}
	
	function isBadWord($str) {
		$list = explode(',',self::$blackListedES);
		
		foreach($list as $word => $aka) {
			$ooooop = self::transformWord($str);
			if(strpos($ooooop,$aka) !== false) {
				return false;
			}
		}
		
		return true;
	}
	
	static function transformWord($worssd)
	{
		$txt = strtolower($worssd);
		$none = strtolower($worssd);
		$arr1 = Array('7',' ','4','á','é','í','ó','ú','1','5','3','0','v','V');
		$arr2 = Array('t','','a','a','e','i','o','u','i','s','e','o','u','u');
		
		for($i=0;$i<count($arr1);$i++) {
			if(strrpos($none,$arr1[$i]) !== false) {
				$none = str_replace($arr1[$i],$arr2[$i],$none);
			}
		}
		
		$none = preg_replace("/[^a-zA-Z]+/","",$none);
		
		return $none;
	}
	
	function checkUserName($str, $user = false)
	{
		$res = new AmfResponse();
		$res->statusCode = 1;
		$res->message = "service unavailable";
		$res->valueObject = null;
		return $res;
		
		if(preg_match("/^[a-zA-Z0-9_!?]+$/",$str) && strlen($str) > 3 && strlen($str) < 12){
			$pdo = $GLOBALS['database']::getConnection();
			
			$om = $this->removeExtraCharactersAndTransform($str);
			$list = explode(',',self::$blackListedES);
			foreach($list as $word => $aka) {
				if(strrpos($om,$aka) !== false) {
					if($user) return false;
					$res = new AmfResponse();
					$res->statusCode = 1;
					$res->message = "success";
					$res->valueObject = false;
					return $res;
				}
			}
			
			$names = $pdo->prepare("SELECT * FROM users WHERE LOWER(username) = LOWER(?) LIMIT 1");
			$names->bindParam(1,$str,PDO::PARAM_STR);
			$names->execute();
			
			if($names->rowCount() > 0){
				if($user) return false;
				$res = new AmfResponse();
				$res->statusCode = 1;
				$res->message = "success";
				$res->valueObject = false;
				return $res;
			}
			
			if($user) return true;
			$res = new AmfResponse();
			$res->statusCode = 1;
			$res->message = "success";
			$res->valueObject = true;
			return $res;
			
		} else {
			if($user) return false;
			$res = new AmfResponse();
			$res->statusCode = 1;
			$res->message = "success";
			$res->valueObject = false;
			return $res;
		}
	}
	
}