<?php

class NamesVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.NamesVO";
	
	public $idPlayer1;
	public $namePlayer1;
	public $idPlayer2;
	public $namePlayer2;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}