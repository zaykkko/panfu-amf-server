<?php

class ProfileVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.ProfileVO";
	
	public $id;
	public $lastBlocked;
	public $bestFriend;
	public $movie;
	public $movieChecked = true;
	public $color;
	public $colorChecked = true;
	public $book;
	public $bookChecked = true;
	public $hobby;
	public $hobbyChecked = true;
	public $song;
	public $songChecked = true;
	public $band;
	public $bandChecked = true;
	public $schoolSubject;
	public $schoolSubjectChecked = true;
	public $sport;
	public $sportChecked = true;
	public $animal;
	public $animalChecked = true;
	public $relStatus;
	public $relStatusChecked = true;
	public $motto;
	public $mottoChecked = true;
	public $bestChar;
	public $bestCharChecked = true;
	public $worstChar;
	public $worstCharChecked = true;
	public $likeMost;
	public $likeMostChecked = true;
	public $likeLeast;
	public $likeLeastChecked = true;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}