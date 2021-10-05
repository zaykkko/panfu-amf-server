<?php

class AddedMessageVO {
	
	public $_VObserver = "com.pandaland.informationserver.features.pinboard.vo.AddedMessageVO";
	
	public $createdMessageVO;
	public $receivers;
	
	function __set($value, $input){
		$this->$value = $input;
	}
	
	function __get($name){
		return $this->$name;
	}
}