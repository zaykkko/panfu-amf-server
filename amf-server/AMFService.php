<?php
require_once 'AMFSessions.php';
class AMFException extends Exception {}

class AMFService {
	private static $requestHeaders;
	private static $gateway;
	private static $execution;
	private static $ahc = 0;
	private static $ahi = 0;
	public static $result;
	public static $type;
	public static $response;
	private static $_service = "amf";
	private static $correct = Array(
		"Origin" => "https://origin.viewalot.eu",
		"Origin2" => "https://lsv.viewalot.eu",
		"Content-Type" => "application/x-amf",
		"X-Requested-With" => "ShockwaveFlash"
	);
	
	private static $_correct = Array(
		"Authorization" => "ovHB3cXIK3e8C5F675u35KNOMY1tu4GE5O42v6OBAp2jpKKfh82Tx3WRXtLttlXodo62mtuo24NuPb4LLoEOj5R",
		"X-User-Key" => "eyJhbGciOiJIUzI1NiIsIncCI6IkpXVCJ9eyJzibmFtZSI6IkpvaG4gRG9lIi0IjoxNTE2MjM5MDIyfQXbPfbIHMI6arZ3Y922BhjWgQzWXcXNrz0ogtVhfEd2o",
		"X-Api-Logger" => "kwIiwibmFtZSI6Ikpnb2huIG9lIiwiaWF0IjoxNTYyOTAyMn0r7k99UnY7s1oMwPNZXmJzN0wZXhKUiruDhLC_1XHim0",
		"Content-Type" => "application/json",
		"X-Action-Type" => "amf.check"
	);
	
	private static $hasAPIHeaders = Array(
		"Authorization" => FALSE,
		"X-User-Key" => FALSE,
		"X-Api-Logger" => FALSE,
		"Content-Type" => FALSE,
		"X-Action-Type" => FALSE
	);
	
	private static $hasHeaders = Array(
		"Origin" => FALSE,
		"Content-Type" => FALSE,
		"X-Requested-With" => FALSE
	);
	
	private static $results = Array(
		"ERROR" => "<style>table{font-family: arial, sans-serif;border-collapse: collapse;width: 50%;}td, th {border: 1px solid #dddddd;text-align: left;padding: 8px;}tr:nth-child(even) {background-color: #0000FF;}</style><body bgcolor=\"#000000\"/><title>A lil beach tried to load this shitty page oh god</title><p style=\"color:#FF0000\">You're so<br>f*cking<br>precious<br>when you<br> S M I L E <br></p><p id=\"langed\" style=\"color:#0000FF\">Wtf r u doin' there?</p><br><br><br><hr><br><p style=\"color:#00FF00;padding:30px;text-align: right;\">&nbsp;<s>- info.lsv.viewalot.eu</s> <br>- info.panfu.es<p><br><br><br><script>var color=[\"#FF0000\",\"#00FF00\",\"#0000FF\",\"#F0F0F0\",\"#0C94F0\"];var i=0;var lang=[\"Wtf r u doin' there?\",\"�Qu� est�s haciendo aqu�?\",\"Was machst du hier?\",\"Co tutaj robisz?\",\"????????\",\"??? ?? ????? ????????\",\"Que fais-tu ici?\"];var norman=setInterval(function(){document.getElementById(\"langed\").innerHTML=lang[i];document.getElementById(\"langed\").style.color=color[Math.floor(Math.random()*(lang.length-1)-1)+1];i++;if(i>lang.length-1){i=0;}},1000);</script>",
		"CONTINUE" => TRUE
	);
	
	function __construct($headers) {
		if(count($headers) > 0) {
			self::$requestHeaders = $headers;
			if($_SERVER['REQUEST_METHOD'] === 'GET') {
				self::$_service = "api";
			}
			return true;
		}
		
		throw new AMFException("Los headers son inv�lidos.",1);
	}
	
