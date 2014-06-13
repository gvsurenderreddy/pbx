<?php

namespace VoIP\Company\StructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Employee
 *
 * @ORM\Table(name="structure_employee")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Employee
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
     * @ORM\Column(name="canceled_at", type="datetime", nullable=true)
     */
    private $canceledAt;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
	
    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255, unique=true)
     */
    private $hash;

    /**
     * @var integer
     *
     * @ORM\Column(name="extension", type="integer")
     */
    private $extension;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="license", type="integer")
     */
    private $license = 10;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = true;
	
    /**
     * @var string
     *
     * @ORM\Column(name="image_url", type="string", length=255, nullable=true)
     */
    private $imageUrl;
    /**
     * @var string
     *
     * @ORM\Column(name="thumb_url", type="string", length=255, nullable=true)
     */
    private $thumbUrl;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Company", inversedBy="employees")
	 * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $company;
	
	/**
	 * @ORM\ManyToMany(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\Subscription", mappedBy="employees")
	 * @ORM\OrderBy({"name" = "ASC"})
	 */
	protected $subscriptions;
	
	/**
     * @ORM\OneToMany(targetEntity="\VoIP\Company\StructureBundle\Entity\EmployeePayment", mappedBy="employee")
	 * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private $payments;

	
	/**
	 * @ORM\ManyToMany(targetEntity="\VoIP\Company\StructureBundle\Entity\Phone", inversedBy="employees")
	 * @ORM\JoinTable(name="structure_employee_has_phone",
	 *      joinColumns={@ORM\JoinColumn(name="employee_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="phone_id", referencedColumnName="id")}
	 *      )
	 * @ORM\OrderBy({"name" = "ASC"})
	 */
	protected $phones;
	
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
	
	public function getStatus()
	{
		if (count($this->getPhones()) == 0) return 'danger';
		elseif(count($this->getPhones()->filter(function($p){
			return $p->getAmiStatusOk();
		})) == 0) return 'warning';
		return 'default';
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
     * @return Employee
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
     * @return Employee
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
     * Set name
     *
     * @param string $name
     * @return Employee
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
     * Set extension
     *
     * @param integer $extension
     * @return Employee
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
     * Set hash
     *
     * @param string $hash
     * @return Employee
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
     * Set company
     *
     * @param \VoIP\Company\StructureBundle\Entity\Company $company
     * @return Employee
     */
    public function setCompany(\VoIP\Company\StructureBundle\Entity\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \VoIP\Company\StructureBundle\Entity\Company 
     */
    public function getCompany()
    {
        return $this->company;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subscriptions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add subscriptions
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\Subscription $subscriptions
     * @return Employee
     */
    public function addSubscription(\VoIP\Company\SubscriptionsBundle\Entity\Subscription $subscriptions)
    {
        $this->subscriptions[] = $subscriptions;

        return $this;
    }

    /**
     * Remove subscriptions
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\Subscription $subscriptions
     */
    public function removeSubscription(\VoIP\Company\SubscriptionsBundle\Entity\Subscription $subscriptions)
    {
        $this->subscriptions->removeElement($subscriptions);
    }

    /**
     * Get subscriptions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Employee
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
     * Add phones
     *
     * @param \VoIP\Company\StructureBundle\Entity\Phone $phones
     * @return Employee
     */
    public function addPhone(\VoIP\Company\StructureBundle\Entity\Phone $phones)
    {
        $this->phones[] = $phones;

        return $this;
    }

    /**
     * Remove phones
     *
     * @param \VoIP\Company\StructureBundle\Entity\Phone $phones
     */
    public function removePhone(\VoIP\Company\StructureBundle\Entity\Phone $phones)
    {
        $this->phones->removeElement($phones);
    }

    /**
     * Get phones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Set imageUrl
     *
     * @param string $imageUrl
     * @return Employee
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get imageUrl
     *
     * @return string 
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set thumbUrl
     *
     * @param string $thumbUrl
     * @return Employee
     */
    public function setThumbUrl($thumbUrl)
    {
        $this->thumbUrl = $thumbUrl;

        return $this;
    }

    /**
     * Get thumbUrl
     *
     * @return string 
     */
    public function getThumbUrl()
    {
        return $this->thumbUrl;
    }

    /**
     * Set license
     *
     * @param integer $license
     * @return Employee
     */
    public function setLicense($license)
    {
        $this->license = $license;

        return $this;
    }

    /**
     * Get license
     *
     * @return integer 
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * Set canceledAt
     *
     * @param \DateTime $canceledAt
     * @return Employee
     */
    public function setCanceledAt($canceledAt)
    {
        $this->canceledAt = $canceledAt;

        return $this;
    }

    /**
     * Get canceledAt
     *
     * @return \DateTime 
     */
    public function getCanceledAt()
    {
        return $this->canceledAt;
    }

    /**
     * Add payments
     *
     * @param \VoIP\Company\StructureBundle\Entity\EmployeePayment $payments
     * @return Employee
     */
    public function addPayment(\VoIP\Company\StructureBundle\Entity\EmployeePayment $payments)
    {
        $this->payments[] = $payments;

        return $this;
    }

    /**
     * Remove payments
     *
     * @param \VoIP\Company\StructureBundle\Entity\EmployeePayment $payments
     */
    public function removePayment(\VoIP\Company\StructureBundle\Entity\EmployeePayment $payments)
    {
        $this->payments->removeElement($payments);
    }

    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPayments()
    {
        return $this->payments;
    }
}
