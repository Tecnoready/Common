<?php

namespace Tecnoready\Common\Service\Email\Adapter;

/**
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface EmailAdapterInterface {
    
    /**
     * 
     * @param \Tecnoready\Common\Model\Email\EmailTemplateInterface $id
     */
    public function find($id);
    
    /*
     * Guarda los cambios en la base de datos
     */
    public function flush();
    
    public function persist($entity);
    
    public function remove($entity);
    
    /**
     * 
     */
    public function createEmailQueue();
    
    /**
     * @return \Tecnoready\Common\Model\Email\ComponentInterface
     */
    public function createComponent();
    
    /**
     * @return \Tecnoready\Common\Model\Email\ORM\ModelEmailTemplate
     */
    public function createEmailTemplate();
}
