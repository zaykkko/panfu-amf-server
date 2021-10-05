<?php

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Vo/FurnitureDataVO.php';
require_once dirname(__FILE__) . '/Vo/HomeDataVO.php';
require_once dirname(__FILE__) . '/Vo/InventoryVO.php';
require_once dirname(__FILE__) . '/Vo/ItemVO.php';
require_once dirname(__FILE__) . '/Vo/ListVO.php';
require_once dirname(__FILE__) . '/Vo/DateVO.php';
require_once dirname(__FILE__) . '/Vo/BollyVO.php';
require_once dirname(__FILE__) . '/Vo/StateVO.php';
require_once dirname(__FILE__) . '/Vo/ClientResponseVO.php';
require_once dirname(__FILE__) . '/Features/ServerManager.php';
require_once dirname(__FILE__) . '/Features/Reporter.php';
require_once dirname(__FILE__) . '/Vo/AttributesResponseVO.php';
require_once dirname(__FILE__) . '/Vo/ProfileVO.php';
require_once dirname(__FILE__) . '/Vo/PokopetVO.php';
require_once dirname(__FILE__) . '/Vo/SmallPlayerInfoVO.php';
require_once dirname(__FILE__) . '/Vo/PokoPetPropertiesVO.php';

class amfPlayerService {
	private $items = null;
	private $available_item_list = Array(104527,104528,104377,104378,104379,104380,104381,104382,104383,104384,104385,104347,104348,104349,104350,104351,104352,104353,104354,104310,104311,104312,104313,104314,104315,104316,104317,104261,104262,104263,104264,104265,104266,104267,104268,104269,104244,104245,104246,104247,104248,104249,104250,104251,104252,104253,104181,104182,104183,104184,104185,104186,104187,104188,104166,104167,104168,104169,104170,104171,104172,104173,104138,104139,104140,104141,104142,104143,104144,104129,104130,104131,104132,104133,104134,104135,104136,104137,104107,104108,104109,104110,104111,104112,104113,104114,104115,104076,104077,104078,104079,104070,104071,104072,104073,104053,104054,104055,104056,104057,104058,104059,104060,104034,104035,104036,104037,104030,104031,104032,104033,103991,103990,103998,103992,103993,103994,103995,103996,103997,103927,103928,103929,103930,103913,103914,103915,103916,102380,102382,102381,102386,103028,103029,103030,103031,101896,101899,101897,101898,102755,102756,102758,102757,101053,101054,101055,101056,4003,103024,103025,103026,103027,102270,102273,102271,102272,102276,102275,102274,102277,103744,103745,103746,103742,103743,103747,103681,103682,103683,103684,103677,103678,103679,103680,103622,103623,103624,103625,103626,103596,103597,103598,103599,103600,103601,103602,103603,103463,103464,103465,103466,103467,103468,103469,103442,103443,103444,103445,103446,103447,103448,103426,103427,103428,103429,103430,103431,103432,103433,103415,103416,103417,103418,103419,103420,103421,103422,103358,103359,103360,103361,103362,103363,103364,103365,103366,103151,103152,103153,103154,103155,103156,103157,103158,103073,103074,103075,103076,103077,103080,103078,103079,103065,103066,103067,103068,103069,103070,103071,103072,102971,102972,102973,102974,102975,102976,102977,102978,102834,102835,102836,102837,102838,102839,102840,102841,102842,102783,102784,102785,102786,102787,102788,102789,102790,102760,102761,102762,102763,102764,102765,102766,102767,102593,102594,102595,102596,102597,102598,102599,102600,102572,102575,102573,102574,102576,102577,102578,102579,102432,102431,102433,102434,102435,102436,102438,102437,102419,102420,102421,102422,102425,102424,102426,102423,102411,102412,102413,102414,102415,102416,102417,102418,102392,102393,102394,102395,102396,102397,102398,102399,102367,102368,102369,102370,102371,102372,102373,102374,102261,102262,102263,102264,102265,102266,102267,102268,102163,102164,102165,102166,102167,102168,102169,102170,102126,102127,102128,102129,102130,102131,102132,102133,102109,102110,102111,102112,102113,102114,102115,102116,102093,102092,102095,102096,102094,102097,102100,102099,102098,102041,102042,102043,102044,102047,102045,102048,102046,102027,102030,102028,102029,102031,102034,102032,102033,101986,101985,101988,101987,101990,101991,101989,101992,101974,101976,101975,101973,101978,101977,101980,101979,101773,101774,101776,101775,101778,101777,101780,101779,101718,101719,101720,101721,101722,101723,101724,101725,101687,101688,101689,101690,101692,101691,101693,101694,101612,101611,101610,101613,101614,101615,101617,101616,101572,101574,101575,101576,101577,101579,101578,101580,101443,101444,101442,101441,101437,101438,101440,101439,101430,101432,101429,101431,101435,101434,101433,101436,101412,101411,101414,101413,101415,101418,101416,101417,101361,101360,101363,101362,101366,101365,101364,101367,101333,101330,101331,101332,101335,101336,101337,101334,101320,101321,101323,101322,101326,101325,101327,101324,101297,101298,101300,101299,101301,101303,101302,101290,101289,101291,101292,101296,101295,101293,101294,101219,101220,101222,101221,101223,101226,101224,101225,101185,101186,101188,101187,101190,101189,101191,101119,101120,101121,101122,101123,101124,101125,101126,101009,101011,101010,101012,101013,101015,101014,101016,100894,100894,100895,100895,100896,100896,100897,100897,100899,100899,100900,100900,100901,100901,100898,100898,100707,100708,100709,100710,100711,100712,100713,100714,100633,100634,100636,100635,100637,100638,100639,100625,100626,100627,100628,100629,100630,100631,100614,100615,100616,100617,100618,100619,100620,100596,100597,100598,100599,100600,100601,100602,100120,100124,100123,100122,100114,100115,100121,100116,103822,103823,103824,103825,103818,103819,103820,103821,103329,103330,103331,103332,103333,103334,103335,103336,103272,103273,103274,103275,103276,103277,103278,103279,103129,103130,103131,103132,103133,103134,103135,103136,102979,102980,102981,102982,102983,102984,102985,102986,102963,102964,102965,102966,102967,102968,102969,102970,102649,102650,102651,102652,102653,102654,102610,102611,102612,102613,102614,102615,102616,102617,102602,102603,102604,102605,102606,102607,102608,102609,102528,102531,102529,102530,102533,102532,102534,102535,102509,102510,102511,102512,102514,102513,102515,102516,102486,102487,102488,102489,102490,102491,102492,102493,102449,102450,102451,102452,102453,102454,102455,102456,102440,102441,102442,102443,102444,102445,102446,102447,102064,102065,102066,102067,102071,102070,102068,102069,102054,102055,102056,102057,102058,102059,102060,102061,101906,101905,101908,101907,101912,101910,101909,101911,101880,101879,101881,101882,101884,101883,101885,101886,101811,101813,101810,101812,101815,101814,101816,101817,101524,101525,101526,101527,101528,101529,101530,101531,101267,101269,101268,101270,101271,101274,101273,101272,101086,101089,101087,101088,101093,101090,101092,101091,100968,100969,100972,100970,100974,100975,100973,100976,100855,100856,100857,100858,100859,100860,100861,100689,100691,100690,100688,100688,100684,100685,100685,100686,100687,100686,9013,3030,5014,3028,5012,9011,6010,5015,9014,9012,6011,9015,3029,5013,4006,6008,100026,100027,100028,103282,103283,103284,103285,103286,103287,103288,103289,102795,102796,102797,102798,102799,102800,102801,101153,101155,101154,101156,101157,101158,101159,101078,101079,101081,101080,101075,101076,101077,101060,101062,101061,101063,101067,101064,101066,101065,100880,100883,100881,100882,100884,100887,100886,100885,100873,100876,100874,100875,100878,100877,100879,100823,100822,100821,100820,100816,100818,100817,100819,104492,104493,104498,104499,104482,104483,104473,104472,104461,104462,104447,104448,104412,104413,104432,104433,104414,104415,104396,104397,104363,104364,104336,104337,104338,104339,104340,104341,104296,104297,104298,104299,104300,104301,104287,104288,104289,104290,104291,104292,104226,104225,104193,104194,104195,104196,104160,104161,103664,103666,103668,103672,104041,104042,100274,100275,100278,100279,100280,100272,100273,100276,100271,100277,103964,103965,103887,103886,103828,103829,103830,103831,103832,103833,103834,103835,103817,103816,103804,103805,103806,103802,103803,103807,103789,103788,103778,103779,103780,103775,103776,103777,103762,103763,103764,103765,103766,103767,103768,103769,103698,103699,103700,103701,103702,103703,103704,103705,103610,103611,103610,103611,103610,103611,103609,103608,103609,103608,103609,103608,103605,103604,103605,103604,103605,103604,103584,103585,103558,103560,103559,103561,103562,103563,103564,103565,103539,103540,103541,103537,103538,103521,103522,103523,103524,103516,103517,103518,103519,103520,103504,103501,103502,103503,103498,103499,103500,103484,103485,103486,103487,103488,103489,103490,103491,103397,103398,103399,103400,103393,103394,103395,103396,103381,103382,103383,103384,103385,103373,103374,103375,103376,103377,103378,103300,103301,103302,103303,103304,103305,103306,103307,103259,103260,103261,103262,103263,103264,103265,103266,103237,103238,103239,103240,103233,103234,103235,103236,103215,103216,103217,103218,103219,103220,103221,103222,103204,103205,103206,103207,103200,103201,103202,103203,103188,103189,103190,103191,103184,103185,103192,103187,103186,103166,103167,103168,103169,103170,103171,103172,103173,103111,103112,103113,103114,103115,103116,103117,103118,102955,102956,102957,102958,102959,102960,102961,102962,102880,102881,102882,102883,102884,102885,102886,102743,102744,102745,102746,102747,102748,102749,102750,102733,102735,102734,102736,102738,102737,102710,102712,102711,102707,102709,102708,102697,102700,102698,102699,102703,102701,102702,102704,102669,102670,102671,102672,102673,102674,102675,102676,102618,102619,102620,102621,102622,102623,102624,102625,102558,102559,102560,102561,102562,102563,102564,102565,102538,102536,102537,102539,102540,102541,102542,102543,102503,102504,102505,102506,102507,102508,102462,102463,102464,102465,102466,102467,102468,102469,102306,102307,102308,102309,102310,102311,102312,102313,101964,101965,101953,101952,101955,101954,101957,101956,101958,101959,101919,101921,101920,101922,101924,101925,101923,101926,101877,101878,101875,101876,101850,101852,101851,101849,101854,101855,101853,101856,101831,101832,101833,101834,101835,101836,101827,101828,101825,101826,101792,101793,101794,101795,101742,101743,101744,101745,101738,101739,101740,101737,101671,101672,101673,101674,101675,101676,101661,101662,101663,101664,101665,101638,101639,101640,101641,101624,101625,101626,101627,101629,101628,101630,101631,101587,101589,101588,101590,101591,101593,101592,101594,101548,101549,101550,101551,101552,101553,101554,101555,101511,101512,101513,101514,101515,101469,101468,101470,101471,101472,101473,101475,101474,101371,101370,101373,101372,101375,101374,101377,101376,101251,101254,101252,101253,101257,101256,101255,101262,101235,101236,101232,101234,101233,101216,101214,101215,101211,101213,101210,101212,101192,101195,101193,101194,101196,101199,101198,101197,101145,101147,101146,101148,101149,101150,101151,101152,100995,100996,100997,100998,100999,101002,101001,101000,100965,100963,100964,100962,100960,100961,100921,100923,100924,100922,100927,100928,100926,100925,100915,100917,100916,100920,100918,100919,100907,100905,100906,100904,100902,100903,100809,100810,100811,100810,100811,100776,100777,100777,100778,100778,100779,100779,100787,100788,100788,100787,100789,100784,100785,100786,100785,100780,100780,100783,100783,100782,100782,100781,100781,100774,100774,100773,100773,100775,100775,100759,100760,100761,100762,100763,100764,100765,100764,100755,100756,100757,100758,100756,100751,100752,100753,100754,100753,100753,100752,100741,100742,100743,100744,100745,100746,100747,100748,100704,100705,100706,100699,100700,100702,100701,100703,100704,100655,100658,100659,100666,100667,100670,100671,100664,100663,100662,100665,100660,100660,100661,100661,100668,100668,100669,100669,100647,100648,100649,100650,100651,100652,100653,100654,100012,100013,100014,100581,100582,100583,100569,100570,100571,100574,100573,100572,100533,100534,100535,100530,100531,100532,100515,100516,100517,100510,100513,100511,100514,100512,100486,100485,100484,100481,100480,100482,100483,100412,100415,100413,100408,100411,100409,100410,100299,100303,100301,100125,100105,100104,100128,100318,100320,100322,5005,3007,100387,100388,100389,100390,100391,100227,100228,100229,100230,100231,100232,100231,100212,100213,100214,100209,100210,100211,1,4,8,2,100131,100132,100133,100134,100135,100136,100100,100097,100101,100098,100099,100096,100009,100010,100011,100006,100007,100008,4005,3017,9009,1008,1005,1007,1006,1003,1004,1002,1001,100497,100498,100499,100500,104010,104011,104012,104013,104014,104015,103441,102325,102322,103438,103440,104556,104554,104555,104553,104558,104557,104464,100311,100307,100316,100318,100332,100334,100336,100338,100340,100342,100344,100346,100348,100350,101345,101347,101349,101353,101384,101351,101380,101391,101378,101386,101388,101393,101394,101396,101408,103642,103668,103659,103643,103664,103644,103645,103672,103646,103655,103647,103648,103657,103649,103650,102845,102848,102849,102879,102850,102851,102852,102853,102854,102856,102857,102858,102896,102897,102898,102899,102900,102901,102903,102904,104080,104068,104088,104081,104089,104090,104091,104092,104093,104069,104096,104098,104097,104099,104100,104101,104102,104105,104103,104104,104106,104465);
	
