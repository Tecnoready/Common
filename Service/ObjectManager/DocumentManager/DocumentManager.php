<?php

namespace Tecnoready\Common\Service\ObjectManager\DocumentManager;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Tecnoready\Common\Service\ObjectManager\DocumentManager\Adapter\DocumentAdapterInterface;

/**
 * Administrador de documentos
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DocumentManager
{
    /**
     * Opciones de configuracion
     * @var array
     */
    private $options;
    
    /**
     * Adaptador
     * @var DocumentAdapterInterface 
     */
    private $adapter;
    
    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "debug" => false,
            "allowed_extensions" => ["txt","zip","rar","docx","doc","xls","xlsx","png","jpeg","jpg"],
        ]);
        $this->options = $resolver->resolve($options);
    }
    
    /**
     * Establece el adaptador a usar para consultas
     * @param ExporterAdapterInterface $adapter
     * @return $this
     * @required
     */
    public function setAdapter(DocumentAdapterInterface $adapter) 
    {
        $this->adapter = $adapter;
        return $this;
    }
}
