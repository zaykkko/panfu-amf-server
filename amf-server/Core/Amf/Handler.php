<?php

/**
 *  This file is part of amfPHP
 *
 * LICENSE
 *
 * This source file is subject to the license that is bundled
 * with this package in the file license.txt.
 */

/**
 * This is the default handler for the gateway. It's job is to handle everything that is specific to Amf for the gateway.
 * 
 * @todo determine if indirection for serializer/deserializer necessary. Makes gateway code lighter, but is cumbersome 
 * @package Amfphp_Core_Amf
 * @author Ariel Sommeria-Klein
 */
class Amfphp_Core_Amf_Handler implements Amfphp_Core_Common_IDeserializer, Amfphp_Core_Common_IDeserializedRequestHandler, Amfphp_Core_Common_IExceptionHandler, Amfphp_Core_Common_ISerializer {
    /**
     * filter called for each amf request header, to give a plugin the chance to handle it.
     * Unless a plugin handles them, amf headers are ignored
     * Headers embedded in the serialized requests are regarded to be a Amf specific, so they get their filter in Amf Handler
     * @param Object $handler. null at call. Return if the plugin can handle
     * @param Amfphp_Core_Amf_Header $header the request header
     * @todo consider an interface for $handler. Maybe overkill here
     */

    const FILTER_AMF_REQUEST_HEADER_HANDLER = 'FILTER_AMF_REQUEST_HEADER_HANDLER';

    /**
     * filter called for each amf request message, to give a plugin the chance to handle it.
     * This is for the Flex Messaging plugin to be able to intercept the message and say it wants to handle it
     * @param Object $handler. null at call. Return if the plugin can handle
     * @param Amfphp_Core_Amf_Message $requestMessage the request message
     * @todo consider an interface for $handler. Maybe overkill here
     */
    const FILTER_AMF_REQUEST_MESSAGE_HANDLER = 'FILTER_AMF_REQUEST_MESSAGE_HANDLER';

    /**
     * filter called for exception handling an Amf packet/message, to give a plugin the chance to handle it.
     * This is for the Flex Messaging plugin to be able to intercept the exception and say it wants to handle it
     * @param Object $handler. null at call. Return if the plugin can handle
     * @todo consider an interface for $handler. Maybe overkill here
     */
    const FILTER_AMF_EXCEPTION_HANDLER = 'FILTER_AMF_EXCEPTION_HANDLER';

    /**
     * Amf specifies that an error message must be aimed at an end point. This stores the last message's response Uri to be able to give this end point
     * in case of an exception during the handling of the message. The default is '/1', because a response Uri is not always available
     * @var String
     */
    protected $lastRequestMessageResponseUri;

    /**
     * return error details
     * @see Amfphp_Core_Config::CONFIG_RETURN_ERROR_DETAILS
     * @var boolean 
     */
    protected $returnErrorDetails = true;

    /**
     * Vo Converter. 
     * @var Amfphp_Core_Common_IVoConverter 
     */
    protected $voConverter;
    /**
     * use this to manipulate the packet directly from your services. This is an advanced option, and should be used with caution!
     * @var Amfphp_Core_Amf_Packet
     */
    public static $requestPacket;

    /**
     * use this to manipulate the packet directly from your services. This is an advanced option, and should be used with caution!
     * @var Amfphp_Core_Amf_Packet
     */
    public static $responsePacket;
    
    /**
     * constructor
     * @param array $sharedConfig
     */
    function __construct($sharedConfig) {
        $this->lastRequestMessageResponseUri = '/1';
        if (isset($sharedConfig[Amfphp_Core_Config::CONFIG_RETURN_ERROR_DETAILS])) {
            $this->returnErrorDetails = $sharedConfig[Amfphp_Core_Config::CONFIG_RETURN_ERROR_DETAILS];
        }
    }

    /**
     * deserialize
     * @see Amfphp_Core_Common_IDeserializer
     * @param array $getData
     * @param array $postData
     * @param string $rawPostData
     * @return string
     */
    function deserialize(array $getData, array $postData, $rawPostData) {
        $deserializer = new Amfphp_Core_Amf_Deserializer();
        //note: this has to be done here and not in the constructor to avoid 
        //disabling scanning when it's another handler that ends up handling the request
        $this->voConverter = Amfphp_Core_FilterManager::getInstance()->callFilters(Amfphp_Core_Gateway::FILTER_VO_CONVERTER, null);
        if($this->voConverter){
            $this->voConverter->setScanEnabled(false);
            $deserializer->voConverter = $this->voConverter;
        }
        $requestPacket = $deserializer->deserialize($getData, $postData, $rawPostData);
        return $requestPacket;
    }

