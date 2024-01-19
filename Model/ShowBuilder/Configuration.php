<?php

namespace Tecnoready\Common\Model\ShowBuilder;

use JMS\Serializer\Annotation as JMS;

/**
 * Configuracion del show builder
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class Configuration implements \JsonSerializable {
    
    /**
     * Añadir refreshview
     * @var bool
     * @JMS\Expose
     */
    private $addRefreshView = true;
    
    /**
     * Añadir scroll view
     * @var bool
     * @JMS\Expose
     */
    private $addScrollView = true;
    
    public function getAddRefreshView(): bool {
        return $this->addRefreshView;
    }

    public function getAddScrollView(): bool {
        return $this->addScrollView;
    }

    public function setAddRefreshView(bool $addRefreshView) {
        $this->addRefreshView = $addRefreshView;
        return $this;
    }

    public function setAddScrollView(bool $addScrollView) {
        $this->addScrollView = $addScrollView;
        return $this;
    }
        
    public function jsonSerialize() {
        $arr = get_object_vars( $this );
        
        return $arr;
    }

}
