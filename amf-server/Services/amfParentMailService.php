<?php

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';

class amfParentMailService {
	
	function trackString($ok = null, $op = null) {
		$mo = new AmfResponse();
		$mo->statusCode = 0;
		$mo->message = "success";
		$mo->valueObject = null;
		return $mo;
	}
}