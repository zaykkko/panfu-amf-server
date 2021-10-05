<?php

class SocialHighscoreVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.SocialHighscoreVO";
	
	public $playerID;
	public $gameID;
	public $otherPlayerID;
	public $playerScore;
	public $otherPlayerScore;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}