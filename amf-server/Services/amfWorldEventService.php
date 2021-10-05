<?php

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';

class amfWorldEventService {
	
	function increaseContainerValue($worldID)
	{
		$param1 = new AmfResponse();
		$param1->statusCode = 0;
		$param1->message = new stdClass();
		$param1->message->value = rand(5,35);
		$param1->message->maxValue = 62;
		
		return $pero;
	}
	
	function loadContainer($worldID)
	{
		$param1 = new AmfResponse();
		$param1->statusCode = 0;
		$param1->message = new stdClass();
		$param1->message->value = rand(5,35);
		$param1->message->maxValue = 62;
		
		return $param1;
	}
}