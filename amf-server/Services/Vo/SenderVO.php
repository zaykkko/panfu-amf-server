<?php

class SenderVO {
	
	public $_VObserver = "com.pandaland.informationserver.features.pinboard.vo.SenderVO";
	
	public $senderName;
	public $senderId;
	
	function __set($value, $input){
		$this->$value = $input;
	}
	
	function __get($name){
		return $this->$name;
	}
	
}