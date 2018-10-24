<?php

namespace Tecnoready\Common\Model\Email;

/**
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface ComponentInterface {
    const TYPE_COMPONENT_BASE = "base";
    const TYPE_COMPONENT_HEADER = "header";
    const TYPE_COMPONENT_BODY = "body";
    const TYPE_COMPONENT_FOOTER = "footer";
}
