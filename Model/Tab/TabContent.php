<?php

namespace Tecnoready\Common\Model\Tab;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Contenido de tab
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class TabContent
{
    const TAB = "_tbx";
    
    private $id;
    private $order;
    private $options;
    private $active = false;
    
    /**
     * @var Tab
     */
    private $tabRoot;

    public function __construct(array $options = []) 
    {
        $this->setOptions($options);
    }
    
    /**
     * Opciones de la tab
     * @param array $options
     * @return \Atechnologies\ToolsBundle\Model\Core\Tab\TabContent
     */
    public function setOptions(array $options = []) 
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "add_content_div" => true,
            "url" => null,
            "title" => null,
            "icon" => null,
        ]);
        $resolver->setRequired(["url","template"]);
        $this->options = $resolver->resolve($options);
        
        return $this;
    }
    
    /**
     * Busca una opcion
     * @param type $name
     * @return type
     */
    public function getOption($name) 
    {
        $value = $this->options[$name];
        if($value === null && $name === "url"){
            $value = $this->tabRoot->getRootUrl();
        }
        return $value;
    }
    
    /**
     * getUrl
     * @return url
     */
    public function getUrl() 
    {
        return $this->options["url"];
    }

    /**
     * getOrder
     * @return order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * setOrder
     * @param  order
     */
    public function setOrder($order) 
    {
        $this->order = $order;
        return $this;
    }

    /**
     * getId
     * @return id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * setId
     * @param  id
     */
    public function setId($id) 
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * getActive
     * @return active
     */
    public function getActive() 
    {
        return $this->active;
    }
    
    /**
     * setActive
     * @param  [type]
     */
    public function setActive($active) 
    {
        $this->active = $active;
        return $this;
    }

    /**
     * setIcon
     * @param  icon
     */
    public function setIcon($icon) 
    {
        $this->options["icon"] = $icon;

        return $this;
    }

    /**
     * getIcon
     * @return icon
     */
    public function getIcon() 
    {
        return $this->options["icon"];
    }
    
    public function getTitle() {
        return $this->options["title"];
    }

    public function setTitle($title) {
        $this->options["title"] = $title;
        return $this;
    }
    
    public function setTabRoot(Tab $tabRoot)
    {
        $this->tabRoot = $tabRoot;
        return $this;
    }
                
    /**
     * Representacion de la tab en arary
     * @return array
     */
    public function toArray() 
    {
        $data = [
            "id" => $this->id,
            "title" => $this->options["title"],
            "active" => $this->active,
            "icon" => $this->options["icon"],
            "options" => $this->options,
        ];

        return $data;
    }
}
