<?php

namespace Tecnoready\Common\Service\Snippet\Adapter;

/**
 * Adaptador de snipped
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface SnippetAdapterInterface
{
    public function persist($entity);
    public function flush();
}
