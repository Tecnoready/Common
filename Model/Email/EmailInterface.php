<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\Email;

/**
 * Description of EmailInterface
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface EmailInterface {
    const STATUS_FAILED = 'FAILED';
    const STATUS_READY = 'READY';
    const STATUS_PROCESSING = 'PROCESSING';
    const STATUS_COMPLETE = 'COMPLETE';
    const STATUS_CANCELLED = 'CANCELLED';
    
    public function setSubject($subject);
    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject();
    /**
     * Set fromEmail
     *
     * @param string $fromEmail
     * @return Email
     */
    public function setFromEmail($fromEmail);
    /**
     * Get fromEmail
     *
     * @return string
     */
    public function getFromEmail();
    /**
     * Set toEmail
     *
     * @param string $toEmail
     * @return Email
     */
    public function setToEmail($toEmail);
    /**
     * Get toEmail
     *
     * @return string
     */
    public function getToEmail();
    /**
     * @return string
     */
    public function getCcEmail();
    /**
     * @param string $ccEmail
     */
    public function setCcEmail($ccEmail);
    /**
     * @return string
     */
    public function getBccEmail();
    /**
     * @param string $bccEmail
     */
    public function setBccEmail($bccEmail);
    /**
     * @return string
     */
    public function getReplyToEmail();
    /**
     * @param string $replyToEmail
     * @return Email
     */
    public function setReplyToEmail($replyToEmail);
    /**
     * @return string
     */
    public function getBody();
    /**
     * @param string $body
     */
    public function setBody($body);
    /**
     * @return \Swift_Mime_Message
     */
    public function getMessage();
    /**
     * @param \Swift_Mime_Message $message
     */
    public function setMessage(\Swift_Mime_Message $message);
    /**
     * @return \DateTime
     */
    public function getCreatedAt();
    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt);
    /**
     * @return \DateTime
     */
    public function getUpdatedAt();
    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt);
    /**
     * @return \DateTime
     */
    public function getSentAt();
    /**
     * @param \DateTime $sentAt
     */
    public function setSentAt($sentAt);
    /**
     * @return string
     */
    public function getCreatedBy();
    /**
     * @param string $createdBy
     */
    public function setCreatedBy($createdBy);
    /**
     * @return string
     */
    public function getUpdatedBy();
    /**
     * @param string $updatedBy
     */
    public function setUpdatedBy($updatedBy);
    /**
     * @return \Swift_Message
     */
    public function getErrorMessage();
    /**
     * @param \Swift_Message $errorMessage
     */
    public function setErrorMessage($errorMessage);
    /**
     * @return string
     */
    public function getStatus();
    /**
     * @param string $status
     */
    public function setStatus($status);
    /**
     * @return string
     */
    public function getRetries();
    /**
     * @param string $retries
     */
    public function setRetries($retries);
    public function getEnvironment();
    public function setEnvironment($environment);
}
