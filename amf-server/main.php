<?php

date_default_timezone_set("America/Monterrey");

set_time_limit(0);
error_reporting(0);

require_once dirname(__FILE__) . '/AMFService.php';

try {
	$AMFService = new AMFService(getallheaders());
	ob_start();
	try{
		$AMFService::verifyHeaders();
	} catch(AMFException $e) {
		exit;
	}
	
} catch(AMFException $e) {
	exit;
}

if($AMFService::$result) {
	try {
		$AMFService::init();
		$AMFService::getHash(uniqid(mt_rand(),true));
		$AMFService::end();
		print($AMFService::$response);
		$AMFService::end(true);
	} catch(AMFException $e) {
		exit;
	}
} else {
	// ; echo $AMFService::$type;
	exit;
}
