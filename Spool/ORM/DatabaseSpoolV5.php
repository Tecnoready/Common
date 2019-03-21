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

use Swift_Mime_Message;

/**
 * Spool en base de datos (Swift v5.4.x)
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DatabaseSpoolV5 extends \Swift_ConfigurableSpool
{
    use DatabaseSpoolTrait;

    /**
     * Queues a message.
     *
     * @param Swift_Mime_Message $message The message to store
     *
     * @return bool    Whether the operation has succeeded
     */
    public function queueMessage(Swift_Mime_Message $message)
    {
        $this->postQueueMessage($message);
    }

}
