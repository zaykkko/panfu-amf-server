<?php

class HomeDataVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.HomeDataVO";
	
	public $id;
	public $playerID;
	public $pets = array();
	public $furnitureList = array();
	public $locked;
	public $trackList = array();
	public $pokoPets = array();
	public $bollies = array();

	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}