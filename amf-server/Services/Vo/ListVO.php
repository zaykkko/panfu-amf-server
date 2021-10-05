<?php

class ListVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.ListVO";
	
	public $list = array();
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}