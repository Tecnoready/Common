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

/**
 * Correo electronico
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @PHPCR\Document(translator="attribute",versionable="full")
 */
class EmailContent extends StaticContent
{
    /** @PHPCR\VersionName */
    protected $versionName;

    /** @PHPCR\VersionCreated */
    protected $versionCreated;
    
    /**
     * @PHPCR\ReferenceOne
     */
    protected $base;
    
    /**
     * @PHPCR\ReferenceOne
     */
    protected $header;
    
    /**
     * @PHPCR\ReferenceOne
     */
    protected $footer;
    
    public function getBase() {
        return $this->base;
    }

    public function getHeader() {
        return $this->header;
    }

    public function getFooter() {
        return $this->footer;
    }

    public function setBase($base) {
        $this->base = $base;
        return $this;
    }

    public function setHeader($header) {
        $this->header = $header;
        return $this;
    }

    public function setFooter($footer) {
        $this->footer = $footer;
        return $this;
    }
}
