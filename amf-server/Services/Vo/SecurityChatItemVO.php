<?php 

class SecurityChatItemVO {
	public $_VObserver = "com.pandaland.mvc.model.vo.SecurityChatItemVO";
	
	public $children;
	public $label;

	function __set($name, $value) {
		$this->$name = $value;
	}

	function __get($name) {
		return $this->$name;
	}

}