	function addToBuddyList($playerId) {
		try {
			if($playerId === $_SESSION['playerId']) {
				$result = new AmfResponse();
				$result->statusCode = 1;
				$result->message = "ATTEMPTED_GAME_MANIPULATION";
				$result->valueObject = null;
				
				try {
					$info = new Server();
					if($info) {
						try {
							$r = $info::sendMessage('<msg t="sys"><body action="suspicious"><security id="report"><ticket><![CDATA[oauth:VfXPfGcMVg64trZRFYXLktTpr2xCZNMnhM3E4cGR7gCAPThr39nEvG3C8]]></ticket><ticketId><![CDATA[oauth:yFZJKzvuZsKJEuRXQ3XQC4wdF2kFtA65PyHYsGPDQ32uyJdLRSFmpg53q]]></tickerId><reportTicket><![CDATA[oauth:LUPUvwXEwPNXg4QUYQwUcCNCAXVCDEYWeqTJmNHa92MxHh2vdxXxrJyMC]]></reportTicket></security><message><reason><![CDATA[Utilización de programas para añadirse a sí mismo en la lista de amigos.]]></reason><user><![CDATA['.$_SESSION['playerId'].']]></user><info function="amfPlayerService.addToBuddyList" arguments="'.$_SESSION['playerId'].'" actioning="KICK"></info></message></body></msg>');
						} catch(Exception $e) {exit;}
					}
				} catch(Exception $e) {}
				
				return $result;
			}
			
			$pdo = $GLOBALS['database']::getConnection();
			
			$stmt = $pdo->prepare("SELECT * FROM buddies WHERE (player_id = ? AND buddy_id = ?) OR (player_id = ? AND buddy_id = ?)");
			$stmt->bindParam(1, $playerId, PDO::PARAM_INT);
			$stmt->bindParam(2, $_SESSION['playerId'], PDO::PARAM_INT);
			$stmt->bindParam(4, $playerId, PDO::PARAM_INT);
			$stmt->bindParam(3, $_SESSION['playerId'], PDO::PARAM_INT);
			$stmt->execute();
			
			if ($stmt->rowCount() == 0) {
				$timestamp = time();
				
				$z = $pdo->prepare("SELECT * FROM `users` WHERE `id` = ? LIMIT 1");
				$z->bindParam(1,$playerId,PDO::PARAM_INT);
				$z->execute();
				$name1 = $z->fetch(PDO::FETCH_ASSOC)["display_name"];
				
				$insert = $pdo->prepare("INSERT INTO buddies (player_id,currentgs,buddy_id,best_friend,username,timestamp) VALUES (?, 0, ?, 0, ?, ?);INSERT INTO buddies (player_id,currentgs,buddy_id,best_friend,username,timestamp) VALUES (?, 0, ?, 0, ?)");
				
				$insert->bindParam(1, $playerId, PDO::PARAM_INT);
				$insert->bindParam(2, $_SESSION['playerId'], PDO::PARAM_INT);
				$insert->bindParam(3, $_SESSION['playerName'], PDO::PARAM_STR);
				$insert->bindParam(4, $timestamp, PDO::PARAM_INT);
				$insert->bindParam(5, $_SESSION['playerId'], PDO::PARAM_INT);
				$insert->bindParam(6, $playerId, PDO::PARAM_INT);
				$insert->bindParam(7, $name1, PDO::PARAM_STR);
				$insert->bindParam(8, $timestamp, PDO::PARAM_INT);
				$insert->execute();
			}
			
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = null;
			
			try {
				$info = new Server();
				if($info) {
					try {
						$r = $info::sendMessage("<msg t='sys'><body action='fsua'><security><ticket><![CDATA[oauth:xGtdpVNc7NdGNnsw4nxDkj4uRJjbcNUZ5Qer38eEmPqdRm7ycYVnycLHQ]]></ticket></security><friendship><accepter><![CDATA[".$_SESSION['playerId']."]]></accepter><sender><![CDATA[".$playerId."]]></sender><value><![CDATA[1]]></value></friendship></body></msg>");
					} catch(Exception $e) {}
				}
			} catch(Exception $e) {}
			
			return $result;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::addToBuddyList\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function reportPlayer($target, $senderIdOrSocket, $reason)
	{
		$result = new AmfResponse();
		$result->statusCode = 0;
		$result->message = "success";
		$result->valueObject = null;
		return $result;
	}
	
	function purchaseItemWood($itemId, $hash) {
		return $this->purchaseItem($itemId,$hash);
	}
	
	function purchaseItem($itemId, $hash) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			$item = $GLOBALS['database']::getItemInfoById($itemId);
			
			if(array_search($itemId,$this->available_item_list) === NULL && !$_SESSION['playerModerator']) {
				$result = new AmfResponse();
				$result->statusCode = 1;
				$result->message = "ATTEMPTED_GAME_MANIPULATION";
				$result->valueObject = null;
				
				try {
					$info = new Server();
					if($info) {
						try {
							$r = $info::sendMessage('<msg t="sys"><body action="suspicious"><security id="report"><ticket><![CDATA[oauth:VfXPfGcMVg64trZRFYXLktTpr2xCZNMnhM3E4cGR7gCAPThr39nEvG3C8]]></ticket><ticketId><![CDATA[oauth:yFZJKzvuZsKJEuRXQ3XQC4wdF2kFtA65PyHYsGPDQ32uyJdLRSFmpg53q]]></tickerId><reportTicket><![CDATA[oauth:LUPUvwXEwPNXg4QUYQwUcCNCAXVCDEYWeqTJmNHa92MxHh2vdxXxrJyMC]]></reportTicket></security><message><reason><![CDATA[Utilización de programas para añadir items no autorizados.]]></reason><user><![CDATA['.$_SESSION['playerId'].']]></user><info function="amfPlayerService.purchaseItem" arguments="'.$itemId.','.$hash.'" actioning="KICK"></info></message></body></msg>');
						} catch(Exception $e) {exit;}
					}
				} catch(Exception $e) {}
				
				return $result;
			}
			
			if($item['hash'] != "") {
				if(!isset($hash) || $item['hash'] != $hash && !$_SESSION['playerModerator']) {
					$result = new AmfResponse();
					$result->statusCode = 5;
					$result->message = "Item hash error.";
					$result->valueObject = null;
					return $result;
				}
			}
			
			if ($item != null) {
				
				if(boolval($item['premium']) && !$_SESSION['playerPremium']){
					$result = new AmfResponse();
					$result->statusCode = 5;
					$result->message = "No premium";
					$result->valueObject = null;
					return $result;
				}
				
				$checkStmt = $pdo->prepare("SELECT * FROM inventory WHERE player_id = ? and item_id = ?");
				$checkStmt->bindParam(1, $_SESSION['playerId'], PDO::PARAM_INT);
				$checkStmt->bindParam(2, $itemId, PDO::PARAM_INT);
				$checkStmt->execute();
				
				if ($checkStmt->rowCount() == 0) {
					$playerStmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
					$playerStmt->bindParam(1, $_SESSION['playerId'], PDO::PARAM_INT);
					$playerStmt->execute();
					$player = $playerStmt->fetch(PDO::FETCH_ASSOC);
					
					if ($player['coins'] >= $item['price']) {
						$timestamp = time();
						
						switch($item['type']){
							case 0:
							case 13:
							case 14:
							case 17:
								$itemType = "FURNITURE";
								break;
							default:
								$itemType = "CLOTHES";
						}
						
						$update = $pdo->prepare("UPDATE users SET coins = coins - :amo WHERE id = :id");
						$update->bindParam(":amo", $item['price'], PDO::PARAM_INT);
						$update->bindParam(":id", $_SESSION['playerId'], PDO::PARAM_INT);
						if($update->execute()) {
						
							$insert = $pdo->prepare("INSERT INTO inventory (player_id, item_id, timestamp, bought, active, parameters, x, y, rot, ele_type) VALUES (:pid, :id, :time, '1', '0', '', '0', '0', '0', :tpo)");
							$insert->bindParam(":pid", $_SESSION['playerId'], PDO::PARAM_INT);
							$insert->bindParam("id", $itemId, PDO::PARAM_INT);
							$insert->bindParam(":time", $timestamp, PDO::PARAM_INT);
							$insert->bindParam(":tpo", $itemType, PDO::PARAM_STR);
							if($insert->execute()){
							
								$tItem = new ItemVO();
								$tItem->id = $item['id'];
								$tItem->name = $item['name'];
								$tItem->type = $item['type'];
								$tItem->price = $item['price'];
								$tItem->zettSort = $item['zett_sort'];
								$tItem->premium = boolval($item['premium']);
								$tItem->bought = true;
								$tItem->active = false;
								$tItem->movementType = $item['movement_type'];
								
								$result = new AmfResponse();
								$result->statusCode = 0;
								$result->message = "success";
								$result->valueObject = $tItem;
								
								if($itemType === 'FURNITURE') {
									try {
										$info = new Server();
										if($info) {
											try {
												$r = $info::sendMessage("<msg t='sys'><body action='furniturebought'><security><ticket><![CDATA[oauth:7zcH2PFcKmq2aWXuKbu4mS8tFwYaDDwYFVbmUmDgnDMCBdGpfset34eM8]]></ticket></security><user><![CDATA[".$_SESSION['playerId']."]]></user></body></msg>");
											} catch(Exception $e) {exit;}
										}
									} catch(Exception $e) {}
								}
								
								return $result;
							}else{
								$result = new AmfResponse();
								$result->statusCode = 6;
								$result->message = "Inventory error - ".$insert->errorInfo();
								$result->valueObject = null;
								return $result;
							}
						}else{
							$result = new AmfResponse();
							$result->statusCode = 6;
							$result->message = "Update coins error";
							$result->valueObject = null;
							return $result;
						}
					}
					
					$result = new AmfResponse();
					$result->statusCode = 6;
					$result->message = "Not enough coins";
					$result->valueObject = null;
					return $result;
				}else{
					$result = new AmfResponse();
					$result->statusCode = 2;
					$result->message = "Already has item";
					$result->valueObject = null;
					return $result;
				}
			}
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::purchaseItem\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function getPanfuName($amfLink)
	{
		$link = explode('://',$amfLink);
		$link2 = $link[1];
		$link3 = explode('/',$link2);
		$link4 = $link3[0];
		$link5=explode('.',$link4);
		$link6=count($link5)>1?$link4[count($link5)-1].$link4[count($link5)-2]:$link5;
		return $link4;
	}
	
	function setAndReturn($lang)
	{
		if(isset($_COOKIE['clientLastLang']))
		{
			if(!$_COOKIE['clientLastLang'] === $lang)
			{
				if(isset($_COOKIE['clientLastLang']))
				{
					unset($_COOKIE['clientLastLang']);
				}
				setCookie("clientLastLang",$lang);
			}
		}
		else
		{
			setCookie("clientLastLang",$lang);
		}
		return $lang;
	}
	
	function getToken($rNum, $sessionKey, $loginCount, $amfLink, $userId)
	{
		$name = $this->getPanfuName($amfLink);
		$newid = $userId / 2;
		$la = $rNum * rand(0,5);
		$le = $la + $sessionKey / rand(2,10);
		if($le > 99999999)
		{
			$le=($le-$rNum)-($sessionKey-193875)+1;
		}
		$li = $le + $loginCount * 2;
		$lo = strlen($name) + rand(0,5);
		$lu = ($li + $lo / 2) + $userId * rand(1,50);
		$map=round($lu + $newid);
		if($map < 0 || strpos($map,"-") !== false)
		{
			return round(str_replace("-","",$map));
		}
		return round($map);
	}
	
	function parseInfo($value, $r)
	{
		$info = explode("...",$value);
		$i = 0;
		$object = new stdClass();
		$object->AchievementPoints = $info[$i];
		$i++;
		$object->DaysOnPPS = $info[$i];
		$i++;
		$object->ChatLocked = $info[$i];
		$i++;
		$object->ChatAllowedOptIn = $info[$i];
		$i++;
		$object->SocialLevel = $info[$i];
		$i++;
		$object->Transformation = $info[$i];
		$i++;
		$object->PokoCount = $info[$i];
		$i++;
		$object->BollyCount = $info[$i];
		$i++;
		$object->Premium = $info[$i];
		$i++;
		$object->PokoAId = $info[$i];
		$i++;
		$object->TourGuide = $info[$i];
		$i++;
		$object->NewUser = $info[$i];
		$object->UserRKey = $r;
		return $object;
	}
	
	function getBirthday($value)
	{
		if(strrpos($value,'date:') === false){
			return 100000000;
		}
		$kk = explode("date: ",$value);
		$ko = $kk[1];
		$ka = str_replace("]","",$ko);
		return $ka;
	}

	function user_ip()
	{
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if(filter_var($client, FILTER_VALIDATE_IP))
		{
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP))
		{
			$ip = $forward;
		}
		else
		{
			$ip = $remote;
		}

		return $ip;
	}

	function initClientUser($info, $specialKey)
	{
		try{
			$info3 = explode("|",$info);
				$pdo = $GLOBALS['database']::getConnection();
				$info2 = explode("%",$info);
				$username = $info2[5];
				$alreadyadded = $pdo->prepare("SELECT * FROM maaboiiis WHERE Player_ID = :id and PPS_ServerName = :sname limit 1");
				$alreadyadded->bindValue(":id",$info2[0]);
				#$alreadyadded->bindValue(":name",$info2[1]);
				$alreadyadded->bindValue(":sname",$this->getPanfuName($info2[3]));
				$alreadyadded->execute();
				$token = $this->getToken(rand(1,10),$info2[2],$info2[5],$info2[3],$info2[0]);
				if($alreadyadded->rowCount() > 0)
				{
					$clientCount = $alreadyadded->fetch(PDO::FETCH_ASSOC);
					if($clientCount['User_regerIP'] === '127.0.0.1')
					{
						$cake = $pdo->prepare("UPDATE maaboiiis SET User_regerIP = :ip WHERE Player_ID = :id and PPS_ServerName = :name");
						$cake->bindValue(":ip",$this->user_ip());
						$cake->bindValue(":id",$info2[0]);
						$cake->bindValue(":name",$this->getPanfuName($info2[3]));
						$cake->execute();
					}
					$clientCount = $clientCount['Client_UserLoginCount'];
					$clientCount++;
					$updater = $pdo->prepare("UPDATE maaboiiis SET PPS_UserLoginCount = :newCount, Client_UserLoginCount = :newCCount, Panfu_SessionKey = :newKey, User_LastLangPlayed = :newRecord, Client_Token = :userToken, Player_Info = :info WHERE Player_ID = :id and PPS_ServerName = :serverName");
					$updater->bindValue(":newCount",$info2[5]);
					$updater->bindValue(":newCCount",$clientCount);
					$updater->bindValue(":newKey",$info2[2]);
					$updater->bindValue(":newRecord",$this->setAndReturn($info2[7]));
					$updater->bindValue(":userToken",$token);
					$updater->bindValue(":id",$info2[0]);
					$updater->bindValue(":info",json_encode($this->parseInfo($info2[9],$specialKey)));
					$updater->bindValue(":serverName",$this->getPanfuName($info2[3]));
					if($updater->execute()){
						$e = true;
					}
					else{
						$e = false;
					}
				}
				else
				{
					$inform = $pdo->prepare("INSERT INTO maaboiiis (Client_ID,Player_ID,Player_Name,Panfu_SessionKey,PPS_ServerName,PPS_Info,PPS_UserLoginCount,Client_UserLoginCount,Player_Clothes,PPS_TeamInfo,User_LastLangPlayed,User_Birthday,home_locked,Player_Info,Client_Token,User_regerIP,Attributes) VALUES (NULL,:userId,:userName,:userKey,:panfuName,:panfuInfo,:userLoginCount,0,NULL,:userTeamInfo,:userLang,:cumple,0,:info,:userClientToken,:ip,:da)");
					$atta = "false#$%0x000000#$%0x000000#$%false#$%0000000#$%false#$%NO#$%0x000000#$%0x000000#$%HirukoFont#$%false#$%PandaGay=true";
					$inform->bindValue(":userId",$info2[0]);
					$inform->bindValue(":userName",$info2[1]);
					$inform->bindValue(":userKey",$info2[2]);
					$inform->bindValue(":panfuName",$this->getPanfuName($info2[3]));
					$inform->bindValue(":panfuInfo",$info2[4]);
					$inform->bindValue(":userLoginCount",$info2[5]);
					if(strpos($info2[6],"}") === false || strpos($info2[6],"{") === false || strpos($info2[6],"Sheriff") === false || strpos($info2[6],"Demon") === false)
					{
						header($_SERVER["SERVER_PROTOCOL"].' 500 Internal Server Error');
						exit;
					}
					$inform->bindValue(":userTeamInfo",$info2[6]);
					$inform->bindValue(":userLang",$this->setAndReturn($info2[7]));
					$inform->bindValue(":cumple",$this->getBirthday($info2[8]));
					$inform->bindValue(":userClientToken",$token);
					$inform->bindValue(":da",$atta);
					if(strpos($info2[9],"...") === false || strpos(strtolower($info2[9]),"fuck") != false || strpos(strtolower($info2[9]),"dick") != false || strpos(strtolower($info2[9]),"suck") != false || strpos(strtolower($info2[9]),"shit") != false || strpos(strtolower($info2[9]),"ass") != false || strpos(strtolower($info2[9]),"jerk") != false || strpos(strtolower($info2[9]),"bitch") != false)
					{
						header($_SERVER["SERVER_PROTOCOL"].' 500 Internal Server Error');
						exit;
					}
					$arr = explode($info2[9],"...");
					/**
					if(count($arr) <= 11)
					{
						header($_SERVER["SERVER_PROTOCOL"].' 500 Internal Server Error');
						exit;
					}
					**/
					$inform->bindValue(":info",json_encode($this->parseInfo($info2[9],$specialKey)));
					$inform->bindValue(":ip",$this->user_ip());
					if($inform->execute()){
						$e = true;
					}
					else{
						$e = false;
					}
				}
				
				$result2 = new ClientResponse();
				$result2->heyheyhey = new stdClass();
				$result2->heyheyhey->data1 = "<font size=\"18\"><font color=\"#FF0000\">Login</font> » <font color=\"#00FF00\">Verify</font> » <font color=\"#0000FF\">Result</font></font><br/><br/>";
				$result2->heyheyhey->type = "MoN";
				$result2->heyheyhey->data2 = "¡Bienvenido de nuevo, $\$name$$!<br/><br/>Si sabes de comandos de Panfu, ejemplo: \"|50;blababla\", puedes escribirlos tranquílamente en el chat, no serán suspendidos por razones obvias y <font color=\"#FF0000\">MENOS</font> serán <font color=\"#FF0000\">EXPUESTOS</font> al historial & burbuja de chat.<br/><br/>* Ciertos comandos fueron <font color=\"#FF0000\">EXCLUIDOS</font> del juego debido a razones evidentes: abuso constante que está asolando la jugabilidad.<br/><br/>-<font color=\"#FF0000\">C </font><font color=\"#00FF00\">yaa</font> <font color=\"#0000FF\">boiii</font>";
				$result2->userAuthToken = $token + 1;
				$result2->userId = $info2[0];
				$result2->userLastLangPlayed = $info2[7];
				$result2->clientModerator = $this->isModerator($info2[0]);
				
				#$result2->totalTimer = floor(microtime(true) * 1000);
				$result2->clientVersion = 1.5;
				$result2->errorTxt = "TOTAL LOCAL ERRORS:".rand(0,2);
				$result2->handShake = "Oh wtf mate";
				#$result2->initTime = date("d.m.Y H:i:s");
				
				$aa = floor(microtime(true) * 1000);
				$hash = crypt($result2->userAuthToken."k9s".$aa,md5($aa));
				$salt = uniqid(mt_rand(), true);
				$_SESSION['CODE'] = crypt($salt,"SHA9").$salt.$hash;
				$result = new AmfResponse();
				$result->statusCode = $e === false?13:0;
				$result->message = $e === false?"unexpected error: " + $inform->errorInfo():"success";
				$result->valueObject = $result2;
				header("Accept-Ranges:bytes",true);
				return $result;
		} catch(PDOException $e) {
			header($_SERVER["SERVER_PROTOCOL"].' 500 Internal Server Error');
			exit;
		}
	}
	
	function isModerator($id)
	{
		if(strrpos($id,$this->ids) !== false){
			return true;
		}else{
			return false;
		}
	}
	
	function setState($categoryId, $nameId, $value) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			$stmo = $pdo->prepare("SELECT `premium` FROM `users` WHERE `id` = :id LIMIT 1");
			$stmo->bindParam(':id',$_SESSION['playerId'],PDO::PARAM_INT);
			$stmo->execute();
			$stmo = $stmo->fetch(PDO::FETCH_ASSOC);
			
			$timestamp = floor(microtime(true) * 1000);
			
			if($categoryId === 10011.0 || $categoryId === 10011) {
				if(!boolval($stmo['premium'])) {
					$result = new AmfResponse();
					$result->statusCode = 1;
					$result->message = "failed";
					$result->valueObject = null;
					return $result;
				}
			}
			
			if($categoryId === 143 && ($nameId == "0" || $nameId == 0) && $value === 35) {
				$stmt2 = $pdo->prepare("SELECT * FROM states WHERE player_id = ? AND category_id = 143 AND name_id = 1");
				$stmt2->bindParam(1, $_SESSION['playerId'], PDO::PARAM_INT);
				$stmt2->execute();
				$value_ = (int) $stmt2->fetch(PDO::FETCH_ASSOC)['value'];
				if($value_ < 23) {
					$stmt2->closeCursor();
					$stmt2 = $pdo->prepare("UPDATE `states` SET `value` = `value` + 1, `last_changed` = ? WHERE `player_id` = ? AND `category_id` = 143 AND `name_id` = 1");
					$stmt2->bindParam(1,$timestamp, PDO::PARAM_INT);
					$stmt2->bindParam(2,$_SESSION['playerId'], PDO::PARAM_INT);
					$stmt2->execute();
				}
			}
			
			$stmt = $pdo->prepare("SELECT * FROM states WHERE player_id = ? AND category_id = ? AND name_id = ?");
			$stmt->bindParam(1, $_SESSION['playerId'], PDO::PARAM_INT);
			$stmt->bindParam(2, $categoryId, PDO::PARAM_INT);
			$stmt->bindParam(3, $nameId, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($stmt->rowCount() > 0) {
				$update = $pdo->prepare("UPDATE states SET value = ?, last_changed = ? WHERE player_id = ? and category_id = ? and name_id = ?");
				$update->bindParam(1, $value, PDO::PARAM_INT);
				$update->bindParam(2, $timestamp, PDO::PARAM_INT);
				$update->bindParam(3, $_SESSION['playerId'], PDO::PARAM_INT);
				$update->bindParam(4, $categoryId, PDO::PARAM_INT);
				$update->bindParam(5, $nameId, PDO::PARAM_INT);
				$update->execute();
			} else {
				$insert = $pdo->prepare("INSERT INTO states VALUES (?, ?, ?, ?, ?)");
				$insert->bindParam(1, $_SESSION['playerId'], PDO::PARAM_INT);
				$insert->bindParam(2, $categoryId, PDO::PARAM_INT);
				$insert->bindParam(3, $nameId, PDO::PARAM_INT);
				$insert->bindParam(4, $value, PDO::PARAM_INT);
				$insert->bindParam(5, $timestamp, PDO::PARAM_INT);
				$insert->execute();
			}
			
			$tState = new StateVO();
			$tState->playerId = (int) $_SESSION['playerId'];
			$tState->cathegoryId = (int) $categoryId;
			$tState->nameId = (int) $nameId;
			$tState->stateValue = (int) $value;
			$tState->lastChanged = (int) $timestamp;
			
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = $tState;
			return $result;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::setState\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function getStates($target = array(), $states = array(), $tId = 0, $tOk = 0, $cat = 0) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			if(!is_numeric($target)){
				$targets = $_SESSION['playerId'];
			} else {
				$targets = $target;
			}
			
			$stmt = $pdo->prepare("SELECT * FROM states WHERE player_id = ?");
			$stmt->bindParam(1, $targets, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($stmt->rowCount() > 0) {
				$storage = array();
				
				if($targets !== $_SESSION['playerId']){
					$ok = $stmt->fetchAll();
					
					foreach($ok as $state) {
							$tState = new StateVO();
							$tState->playerId = (int) $state['player_id'];
							$tState->cathegoryId = (int) $state['category_id'];
							$tState->nameId = (int) $state['name_id'];
							$tState->stateValue = (int) $state['value'];
							$tState->lastChanged = (int) $state['last_changed'];
								
							array_push($storage, $tState);
					}
					
				}else{
			
					foreach($stmt as $state) {
						if (in_array($state['category_id'], $target)) {
							$tState = new StateVO();
							$tState->playerId = (int) $state['player_id'];
							$tState->cathegoryId = (int) $state['category_id'];
							$tState->nameId = (int) $state['name_id'];
							$tState->stateValue = (int) $state['value'];
							$tState->lastChanged = (int)$state['last_changed'];
							
							array_push($storage, $tState);
						}
					}
				}
				
				$tList = new ListVO();
				$tList->list = $storage;
				
				$result = new AmfResponse();
				$result->statusCode = 0;
				$result->message = "success";
				$result->valueObject = $tList;
				return $result;
			}
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::getStates\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function getPlayerCard($playerId) {
		try{
			if($playerId == 1010) {
				$playerInfo = self::botInfo();
			} else {
				$playerInfo = $GLOBALS['database']::getPlayerInfo($playerId);
			}
			
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = $playerInfo;
			return $result;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::getPlayerCard\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function getPlayerHome($playerId) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			$stmt = $pdo->prepare("SELECT DISTINCT * FROM users WHERE id = :id LIMIT 1");
			$stmt->bindParam(':id', $playerId, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($stmt->rowCount() > 0) {
				$player = $stmt->fetch(PDO::FETCH_ASSOC);
				
				$inventoryStmt = $pdo->prepare("SELECT DISTINCT * FROM inventory WHERE player_id = :id");
				$inventoryStmt->bindParam(':id', $playerId, PDO::PARAM_INT);
				$inventoryStmt->execute();
				
				$inventory = $inventoryStmt->fetchAll();
				
				$storage = array();
				
				$withoutHouse = false;
				
				foreach($inventory as $invItem) {
					$item = $GLOBALS['database']::getItemInfoById($invItem["item_id"]);
					
					if($item != null) {
						switch($item["type"]) {
							case 0:
							case 13:
							case 14:
							case 17:
								if(boolval($item['premium']) && $_SESSION['playerPremium']) {
									$tFurniture = new FurnitureDataVO();
									$tFurniture->id = $invItem["item_id"];
									$tFurniture->uid = (int) $invItem["player_id"];
									$tFurniture->rot = (int) $invItem["rot"];
									$tFurniture->x = (int) $invItem["x"];
									$tFurniture->y = (int) $invItem["y"];
									$tFurniture->type = $item["type"];
									$tFurniture->parameters = $invItem["parameters"];
									$tFurniture->premium = boolval($item["premium"]);
									$tFurniture->bought = false;
									$tFurniture->active = boolval($invItem["active"]);
								} elseif(boolval($item['premium']) && !$_SESSION['playerPremium']) {
									if($item['type'] === '00') {
										$withoutHouse = true;
									}
									
									$tFurniture = new FurnitureDataVO();
									$tFurniture->id = $invItem["item_id"];
									$tFurniture->uid = (int) $invItem["player_id"];
									$tFurniture->rot = 0;
									$tFurniture->x = 0;
									$tFurniture->y = 0;
									$tFurniture->type = $item["type"];
									$tFurniture->parameters = $invItem["parameters"];
									$tFurniture->premium = boolval($item["premium"]);
									$tFurniture->bought = false;
									$tFurniture->active = 0;
								} else {
									$tFurniture = new FurnitureDataVO();
									$tFurniture->id = $invItem["item_id"];
									$tFurniture->uid = (int) $invItem["player_id"];
									$tFurniture->rot = (int) $invItem["rot"];
									$tFurniture->x = (int) $invItem["x"];
									$tFurniture->y = (int) $invItem["y"];
									$tFurniture->type = $item["type"];
									$tFurniture->parameters = $invItem["parameters"];
									$tFurniture->premium = boolval($item["premium"]);
									$tFurniture->bought = false;
									$tFurniture->active = boolval($invItem["active"]);
								}
								
								array_push($storage, $tFurniture);
						}
					}
				}
				
				if($withoutHouse) {
					foreach($tFurniture as $furni) {
						if($furni->type === '00' && $furni->id === '100') {
							$furni->active = boolval(1);
							break;
						}
					}
				}
				
				$tHomeData = new HomeDataVO();
				$tHomeData->id = 0;
				$tHomeData->playerID = $playerId;
				$tHomeData->locked = boolval($player["home_locked"]);
				$tHomeData->furnitureList = $storage;
				$tHomeData->trackList = null;
				$tHomeData->pets = null;
				
				$mod = $pdo->prepare("SELECT DISTINCT * FROM pets WHERE pet_type = 'pokopet' AND owner_id = ?");
				$mod->bindParam(1,$playerId,PDO::PARAM_INT);
				$mod->execute();
				
				$helper = new PetContext();
				
				$pokopets = Array();
								
				if($mod->rowCount() > 0){
					$fofo = $mod->fetchAll();

					foreach($fofo as $info){
						$pokoHolder = new PokoPetVO();
						
						$pokoHolder->id = (int) $info['pet_id'];
						$pokoHolder->name = $info['pet_name'];
						$pokoHolder->x = (int) $info['x'];
						$pokoHolder->y = (int) $info['y'];
						$pokoHolder->selected = $info['selected'];
						$pokoHolder->status = $info['status'];
						$pokoHolder->abilities = $helper->getAbilitiesByString($info['abilities']);
						$pokoHolder->activity = $info['activity'];
						$pokoHolder->type = (int) $info['pet_id'];
						$pokoHolder->state = $info['state'];
						$pokoHolder->percentToNextLevel = $info['percentToNextLevel'];
						$pokoHolder->lastFed = new DateVO();
						$pokoHolder->lastFed->date = (int) $info['lastFed'];
						$pokoHolder->properties = $helper->getPokoProperties($info['properties']);
						$pokoHolder->z = (int) $info['z'];
						
						array_push($pokopets,$pokoHolder);
					}
				}
				$tHomeData->pokoPets = $pokopets;
				
				$mod->closeCursor();
				
				$mod = $pdo->prepare("SELECT * FROM pets WHERE pet_type = 'bolly' AND owner_id = ?");
				$mod->bindParam(1,$playerId,PDO::PARAM_INT);
				$mod->execute();
				
				$bollies2 = Array();
				
				if($mod->rowCount() > 0){
					$bollies = $mod->fetchAll();
					
					foreach($bollies as $bolly){
						$bolly2 = new BollyVO();
						$bolly2->id = (int) $bolly['pet_id'];
						$bolly2->name = $bolly['pet_name'];
						$bolly2->type = (int) $bolly['colour'];
						$bolly2->price = (int) $bolly['pet_price'];
						$bolly2->state = $bolly['state'];
						$bolly2->activity = $bolly['activity'];
						$bolly2->health = $bolly['health'];
						$bolly2->rest = (int) $bolly['rest'];
						$bolly2->energy = (int) $bolly['energy'];
						$bolly2->rescueTime = (int) $bolly['rescueTime'];
						$bolly2->x = (int) $bolly['x'];
						$bolly2->y = (int) $bolly['y'];
						$bolly2->z = (int) $bolly['z'];
						$bolly2->colour = 1337;
						$bolly2->style = $bolly['style'];
						
						array_push($bollies2,$bolly2);
					}
				}
				$tHomeData->bollies = $bollies2;
				
				$result = new AmfResponse();
				$result->statusCode = 0;
				$result->message = "success";
				$result->valueObject = $tHomeData;
				return $result;
			}
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::getPlayerHome\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}

	
	function updateScore($amount)
	{
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			if($amount >= $_SESSION['playerCoins']) {
				$added = $amount - $_SESSION['playerCoins'];
				
				if($added < 99999 && $amount < 9999999) {
					$_SESSION['playerCoins'] = $amount;
					
					$money = $pdo->prepare("UPDATE users SET coins = :amount WHERE id = :id");
					
					$money->bindValue(":amount",$amount);
					$money->bindValue(":id",$_SESSION['playerId']);
					$money->execute();
					
					$mo = new AmfResponse();
					$mo->statusCode = 0;
					$mo->message = "success";
					$mo->valueObject = null;
				} else {
					$money = null;
					
					$mo = new AmfResponse();
					$mo->statusCode = 1;
					$mo->message = "ATTEMPTED_GAME_MANIPULATION";
					$mo->valueObject = null;

					$report = new AMFReport('Intento de manipulación, monedas.',$_SESSION['playerId'],'amfPlayerService.updateScore',$amount,'WARN');
					$report->sendReport();
				}
			} else {
				$money = $pdo->prepare("UPDATE users SET coins = :amount WHERE id = :id");
					
				$money->bindValue(":amount",$amount);
				$money->bindValue(":id",$_SESSION['playerId']);
				$money->execute();
				
				$mo = new AmfResponse();
				$mo->statusCode = 0;
				$mo->message = "success";
				$mo->valueObject = null;
			}
			
			return $mo;
			
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::getPlayerHome\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function updateFurnitures($data = array())
	{
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			foreach($data as $item) {
				$lazy = json_decode(json_encode($item), true);
				$info = $GLOBALS['database']::getItemInfoById($lazy['id']);
				if($_SESSION['playerPremium'] && boolval($info['premium'])) {
					$temp = $pdo->prepare("SELECT * FROM inventory WHERE player_id = :id AND item_id = :item LIMIT 1");
					$temp->bindValue(":id",$_SESSION['playerId']);
					$temp->bindValue(":item",$lazy['id']);
					$temp->execute();
					
					if($temp->rowCount() > 0){
						$info = $temp->fetch(PDO::FETCH_ASSOC);
						
						$kkk = $pdo->prepare("UPDATE inventory SET active = ?, x = ?, y = ?, rot = ?, parameters = 'locked=false' WHERE player_id = ? AND item_id = ?");
						$a = $lazy['active'] === true?1:0;
						$kkk->bindValue(1,$a);
						$kkk->bindValue(2,$lazy['x']);
						$kkk->bindValue(3,$lazy['y']);
						$kkk->bindValue(4,$lazy['rot']);
						$kkk->bindValue(5,$lazy['uid']);
						$kkk->bindValue(6,$lazy['id']);
						$kkk->execute();
					}
				} elseif(!boolval($info['premium'])) {
					$lazy = json_decode(json_encode($item), true);
					$temp = $pdo->prepare("SELECT * FROM inventory WHERE player_id = :id AND item_id = :item LIMIT 1");
					$temp->bindValue(":id",$_SESSION['playerId']);
					$temp->bindValue(":item",$lazy['id']);
					$temp->execute();
					
					if($temp->rowCount() > 0){
						$info = $temp->fetch(PDO::FETCH_ASSOC);
						
						$kkk = $pdo->prepare("UPDATE inventory SET active = ?, x = ?, y = ?, rot = ?, parameters = 'locked=false' WHERE player_id = ? AND item_id = ?");
						$a = $lazy['active'] === true?1:0;
						$kkk->bindValue(1,$a);
						$kkk->bindValue(2,$lazy['x']);
						$kkk->bindValue(3,$lazy['y']);
						$kkk->bindValue(4,$lazy['rot']);
						$kkk->bindValue(5,$lazy['uid']);
						$kkk->bindValue(6,$lazy['id']);
						$kkk->execute();
					}
				}
			}
				
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = null;
			return $result;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::getPlayerInfoList\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	static function botInfo() {
		$activeInventory = $GLOBALS['database']::getPlayerInventory(1010);
		$inactiveInventory = array();
		$buddies = null;
				
		$tPlayerInfo = new PlayerInfoVO();
		$tPlayerInfo->id = 1010;
		$tPlayerInfo->name = '~ Lady Bot ~';
		$tPlayerInfo->sex = 'girl';
		$tPlayerInfo->birthday = null;
		$tPlayerInfo->coins = 999999;
		$tPlayerInfo->chatId = 2;
		$tPlayerInfo->isPremium = boolval(2);
		$tPlayerInfo->currentGameServer = 1;
		$tPlayerInfo->socialLevel = 66;
		$tPlayerInfo->socialScore = rand(0,79);
		$tPlayerInfo->lastLogin = time();
		$tPlayerInfo->signupDate = 1320043234;
		$tPlayerInfo->daysOnPanfu = floor((time() - 1320043234) / (60*60*24));
		$tPlayerInfo->helperStatus = boolval(1);
		$tPlayerInfo->isSheriff = boolval(3);
		$tPlayerInfo->isTourFinished = boolval(1);
		$tPlayerInfo->state = 'babyTransformation';
		$tPlayerInfo->membershipStatus = 2;
		$tPlayerInfo->activeInventory = $activeInventory;
		$tPlayerInfo->inactiveInventory = $inactiveInventory;
		$tPlayerInfo->buddies = $buddies;
		$tPlayerInfo->blocked = null;
		$tPlayerInfo->bollies = Array();
		$tPlayerInfo->pokoPets = Array();
		$tPlayerInfo->attributes = "{dda23bda-bf35-688b-1a75-fce71eb81198}";
				
		return $tPlayerInfo;
	}
	
	function getPlayerInfoList($players = array(), $detailed) {
		try {
			$storage = array();
			
			foreach($players as $player) {
				if($player === '1010') {
					$playerInfo =  self::botInfo();
				} else {
					$playerInfo = $GLOBALS['database']::getPlayerInfo($player);
				}
				array_push($storage, $playerInfo);
			}
			
			$tList = new ListVO();
			$tList->list = $storage;
				
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = $tList;
			return $result;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::getPlayerInfoList\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function lockHome($locked) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			$stmt = $pdo->prepare("UPDATE users SET home_locked = ? WHERE id = ?");
			$stmt->bindParam(1, $locked, PDO::PARAM_INT);
			$stmt->bindParam(2, $_SESSION['playerId'], PDO::PARAM_INT);
			$stmt->execute();
			
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = null;
			return $result;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::lockHome\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function removeFromBuddyList($playerId) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			$delete = $pdo->prepare("DELETE FROM buddies WHERE (player_id,buddy_id) IN ((?,?),(?,?))");
			$delete->bindParam(1,$playerId,PDO::PARAM_INT);
			$delete->bindParam(2,$_SESSION['playerId'],PDO::PARAM_INT);
			$delete->bindParam(4,$_SESSION['playerId'],PDO::PARAM_INT);
			$delete->bindParam(3,$playerId,PDO::PARAM_INT);
			$delete->execute();
			
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = null;
			
			try {
				$info = new Server();
				if($info) {
					try {
						$r = $info::sendMessage("<msg t='sys'><body action='fsua'><security><ticket><![CDATA[oauth:xGtdpVNc7NdGNnsw4nxDkj4uRJjbcNUZ5Qer38eEmPqdRm7ycYVnycLHQ]]></ticket></security><friendship><accepter><![CDATA[".$_SESSION['playerId']."]]></accepter><sender><![CDATA[".$playerId."]]></sender><value><![CDATA[0]]></value></friendship></body></msg>");
					} catch(Exception $e) {
						echo $e;
					}
				}
			} catch(Exception $e) {
				echo $e;
			}
			
			return $result;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::removeFromBuddyList\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function removeItems($items = array()) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			foreach($items as $item) {
				$delete = $pdo->prepare("delete FROM inventory WHERE player_id = ? and item_id = ?");
				$delete->bindParam(1, $_SESSION['playerId'], PDO::PARAM_INT);
				$delete->bindParam(2, $item, PDO::PARAM_INT);
				$delete->execute();
			}
			
			$activeInventory = $GLOBALS['database']::getPlayerInventory($_SESSION['playerId']);
			$inactiveInventory = $GLOBALS['database']::getPlayerInventory($_SESSION['playerId'], false);
			
			$tInventory = new InventoryVO();
			$tInventory->activeItems = $activeInventory;
			$tInventory->inactiveItems = $inactiveInventory;
			
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = $tInventory;
			return $result;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::removeItems\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function updateItems($active, $inactive) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			foreach($active as $item) {
				$lazy = json_decode(json_encode($item), true);
				
				//$items = $GLOBALS['database']::getItemInfoById($lazy['id']);
				
				/*
				if(!boolval((int) $items['premium'])) {
					$canChange = true;
				} elseif(boolval($items['premium']) && $_SESSION['playerPremium']) {
					$canChange = true;
				} else {
					$canChange = false;
				}*/
				
				//if($canChange) {
					$update = $pdo->prepare("UPDATE inventory SET active = 1 WHERE player_id = ? and item_id = ?");
					$update->bindParam(1, $_SESSION['playerId'], PDO::PARAM_INT);
					$update->bindParam(2, $lazy['id'], PDO::PARAM_INT);
					$update->execute();
				//}
			}
			
			foreach($inactive as $item) {
				$lazy = json_decode(json_encode($item), true);
				
				//$items = $GLOBALS['database']::getItemInfoById($lazy['id']);
				
				$update = $pdo->prepare("UPDATE inventory SET active = 0 WHERE player_id = ? and item_id = ?");
				$update->bindParam(1, $_SESSION['playerId'], PDO::PARAM_INT);
				$update->bindParam(2, $lazy['id'], PDO::PARAM_INT);
				$update->execute();
			}
			
			$playerInfo = $GLOBALS['database']::getPlayerInfo($_SESSION['playerId']);
			
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = $playerInfo;
			$result->valueObject->isPremium = $_SESSION['playerPremium'];
			return $result;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::updateItems\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function updatePlayerState($playerId, $state) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			echo $state;
			switch($state) {
				case 'babyTransformation':
				case 'littleBabyTransformation':
					$update = $pdo->prepare("UPDATE users SET state = ? WHERE id = ?");
					$update->bindParam(1, $state, PDO::PARAM_STR);
					$update->bindParam(2, $_SESSION['playerId'], PDO::PARAM_INT);
					$update->execute();
					
					$result = new AmfResponse();
					$result->statusCode = 0;
					$result->message = "success";
					$result->valueObject = null;
					return $result;
				case '':
				case null:
					$update = $pdo->prepare("UPDATE `users` SET `state` = '' WHERE `id` = :id");
					$update->bindParam(":id",$_SESSION['playerId'],PDO::PARAM_INT);
					$update->execute();
					
					$result = new AmfResponse();
					$result->statusCode = 0;
					$result->message = "success";
					$result->valueObject = null;
					return $result;
					break;
				default:
					return null;
			}
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::updateState\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
	function updateTourFinished($status) {
		try {
			$pdo = $GLOBALS['database']::getConnection();
			
			$update = $pdo->prepare("UPDATE users SET tour_finished = ? WHERE id = ?");
			$update->bindParam(1, $status, PDO::PARAM_BOOL);
			$update->bindParam(2, $_SESSION['playerId'], PDO::PARAM_INT);
			$update->execute();
			
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = null;
			return $result;
		} catch(PDOException $e) {
			$error = date("d.m.Y H:i:s") . "\amfPlayerService::updateTourFinished\tError: (" . $e->getCode . ") " . $e->getMessage;
            throw new Exception($error);
		}
	}
	
}
