<?php

namespace Tecnoready\Common\Model\ShowBuilder;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tecnoready\Common\Model\ShowBuilder\Title;
use Tecnoready\Common\Model\ShowBuilder\Item;
use JMS\Serializer\Annotation as JMS;

/**
 * Contenido de show
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class Content implements \JsonSerializable
{
    use TraitTransformers;
    
    /**
     * @var array
     * @JMS\Expose
     */
    private $items;
    
    public function __construct()
    {
        $this->items = [];
    }
    
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Añade un titulo
     * @param type $label
     * @param type $icon
     * @param array $options
     * @return $this
     */
    public function addSubTitle($label,$icon = null,array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "sub_title" => null,
        ]);
        $options = $resolver->resolve($options);
        
        $label = $this->transformValue($label);
        
        $title = new Title();
        $title->setText($label);
        $title->setSubTitle($options["sub_title"]);
        $title->setIcon($icon);
        $this->items[] = $title;
        
        return $this;
    }
    
    /**
     * Añade un item (linea)
     * @param type $value
     * @param array $options
     * @return $this
     */
    public function addItem($value,array $options = [])
    {
        //Se ignoran valores vacio
        if(empty($value)){
            return $this;
        }
        $value = $this->transformValue($value);
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "label" => null
        ]);
        $options = $resolver->resolve($options);
        
        if(!empty($options["label"])){
            $value = sprintf("%s: %s",$options["label"],$value);
        }
        
        $item = new Item();
        $item->setText($value);
        $this->items[] = $item;
        
        return $this;
    }
    
    /**
     * Añade un custom widget del usuario
     * @param ModelShowWidget $widget
     * @return $this
     */
    public function addWidget(ModelShowWidget $widget) {
        $this->items[] = $widget;
        return $this;
    }
    
    /**
     * Se agrega una imagen guardada con UploaderBundle
     * @param type $entity
     * @param array $options
     * @return $this
     */
    public function addImage($entity,array $options = [])
    {
        //Se ignoran valores vacio
        if(empty($entity)){
            return $this;
        }
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "label" => null,
            "default" => null,
            "property" => null,
            "properties" => []
        ]);
        $options = $resolver->resolve($options);
        
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "width" => 50,
            "height" => 50,
        ]);
        $properties = $resolver->resolve($options["properties"]);
        
        $entity = $this->transformValue($entity,[
            "property" => $options["property"],
            "default" => $options["default"],
        ]);
        
        $item = new Image();
        $item->setIcon($entity);
        $item->setText($options["label"]);
        $item->setWidth($properties["width"]);
        $item->setHeight($properties["height"]);
        
        $this->items[] = $item;
        return $this;
    }
    
    public function jsonSerialize() {
        $arr = get_object_vars( $this );
        
        return $arr;
    }
}