	static function verifyHeaders() {
		switch(self::$_service) {
			case "amf":
				foreach(self::$requestHeaders as $header => $arg) {
					switch($header) {
						case 'Origin':
							if($arg === self::$correct[$header] || $arg === self::$correct[$header.'2']) {
								self::$ahc = self::$ahc+1;
								self::$hasHeaders[$header] = TRUE;
							} else {
								self::$ahi = self::$ahi+1;
								self::$hasHeaders[$header] = FALSE;
							}
							break;
						case 'Content-Type':
							if($arg === self::$correct[$header]) {
								self::$ahc = self::$ahc+1;
								self::$hasHeaders[$header] = TRUE;
							} else {
								self::$ahi = self::$ahi+1;
								self::$hasHeaders[$header] = FALSE;
							}
							break;
						case 'X-Requested-With':
							if(strpos($arg,self::$correct[$header]) !== FALSE) {
								self::$ahc = self::$ahc+1;
								self::$hasHeaders[$header] = TRUE;
							} else {
								self::$ahi = self::$ahi+1;
								self::$hasHeaders[$header] = FALSE;
							}
							break;
					}
				}
				break;
			case "api":
				foreach(self::$requestHeaders as $header => $arg) {
					switch($header) {
						case "Content-Type":
							if(strpos($arg,self::$_correct[$header]) !== FALSE) {
								self::$ahc = self::$ahc+1;
								self::$hasAPIHeaders[$header] = TRUE;
							} else {
								self::$ahi = self::$ahi+1;
								self::$hasAPIHeaders[$header] = FALSE;
							}
							break;
						case "Authorization":
						case "X-User-Key":
						case "X-Api-Logger":
						case "X-Action-Type":
							if($arg === self::$_correct[$header]) {
								self::$ahc = self::$ahc+1;
								self::$hasAPIHeaders[$header] = TRUE;
							} else {
								header($_SERVER['SERVER_PROTOCOL']." 403 Forbidden");
								throw new AMFException('Header error',3);
							}
							break;
					}
				}
				break;
			default:
				throw new AMFException('Content type error',3);
		}
		
		if(self::$_service === "amf") {
		
			if($_SERVER['REQUEST_METHOD'] != 'POST') {
				self::endPointHeaders();
				header($_SERVER['SERVER_PROTOCOL']." 405 Method Not Allowed");
				self::$result = false;
				self::$type = "ERROR";
				
				throw new AMFException('Method not allowed',1);
			}
			
			$data = file_get_contents('php://input');
			
			if($data === null || $data === '') {
				self::endPointHeaders();
				header($_SERVER['SERVER_PROTOCOL']." 500 Internal Server Error");
				self::$result = false;
				self::$type = "ERROR";
				
				throw new AMFException('Content Error',3);
			}
			
			if(self::$ahc > 2 && self::$ahi == 0) {
				self::$result = true;
				self::$type = "CONTINUE";
				session_start();
				
				return true;
			} else {
				
				self::$result = false;
				self::$type = "ERROR";
				
				throw new AMFException('Headers no definidos',1);
			}
		
		} else if(self::$_service === 'api') {
			
			if($_SERVER['REQUEST_METHOD'] != 'GET') {
				self::endPointHeaders();
				header($_SERVER['SERVER_PROTOCOL']." 405 Method Not Allowed");
				self::$result = false;
				self::$type = "ERROR";
				
				throw new AMFException('Method not allowed',1);
			}
			
			$data = json_decode(file_get_contents('php://input'),true);
			
			if($data === null && json_last_error() !== json_error_none) {
				self::endPointHeaders();
				header($_SERVER['SERVER_PROTOCOL']." 500 Internal Server Error");
				self::$result = false;
				self::$type = "ERROR";
				
				throw new AMFException('Content Error',3);
			}
			
			if(self::$ahc > 2 && self::$ahi == 0) {
				self::$result = true;
				self::$type = "CONTINUE";
				
				return true;
			} else {
				
				self::endPointHeaders();
				header($_SERVER['SERVER_PROTOCOL']." 500 Internal Server Error");
				self::$result = false;
				self::$type = "ERROR";
				
				throw new AMFException('Headers no definidos',1);
			}
		}
		
		self::endPointHeaders();
		header($_SERVER['SERVER_PROTOCOL']." 500 Internal Server Error");
		self::$result = false;
		self::$type = "ERROR";
		
		throw new AMFException('Headers no definidos',1);
	}

