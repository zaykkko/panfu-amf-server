<?php

class AmfResponse {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.AmfResponse";
	
	public $statusCode;
	public $message;
	public $valueObject;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}