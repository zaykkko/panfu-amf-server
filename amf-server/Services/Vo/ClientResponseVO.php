<?php

class ClientResponse {
	
	public $userAuthToken;
	public $userId;
	public $userLastLangPlayed;
	public $clientModerator;
	public $heyheyhey;
	public $errorTxt;
	public $handShake;

	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}