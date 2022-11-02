<?php

namespace Tecnoready\Common\Model\ShowBuilder;

use JMS\Serializer\Annotation as JMS;

/**
 * Vista de show dinamica
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class ShowView
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
     * Contenido
     * @var Content
     * @JMS\Expose
     * @JMS\SerializedName("content")
     */
    private $content;
    
    
    public function getTitle(): Title
    {
        return $this->title;
    }

    public function getContent(): Content
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

    
}
