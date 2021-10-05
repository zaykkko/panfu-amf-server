<?php

class DateVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.DateVO";
	
	public $date;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}