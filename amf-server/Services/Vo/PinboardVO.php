<?php

class PinboardVO {
	
	public $_VObserver = "com.pandaland.informationserver.features.pinboard.vo.PinboardVO";
	
	public $messages;
	public $undeletedMessagesCount;
	public $offset;
	public $limit;
	
	function __set($value, $input){
		$this->$value = $input;
	}
	
	function __get($name){
		return $this->$name;
	}
}