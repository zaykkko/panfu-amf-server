<?php

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';

class amfGameService {
	
	function setHighScore($ok = 25, $ob)
	{
		try{
			
			$m = $GLOBALS['database']::addScore($ok,$ob);
			
			$mo = new AmfResponse();
			$mo->statusCode = 0;
			$mo->message = "success wow - ".$m;
			$mo->valueObject = 0;
			return $mo;
			
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfGameService::finishMiniGame\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function getHighScoreLists()
	{
		$mo = new AmfResponse();
		$mo->statusCode = 0;
		$mo->message = "success";
		$mo->valueObject = null;
		return $mo;
	}
	
	function finishMiniGame($ok = 25, $ob = 2)
	{
		try{
			
			$mo = new AmfResponse();
			$mo->statusCode = 0;
			$mo->message = "success";
			$mo->valueObject = 0;
			return $mo;
			
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfGameService::finishMiniGame\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
}