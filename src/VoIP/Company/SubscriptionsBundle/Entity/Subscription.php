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
     * @ORM\Column(name="type", type="string", length=40)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=128)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="secret", type="string", length=255)
     */
    private $secret;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=128)
     */
    private $number;

    /**
     * @var boolean
     *
     * @ORM\Column(name="receive_call", type="boolean")
     */
    private $receiveCall;

    /**
     * @var integer
     *
     * @ORM\Column(name="prefix", type="integer")
     */
    private $prefix;

    /**
     * @var string
     *
     * @ORM\Column(name="host", type="string", length=255)
     */
    private $host;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255)
     */
    private $hash;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Company", inversedBy="subscriptions")
	 * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $company;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\PBX\RealTimeBundle\Entity\SipPeer", inversedBy="subscriptions")
	 * @ORM\JoinColumn(name="ast_sippeer_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $astPeer;
	
	/**
	 * @ORM\ManyToMany(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\Country", inversedBy="subscriptions")
	 * @ORM\JoinTable(name="structure_subscription_has_country",
	 *      joinColumns={@ORM\JoinColumn(name="subscription_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="country_id", referencedColumnName="id")}
	 *      )
	 */
	protected $countries;
	
	/**
     * @ORM\OneToOne(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\DialPlanItem", inversedBy="subscription")
	 * @ORM\JoinColumn(name="dialplan_firstitem_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $dialPlanFirstItem;
	
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
	
	public function getDepth()
	{
		if (!($firstItem = $this->getDialPlanFirstItem())) return 0;
		else return $firstItem->getDepth() + 1;
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
     * Set type
     *
     * @param string $type
     * @return Subscription
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
     * Set username
     *
     * @param string $username
     * @return Subscription
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set secret
     *
     * @param string $secret
     * @return Subscription
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Get secret
     *
     * @return string 
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set number
     *
     * @param string $number
     * @return Subscription
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set receiveCall
     *
     * @param boolean $receiveCall
     * @return Subscription
     */
    public function setReceiveCall($receiveCall)
    {
        $this->receiveCall = $receiveCall;

        return $this;
    }

    /**
     * Get receiveCall
     *
     * @return boolean 
     */
    public function getReceiveCall()
    {
        return $this->receiveCall;
    }

    /**
     * Set host
     *
     * @param string $host
     * @return Subscription
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host
     *
     * @return string 
     */
    public function getHost()
    {
        return $this->host;
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
     * Set astPeer
     *
     * @param \VoIP\PBX\RealTimeBundle\Entity\SipPeer $astPeer
     * @return Subscription
     */
    public function setAstPeer(\VoIP\PBX\RealTimeBundle\Entity\SipPeer $astPeer = null)
    {
        $this->astPeer = $astPeer;

        return $this;
    }

    /**
     * Get astPeer
     *
     * @return \VoIP\PBX\RealTimeBundle\Entity\SipPeer 
     */
    public function getAstPeer()
    {
        return $this->astPeer;
    }

    /**
     * Set prefix
     *
     * @param integer $prefix
     * @return Subscription
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get prefix
     *
     * @return integer 
     */
    public function getPrefix()
    {
        return $this->prefix;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set dialPlanFirstItem
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\DialPlanItem $dialPlanFirstItem
     * @return Subscription
     */
    public function setDialPlanFirstItem(\VoIP\Company\SubscriptionsBundle\Entity\DialPlanItem $dialPlanFirstItem = null)
    {
        $this->dialPlanFirstItem = $dialPlanFirstItem;

        return $this;
    }

    /**
     * Get dialPlanFirstItem
     *
     * @return \VoIP\Company\SubscriptionsBundle\Entity\DialPlanItem 
     */
    public function getDialPlanFirstItem()
    {
        return $this->dialPlanFirstItem;
    }
}