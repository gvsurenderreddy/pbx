<?php

namespace VoIP\Company\StructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phone
 *
 * @ORM\Table(name="structure_phone")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Phone
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
     * @var integer
     *
     * @ORM\Column(name="extension", type="integer")
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=40)
     */
    private $type;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Office", inversedBy="phones")
	 * @ORM\JoinColumn(name="office_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $office;
	
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
     * @return Phone
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
     * @return Phone
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
     * Set extension
     *
     * @param integer $extension
     * @return Phone
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return integer 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Phone
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Phone
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set office
     *
     * @param \VoIP\Company\StructureBundle\Entity\Office $office
     * @return Phone
     */
    public function setOffice(\VoIP\Company\StructureBundle\Entity\Office $office = null)
    {
        $this->office = $office;

        return $this;
    }

    /**
     * Get office
     *
     * @return \VoIP\Company\StructureBundle\Entity\Office 
     */
    public function getOffice()
    {
        return $this->office;
    }
}
