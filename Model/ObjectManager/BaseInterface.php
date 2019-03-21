<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\ObjectManager;

use DateTime;

/**
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface BaseInterface
{
    public function setDescription($description);
    
    public function getDescription();
    
    public function setUser($user);
    
    public function getUser();
    
    public function setCreatedFromIp($createdFromIp);
    
    public function getCreatedFromIp();
    
    public function setCreatedAt(DateTime $createdAt);
    
    public function getCreatedAt();
    
    public function getId();
    
    public function setId($id);
    
    public function getUserAgent();

    public function setUserAgent($userAgent);
    
    public function getObjectId();

    public function setObjectId($objectId);
    
    public function getObjectType();

    public function setObjectType($objectType);
}
