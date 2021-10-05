<?php

class AttributeResponseAAAVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.ObjectVO";
	
	public $performed;
	public $name;
	public $status;
	public $statusGlow;
	public $statusColor;
	public $nameColor;
	public $nameGlow;
	public $textFont;
	public $alpha;
	public $stars;
	public $walk;

	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}

?>