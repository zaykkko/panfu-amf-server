<?php

require_once dirname(__FILE__) . '/../Vo/PokoPetPropertiesVO.php';

class PetContext {
	private $pets = Array(
		"bolly" => array(
			"blue" => array(
				"id" => "20001",
				"x" => "60",
				"y" => "0",
				"z" => "0",
				"colour" => "0x00CCFF",
				"normal" => "bollyNormal",
				"idle" => "bollyFlying",
				"game" => "bollyRocket",
				"denying" => "bollyDenying",
				"sleeping" => "bollySleeping",
				"eating" => "bollyFood",
				"cardBack" => "bollyBackground_blue",
				"style" => "normal",
				"name" => "Blue Bolly"
			),
			
			"blueWood" => array(
				"id" => "101696",
				"x" => "60",
				"y" => "0",
				"z" => "0",
				"colour" => "0x00CCFF",
				"normal" => "bollyNormal",
				"idle" => "bollyFlying",
				"game" => "bollyRocket",
				"denying" => "bollyDenying",
				"sleeping" => "bollySleeping",
				"eating" => "bollyFood",
				"cardBack" => "bollyBackground_blue",
				"style" => "normal",
				"name" => "Blue Bolly Wood"
			),
			
			"red" => array(
				"id" => "20002",
				"x" => "-80",
				"y" => "-5",
				"z" => "1",
				"colour" => "0xFF0000",
				"normal" => "bollyNormal",
				"idle" => "bollyDreaming",
				"game" => "bollyJuggle",
				"denying" => "bollyDenying",
				"sleeping" => "bollySleeping",
				"eating" => "bollyFood",
				"cardBack" => "bollyBackground_red",
				"style" => "normal",
				"name" => "Red Bolly"
			),
			
			"grey" => array(
				"id" => "20003",
				"x" => "38",
				"y" => "5",
				"z" => "4",
				"colour" => "0x333333",
				"normal" => "bollyNormal",
				"idle" => "bollyRunning",
				"game" => "bollyBubbles",
				"denying" => "bollyDenying",
				"sleeping" => "bollySleeping",
				"eating" => "bollyFood",
				"cardBack" => "bollyBackground_grey",
				"style" => "normal",
				"name" => "Black Bolly"
			),
			
			"pink" => array(
				"id" => "20004",
				"x" => "53",
				"y" => "-5",
				"z" => "3",
				"colour" => "0xFF00CC",
				"normal" => "bollyNormal",
				"idle" => "bollyCombing",
				"game" => "bollyBubbles",
				"denying" => "bollyDenying",
				"sleeping" => "bollySleeping",
				"eating" => "bollyFood",
				"cardBack" => "bollyBackground_pink",
				"style" => "normal",
				"name" => "Pink Bolly"
			),
			
			"green" => array(
				"id" => "20005",
				"x" => "38",
				"y" => "-5",
				"z" => "2",
				"colour" => "0x00FF00",
				"normal" => "bollyNormal",
				"idle" => "bollyRelaxing",
				"game" => "bollyJuggle",
				"denying" => "bollyDenying",
				"sleeping" => "bollySleeping",
				"eating" => "bollyFood",
				"cardBack" => "bollyBackground_green",
				"style" => "normal",
				"name" => "Green Bolly"
			),
			
			"easter" => array(
				"id" => "20006",
				"x" => "80",
				"y" => "-5",
				"z" => "5",
				"colour" => "-1",
				"normal" => "bollyNormal",
				"idle" => "bollyEaster",
				"game" => "bollyJuggle",
				"denying" => "bollyDenying",
				"sleeping" => "bollySleeping",
				"eating" => "bollyFood",
				"cardBack" => "bollyBackground_easter",
				"style" => "easter",
				"useSoundOfId" => "20001",
				"name" => "Easter Bolly"
			),
			
			"tiger" => array(
				"id" => "100266",
				"x" => "80",
				"y" => "-5",
				"z" => "5",
				"colour" => "-3",
				"normal" => "bollyNormal",
				"idle" => "bollyTiger",
				"game" => "bollyJuggle",
				"denying" => "bollyDenying",
				"sleeping" => "bollySleeping",
				"eating" => "bollyFood",
				"cardBack" => "bollyBackground_tiger",
				"style" => "tiger",
				"useSoundOfId" => "20001",
				"name" => "Tiger Bolly"
			),
			
			"gold" => array(
				"id" => "100392",
				"x" => "80",
				"y" => "-5",
				"z" => "5",
				"colour" => "-4",
				"normal" => "bollyNormal",
				"idle" => "bollyGold",
				"game" => "bollyJuggle",
				"denying" => "bollyDenying",
				"sleeping" => "bollySleeping",
				"eating" => "bollyFood",
				"cardBack" => "bollyBackground_gold",
				"style" => "gold",
				"useSoundOfId" => "20001",
				"name" => "Gold Bolly"
			),
			
			"easter09" => array(
				"id" => "100524",
				"x" => "80",
				"y" => "-5",
				"z" => "5",
				"colour" => "-5",
				"normal" => "bollyNormal",
				"idle" => "bollyEaster09",
				"game" => "bollyJuggle",
				"denying" => "bollyDenying",
				"sleeping" => "bollySleeping",
				"eating" => "bollyFood",
				"cardBack" => "bollyBackground_easter09",
				"style" => "easter09",
				"useSoundOfId" => "20001",
				"name" => "Easter Super Bolly"
			),
			
			"red_bolly_wood" => array(
				"id" => "101697",
				"x" => "80",
				"y" => "-5",
				"z" => "1",
				"colour" => "0xFF0000",
				"normal" => "bollyNormal",
				"idle" => "bollyDreaming",
				"game" => "bollyJuggle",
				"denying" => "bollyDenying",
				"sleeping" => "bollySleeping",
				"eating" => "bollyFood",
				"cardBack" => "bollyBackground_red",
				"style" => "normal",
				"useSoundOfId" => "20001",
				"name" => "Red Wood Bolly"
			),
			
			"woobycolorful" => array(
				"id" => "100575",
				"x" => "10",
				"y" => "-5",
				"z" => "5",
				"colour" => "-100575",
				"normal" => "woobyColorfulNormal",
				"idle" => "woobyColorfulNormal",
				"game" => "woobyColorfulBubbles",
				"denying" => "woobyColorfulDenying",
				"sleeping" => "woobyColorfulSleeping",
				"eating" => "woobyColorfulEating",
				"cardBack" => "bollyBackground_woobycolorful",
				"style" => "normal",
				"useSoundOfId" => "20004",
				"name" => "Colorful Wooby"
			),
			
			"woobyorange" => array(
				"id" => "100576",
				"x" => "600",
				"y" => "35",
				"z" => "5",
				"colour" => "-100576",
				"normal" => "woobyOrangeNormal",
				"idle" => "woobyOrangeNormal",
				"game" => "woobyOrangeBubbles",
				"denying" => "woobyOrangeDenying",
				"sleeping" => "woobyOrangeSleeping",
				"eating" => "woobyOrangeEating",
				"cardBack" => "bollyBackground_woobyorange",
				"style" => "normal",
				"useSoundOfId" => "20005",
				"name" => "Orange Wooby"
			),
			
			"woobygreen" => array(
				"id" => "100683",
				"x" => "600",
				"y" => "35",
				"z" => "5",
				"colour" => "-100646",
				"normal" => "woobyGreenNormal",
				"idle" => "woobyGreenNormal",
				"game" => "woobyGreenBubbles",
				"denying" => "woobyGreenDenying",
				"sleeping" => "woobyGreenSleeping",
				"eating" => "woobyGreenEating",
				"cardBack" => "bollyBackground_woobygreen",
				"style" => "normal",
				"useSoundOfId" => "20002",
				"name" => "Green Wooby"
			),
			
			"woobyheart" => array(
				"id" => "100825",
				"x" => "600",
				"y" => "35",
				"z" => "5",
				"colour" => "-100824",
				"normal" => "woobyHeartNormal",
				"idle" => "woobyHeartNormal",
				"game" => "woobyHeartPlaying",
				"denying" => "woobyHeartDenying",
				"sleeping" => "woobyHeartSleeping",
				"eating" => "woobyHeartEating",
				"cardBack" => "bollyBackground_woobyheart",
				"style" => "normal",
				"useSoundOfId" => "20005",
				"name" => "Heart Wooby"
			),
			
			"woobyblue" => array(
				"id" => "101962",
				"x" => "600",
				"y" => "35",
				"z" => "5",
				"colour" => "-101962",
				"normal" => "woobyBlueNormal",
				"idle" => "woobyBlueClean",
				"game" => "woobyBluePlaying",
				"denying" => "woobyBlueDenying",
				"sleeping" => "woobyBlueSleeping",
				"eating" => "woobyBlueEating",
				"cardBack" => "WoobyBlueBackground",
				"style" => "normal",
				"useSoundOfId" => "20005",
				"name" => "Blue Wooby"
			),
			
			"woobyorangewood" => array(
				"id" => "100624",
				"x" => "600",
				"y" => "35",
				"z" => "5",
				"colour" => "-100576",
				"normal" => "woobyOrangeNormal",
				"idle" => "woobyOrangeBubbles",
				"game" => "woobyOrangeBubbles",
				"denying" => "woobyOrangeDenying",
				"sleeping" => "woobyOrangeSleeping",
				"eating" => "woobyOrangeEating",
				"cardBack" => "bollyBackground_woobyorange",
				"style" => "normal",
				"useSoundOfId" => "20004",
				"name" => "Orange Wood Wooby"
			),
			
			"woobygreenwood" => array(
				"id" => "100646",
				"x" => "600",
				"y" => "35",
				"z" => "5",
				"colour" => "-100646",
				"normal" => "woobyGreenNormal",
				"idle" => "woobyGreenNormal",
				"game" => "woobyGreenBubbles",
				"denying" => "woobyGreenDenying",
				"sleeping" => "woobyGreenSleeping",
				"eating" => "woobyGreenEating",
				"cardBack" => "bollyBackground_woobygreen",
				"style" => "normal",
				"useSoundOfId" => "20005",
				"name" => "Green Wood Wooby"
			),
			
			"woobyheartwood" => array(
				"id" => "100824",
				"x" => "600",
				"y" => "35",
				"z" => "5",
				"colour" => "-100824",
				"normal" => "woobyHeartNormal",
				"idle" => "woobyHeartNormal",
				"game" => "woobyHeartPlaying",
				"denying" => "woobyHeartDenying",
				"sleeping" => "woobyHeartSleeping",
				"eating" => "woobyHeartEating",
				"cardBack" => "bollyBackground_woobyheart",
				"style" => "normal",
				"useSoundOfId" => "20005",
				"name" => "Heart Wood Wooby"
			),
			
			"woobyeaster" => array(
				"id" => "101862",
				"x" => "600",
				"y" => "35",
				"z" => "5",
				"colour" => "-101862",
				"normal" => "woobyEasterIdle",
				"idle" => "woobyEasterClean",
				"game" => "woobyEasterPlaying",
				"denying" => "woobyEasterDenying",
				"sleeping" => "woobyEasterSleeping",
				"eating" => "woobyEasterEating",
				"cardBack" => "WoobyEasterBackground",
				"style" => "normal",
				"useSoundOfId" => "20005",
				"name" => "Easter Wooby"
			)
		),
		
		"pokopet" => array(
			"turtle" => array(
				"type" => "1",
				"x" => "600",
				"y" => "35",
				"z" => "5",
				"colour" => "-1000",
				"normal" => "turtleIdle",
				"idle" => "turtleIdle",
				"game" => "turtleTrick",
				"denying" => "turtlePissed",
				"sleeping" => "turtleIdle",
				"eating" => "turtleEating",
				"cardBack" => "turtleIdle",
				"style" => "normal",
				"useSoundOfId" => "20005",
				"name" => "Casco",
				"price" => 4500
			),
			
			"paradisebird" => array(
				"type" => "2",
				"x" => "600",
				"y" => "35",
				"z" => "5",
				"colour" => "-1001",
				"normal" => "birdIdle",
				"idle" => "birdIdle",
				"game" => "birdTrick",
				"denying" => "birdPissed",
				"sleeping" => "turtleIdle",
				"eating" => "birdEating",
				"cardBack" => "turtleIdle",
				"style" => "normal",
				"useSoundOfId" => "20005",
				"name" => "Stella",
				"price" => 8000
			),
			
			"fox" => array(
				"type" => "3",
				"x" => "600",
				"y" => "35",
				"z" => "5",
				"colour" => "-1002",
				"normal" => "foxIdle",
				"idle" => "foxIdle",
				"game" => "foxTrick",
				"denying" => "foxPissed",
				"sleeping" => "turtleIdle",
				"eating" => "foxEating",
				"cardBack" => "turtleIdle",
				"style" => "normal",
				"useSoundOfId" => "20005",
				"name" => "Soque",
				"price" => 7500
			),
			
			"hedgehog" => array(
				"type" => "4",
				"x" => "600",
				"y" => "35",
				"z" => "5",
				"colour" => "-1003",
				"normal" => "hedgehogIdle",
				"idle" => "hedgehogIdle",
				"game" => "hedgehogTrick",
				"denying" => "hedgehogPissed",
				"sleeping" => "turtleIdle",
				"eating" => "hedgehogEating",
				"cardBack" => "turtleIdle",
				"style" => "normal",
				"useSoundOfId" => "20005",
				"name" => "Abrazo",
				"price" => 1200
			),
			
			"bluedragon" => array(
				"type" => "5",
				"x" => "600",
				"y" => "35",
				"z" => "5",
				"colour" => "-1004",
				"normal" => "bluedragonIdle",
				"idle" => "bluedragonIdle",
				"game" => "bluedragonTrick",
				"denying" => "bluedragonPissed",
				"sleeping" => "turtleIdle",
				"eating" => "bluedragonEating",
				"cardBack" => "turtleIdle",
				"style" => "normal",
				"useSoundOfId" => "20005",
				"name" => "Woody",
				"price" => "VOUCHER_KK_VERIFICATION_NEEDED_PLEASEEEEE_DONT_KILL_ME"
			),
			
			"bugsy" => array(
				"type" => "6",
				"x" => "600",
				"y" => "35",
				"z" => "5",
				"colour" => "-1005",
				"normal" => "bugsyIdle",
				"idle" => "bugsyIdle",
				"game" => "bugsyTrick",
				"denying" => "bugsyPissed",
				"sleeping" => "bugsyIdle",
				"eating" => "bugsyEating",
				"cardBack" => "bugsyIdle",
				"style" => "normal",
				"useSoundOfId" => "20005",
				"name" => "Bugsy",
				"price" => 60
			),
			
			"tork" => array(
				"type" => "7",
				"x" => "600",
				"y" => "35",
				"z" => "5",
				"colour" => "-1006",
				"normal" => "torkIdle",
				"idle" => "torkIdle",
				"game" => "torkTrick",
				"denying" => "torkPissed",
				"sleeping" => "torkIdle",
				"eating" => "torkEating",
				"cardBack" => "torkIdle",
				"style" => "normal",
				"useSoundOfId" => "20005",
				"name" => "Tork",
				"price" => 5500
			),
			
			"pingoo" => array(
				"type" => "8",
				"x" => "600",
				"y" => "35",
				"z" => "5",
				"colour" => "-1007",
				"normal" => "pingooIdle",
				"idle" => "pingooIdle",
				"game" => "pingooTrick",
				"denying" => "pingooPissed",
				"sleeping" => "pingooIdle",
				"eating" => "pingooEating",
				"cardBack" => "pingooIdle",
				"style" => "normal",
				"useSoundOfId" => "20005",
				"name" => "Pingoo",
				"price" => 7890
			),
			
			"marieta" => array(
				"type" => "9",
				"x" => "600",
				"y" => "35",
				"z" => "5",
				"colour" => "-1008",
				"normal" => "marietaIdle",
				"idle" => "marietaIdle",
				"game" => "marietaTrick",
				"denying" => "marietaPissed",
				"sleeping" => "marietaIdle",
				"eating" => "marietaEating",
				"cardBack" => "marietaIdle",
				"style" => "normal",
				"useSoundOfId" => "20005",
				"name" => "Marieta",
				"price" => 60
			)
		)
	);
	
