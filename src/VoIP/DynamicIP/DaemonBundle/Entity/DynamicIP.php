<?php

namespace VoIP\DynamicIP\DaemonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DynamicIP
 *
 * @ORM\Table(name="dynamic_ip")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class DynamicIP
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, unique=true)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="previous_ip", type="string", length=255, nullable=true)
     */
    private $previousIp;

    /**
     * @var string
     *
     * @ORM\Column(name="current_ip", type="string", length=255, nullable=true)
     */
    private $currentIp;
	
	/**
	 * @ORM\PrePersist
	 */
	public function prePersist()
	{
		$this->createdAt = new \DateTime();
	    $this->updatedAt = new \DateTime();
	}
	
	/**
	 * @ORM\PreUpdate
	 */
	public function preUpdate()
	{
	    $this->updatedAt = new \DateTime();
	}


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return DynamicIP
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return DynamicIP
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return DynamicIP
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set previousIp
     *
     * @param string $previousIp
     * @return DynamicIP
     */
    public function setPreviousIp($previousIp)
    {
        $this->previousIp = $previousIp;

        return $this;
    }

    /**
     * Get previousIp
     *
     * @return string 
     */
    public function getPreviousIp()
    {
        return $this->previousIp;
    }

    /**
     * Set currentIp
     *
     * @param string $currentIp
     * @return DynamicIP
     */
    public function setCurrentIp($currentIp)
    {
        $this->currentIp = $currentIp;

        return $this;
    }

    /**
     * Get currentIp
     *
     * @return string 
     */
    public function getCurrentIp()
    {
        return $this->currentIp;
    }
}