	static function getHash($salt) {
		$GLOBALS['CODE'] = crypt($salt,"SHA9").uniqid(mt_rand(), true).crypt(rand(0,5),$salt.md5(floor(microtime(true) * 1000)));
	}
	
	static function init() {
		if(self::$_service === 'amf') {
			require_once dirname(__FILE__) . '/classes.php';
			
			global $CLIENTVERSION;
			global $CODE;
			global $extimer;
			global $respCount;
			
			$GLOBALS['CLIENTVERSION']= 1.5;
			
			self::$execution = new ExcTime();
			self::$execution->start();
			
			self::$gateway = Amfphp_Core_HttpRequestGatewayFactory::createGateway();

			return self::$gateway->service();
		} else if(self::$_service === 'api') {
			$_arg = json_decode(file_get_contents('php://input'),true);
			
			if(!isset($_arg['target']) || !isset($_arg['confirmation']) || !isset($_arg['password'])) {
				self::endPointHeaders();
				header($_SERVER['SERVER_PROTOCOL']." 500 Internal Server Error");
				die(json_encode(Array("result"=>false,"numeric_result"=>3,"error_str"=>"Fields doesn't match.","data"=>Array(),"limit"=>0)));
			}
			
			if($_arg['confirmation'] !== base64_encode(md5($_arg['target'].$_arg['password']))) {
				self::endPointHeaders();
				header($_SERVER['SERVER_PROTOCOL']." 500 Internal Server Error");
				die(json_encode(Array("result"=>false,"numeric_result"=>2,"error_str"=>"Confirmation doesn't match.","data"=>Array(),"limit"=>0)));
			}
			
			require_once dirname(__FILE__) . '/Includes/Database.php'; 
			
			$_con = new DBConnection();
			$info = $_con->getAPInfo($_arg['target'],$_arg['password']);
			
			if(!$info['rest']) {
				$result = Array("result"=>false,"numeric_result"=>1,"error_str"=>$info['message'],"data"=>Array(),"limit"=>0);
			} else {
				$result = Array("result"=>true,"numeric_result"=>0,"error_str"=>"","data"=>Array("id"=>(int)$info['args']['id'],"premium"=>(int)$info['args']['premium'],"moderator"=>$info['args']['sheriff'],"ticket"=>(int)$info['args']['auth_token'],"clothes"=>(($info['clothes']!="")?$_con->__parse($info['clothes']):""),"timestamp"=>floor(microtime(true)*1000)),"limit"=>count($info));
			}
			
			header("Content-Type: application/json");
			header("Connection: close");
			
			die(json_encode($result));
		}
		
		self::endPointHeaders();
		header($_SERVER['SERVER_PROTOCOL']." 500 Internal Server Error");
		exit();
	}
	
	static function end($bol = false) {
		if($bol) exit();
		
		if(self::$_service === 'amf') {
			self::$execution->stop();
			
			self::$response = self::$gateway->output();
		}
	}
	
	static function endPointHeaders() {
		header($_SERVER['SERVER_PROTOCOL']." 401 Unauthorized");
		header("Connection: close");
		
		header_remove("x-powered-by");
		header_remove("Pragma");
		header_remove("Expires");
	}
	
	static function result($value, $resultId) {
		self::$result = $value;
		self::$type = self::$results[$resultId];
		if(self::$resultId == 'ERROR') self::endPointHeaders();
		
		return true;
	}
}