    /**
     * creates a ServiceCallParameters object from an Amfphp_Core_Amf_Message
     * supported separators in the targetUri are '/' and '.'
     * @param Amfphp_Core_Amf_Message $Amfphp_Core_Amf_Message
     * @return Amfphp_Core_Common_ServiceCallParameters
     */
    function getServiceCallParameters(Amfphp_Core_Amf_Message $Amfphp_Core_Amf_Message) {
        $targetUri = str_replace('.', '/', $Amfphp_Core_Amf_Message->targetUri);
        $split = explode('/', $targetUri);
        $ret = new Amfphp_Core_Common_ServiceCallParameters();
        $ret->methodName = array_pop($split);
        $ret->serviceName = join($split, '/');
        $ret->methodParameters = $Amfphp_Core_Amf_Message->data;
        return $ret;
    }
	
	function handleRandomPhrase() {
		$o = Array('Santa tell me, if you\'re really thereeeeee, DON\'T MAKE ME FALL IN LOVE AGAIN, if he won\'t be here next year. Santa tell me if he really cares, \'CAUSE I CAN GIVE IT ALL AWAY IF HE WON\'T BE HERE NEXT YEAAAAAAARR....','You\'re so - fucking - precious - when you - * S M I L E *','Who would bin? A big boi or a triangular boi?');
		
		return $o[rand(0,2)];
	}

    /**
     * process a request and generate a response.
     * throws an Exception if anything fails, so caller must encapsulate in try/catch
     *
     * @param Amfphp_Core_Amf_Message $requestMessage
     * @param Amfphp_Core_Common_ServiceRouter $serviceRouter
     * @return Amfphp_Core_Amf_Message the response Message for the request
     */
    function handleRequestMessage(Amfphp_Core_Amf_Message $requestMessage, Amfphp_Core_Common_ServiceRouter $serviceRouter) {
        $filterManager = Amfphp_Core_FilterManager::getInstance();
        $fromFilters = $filterManager->callFilters(self::FILTER_AMF_REQUEST_MESSAGE_HANDLER, null, $requestMessage);
        if ($fromFilters) {
            $handler = $fromFilters;
            return $handler->handleRequestMessage($requestMessage, $serviceRouter);
        }

        //plugins didn't do any special handling. Assumes this is a simple Amfphp_Core_Amf_ RPC call
        $serviceCallParameters = $this->getServiceCallParameters($requestMessage);

		if($serviceCallParameters->serviceName === "amfRegistrationService" || $serviceCallParameters->serviceName === "amfConnectionService" && $serviceCallParameters->methodName === "iniciarSesiónATravésDeNumeritosUnTantoPeculiares") {
			if($serviceCallParameters->serviceName === "amfRegistrationService") {
				$ret = $serviceRouter->executeServiceCall($serviceCallParameters->serviceName, $serviceCallParameters->methodName, $serviceCallParameters->methodParameters);
			} elseif(str_replace('/','',$requestMessage->responseUri) === '1') {
				unset($_SESSION['respCount']);
				$ret = $serviceRouter->executeServiceCall($serviceCallParameters->serviceName, $serviceCallParameters->methodName, $serviceCallParameters->methodParameters);
			} else {
				$ret = $serviceRouter->executeServiceCall('errorServiceClass','showEverythingIsFine',Array("El número de respuesta ya fue dado anteriormente, request denegada.",str_replace('/','',$requestMessage->responseUri),"No es fatal"));
			}
		} else {
			if(!isset($_SESSION['playerId']) || !isset($_SESSION['playerName']) || !isset($_SESSION['playerPremium']) || !isset($_SESSION['playerModerator']) || !isset($_SESSION['playerCoins'])) {
				$ret = $serviceRouter->executeServiceCall('errorServiceClass','showEverythingIsFine', Array("La sesión ha vencido.",str_replace('/','',$requestMessage->responseUri),"No es fatal"));
			} elseif(!isset($_SESSION['respCount'])) {
				$_SESSION['respCount'] = str_replace('/','',$requestMessage->responseUri);
				$ret = $serviceRouter->executeServiceCall($serviceCallParameters->serviceName, $serviceCallParameters->methodName, $serviceCallParameters->methodParameters);
			} elseif($_SESSION['respCount'] === str_replace('/','',$requestMessage->responseUri) || $_SESSION['respCount'] > str_replace('/','',$requestMessage->responseUri)) {
				$ret = $serviceRouter->executeServiceCall('errorServiceClass','showEverythingIsFine', Array("El número de respuesta ya fue dado anteriormente, request denegada.",str_replace('/','',$requestMessage->responseUri),"No es fatal"));
			} elseif($GLOBALS['respCount'] < str_replace('/','',$requestMessage->responseUri)) {
				$_SESSION['respCount'] = str_replace('/','',$requestMessage->responseUri);
				$ret = $serviceRouter->executeServiceCall($serviceCallParameters->serviceName, $serviceCallParameters->methodName, $serviceCallParameters->methodParameters);
			} else {
				$ret = $serviceRouter->executeServiceCall('errorServiceClass','showEverythingIsFine',Array("El número de respuesta ya fue dado anteriormente, request denegada.",str_replace('/','',$requestMessage->responseUri),"No es fatal"));
			}
		}
		
        $responseMessage = new Amfphp_Core_Amf_Message();
        $responseMessage->data = $ret;
        $responseMessage->targetUri = $requestMessage->responseUri . Amfphp_Core_Amf_Constants::CLIENT_SUCCESS_METHOD;
        //not specified
        //$responseMessage->responseUri = '';
        //$responseMessage->responseUri = crypt(uniqid(mt_rand(),true).md5(rand(0,10) . $requestMessage->responseUri),'SC9'.uniqid(mt_rand(),true));
        $responseMessage->responseUri = md5(time().uniqid(mt_rand(),true).$requestMessage->responseUri);
        return $responseMessage;
    }

