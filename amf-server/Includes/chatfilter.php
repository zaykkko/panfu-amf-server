<?php


class ChatFilter {
	private $texto;
	private $playerId;
	private $idioma;
	private $can_Send = true;
	private $nuevotexto = null;
	private $session;
	private $mmods = true;
	
	function __construct($userId, $lang, $product, $txt)
	{
		$this->playerId = $userId;
		$this->idioma = $lang;
		$this->producto = $product;
		$this->texto = $txt;
		$this->session = "abb";
	}
	
	function start()
	{
		$badwords = Array('concha de','chota','puto','puta','put@','pvta','put0','put4','conchudo','ierda','caca','kkck','maric','pendej','pelotud','bolud','autist','autism','bolud','polla','pene','vagin','chupala','idiot','supercpps','SuperCPPS','SUPERCPPS','spps','CPPS','Cpps','CPps','cpPS','CpPs','CPPs','CpPS','contrase','.xyz','.com','.es','.eu','.ru','.en','.in','.pl','.dk','.rf','.onion','.me','.pw','fuck','bitch','putain','kys','connard','ass','shit','shiet','sh1t','sh13t','cock','penis','dick','dosser','wank','shutup','shutup','army','Wild Ninjas','WILD NINJAS','WIld Ninjas','kill','hell','satan','hijo de');
		if(strrpos($this->texto,'%') !== false)
		{
			if(count(explode('%',$this->texto)) > 4)
			{
				$this->can_Send = false;
				$this->mmods = false;
				if(explode('%',$this->texto)[2] === 'z')
				{
					$this->nuevotexto = "PHRASE_CHAT_COMMAND|INVALID|undefined";
				}
				else
				{
					$exploders = explode("#",$this->texto);
					$commands = explode('%',$this->texto);
					$rest = $exploders[1];
					$rest2 = explode('%',$rest);
					$rest3 = array_splice($rest2,2,count($rest2));
					empty($rest3[count($rest3)-1]);
					unset($rest3[count($rest3)-1]);
					$rest4 = implode('%',$rest3);
					$this->nuevotexto = 'PHRASE_CHAT_COMMAND|VALID|'.$commands[2].'|'.$commands[3].'|'.$rest4;
					$this->texto = "";
				}
			}
			else
			{
				$this->nuevotexto = $this->texto;
			}
		}
		else
		{
			if(strrpos($this->texto,"cion") !== false)
			{
				$this->nuevotexto = str_replace("cion","ción",$this->texto);
				$this->texto = $this->nuevotexto;
			}
			else if(strrpos($this->texto,"CION") !== false)
			{
				$this->nuevotexto = str_replace("CION","CIÓN",$this->texto);
				$this->texto = $this->nuevotexto;
			}
			else
			{
				$this->nuevotexto = $this->texto;
			}
		}
		return null;
		$i = 0;
		WHILE($i < count($badwords))
		{
			if(strrpos(strtolower($this->texto),strtolower($badwords[$i])) !== false)
			{
				$this->nuevotexto = "\'#~~#\"#NO ONE DID THAT";
				$this->can_Send = false;
			}
			$i++;
		}
		if($this->nuevotexto === null)
		{
			$this->nuevotexto = $this->texto;
		}
	}
	
	function printID()
	{
		header_remove('x-powered-by');
		header_remove('pragma');
		header_remove('expires');
		header("Cache-Control: private, no-store, no-cache, must-revalidate");
		header("X-Client-Request-Hash: ".md5(rand(0,5000).md5($this->session.$this->nuevotexto).rand(0,5000)));
		header("X-Client-Player-Id: ".rand(344959,9999999));
		header("X-Client-Version: 1.2.0");
		header("X-Client-Valid-Key: j8Rq?-d=K+5+XKahCK-e&+H#jgH*5XER38&fwAwmfE^M29vA?w^J%7v8R^qQ?eKVQeRJSddWP2beQ@EzZZr2?BYjekzUXF4FLh=T2HnWcYbEUN44YzB9zS6X-sct25/9!u-RNGWmawpx7nzJjPUyjTd");
		header("Connection: close");
		header("X-Client-Execute-Time: now");
		header('Content-type: application/json; charset=utf-8');
		//header($_SERVER["SERVER_PROTOCOL"].' 200 Connection Established');
		session_destroy();
		$response = new stdClass();
		$response->id = $this->nuevotexto;
		$response->can_send = $this->can_Send;
		$response->is_equiv = $this->texto === 'hola'?true:false;
		$response->text_length = $this->texto === ""?strlen($response->id):strlen($this->texto);
		$response->moderated = $this->mmods;
		$response->original_text = $this->texto;
		echo json_encode($response);
	}
}

?>