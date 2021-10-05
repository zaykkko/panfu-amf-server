<?php

class ExcTime { 
    private $comienzo; 
 
    function getMicrotime() { 
        list($milisegundos, $segundos) = explode(" ", microtime()); 
        return ( (float) $milisegundos + (float) $segundos ); 
    }

    function start() { 
        $this->comienzo = floor(microtime(true)*1000);
        return true; 
    } 

    function stop($formatear = false, $nroDecimales = 0, $session = false) { 
        $tiempo = floor(microtime(true)*1000) - $this->comienzo; 
		$GLOBALS['extimer'] = $tiempo;
    }
}