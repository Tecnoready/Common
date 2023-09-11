<?php

namespace Tecnoready\Common\Model\ShowBuilder;

use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\SerializerInterface;

/**
 * Vista de show dinamica
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class ShowView implements \JsonSerializable
{
    /**
     * Titulo
     * @var Title
     * @JMS\SerializedName("title")
     * @JMS\Expose
     * @JMS\Type("Tecnoready\Common\Model\ShowBuilder\Title")
     */
    private $title;
    /**
     * Opciones extras
     * @var array
     * @JMS\SerializedName("extra_options")
     * @JMS\Expose
     * @JMS\Type("array")
     */
    private $extraOptions;
    
    /**
     * Configuracion
     * @var Configuration
     * @JMS\Expose
     * @JMS\SerializedName("configuration")
     */
    private $configuration;
    
    /**
     * Contenido
     * @var Content
     * @JMS\Expose
     * @JMS\SerializedName("content")
     */
    private $content;
    
    /**
     * @var SerializerInterface 
     * @JMS\Exclude
     */
    private $serializer;
    
    public function __construct(SerializerInterface $serializer) {
        $this->serializer = $serializer;
        $this->configuration = new Configuration();
        $this->extraOptions = [];
    }
    
    public function getTitle(): Title
    {
        return $this->title;
    }

//    public function getContent(): Content
//    {
//        if($this->content === null){
//            $this->content = new Content();
//        }
//        return $this->content;
//    }
    
    public function configuration(): Configuration {
        return $this->configuration;
    }
        
    public function content(): Content
    {
        if($this->content === null){
            $this->content = new Content();
        }
        return $this->content;
    }

    public function setTitle(Title $title)
    {
        $this->title = $title;
        return $this;
    }

    public function setContent(Content $content)
    {
        $this->content = $content;
        return $this;
    }
    
    /**
     * Opcion extra
     * @param type $name
     * @param type $value
     * @return $this
     */
    public function setExtraOption($name,$value) {
        $this->extraOptions[$name] = $value;
        
        return $this;
    }
    
    public function end()
    {
        $this->content()->setTransformers([]);
        
        //return json_encode($this, JSON_PRETTY_PRINT);
        $data = $this->serializer->serialize($this,"json");
        return $data;
    }
    
    public function jsonSerialize() {
        $arr = get_object_vars( $this );
        
        return $arr;
    }

}
