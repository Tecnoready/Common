<?php

namespace Tecnoready\Common\Model\Email\ORM;

use Doctrine\ORM\Mapping as ORM;
use Tecnoready\Common\Model\Email\EmailQueueInterface;

/**
 * Modelo de email almacenado para generar el correo posteriormente y enviarlo
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @ORM\MappedSuperclass()
 */
abstract class ModelEmailQueue implements EmailQueueInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="string", length=36)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="text")
     */
    protected $subject;
    
    /**
     * @var string
     *
     * @ORM\Column(name="from_email", type="json_array", length=255)
     */
    protected $fromEmail;
    
    /**
     * @var string
     *
     * @ORM\Column(name="to_email", type="json_array")
     */
    protected $toEmail;
    
    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    protected $body;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    protected $status;
    
    /**
     * @var string $environment
     *
     * @ORM\Column(name="environment", type="string", nullable=true)
     */
    protected $environment;
    
    /**
     * @var string
     *
     * @ORM\Column(name="attachs", type="json_array")
     */
    protected $attachs;

    /**
     * @var \DateTime $sentAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var \DateTime $sendAt
     *
     * @ORM\Column(name="send_at", type="datetime", nullable=true)
     */
    protected $sendAt;

    /**
     * @var string
     *
     * @ORM\Column(name="retries", type="integer")
     */
    protected $retries = 0;
    
    use \Tecnoready\Common\Model\Traits\ExtraDataTrait;
    
    public function __construct() {
        $this->extraData = [];
    }
    
    public function getSubject() {
        return $this->subject;
    }

    public function getFromEmail() {
        return $this->fromEmail;
    }

    public function getToEmail() {
        return $this->toEmail;
    }

    public function getBody() {
        return $this->body;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getEnvironment() {
        return $this->environment;
    }

    public function getAttachs() {
        return $this->attachs;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
        return $this;
    }

    public function setFromEmail($fromEmail) {
        $this->fromEmail = $fromEmail;
        return $this;
    }

    public function setToEmail(array $toEmail) {
        $this->toEmail = $toEmail;
        return $this;
    }

    public function setBody($body) {
        $this->body = $body;
        return $this;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function setEnvironment($environment) {
        $this->environment = $environment;
        return $this;
    }

    public function setAttachs($attachs) {
        $this->attachs = $attachs;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function onCreatedAt()
    {
        $this->createdAt = new \DateTime();

        return $this;
    }

    public function getSendAt()
    {
        return $this->sendAt;
    }

    public function setSendAt(\DateTime $sendAt)
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    public function onSendAt()
    {
        $this->sendAt = new \DateTime();

        return $this;
    }

    public function onSendSuccessAt()
    {
        $this->onSendAt();
        $this->status = self::STATUS_SENT;
        
        return $this;
    }

    public function onSendErrorAt()
    {
        $this->retries = $this->retries + 1;
        $this->status = self::STATUS_FAIL;
        
        return $this;
    }

    public function getRetries()
    {
        return $this->retries;
    }

    public function setRetries($retries)
    {
        $this->retries = $retries;

        return $this;
    }
}