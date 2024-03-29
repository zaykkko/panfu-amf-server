<?php

define('AMFPHP_ROOTPATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('AMFPHP_VERSION', '2.2.2');

//includes
require_once dirname(__FILE__) . '/Includes/Database.php';
global $Database;
$GLOBALS['database'] = new DBConnection();
require_once dirname(__FILE__) . '/Includes/ExecutionTimer.php';

//core/common
require_once AMFPHP_ROOTPATH . 'Core/Common/ClassFindInfo.php';
require_once AMFPHP_ROOTPATH . 'Core/Common/IDeserializer.php';
require_once AMFPHP_ROOTPATH . 'Core/Common/IExceptionHandler.php';
require_once AMFPHP_ROOTPATH . 'Core/Common/IDeserializedRequestHandler.php';
require_once AMFPHP_ROOTPATH . 'Core/Common/ISerializer.php';
require_once AMFPHP_ROOTPATH . 'Core/Common/IVoConverter.php';
require_once AMFPHP_ROOTPATH . 'Core/Common/ServiceRouter.php';
require_once AMFPHP_ROOTPATH . 'Core/Common/ServiceCallParameters.php';

//core/amf
require_once AMFPHP_ROOTPATH . 'Core/Amf/Constants.php';
require_once AMFPHP_ROOTPATH . 'Core/Amf/Deserializer.php';
require_once AMFPHP_ROOTPATH . 'Core/Amf/Handler.php';
require_once AMFPHP_ROOTPATH . 'Core/Amf/Header.php';
require_once AMFPHP_ROOTPATH . 'Core/Amf/Message.php';
require_once AMFPHP_ROOTPATH . 'Core/Amf/Packet.php';
require_once AMFPHP_ROOTPATH . 'Core/Amf/Serializer.php';
require_once AMFPHP_ROOTPATH . 'Core/Amf/Util.php';

//core/Amf/types
require_once AMFPHP_ROOTPATH . 'Core/Amf/Types/ByteArray.php';
require_once AMFPHP_ROOTPATH . 'Core/Amf/Types/Undefined.php';
require_once AMFPHP_ROOTPATH . 'Core/Amf/Types/Date.php';
require_once AMFPHP_ROOTPATH . 'Core/Amf/Types/Vector.php';
require_once AMFPHP_ROOTPATH . 'Core/Amf/Types/Xml.php';
require_once AMFPHP_ROOTPATH . 'Core/Amf/Types/XmlDocument.php';

//core
require_once AMFPHP_ROOTPATH . 'Core/Config.php';
require_once AMFPHP_ROOTPATH . 'Core/Exception.php';
require_once AMFPHP_ROOTPATH . 'Core/Gateway.php';
require_once AMFPHP_ROOTPATH . 'Core/FilterManager.php';
require_once AMFPHP_ROOTPATH . 'Core/HttpRequestGatewayFactory.php';
require_once AMFPHP_ROOTPATH . 'Core/PluginManager.php';