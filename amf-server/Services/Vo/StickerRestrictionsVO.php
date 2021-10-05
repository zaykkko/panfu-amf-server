<?php

class StickerRestrictionsVO {
	
	public $_VObserver = "com.pandaland.informationserver.features.stickers.vo.StickerRestrictionsVO";
	
	public $minLevel;
	public $coins;
	public $premium;
	
	function __set($value, $input){
		$this->$value = $input;
	}
	
	function __get($name){
		return $this->$name;
	}
	
}