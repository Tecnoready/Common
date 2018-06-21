<?php

namespace Tecnoready\Common\Model\Email;

/**
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface EmailQueueInterface {
    const STATUS_NOT_SENT = "not_sent";
    const STATUS_SENT = "sent";
    const STATUS_FAIL = "fail";
    
    const ATTACH_DOCUMENTS = "attach_documents";
}
