<?php

namespace Tecnoready\Common\Model\Configuration;

/**
 * TraceableTrait
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class TraceableTrait 
{
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }
    
    /**
     * Set createdFromIp
     *
     * @param string $createdFromIp
     * @return Payment
     */
    public function setCreatedFromIp($createdFromIp) {
        $this->createdFromIp = $createdFromIp;

        return $this;
    }

    /**
     * Get createdFromIp
     *
     * @return string 
     */
    public function getCreatedFromIp() {
        return $this->createdFromIp;
    }

    /**
     * Set updatedFromIp
     *
     * @param string $updatedFromIp
     * @return Payment
     */
    public function setUpdatedFromIp($updatedFromIp) {
        $this->updatedFromIp = $updatedFromIp;

        return $this;
    }

    /**
     * Get updatedFromIp
     *
     * @return string 
     */
    public function getUpdatedFromIp() {
        return $this->updatedFromIp;
    }
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return BankAccount
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return BankAccount
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }
}
