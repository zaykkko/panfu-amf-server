<?php

class PokoPetVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.PokoPetVO";
	
	public $selected;
	public $x;
	public $y;
	public $state;
	public $status;
	public $id;
	public $properties;
	public $abilities;
	public $activity;
	public $percentToNextLevel;
	public $lastFed;
	public $type;
	public $name;
	public $z;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}