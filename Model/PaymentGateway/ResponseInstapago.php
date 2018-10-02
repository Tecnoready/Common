<?php

namespace Tecnoready\Common\Model\PaymentGateway;

/**
 * Respuesta de una transaccion de instapago
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ResponseInstapago {
    /**
     * Código del pago (Máx. 32 caracteres),
     * @var string
     */
    protected $id;
    
    /**
     *  Indica si fue procesado el pago (true o false)
     * @var bool 
     */
    protected $success;
    
    /**
     * Descripción de la respuesta (Máx. 200 caracteres),
     * @var string 
     */
    protected $message;
    
    /**
     * Código del respuesta del pago (Máx. 3 caracteres),
     * @var string 
     */
    protected $code;
    
    /**
     * Número de referencia del pago (Máx. 6 caracteres),
     * @var string 
     */
    protected $reference;
    
    /**
     *  Informe de la transacción en formato HTML codificado,
     * @var string 
     */
    protected $voucher;
    
    /**
     * Número de orden indicado por el comercio
     * @var string 
     */
    protected $orderNumber;
    
    /**
     * Número de identificación bancario universal (Máx. 12 caracteres),
     * @var string 
     */
    protected $sequence;
    
    /**
     * Número de aprobación bancaria (Máx. 6 caracteres),
     * @var string 
     */
    protected $approval;
    /**
     * Número de lote asignado a la transacción (Máx. 3 caracteres),
     * @var int 
     */
    protected $lote;
    
    /**
     * Código de respuesta de la transacción (Máx. 2 caracteres),
     * @var int 
     */
    protected $responseCode;
    /**
     * Indica si la transacción ha sido diferida (true o false),
     * @var bool 
     */
    protected $deferred;
    
    /**
     * Fecha de la transacción (Formato MM/dd/yyyy hh:mm:ss tt),
     * @var \Datetime 
     */
    protected $datetime;
    /**
     * Monto de la transacción (Formato 0.00)
     * @var double 
     */
    protected $amount;
    
    protected $authid;
    
    public function getId() {
        return $this->id;
    }

    public function isSuccess() {
        return $this->success;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getCode() {
        return $this->code;
    }

    public function getReference() {
        return $this->reference;
    }

    public function getVoucher() {
        return $this->voucher;
    }

    public function getOrderNumber() {
        return $this->orderNumber;
    }

    public function getSequence() {
        return $this->sequence;
    }

    public function getApproval() {
        return $this->approval;
    }

    public function getLote() {
        return $this->lote;
    }

    public function getResponseCode() {
        return $this->responseCode;
    }

    public function getDeferred() {
        return $this->deferred;
    }

    public function getDatetime() {
        return $this->datetime;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getAuthid() {
        return $this->authid;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setSuccess($success) {
        $this->success = $success;
        return $this;
    }

    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }

    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

    public function setReference($reference) {
        $this->reference = $reference;
        return $this;
    }

    public function setVoucher($voucher) {
        $this->voucher = $voucher;
        return $this;
    }

    public function setOrderNumber($orderNumber) {
        $this->orderNumber = $orderNumber;
        return $this;
    }

    public function setSequence($sequence) {
        $this->sequence = $sequence;
        return $this;
    }

    public function setApproval($approval) {
        $this->approval = $approval;
        return $this;
    }

    public function setLote($lote) {
        $this->lote = $lote;
        return $this;
    }

    public function setResponseCode($responseCode) {
        $this->responseCode = $responseCode;
        return $this;
    }

    public function setDeferred($deferred) {
        $this->deferred = $deferred;
        return $this;
    }

    public function setDatetime(\Datetime $datetime = null) {
        $this->datetime = $datetime;
        return $this;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
        return $this;
    }

    public function setAuthid($authid) {
        $this->authid = $authid;
        return $this;
    }

    public static function createFromArray($className,array $data) {
        $accessor = \Symfony\Component\PropertyAccess\PropertyAccess::createPropertyAccessor();
        $response = new $className();
        foreach ($data as $propertyPath => $value) {
            if($accessor->isWritable($response, $propertyPath)){
                if($propertyPath == "datetime"){
                    $value = \DateTime::createFromFormat("m/d/Y g:i:s A",$value);
                    if (!$value) {
                        $value = new \Datetime();
                    }
                }
                if($propertyPath == "voucher"){
                    $value = html_entity_decode($value);
                }                
                $accessor->setValue($response, $propertyPath, $value);
            }
        }
        return $response;
    }
}
