<?php

require_once dirname(__FILE__) . '/Vo/AmfResponse.php';
require_once dirname(__FILE__) . '/Vo/SecurityChatItemVO.php';

class amfLanguageService {
	private $id;
	private $lang;
	
	function createVoAndReturn($child, $label)
	{
		$non = new SecurityChatItemVO();
		$non->children = $child;
		$non->label = $label;
		return $non;
	}
	
	function getSpanishIngameSnippets()
	{
		$securityDialog = new SecurityChatItemVO();
		$securityDialog->children = array($this->createVoAndReturn(null,'¡Sígueme!'),$this->createVoAndReturn(null,'Sí'),$this->createVoAndReturn(null,'No'),$this->createVoAndReturn(null,'Ok'),$this->createVoAndReturn(null,'Hola'));
		$securityDialog->label = "ROOT";
		$langed = new SecurityChatItemVO();
		$langed->label = "Despedida";
		$langed->children = array($this->createVoAndReturn(null,'Adiós'),$this->createVoAndReturn(null,'¡Hasta luego!'),$this->createVoAndReturn(null,'¡Nos vemos mañana!'),$this->createVoAndReturn(null,'¡Hasta mañana!'),$this->createVoAndReturn(null,'¡Espero verte más tarde!'),$this->createVoAndReturn(null,'Chau'),$this->createVoAndReturn(null,'Hasta luego'));
		array_push($securityDialog->children,$langed);
		$langed2 = new SecurityChatItemVO();
		$langed2->children = array($this->createVoAndReturn(null,'Hoy'),$this->createVoAndReturn(null,'Mañana'),$this->createVoAndReturn(null,'En un mes'),$this->createVoAndReturn(null,'En una semana'),$this->createVoAndReturn(null,'En una hora'),$this->createVoAndReturn(null,'En media hora'),$this->createVoAndReturn(null,'En un año'));
		$langed2->label = "Tiempo";
		$langed = new SecurityChatItemVO();
		$langed->children = array($this->createVoAndReturn(null,'Lunes'),$this->createVoAndReturn(null,'Martes'),$this->createVoAndReturn(null,'Miércoles'),$this->createVoAndReturn(null,'Jueves'),$this->createVoAndReturn(null,'Viernes'),$this->createVoAndReturn(null,'Sábado'),$this->createVoAndReturn(null,'Domingo'));
		$langed->label = "Días";
		array_push($langed2->children,$langed);
		array_push($securityDialog->children,$langed2);
		$langed2 = new SecurityChatItemVO();
		$langed2->label = "Comportamiento";
		$langed2->children = array($this->createVoAndReturn(null,'¡Eso no fue amable!'),$this->createVoAndReturn(null,'¡Eres estúpido!'),$this->createVoAndReturn(null,'¡Oh vaya!'),$this->createVoAndReturn(null,'¡Eres un payaso!'),$this->createVoAndReturn(null,'¡Me hiciste enojar!'),$this->createVoAndReturn(null,'Parece que nunca aprenderás'),$this->createVoAndReturn(null,'Mis padres no me dejan'));
		array_push($securityDialog->children,$langed2);
		$langed2 = new SecurityChatItemVO();
		$langed2->label = "Preguntas";
		$langed2->children = array($this->createVoAndReturn(null,'¿Qué tal?'),$this->createVoAndReturn(null,'¿Quieres ser mi amigo?'),$this->createVoAndReturn(null,'¿Cuál es tu juego favorito?'),$this->createVoAndReturn(null,'¿Cuál es tu sala favorita?'),$this->createVoAndReturn(null,'¿De dónde eres?'),$this->createVoAndReturn(null,'¿Cuál es tu animal favorito?'),$this->createVoAndReturn(null,'¿Dónde quieres ir?'),$this->createVoAndReturn(null,'¿Cuál es tu color favorito?'),$this->createVoAndReturn(null,'¿Quieres visitar mi casa del árbol?'),$this->createVoAndReturn(null,'¿Dónde conseguiste eso?'));
		array_push($securityDialog->children,$langed2);
		$langed2 = new SecurityChatItemVO();
		$langed2->label = "Respuestas";
		$langed2->children = array($this->createVoAndReturn(null,'Adulto'),$this->createVoAndReturn(null,'Joven'),$this->createVoAndReturn(null,'Niño'),$this->createVoAndReturn(null,'Niña'));
		$langed = new SecurityChatItemVO();
		$langed->label = "Colores";
		$langed->children = array($this->createVoAndReturn(null,'Rojo'),$this->createVoAndReturn(null,'Burdeo'),$this->createVoAndReturn(null,'Bordó'),$this->createVoAndReturn(null,'Rosa'),$this->createVoAndReturn(null,'El color negro'),$this->createVoAndReturn(null,'Marrón'),$this->createVoAndReturn(null,'Gris'),$this->createVoAndReturn(null,'Verde'),$this->createVoAndReturn(null,'Blanco'),$this->createVoAndReturn(null,'Amarillo'),$this->createVoAndReturn(null,'Azul'),$this->createVoAndReturn(null,'Naranja'));
		array_push($langed2->children,$langed);
		$langed = new SecurityChatItemVO();
		$langed->label = "Ánimo";
		$langed->children = array($this->createVoAndReturn(null,'Bien'),$this->createVoAndReturn(null,'Más o menos'),$this->createVoAndReturn(null,'Mal'),$this->createVoAndReturn(null,'¡Déjame en paz!'));
		array_push($langed2->children,$langed);
		$langed = new SecurityChatItemVO();
		$langed->label = "Países";
		$langed->children = array($this->createVoAndReturn(null,'Argentina'),$this->createVoAndReturn(null,'Estados Unidos de América'),$this->createVoAndReturn(null,'México'),$this->createVoAndReturn(null,'Ecuador'),$this->createVoAndReturn(null,'Venezuela'),$this->createVoAndReturn(null,'Colombia'),$this->createVoAndReturn(null,'Chile'),$this->createVoAndReturn(null,'Uruguay'),$this->createVoAndReturn(null,'Paraguay'),$this->createVoAndReturn(null,'Bolivia'),$this->createVoAndReturn(null,'Cuyana francesa'),$this->createVoAndReturn(null,'Reino Unido'),$this->createVoAndReturn(null,'R. Dominicana'),$this->createVoAndReturn(null,'Luxemburgo'));
		$lala = new SecurityChatItemVO();
		$lala->label = "Otros";
		$lala->children = array($this->createVoAndReturn(null,'Narnia'),$this->createVoAndReturn(null,'El país del nunca jamás'),$this->createVoAndReturn(null,'¡Qué te importa!'),$this->createVoAndReturn(null,'Canadá'),$this->createVoAndReturn(null,'Alemania'),$this->createVoAndReturn(null,'España'),$this->createVoAndReturn(null,'De un lugar'),$this->createVoAndReturn(null,'Argentina'),$this->createVoAndReturn(null,'P. Sherman Calle Wallaby 42 Sidney'),$this->createVoAndReturn(null,'Tierra del fuego'));
		$mama = new SecurityChatItemVO();
		$mama->label = "Panfu";
		$mama->children = array($this->createVoAndReturn(null,'Ciudad'),$this->createVoAndReturn(null,'Volcán'),$this->createVoAndReturn(null,'Establo de Ponys'),$this->createVoAndReturn(null,'San Franpanfu'),$this->createVoAndReturn(null,'Selva'),$this->createVoAndReturn(null,'Heladería'),$this->createVoAndReturn(null,'Tienda de regalos'),$this->createVoAndReturn(null,'Tienda de animales'),$this->createVoAndReturn(null,'Campo de deportes'),$this->createVoAndReturn(null,'Cueva'),$this->createVoAndReturn(null,'Piscina'),$this->createVoAndReturn(null,'Casa del árbol'),$this->createVoAndReturn(null,'Restaurante'),$this->createVoAndReturn(null,'Playa'),$this->createVoAndReturn(null,'En otro lugar'));
		array_push($langed->children,$mama);
		array_push($langed->children,$lala);
		array_push($langed2->children,$langed);
		$langed = new SecurityChatItemVO();
		$langed->label = "Juegos";
		$langed->children = array($this->createVoAndReturn(null,'Preparar helados'),$this->createVoAndReturn(null,'Cloud number nine'),$this->createVoAndReturn(null,'Bolly hop'),$this->createVoAndReturn(null,'Balloon pop'),$this->createVoAndReturn(null,'Hubi'),$this->createVoAndReturn(null,'LOL'),$this->createVoAndReturn(null,'WOW'),$this->createVoAndReturn(null,'Minecraft'));
		$juju = new SecurityChatItemVO();
		$juju->label = "Algunos juegos de Steam";
		$juju->children = array($this->createVoAndReturn(null,'PUBG'),$this->createVoAndReturn(null,'R6S'),$this->createVoAndReturn(null,'H1Z1'),$this->createVoAndReturn(null,'Counter Strike'),$this->createVoAndReturn(null,'Half Life'),$this->createVoAndReturn(null,'Doom'),$this->createVoAndReturn(null,'Juegos de simulación'),$this->createVoAndReturn(null,'Dark Souls'),$this->createVoAndReturn(null,'Juegos de terror'),$this->createVoAndReturn(null,'Juegos Battle Royale'),$this->createVoAndReturn(null,'Juegos metroidvania'),$this->createVoAndReturn(null,'Otro tipo'),$this->createVoAndReturn(null,'South Park'),$this->createVoAndReturn(null,'Juegos competitivos'),$this->createVoAndReturn(null,'Juegos sandbox'),$this->createVoAndReturn(null,'Red Dead Redemption'),$this->createVoAndReturn(null,'World of Warcraft'),$this->createVoAndReturn(null,'League of Legends'),$this->createVoAndReturn(null,'Fortnite'));
		$aaa = new SecurityChatItemVO();
		$aaa->label = "GTA";
		$aaa->children = array($this->createVoAndReturn(null,'GTA V'),$this->createVoAndReturn(null,'GTA San Andreas'),$this->createVoAndReturn(null,'GTA Vice City'),$this->createVoAndReturn(null,'¿Alguien me regala alguna clave de Steam? estoy bien pobre :('));
		array_push($langed->children,$aaa);
		array_push($langed->children,$juju);
		array_push($langed2->children,$langed);
		$langed = new SecurityChatItemVO();
		$langed->label = "Animales";
		$langed->children = array($this->createVoAndReturn(null,'Perro'),$this->createVoAndReturn(null,'Gato'),$this->createVoAndReturn(null,'Elefante'),$this->createVoAndReturn(null,'Tigre'),$this->createVoAndReturn(null,'Hamster'),$this->createVoAndReturn(null,'Araña'),$this->createVoAndReturn(null,'Jirafa'),$this->createVoAndReturn(null,'Pez'),$this->createVoAndReturn(null,'Conejo'),$this->createVoAndReturn(null,'Ratón'),$this->createVoAndReturn(null,'Creeper'),$this->createVoAndReturn(null,'León'),$this->createVoAndReturn(null,'Morza'),$this->createVoAndReturn(null,'Foca'),$this->createVoAndReturn(null,'Pingüino'),$this->createVoAndReturn(null,'Bolly'),$this->createVoAndReturn(null,'Pokopet'));
		$moreAnimals = new SecurityChatItemVO();
		$moreAnimals->label = "Más :D";
		$moreAnimals->children = array($this->createVoAndReturn(null,'Coyote'),$this->createVoAndReturn(null,'Ocelote'),$this->createVoAndReturn(null,'Caballo'),$this->createVoAndReturn(null,'Pollo'),$this->createVoAndReturn(null,'Rana'),$this->createVoAndReturn(null,'Caracol'),$this->createVoAndReturn(null,'Abeja'),$this->createVoAndReturn(null,'Avispa'),$this->createVoAndReturn(null,'Picar'),$this->createVoAndReturn(null,'Morder'),$this->createVoAndReturn(null,'Grr'));
		array_push($langed->children,$moreAnimals);
		array_push($langed2->children,$langed);
		$langed = new SecurityChatItemVO();
		$langed->children = array($this->createVoAndReturn(null,'¡Me gusta tu casa del árbol!'),$this->createVoAndReturn(null,'¡Me gustan tus muebles!'),$this->createVoAndReturn(null,'¡Qué buena deocoración!'),$this->createVoAndReturn(null,'¡Me ENCANTA tu casa del árbol!'),$this->createVoAndReturn(null,'¡Qué chulo!'),$this->createVoAndReturn(null,'¡Me gusta tu ropa!'),$this->createVoAndReturn(null,'¡Me gusta tu traje!'),$this->createVoAndReturn(null,'¡Eres lindo!'),$this->createVoAndReturn(null,'¡Eres linda!'),$this->createVoAndReturn(null,'¡Te amo!'));
		$langed->label = "Belleza";
		array_push($langed2->children,$langed);
		array_push($securityDialog->children,$langed2);
		
		return $securityDialog;
	}
	
	function getSpanishHelperSnippets()
	{
		$helper = new SecurityChatItemVO();
		$helper->label = "ROOT";
		$helper->children = array();
		$mama = new SecurityChatItemVO();
		$mama->label = "home";
		$mama->children = array($this->createVoAndReturn(null,'¡Bienvenido a una casa del árbol! Este es el lugar donde vivimos nosotros, los pandas, es un habitat muy cómodo, debo admitirlo.'));
		array_push($helper->children,$mama);
		return $helper;
	}
	
	function getSecureChatSnippets($id, $type)
	{
		$this->id = $id;
		$this->type = $type;
		$response = new AmfResponse();
		$totalTimer = floor(microtime(true) * 1000);
		
		switch(strtolower($type))
		{
			case "sc_all":
				$response->message = "SC_ALL";
				$response->valueObject = $this->getSpanishIngameSnippets();
				$response->statusCode = 0;
				break;
			case "sc_helper":
				$response->message = "SC_HELPER";
				$response->valueObject = $this->getSpanishHelperSnippets();
				$response->statusCode = 0;
				break;
			default:
				$response->message = "failed";
				$response->statusCode = 1;
				break;
		}
		
		return $response;
	}
}