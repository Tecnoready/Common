<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Spool\ORM;

use Tecnoready\Common\Model\Email\ORM\EmailRepository;
use Swift_Transport;
use Tecnoready\Common\Model\Email\EmailInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Trait de spool de base de datos
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
trait DatabaseSpoolTrait
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var boolean
     */
    protected $keepSentMessages;

    /**
     * @var string
     */
    private $environment;

    public function __construct(EntityManagerInterface $em,$entityClass, $environment, $keepSentMessages = false)
    {
        $this->em = $em;
        
        $this->keepSentMessages = $keepSentMessages;
        $obj = new $entityClass;
        if (!$obj instanceof EmailInterface) {
            throw new \InvalidArgumentException("The entity class '{$entityClass}'' does not extend from EmailInterface");
        }
        $this->entityClass = $entityClass;
        $this->environment = $environment;
    }

    /**
     * Starts this Spool mechanism.
     */
    public function start()
    {
        // TODO: Implement start() method.
    }

    /**
     * Stops this Spool mechanism.
     */
    public function stop()
    {
        // TODO: Implement stop() method.
    }

    /**
     * Tests if this Spool mechanism has started.
     *
     * @return bool
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * Sends messages using the given transport instance.
     *
     * @param Swift_Transport $transport A transport instance
     * @param string[] $failedRecipients An array of failures by-reference
     *
     * @return int     The number of sent emails
     */
    public function flushQueue(Swift_Transport $transport, &$failedRecipients = null)
    {
        if (!$transport->isStarted()) {
            $transport->start();
        }
        $count = 0;
        $limit = $this->getMessageLimit();
        $limit = $limit > 0 ? $limit : null;
        $this->em->getRepository($this->entityClass)->updateTruncatedMessages($this->environment);

        $emails = $this->em->getRepository($this->entityClass)->getEmailQueue($this->environment, $limit);
        if (!count($emails)) {
            return 0;
        }
        $logger = new \Swift_Plugins_Loggers_ArrayLogger();
        $transport->registerPlugin(new \Swift_Plugins_LoggerPlugin($logger));

        $failedRecipients = (array) $failedRecipients;
        $count = 0;
        $time = time();
        $messagesExpired = [];
        $expired = false;
        foreach ($emails as $email) {
            if ($expired === true) {
                $messagesExpired[] = $email->getId();
                continue;
            }
            try {
                /* @var $message \Swift_Mime_Message */
                $message = $email->getMessage();
                if (!$message) {
                    throw new \Swift_SwiftException('The email was not sent, by no unserialize.');
                }
                $count_ = $transport->send($message, $failedRecipients);
                if ($count_ > 0) {
                    $this->em->getRepository($this->entityClass)->markCompleteSending($email);
                    $count += $count_;
                } else {
                    throw new \Swift_SwiftException('The email was not sent.');
                }
            } catch (\Exception $ex) {
                $message = $ex->getMessage() . " - " . $logger->dump();
                $this->em->getRepository($this->entityClass)->markFailedSending($email, $message);
            }
            if ($this->getTimeLimit() && (time() - $time) >= $this->getTimeLimit()) {
                $expired = true;
            }
        }
        if (count($messagesExpired) > 0) {
            $this->em->getRepository($this->entityClass)->markExpiredToReady($messagesExpired);
        }
        return $count;
    }

    protected function postQueueMessage($message)
    {
        $email = new $this->entityClass;
        $email->setFromEmail(implode('; ', array_keys($message->getFrom())));
        if ($message->getTo() !== null) {
            $email->setToEmail(implode('; ', array_keys($message->getTo())));
        }
        if ($message->getCc() !== null) {
            $email->setCcEmail(implode('; ', array_keys($message->getCc())));
        }
        if ($message->getBcc() !== null) {
            $email->setBccEmail(implode('; ', array_keys($message->getBcc())));
        }
        if ($message->getReplyTo() !== null) {
            $email->setReplyToEmail(implode('; ', array_keys($message->getReplyTo())));
        }
        $email->setBody($message->getBody());
        $email->setSubject($message->getSubject());
        $email->setMessage($message);
        $email->setEnvironment($this->environment);

        $this->em->getRepository($this->entityClass)->addEmail($email);

        return true;
    }

}