	function getAbilitiesByString($_txt)
	{
		$arr = explode('|',$_txt);
		$aa_HOLDER = Array();
		
		foreach($arr as $ability){
			$abilityHolder = explode(':',$ability);
			
			if(isset($abilityHolder[0]) && $abilityHolder[0] != '' && isset($abilityHolder[1])){
				
				$mo = new stdClass();
				$mo->$abilityHolder[0] = $abilityHolder[1];
				
				array_push($aa_HOLDER,$mo);
			}
		}
		
		return $aa_HOLDER;
	}
	
	function getPokoProperties($_txt)
	{
		$OK_ANOTHER_HOLDER = new PokoPetPropertiesVO();
		$arr = explode('|',$_txt);
		
		foreach($arr as $ior){
			list($name,$value) = explode(':',$ior);
			
			$OK_ANOTHER_HOLDER->$name = $value;
		}
		
		return $OK_ANOTHER_HOLDER;
	}
	
	function getPetInfoById($id,$type){
		if(isset($this->pets[$type])){
			
			foreach($this->pets[$type] as $pet){
				if($type === "bolly"){
					if($pet['id'] == $id){
						return $pet;
					}
				}elseif($type === 'pokopet'){
					if($pet['type'] == $id){
						return $pet;
					}
				}
			}
			
			return null;
		}
		
		return null;
	}
}