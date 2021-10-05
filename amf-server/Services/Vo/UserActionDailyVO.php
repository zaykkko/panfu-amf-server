<?php

class UserActionDailyVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.UserActionDailyVO";
	
	public $playerId;
	public $actionId;
	public $doneToday;
	public $time;
	public $doneInTime;
	public $lastDoneActionTime;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}