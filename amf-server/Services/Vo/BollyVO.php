<?php

class BollyVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.BollyVO";
	
	public $id;
	public $name;
	public $type;
	public $price;
	public $state;
	public $activity;
	public $health;
	public $rest;
	public $energy;
	public $rescueTime;
	public $x;
	public $y;
	public $z;
	public $colour;
	public $style;

	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}