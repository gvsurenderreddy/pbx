<?php

namespace VoIP\Company\SubscriptionsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subscription
 *
 * @ORM\Table(name="structure_out_group")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class OutGroup
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
     * @var \DateTime
     *
     * @ORM\Column(name="last_attribution", type="datetime", nullable=true)
     */
    private $lastAttribution;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=8)
     */
    private $hash;
	
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = true;
	
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_public", type="boolean")
     */
    private $isPublic = false;
	
	/**
     * @ORM\OneToMany(targetEntity="\VoIP\Company\StructureBundle\Entity\Company", mappedBy="outGroup")
	 * @ORM\OrderBy({"name" = "ASC"})
     */
    private $companies;
	
	/**
	 * @ORM\ManyToMany(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\OutLine", inversedBy="outGroups")
	 * @ORM\JoinTable(name="structure_outgroup_has_outline",
	 *      joinColumns={@ORM\JoinColumn(name="outgroup_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="outline_id", referencedColumnName="id")}
	 *      )
	 * @ORM\OrderBy({"name" = "ASC"})
	 */
	protected $outLines;
	
	/**
	 * @ORM\PrePersist
	 */
	public function prePersist()
	{
		$this->createdAt = new \DateTime();
	    $this->updatedAt = new \DateTime();
		if (!$this->hash) $this->generateHash();
	}
	
	/**
	 * @ORM\PreUpdate
	 */
	public function preUpdate()
	{
	    $this->updatedAt = new \DateTime();
		if (!$this->hash) $this->generateHash();
	}

	public function generateHash()
	{
		$this->hash = hash('crc32b', uniqid('', true));
	}
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->companies = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rates = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return OutGroup
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
     * @return OutGroup
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
     * Set lastAttribution
     *
     * @param \DateTime $lastAttribution
     * @return OutGroup
     */
    public function setLastAttribution($lastAttribution)
    {
        $this->lastAttribution = $lastAttribution;

        return $this;
    }

    /**
     * Get lastAttribution
     *
     * @return \DateTime 
     */
    public function getLastAttribution()
    {
        return $this->lastAttribution;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return OutGroup
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return OutGroup
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Add companies
     *
     * @param \VoIP\Company\StructureBundle\Entity\Company $companies
     * @return OutGroup
     */
    public function addCompany(\VoIP\Company\StructureBundle\Entity\Company $companies)
    {
        $this->companies[] = $companies;

        return $this;
    }

    /**
     * Remove companies
     *
     * @param \VoIP\Company\StructureBundle\Entity\Company $companies
     */
    public function removeCompany(\VoIP\Company\StructureBundle\Entity\Company $companies)
    {
        $this->companies->removeElement($companies);
    }

    /**
     * Get companies
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * Set isPublic
     *
     * @param boolean $isPublic
     * @return OutGroup
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * Get isPublic
     *
     * @return boolean 
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Add outLines
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\OutLine $outLines
     * @return OutGroup
     */
    public function addOutLine(\VoIP\Company\SubscriptionsBundle\Entity\OutLine $outLines)
    {
        $this->outLines[] = $outLines;

        return $this;
    }

    /**
     * Remove outLines
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\OutLine $outLines
     */
    public function removeOutLine(\VoIP\Company\SubscriptionsBundle\Entity\OutLine $outLines)
    {
        $this->outLines->removeElement($outLines);
    }

    /**
     * Get outLines
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOutLines()
    {
        return $this->outLines;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return OutGroup
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
}
