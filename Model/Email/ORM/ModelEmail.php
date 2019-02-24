<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\Email\ORM;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Description of Email
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class ModelEmail implements \Tecnoready\Common\Model\Email\EmailInterface {
    
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
     * @ORM\Column(name="from_email", type="string", length=255)
     */
    protected $fromEmail;
    /**
     * @var string
     *
     * @ORM\Column(name="to_email", type="string", length=255, nullable=true)
     */
    protected $toEmail;
    /**
     * @var string
     *
     * @ORM\Column(name="cc_email", type="string", length=255, nullable=true)
     */
    protected $ccEmail;
    /**
     * @var string
     *
     * @ORM\Column(name="bcc_email", type="string", length=255, nullable=true)
     */
    protected $bccEmail;
    /**
     * @var string
     *
     * @ORM\Column(name="reply_to_email", type="string", length=255, nullable=true)
     */
    protected $replyToEmail;
    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    protected $body;
    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    protected $message;
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    protected $status;
    /**
     * @var string
     *
     * @ORM\Column(name="retries", type="integer")
     */
    protected $retries;
    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;
    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;
    /**
     * @var string $createdBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(name="created_by", type="string", nullable=true)
     */
    protected $createdBy;
    /**
     * @var string $updatedBy
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(name="updated_by", type="string", nullable=true)
     */
    protected $updatedBy;
    
    /**
     * @var \DateTime $sentAt
     *
     * @ORM\Column(name="sent_at", type="datetime", nullable=true)
     */
    protected $sentAt;
    /**
     * @var \Swift_Message
     *
     * @ORM\Column(name="error_message", type="text", nullable=true)
     */
    protected $errorMessage;
    
    /**
     * @var string $environment
     *
     * @ORM\Column(name="environment", type="string", nullable=true)
     */
    protected $environment;
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set subject
     *
     * @param string $subject
     * @return Email
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }
    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }
    /**
     * Set fromEmail
     *
     * @param string $fromEmail
     * @return Email
     */
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;
        return $this;
    }
    /**
     * Get fromEmail
     *
     * @return string
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }
    /**
     * Set toEmail
     *
     * @param string $toEmail
     * @return Email
     */
    public function setToEmail($toEmail)
    {
        $this->toEmail = $toEmail;
        return $this;
    }
    /**
     * Get toEmail
     *
     * @return string
     */
    public function getToEmail()
    {
        return $this->toEmail;
    }
    /**
     * @return string
     */
    public function getCcEmail()
    {
        return $this->ccEmail;
    }
    /**
     * @param string $ccEmail
     */
    public function setCcEmail($ccEmail)
    {
        $this->ccEmail = $ccEmail;
    }
    /**
     * @return string
     */
    public function getBccEmail()
    {
        return $this->bccEmail;
    }
    /**
     * @param string $bccEmail
     */
    public function setBccEmail($bccEmail)
    {
        $this->bccEmail = $bccEmail;
    }
    /**
     * @return string
     */
    public function getReplyToEmail()
    {
        return $this->replyToEmail;
    }
    /**
     * @param string $replyToEmail
     * @return Email
     */
    public function setReplyToEmail($replyToEmail)
    {
        $this->replyToEmail = $replyToEmail;
        return $this;
    }
    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
    /**
     * @return \Swift_Mime_Message
     */
    public function getMessage()
    {
        return unserialize(base64_decode($this->message));
    }
    /**
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = base64_encode(serialize($message));
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
    /**
     * @return \DateTime
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }
    /**
     * @param \DateTime $sentAt
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;
    }
    /**
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
    /**
     * @param string $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }
    /**
     * @return string
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
    /**
     * @param string $updatedBy
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }
    /**
     * @return \Swift_Message
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
    /**
     * @param \Swift_Message $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }
    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
    /**
     * @return string
     */
    public function getRetries()
    {
        return $this->retries;
    }
    /**
     * @param string $retries
     */
    public function setRetries($retries)
    {
        $this->retries = $retries;
    }
    
    public function getEnvironment() {
        return $this->environment;
    }

    public function setEnvironment($environment) {
        $this->environment = $environment;
        return $this;
    }
}
