<?php

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Vo/ProfileVO.php';

class amfProfileService {
	
	
	function getProfile($id, $premium) {
		if($id == 1010) {
			$tProfile = new ProfileVO();
			$tProfile->id = 1010;
			$tProfile->bestFriend = "¿Qué chingados es esto?";
			$tProfile->movie = "Narnia";
			$tProfile->color = "Naranja, azul, rosa, burdeo & negro.";
			$tProfile->book = "Todos los libros de Narnia.";
			$tProfile->hobby = !$_SESSION['playerPremium']?"MEMBERSHIP_REQUIRED":"Continuidad.";
			$tProfile->song = "Him & I ~ Halsey";
			$tProfile->band = "Halsey, Fetty Wap y Paramore.";
			$tProfile->schoolSubject = "¿Materia?";
			$tProfile->sport = "Muchos...";
			$tProfile->animal = "Caballo, estrella, perro & ocelote.";
			$tProfile->relStatus = !$_SESSION['playerPremium']?"MEMBERSHIP_REQUIRED":"Con Tigo, te amo amorsito.";
			$tProfile->motto = "Intentarlo una vez no es suficiente.";
			$tProfile->bestChar = !$_SESSION['playerPremium']?"MEMBERSHIP_REQUIRED":"Copiar mensajes.";
			$tProfile->worstChar = !$_SESSION['playerPremium']?"MEMBERSHIP_REQUIRED":"Suspender por malotes.";
			$tProfile->likeMost = "Panfu & Bots .)";
			$tProfile->likeLeast = "Club Penguin .(";
						
			$result = new AmfResponse();
			$result->statusCode = 0;
			$result->message = "success";
			$result->valueObject = $tProfile;
			return $result;
		} else {
			try {
				$pdo = $GLOBALS['database']::getConnection();
				
				$stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id LIMIT 1");
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
				
				if ($stmt->rowCount() > 0) {
					$player = $stmt->fetch(PDO::FETCH_ASSOC);
						
					$tProfile = new ProfileVO();
					$tProfile->id = (int) $player['id'];
					$tProfile->bestFriend = $player['best_friend'] === null?"":$player['best_friend'];
					$tProfile->movie = $player['movie'] === null?'':$player['movie'];
					$tProfile->color = $player['color'] === null?'': $player['color'];
					$tProfile->book = $player['book'] === null?'':$player['book'];
					$tProfile->hobby = $player['hobby'] === null || !$_SESSION['playerPremium']?'':$player['hobby'];
					$tProfile->song = $player['song'] === null?'':$player['song'];
					$tProfile->band = $player['band'] === null?'':$player['band'];
					$tProfile->schoolSubject = $player['school_subject'] === null?'':$player['school_subject'];
					$tProfile->sport = $player['sport'] === null?'':$player['sport'];
					$tProfile->animal = $player['animal'] === null?'':$player['animal'];
					$tProfile->relStatus = $player['rel_status'] === null || !$_SESSION['playerPremium']?'':$player['rel_status'];
					$tProfile->motto = $player['motto'] === null?'':$player['motto'];
					$tProfile->bestChar = $player['best_char'] === null || !$_SESSION['playerPremium']?'':$player['best_char'];
					$tProfile->worstChar = $player['worst_char'] === null || !$_SESSION['playerPremium']?'':$player['worst_char'];
					$tProfile->likeMost = $player['like_most'] === null?'':$player['like_most'];
					$tProfile->likeLeast = $player['like_least'] === null?'':$player['like_least'];
						
					$result = new AmfResponse();
					$result->statusCode = 0;
					$result->message = "success";
					$result->valueObject = $tProfile;
					return $result;
				}
			} catch(PDOException $e) {
					$error = date("d.m.Y H:i:s") . "\amfProfileService::getProfile\tError: (" . $e->getCode . ") " . $e->getMessage;
					throw new Exception($error);
			}
		}
	}
	
}