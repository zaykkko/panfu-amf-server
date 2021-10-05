<?php

class BeSmarterScoreVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.BeSmarterScoreVO";
	
	public $correctAnswers;
	public $playerName;
	public $playerId;
	public $points;
	public $falseAnswers;
	public $time;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}