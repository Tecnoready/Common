<?php

namespace Tecnoready\Common\Model\Traits;

/**
 * Data extra en forma de array
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
trait ExtraDataTrait 
{
    /**
     * Se debe inicializar la variable $extraData en un array
     *  public function __construct() {
            $this->extraData = [];
        }
     */
    
    /**
     * Informacion extra
     * @var string
     * @ORM\Column(name="extra_data",type="json_array",nullable=false)
     */
    protected $extraData;
    
    public function setExtraData($key,$value)
    {
        $this->extraData[$key] = $value;
    }
    public function setExtras(array $extra)
    {
        $this->extraData = $extra;
    }
    
    public function getExtraData($key,$default = null)
    {
        if(isset($this->extraData[$key])){
            $default = $this->extraData[$key];
        }
        return $default;
    }
}
