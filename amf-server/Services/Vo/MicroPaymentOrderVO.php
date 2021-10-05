<?php

class MicroPaymentOrderVO {
	public $_VObserver = "com.pandaland.mvc.model.vo.MicroPaymentOrderVO";
	
	public $membershipPrice;
	public $bundlePrice;
	public $duration;
	public $smsProviders;
	public $snippet;
	public $code;
	public $country;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
}