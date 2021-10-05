<?php

class InventoryVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.InventoryVO";
	
	public $activeItems = array();
	public $inactiveItems = array();
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}