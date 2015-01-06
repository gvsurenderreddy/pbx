<?php

namespace VoIP\Company\SubscriptionsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subscription
 *
 * @ORM\Table(name="structure_subscription")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Subscription
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="did", type="string", length=128)
     */
    private $did;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=8, unique=true)
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="voicemail", type="string", length=8)
     */
    private $voicemail;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Company", inversedBy="subscriptions")
	 * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $company;
	
	/**
	 * @ORM\ManyToMany(targetEntity="\VoIP\Company\StructureBundle\Entity\Employee", inversedBy="subscriptions")
	 * @ORM\JoinTable(name="structure_subscription_has_employee",
	 *      joinColumns={@ORM\JoinColumn(name="subscription_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="employee_id", referencedColumnName="id")}
	 *      )
	 * @ORM\OrderBy({"name" = "ASC"})
	 */
	protected $employees;

    /**
     * @ORM\ManyToMany(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\Country", inversedBy="subscriptions")
     * @ORM\JoinTable(name="structure_subscription_has_country",
     *      joinColumns={@ORM\JoinColumn(name="subscription_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="country_id", referencedColumnName="id")}
     *      )
     * @ORM\OrderBy({"name" = "ASC"})
     */
    protected $countries;
	
	/**
	 * @ORM\PrePersist
	 */
	public function prePersist()
	{
		$this->setCreatedAt(new \DateTime());
	    $this->setUpdatedAt(new \DateTime());
		$this->setHash(hash('crc32b', uniqid('', true)));
        $this->setVoicemail(false);
	}
	
	/**
	 * @ORM\PreUpdate
	 */
	public function preUpdate()
	{
	    $this->setUpdatedAt(new \DateTime());
	}

	
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->employees = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Subscription
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
     * @return Subscription
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
     * @return Subscription
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
     * Set did
     *
     * @param string $did
     * @return Subscription
     */
    public function setDid($did)
    {
        $this->did = $did;

        return $this;
    }

    /**
     * Get did
     *
     * @return string 
     */
    public function getDid()
    {
        return $this->did;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Subscription
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
     * @return Subscription
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
     * Add employees
     *
     * @param \VoIP\Company\StructureBundle\Entity\Employee $employees
     * @return Subscription
     */
    public function addEmployee(\VoIP\Company\StructureBundle\Entity\Employee $employees)
    {
        $this->employees[] = $employees;

        return $this;
    }

    /**
     * Remove employees
     *
     * @param \VoIP\Company\StructureBundle\Entity\Employee $employees
     */
    public function removeEmployee(\VoIP\Company\StructureBundle\Entity\Employee $employees)
    {
        $this->employees->removeElement($employees);
    }

    /**
     * Get employees
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * Add countries
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\Country $countries
     * @return Subscription
     */
    public function addCountry(\VoIP\Company\SubscriptionsBundle\Entity\Country $countries)
    {
        $this->countries[] = $countries;

        return $this;
    }

    /**
     * Remove countries
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\Country $countries
     */
    public function removeCountry(\VoIP\Company\SubscriptionsBundle\Entity\Country $countries)
    {
        $this->countries->removeElement($countries);
    }

    /**
     * Get countries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCountries()
    {
        return $this->countries;
    }



    /**
     * Set voicemail
     *
     * @param string $voicemail
     * @return Subscription
     */
    public function setVoicemail($voicemail)
    {
        $this->voicemail = $voicemail;

        return $this;
    }

    /**
     * Get voicemail
     *
     * @return string 
     */
    public function getVoicemail()
    {
        return $this->voicemail;
    }
}
