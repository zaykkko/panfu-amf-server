<?php

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Vo/MicroPaymentOrderVO.php';
require_once dirname(__FILE__) . '/Vo/SmsProviderVO.php';
require_once dirname(__FILE__) . '/Vo/PriceVO.php';

class amfMoPayService {
	private $id = 50;
	
	function createProvider($shortCode, $name)
	{
		$mom = new SmsProviderVO();
		$mom->name = $name;
		$mom->shortCode = $shortCode;
		return $mom;
	}
	
	function getMembershipCode($countryIp)
	{
		$amf = new AmfResponse();
		$non = new MicroPaymentOrderVO();
		$non->country = "México";
		$non->code = "Panfu.me";
		$non->snippet = "";
		$non->smsProviders = Array($this->createProvider("Continuidad","Telcel"));
		$non->membershipPrice = new PriceVO();
		$non->membershipPrice->currency = 8500;
		$non->membershipPrice->value = "USD";
		$non->bundlePrice = null;
		$non->duration = 1;
		$amf->message = "success";
		$amf->statusCode = 6;
		$amf->valueObject = $non;
		return $amf;
	}
}