<?php

class LoginResultVO {
	
	public $_VObserver = "com.pandaland.mvc.model.vo.LoginResultVO";
	
	public $playerInfo;
	public $partnerTracking;
	public $ticketId;
	public $showTour;
	public $showNewsletterScreen;
	public $promoMessageKey;
	public $gameplayPanfu;
	public $gameServers = array();
	public $date;
	public $loginCount;
	public $goldPandaDay;
	public $blockedUser;
	public $membershipStatus;
	public $email;
	public $hungryPokoPets = array();
	public $promoMembership;
	public $unreadMessagesCount = 0;
	public $undeletedMessagesCount = 0;
	
	function __set($name, $value) {
		$this->$name = $value;
	}
	
	function __get($name) {
		return $this->$name;
	}
	
}