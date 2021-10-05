<?php

class AMFServiceErrorVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.AMFServiceErrorVO";
	
	public $reason;
	public $result;
	public $level;
	public $service;
	public $userRecurred;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}