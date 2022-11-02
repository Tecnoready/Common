<?php

namespace Tecnoready\Common\Service\ShowBuilder\DataTransformer;

use Tecnoready\Common\Service\ShowBuilder\DataTransformerInterface;
use Tecnocreaciones\Bundle\ToolsBundle\Service\ImageManager;

/**
 * Genera la ruta completa
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class VichImageTransformer implements DataTransformerInterface
{
    /**
     * @var ImageManager 
     */
    private $imageManager;
    
    public function getName()
    {
        return "vich_image";
    }

    public function supports($value,array $options = [])
    {
        return is_object($value) && isset($options["property"]);
    }

    public function transform($entity, array $options = [])
    {
        if(isset($options["property"])){
            $url = $this->imageManager->generateUrl($entity, $options["property"],[
                "default" => $options["default"]
            ]);
            $entity = $url;
        }
        return $entity;
    }
    
    /**
     * @required
     * @param ImageManager $imageManager
     * @return $this
     */
    public function setImageManager(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
        return $this;
    }
}
