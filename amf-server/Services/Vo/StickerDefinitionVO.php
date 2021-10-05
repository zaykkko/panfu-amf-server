<?php

class StickerDefinitionVO {
	
	public $_VObserver = "com.pandaland.informationserver.features.stickers.vo.StickerDefinitionVO";
	
	public $points;
	public $restrictions;
	public $id;
	
	function __set($value, $input){
		$this->$value = $input;
	}
	
	function __get($name){
		return $this->$name;
	}
	
}