<?php

class StickerVO {
	
	public $_VObserver = "com.pandaland.informationserver.features.stickers.vo.StickerVO";
	
	public $amount;
	public $definitionId;
	
	function __set($value, $input){
		$this->$value = $input;
	}
	
	function __get($name){
		return $this->$name;
	}
	
}