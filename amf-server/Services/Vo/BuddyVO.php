<?php

class BuddyVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.BuddyVO";
	
	public $id;
	public $name;
	public $premium;
	public $bestfriend;
	public $currentGameServer;
	public $socialLevel;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}