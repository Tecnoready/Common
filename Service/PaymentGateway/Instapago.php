<?php

namespace Tecnoready\Common\Service\PaymentGateway;

use GuzzleHttp\Client;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Tecnoready\Common\Util\StringUtil;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Tecnoready\Common\Model\PaymentGateway\ResponseInstapago;

/**
 * Pasarela de pago para procesar una tarjeta de credito con instapago banesco
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @see https://instapago.com/wp-content/uploads/Gu%C3%ADa-Integraci%C3%B3n-API-Instapago-1.7.pdf
 * @see https://instapago.com/
 */
class Instapago 
{
    /**
     * Estatus: Retener (pre-autorización).
     */
    const STATUS_ID_PRE_AUTHORIZATION = "1";
    /**
     * Tarjetas de prueba (Pueden indicar cualquier valor para Cédula o RIF, Fecha de
     * Vencimiento y CVC:):
     * Visa: 4111111111111111 
     */
    
    /**
     * Estatus: Pagar (autorización).
     */
    const STATUS_ID_AUTHORIZATION = "2";
    
    /**
     * Url base para hacer las llamadas
     */
    const BASE_URI = "https://api.instapago.com/";
    
    /**
     * Método HTTP: POST
     * Tipo de codificación: application/x-www-form-urlencoded 
     */
    const API_CREATE_PAYMENT = "payment";
    
    /**
     * Método HTTP: POST
     * Tipo de codificación: application/x-www-form-urlencoded 
     */
    const API_COMPLETE_PAYMENT = "complete";
    
    /**
     * Método: DELETE
     * Tipo de codificación: application/x-www-form-urlencoded 
     */
    const API_CANCEL_PAYMENT = "payment";
    
    /**
     * Método: GET
     * Tipo de codificación: application/x-www-form-urlencoded 
     */
    const API_QUERY_PAYMENT = "payment";
    
    /**
     * Para todas las transacciones realizadas bajo el API, los códigos HTTP de respuestas
     * corresponden a los siguientes estados:
     * o 201: Pago procesado, se detalla la transacción en la respuesta.
     * o 400: Error al validar los datos enviados (Adicionalmente se devuelve una cadena de
     * caracteres con la descripción del error).
     * o 401: Error de autenticación, ha ocurrido un error con las llaves utilizadas.
     * o 403: Pago Rechazado por el banco.
     * o 500: Ha Ocurrido un error interno dentro del servidor.
     * o 503: Ha Ocurrido un error al procesar los parámetros de entrada. Revise los datos
     * enviados y vuelva a intentarlo.
     * Si recibe un código de respuesta diferente a los antes descritos deben ser tomados como
     * errores de protocolo HTTP.
     */
    
    
    private $publicKey = null;
    private $privateKey = null;
    
    private $options;

