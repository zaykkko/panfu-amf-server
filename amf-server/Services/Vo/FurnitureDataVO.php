<?php

class FurnitureDataVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.FurnitureDataVO";
	
	public $id;
	public $uid;
	public $rot;
	public $x;
	public $y;
	public $roomID = 0;
	public $type;
	public $parameters;
	public $premium;
	public $bought;
	public $active;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}