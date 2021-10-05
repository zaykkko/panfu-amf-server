<?php

class PriceVO {
	public $_VObserver = "com.pandaland.mvc.model.vo.PriceVO";
	
	public $value;
	public $currency;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
}