<p  align="center">
<img src="https://i.imgur.com/UPLLVsW.png" width="350"></img>
<p align="center">Emulador del servicio de  <a href="https://en.wikipedia.org/wiki/Panfu">Panfu</a> que utiliza la estructura <a href="https://en.wikipedia.org/wiki/Action_Message_Format">AMF</a>, fue hecho en <a href="https://php.net">PHP</a> utilizando <a href="https://amfphp.org">AmfPHP</a>.</p>
</p>
<p align="center">Este README fue creado en 2019 pero fue un poquito actualizado en 2021.</p>

## Descripción
El proyecto comenzó su desarrollo en septiembre de 2017 y fue descontinuado a finales de 2018. La idea principal era poner en práctica y al mismo tiempo aprender php, por eso hay partes del código que no están muy bien logradas

Al mismo tiempo, este proyecto era un fork a otro proyecto que estaba incompleto (no recuerdo su nombre, pero el repositorio ya no existe).

Tiene casi todos los servicios que utilizaba Panfu implementados.

## Instalación
Cree una nueva base de datos e importe el archivo [panfu.sql](panfu.sql) que contiene todas las estructuras de todas las bases de datos que se utilizarán.

Tenga en cuenta que el archivo de configuración de directorio _(.htaccess)_ reescribe endpoints específicos hacia su archivo de inicialización específico.
Por ejemplo "/gateway" es reescrito a "/amf-server/main.php" mientras que "/tracking" a "/tracking-server/main.php".

## Requisitos
+ Es necesaria una versión de PHP ≥ 5.6 & < 7.4 (apróx.)
	+ Es necesaria la activación de la extensión cURL. 
+ Servidor MySQL (MariaDB también funciona).

## Configuración
Para cambiar los datos de la base de datos deberá de editar el código del archivo [Database.php](/amf-server/Includes/Database.php).

## Encender la aplicación
No necesita ser encendida, solo ser ubicada en un directorio específico donde será llamada por el servicio de AMF del cliente de Panfu.