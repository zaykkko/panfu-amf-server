<?php

class ItemVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.ItemVO";
	
	public $id;
	public $name;
	public $type;
	public $price;
	public $zettSort;
	public $premium;
	public $bought;
	public $active;
	public $movementType;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}