    /**
     * handle deserialized request
     * @see Amfphp_Core_Common_IDeserializedRequestHandler
     * @param mixed $deserializedRequest
     * @param Amfphp_Core_Common_ServiceRouter $serviceRouter
     * @return mixed
     */
    function handleDeserializedRequest($deserializedRequest, Amfphp_Core_Common_ServiceRouter $serviceRouter) {
        self::$requestPacket = $deserializedRequest;
        self::$responsePacket = new Amfphp_Core_Amf_Packet();
        $numHeaders = count(self::$requestPacket->headers);
        for ($i = 0; $i < $numHeaders; $i++) {
            $requestHeader = self::$requestPacket->headers[$i];
            //handle a header. This is a job for plugins, unless comes a header that is so fundamental that it needs to be handled by the core
            $fromFilters = Amfphp_Core_FilterManager::getInstance()->callFilters(self::FILTER_AMF_REQUEST_HEADER_HANDLER, null, $requestHeader);
            if ($fromFilters) {
                $handler = $fromFilters;
                $handler->handleRequestHeader($requestHeader);
            }
        }

        $numMessages = count(self::$requestPacket->messages);

        //set amf version to the one detected in request
        self::$responsePacket->amfVersion = self::$requestPacket->amfVersion;

        //handle each message
        for ($i = 0; $i < $numMessages; $i++) {
            $requestMessage = self::$requestPacket->messages[$i];
            $this->lastRequestMessageResponseUri = $requestMessage->responseUri;
            $responseMessage = $this->handleRequestMessage($requestMessage, $serviceRouter);
            self::$responsePacket->messages[] = $responseMessage;
        }

        return self::$responsePacket;
    }

    /**
     * handle exception
     * @see Amfphp_Core_Common_IExceptionHandler
     * @param Exception $exception
     * @return Amfphp_Core_Amf_Packet
     */
    function handleException(Exception $exception) {
        $errorPacket = new Amfphp_Core_Amf_Packet();
        $filterManager = Amfphp_Core_FilterManager::getInstance();
        $fromFilters = $filterManager->callFilters(self::FILTER_AMF_EXCEPTION_HANDLER, null);
        if ($fromFilters) {
            $handler = $fromFilters;
            return $handler->generateErrorResponse($exception);
        }

        //no special handling by plugins. generate a simple error response with information about the exception
        $errorResponseMessage = null;
        $errorResponseMessage = new Amfphp_Core_Amf_Message();
        $errorResponseMessage->targetUri = $this->lastRequestMessageResponseUri . Amfphp_Core_Amf_Constants::CLIENT_FAILURE_METHOD;
        //not specified
        $errorResponseMessage->responseUri = crypt(uniqid(mt_rand(),true).md5(rand(0,10) . $errorResponseMessage->responseUri),'SC9'.uniqid(mt_rand(),true));
        $data = new stdClass();
        $data->faultCode = $exception->getCode();
        $data->faultString = $exception->getMessage();
        if ($this->returnErrorDetails) {
            //$data->faultDetail = $exception->getTraceAsString();
            $data->faultDetail = '';
            $data->rootCause = $exception;
        } else {
            $data->faultDetail = '';
        }
        $errorResponseMessage->data = $data;

        $errorPacket->messages[] = $errorResponseMessage;
        return $errorPacket;
    }

    /**
     * serialize
     * @see Amfphp_Core_Common_ISerializer
     * @param mixed $data
     * @return mixed
     */
    function serialize($data) {

        $serializer = new Amfphp_Core_Amf_Serializer();
        $serializer->voConverter = $this->voConverter;
        return $serializer->serialize($data);
    }

}

?>
