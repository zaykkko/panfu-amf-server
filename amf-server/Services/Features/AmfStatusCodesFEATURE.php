<?php

class AmfStatusCodesFEATURE {	
	private $success = 0;
	private $general_error = 1;
	private $error_on_db = 2;
	private $data_not_found = 3;
	private $wrong_user_or_pass = 4;
	private $not_premium = 5;
	private $player_constrainst = 6;
	private $player_blocked = 7;
	private $warning = 8;
	private $out_of_date = 9;
	private $already_owned = 10;
	private $already_ordered = 11;
	private $already_premium = 12;
	private $voucher_wrong = 13;

	function getCode($str) {
		switch($str){
			case "success":
			case "yea":
			case "yee":
			case "yai":
			case "yeah":
				return $this->success;
			case "nope":
			case "no":
			case "shut":
			case "fuckoff":
			case "ohno":
			case "400":
			case "internalservererror":
				return $this->general_error;
			case "isprem":
			case "alprem":
			case "premium":
				return $this->already_premium;
			case "premerror":
			case "premno":
			case "premnope":
			case "premiumno":
				return $this->not_premium;
			case "fueradtiempo":
			case "fueradetiempo":
			case "outoftime":
			case "outtatime":
			case "timeout":
				return $this->out_of_date;
			case "ban":
			case "banned":
			case "block":
			case "noblock":
			case "blokk":
			case "blok":
			case "ohban":
				return $this->player_blocked;
			case "warn":
			case "warning":
			case "adv":
			case "advertencia":
				return $this->warning;
			case "404":
			case "notfound":
			case "ohnotfound":
				return $this->data_not_found;
			default:
				return $this->general_error;
		}
	}
	
}