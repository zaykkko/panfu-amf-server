<?php
require_once dirname(__FILE__) . '/Vo/AmfResponse.php';

class amfComufyService {
	
	function register($channel, $name) {
		$resp = new AmfResponse();
		$resp->statusCode = 0;
		$resp->message = "subscription success";
		$resp->valueObject = null;
		
		return $resp;
	}
	
	function validate($channel, $name)
	{
		$resp = new AmfResponse();
		$resp->statusCode = 0;
		$resp->message = "SUCCESS";
		$resp->valueObject = null;
		
		return $resp;
	}
	
	function hasActiveSubscription()
	{
		$resp = new AmfResponse();
		$resp->statusCode = 1;
		$resp->message = "nope";
		$resp->valueObject = null;
		
		return $resp;
	}
}