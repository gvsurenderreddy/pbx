<?php

namespace VoIP\PBX\RealTimeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Extension
 *
 * @ORM\Table(name="ast_extensions")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Extension
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
     * @ORM\Column(name="context", type="string", length=255)
     */
    private $context;

    /**
     * @var string
     *
     * @ORM\Column(name="exten", type="string", length=20)
     */
    private $exten;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer")
     */
    private $priority;

    /**
     * @var string
     *
     * @ORM\Column(name="app", type="string", length=20)
     */
    private $app;

    /**
     * @var string
     *
     * @ORM\Column(name="appdata", type="string", length=128)
     */
    private $appdata;
	
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
     * @return Extension
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
     * @return Extension
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
     * Set context
     *
     * @param string $context
     * @return Extension
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string 
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set exten
     *
     * @param string $exten
     * @return Extension
     */
    public function setExten($exten)
    {
        $this->exten = $exten;

        return $this;
    }

    /**
     * Get exten
     *
     * @return string 
     */
    public function getExten()
    {
        return $this->exten;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return Extension
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set app
     *
     * @param string $app
     * @return Extension
     */
    public function setApp($app)
    {
        $this->app = $app;

        return $this;
    }

    /**
     * Get app
     *
     * @return string 
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * Set appdata
     *
     * @param string $appdata
     * @return Extension
     */
    public function setAppdata($appdata)
    {
        $this->appdata = $appdata;

        return $this;
    }

    /**
     * Get appdata
     *
     * @return string 
     */
    public function getAppdata()
    {
        return $this->appdata;
    }
}
