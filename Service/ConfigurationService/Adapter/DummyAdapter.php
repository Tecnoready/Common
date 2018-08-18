<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\ConfigurationService\Adapter;

/**
 * Adaptador para pruebas
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DummyAdapter implements ConfigurationAdapterInterface {
    public function createNew() {
        return new \Tecnocreaciones\Bundle\ToolsBundle\Entity\Configuration\Configuration();
    }

    public function find($key) {
    }

    public function findAll() {
        
    }

    public function flush() {
        
    }

    public function persist(\Tecnoready\Common\Model\Configuration\BaseEntity\ConfigurationInterface $configuration) {
        
    }

    public function update($key, $value, $description, $wrapperName) {
        
    }

}
