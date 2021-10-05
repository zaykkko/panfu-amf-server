<?php

class GameServerVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.GameServerVO";
	
	public $id;
	public $name;
	public $playercount;
	public $url;
	public $port;
	public $ageFrom;
	public $ageTo;
	public $premiumonly;
	public $availableFor;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}