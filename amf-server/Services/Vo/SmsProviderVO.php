<?php

class SmsProviderVO {
	public $_VObserver = "com.pandaland.mvc.model.vo.SmsProviderVO";
	
	public $shortCode;
	public $name;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
}