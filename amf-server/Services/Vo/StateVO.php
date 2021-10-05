<?php

class StateVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.StateVO";
	
	public $playerId;
	public $cathegoryId;
	public $nameId;
	public $stateValue;
	public $lastChanged;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}