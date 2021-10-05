<?php

class PokoPetPropertiesVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.PokoPetPropertiesVO";
	
	public $agility;
	public $health;
	public $level;
	public $experience;
	public $maxHealth;
	public $power;
	public $speed;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}