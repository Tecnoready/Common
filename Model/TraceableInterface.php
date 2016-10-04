<?php

/*
 * This file is part of the Witty Growth C.A. - J406095737 package.
 * 
 * (c) www.mpandco.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model;

/**
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface TraceableInterface {
    
    public function getDeletedAt();

    public function setDeletedAt($deletedAt);
    
    /**
     * Set createdFromIp
     *
     * @param string $createdFromIp
     */
    public function setCreatedFromIp($createdFromIp);

    /**
     * Get createdFromIp
     *
     * @return string 
     */
    public function getCreatedFromIp();

    /**
     * Set updatedFromIp
     *
     * @param string $updatedFromIp
     */
    public function setUpdatedFromIp($updatedFromIp);

    /**
     * Get updatedFromIp
     *
     * @return string 
     */
    public function getUpdatedFromIp();
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return BankAccount
     */
    public function setCreatedAt($createdAt);

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt();

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return BankAccount
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt();
}