    public function __construct($publicKey, $privateKey,array $options = []) {
        
        \Tecnoready\Common\Util\ConfigurationUtil::checkLib("optionsResolver");
        \Tecnoready\Common\Util\ConfigurationUtil::checkLib("propertyAccess");
        \Tecnoready\Common\Util\ConfigurationUtil::checkLib("guzzleHttp");
        
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "class_response" => ResponseInstapago::class,
            "timeout" => 20,
        ]);
        $this->options = $resolver->resolve($options);
    }
    
    /**
     * Realiza una autorizacion(retencion de fondos) a la tarjeta de credito
     * @param array $options
     * @return ResponseInstapago
     */
    public function preAuthorization(array $options) {
        $options["StatusId"] = self::STATUS_ID_PRE_AUTHORIZATION;
        return $this->executePost($options);
    }
    
    /**
     * Realiza un cargo a la tarjeta de credito
     * @param array $options
     * @return ResponseInstapago
     */
    public function authorization(array $options) {
        $options["StatusId"] = self::STATUS_ID_AUTHORIZATION;
        return $this->executePost($options);
    }
    
    /**
     * Ejecuta una llamada por metodo post
     * @param array $options
     * @return ResponseInstapago
     */
    private function executePost(array $options) {
        $resolver = $this->getResolver();
        $parameters = $resolver->resolve($options);
//        var_dump($parameters);
        $client = $this->getClient();
        $response = $client->post(self::API_CREATE_PAYMENT,[
            "form_params" => $parameters,
        ]);
        $responseData = null;
        if($response->getStatusCode() === 200){
            $responseArray = json_decode((string)$response->getBody(),true);
            if(is_array($responseArray)){
                $responseData = ResponseInstapago::createFromArray($this->options["class_response"],$responseArray);
            }
        }
        
        return $responseData;
    }


    /**
     * @return OptionsResolver
     */
    private function getResolver() {
        $resolver = new OptionsResolver();
        $resolver->setDefined([
            "KeyID",
            "PublicKeyId",
            "Amount",//Monto a Debitar, utilizando punto “.” como separador decimal. Por ejemplo: 200.00.
            "Description",
            "CardHolder",
            "CardHolderId",
            "CardNumber",
            "CVC",
            "ExpirationDate",//MM/YYYY
            "StatusId",// "1": Retener (pre-autorización). "2": Pagar (autorización).
            "IP",
            "OrderNumber",
            "Address",
            "City",
            "ZipCode",
            "State",
        ]);
        
        $resolver->setRequired([
            "KeyID",
            "PublicKeyId",
            "Amount",
            "Description",
            "CardHolder",
            "CardHolderId",
            "CardNumber",
            "CVC",
            "ExpirationDate",
            "StatusId",
            "IP",
        ]);
        
        $resolver->setDefaults([
            "KeyID" => $this->privateKey,
            "PublicKeyId" => $this->publicKey,
        ]);
        $resolver->setAllowedValues("StatusId",[self::STATUS_ID_PRE_AUTHORIZATION,self::STATUS_ID_AUTHORIZATION]);
        $resolver->setAllowedTypes("KeyID","string");
        $resolver->setAllowedTypes("PublicKeyId","string");
        
        $resolver->setNormalizer("CVC",function (Options  $options, $value){
            $exp = "/[^0-9]/";
            $value = StringUtil::clean($value,$exp);
            if(!StringUtil::validCharacters($value,$exp)){
                throw new InvalidArgumentException(sprintf("El formato del cvv de la tarjeta '%s' debe ser %s",$value,$exp));
            }
            $limit = 4;
            if(strlen($value) > 4){
                throw new InvalidArgumentException(sprintf("La longitud del cvv '%s' no puede exceder %s digitos",$value,$limit));
            }
            return $value;
        });
        $resolver->setNormalizer("Amount",function(Options  $options, $value){
            $value = str_replace(".","", $value);
            $value = str_replace(",",".", $value);
            return $value;
        });
        $resolver->setNormalizer("CardHolder",function(Options  $options, $value){
            $value = mb_strtoupper($value);
            $value = StringUtil::clearAccents($value);
            $exp = "/[^A-Z ]/";
            if(!StringUtil::validCharacters($value,$exp)){
                throw new InvalidArgumentException(sprintf("El formato del nombre '%s' debe ser %s",$value,$exp));
            }
            return $value;
        });
        $resolver->setNormalizer("CardNumber",function(Options  $options, $value){
            $exp = "/[^0-9]/";
            $value = StringUtil::clean($value,$exp);
            if(!StringUtil::validCharacters($value,$exp)){
                throw new InvalidArgumentException(sprintf("El formato del numero de la tarjeta '%s' debe ser %s",$value,$exp));
            }
            return $value;
        });
        $resolver->setNormalizer("ExpirationDate",function(Options  $options, $value){
            $format = "MM/YYYY";
            $exp = "/[^0-9\/]/";
            $value = StringUtil::clean($value,$exp);
            if(strlen($value) != strlen($format) || substr($value,2,1) !== "/"){
                throw new InvalidArgumentException(sprintf("El formato de la fecha '%s' debe ser %s",$value,$format));
            }
            return $value;
        });
        $resolver->setAllowedValues("IP",function($value){
            if(!filter_var($value,FILTER_VALIDATE_IP)){
                return false;
            }
            return true;
        });
        
        $resolver->setAllowedValues("KeyID",function($value){
           return strlen($value) > 10;
        });
        $resolver->setAllowedValues("PublicKeyId",function($value){
           return strlen($value) > 10;
        });
        return $resolver;
    }
    
    /**
     * Cliente para hacer peticiones http
     * @return Client
     */
    private function getClient() {
        $client = new Client([
            "base_uri" => self::BASE_URI,
            "timeout" => $this->options["timeout"],
        ]);
        return $client;
    }
}
