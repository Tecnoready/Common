<?php

namespace Tecnoready\Common\Service\ShowBuilder;

use JMS\Serializer\SerializerInterface;
use Tecnoready\Common\Model\ShowBuilder\Title;
use Tecnoready\Common\Model\ShowBuilder\Item;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tecnoready\Common\Service\ShowBuilder\DataTransformer\DateTimeTransformer;
use Tecnoready\Common\Service\ShowBuilder\DataTransformerInterface;
use InvalidArgumentException;
use Tecnoready\Common\Model\ShowBuilder\ShowView;

/**
 * Constructor de show dinamico
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ShowBuilderManager
{
    use \Tecnoready\Common\Model\ShowBuilder\TraitTransformers;
    
    /**
     * @var SerializerInterface 
     */
    private $serializer;
    
    /**
     * Data a serializar
     * @var ShowView
     */
    private $showView;
    
    public function __construct()
    {
        $this->transformers = [];
        $this->addTransformer(new DateTimeTransformer());
//        $this->addTransformer(new VichImageTransformer());
    }
    
    public function addTransformer(DataTransformerInterface $transformer)
    {
        if(isset($this->transformers[$transformer->getName()])){
            throw new InvalidArgumentException(sprintf("El transformador '%s' ya fue agregado.",$transformer->getName()));
        }
        $this->transformers[$transformer->getName()] = $transformer;
        
        return $this;
    }
    
    /**
     * Inicia el constructor
     * @return ShowView
     */
    public function start()
    {
        $this->showView = new ShowView($this->serializer);
        $this->showView->content()->setTransformers($this->transformers);
        return $this->showView;
    }
    
    /**
     * Finaliza la construccion y lo devuelve serializado
     * @return string
     */
    public function end()
    {
//        $data = $this->serializer->serialize($this->builder,"json");
//        $this->builder = [];
        $this->showView->end();
        $showView = $this->showView;
        $this->showView = null;
        $data = $this->serializer->serialize($showView,"json");
        return $data;
    }
    
    /**
     * Retorna el contenido del show
     * @return \Tecnoready\Common\Model\ShowBuilder\Content
     */
    public function content()
    {
        return $this->showView->content();
    }
    
    /**
     * Añade un titulo
     * @param type $label
     * @param type $icon
     * @param array $options
     * @return $this
     */
    public function addTitle($label,$icon = null,array $options = [])
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
        $this->showView->setTitle($title);
        
//        $this->builder[] = $title;
        return $this;
    }
    
    /**
     * Añade un item (linea)
     * @param type $label
     * @param array $options
     * @return $this
     */
//    public function addItem($value,array $options = [])
//    {
//        $value = $this->transformValue($value);
//        $resolver = new OptionsResolver();
//        $resolver->setDefaults([
//            "label" => null
//        ]);
//        $options = $resolver->resolve($options);
//        
//        if(!empty($options["label"])){
//            $value = sprintf("%s: %s",$options["label"],$value);
//        }
//        
//        $item = new Item();
//        $item->setText($value);
//        $this->builder[] = $item;
//        
//        return $this;
//    }
    
    /**
     * @required
     * @param SerializerInterface $serializer
     * @return $this
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        return $this;
    }
}
