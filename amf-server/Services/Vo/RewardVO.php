<?php

class RewardVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.RewardVO";
	
	public $levelStatus;
	public $type;
	public $number;
	public $item;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}