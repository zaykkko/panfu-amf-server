<?php

class SmallPlayerInfoVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.SmallPlayerInfoVO";
	
	public $playerId;
	public $playerName;
	public $currentGameServer;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}