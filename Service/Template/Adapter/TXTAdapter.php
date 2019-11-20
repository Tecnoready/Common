<?php

namespace Tecnoready\Common\Service\Template\Adapter;

use Tecnoready\Common\Service\Template\AdapterInterface;
use Tecnoready\Common\Model\Template\TemplateInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use RuntimeException;

/**
 * Adaptador para exportar a txt
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class TXTAdapter implements AdapterInterface
{
    public function render(TemplateInterface $template, array $variables)
    {
        return true;
    }
    
    public function compile($filename, $spreadsheet, array $parameters)
    {
        return true;
    }

    public function getDefaultParameters()
    {
        return [];
    }

    public function getExtension()
    {
        return TemplateInterface::TYPE_TXT;
    }
}
