<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use PHPCR\NodeInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Contenido estatico
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @PHPCR\MappedSuperclass()
 */
class StaticContent
{
    /**
     * @PHPCR\Id
     */
    protected $id;
    
    /**
     * The language this document currently is in
     * @PHPCR\Locale
     */
    private $locale;
    
    /**
     * PHPCR node.
     *
     * @var NodeInterface
     * @PHPCR\Node
     */
    protected $node;
    
    /**
     * @var MenuNode[]
     */
    protected $menuNodes;
    
    public function __construct()
    {
        $this->menuNodes = new ArrayCollection();
    }
    
     /**
     * {@inheritdoc}
     */
    public function addMenuNode(NodeInterface $menu)
    {
        $this->menuNodes->add($menu);
    }

    /**
     * {@inheritdoc}
     */
    public function removeMenuNode(NodeInterface $menu)
    {
        $this->menuNodes->removeElement($menu);
    }

    /**
     * {@inheritdoc}
     */
    public function getMenuNodes()
    {
        return $this->menuNodes;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }
    
}
