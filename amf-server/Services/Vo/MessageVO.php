<?php

class MessageVO {
	
	public $_VObserver = "com.pandaland.informationserver.features.pinboard.vo.MessageVO";
	
	public $sender;
	public $parentMessageId;
	public $typeId;
	public $content;
	public $messageId;
	public $read;
	public $createdAt;
	public $replied;
	
	function __set($value, $input){
		$this->$value = $input;
	}
	
	function __get($name){
		return $this->$name;
	}
}