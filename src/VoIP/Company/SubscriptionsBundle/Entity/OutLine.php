<?php

namespace VoIP\Company\SubscriptionsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subscription
 *
 * @ORM\Table(name="structure_out_line")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class OutLine
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=40, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=128, unique=true)
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
     * @ORM\Column(name="host", type="string", length=255)
     */
    private $host;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=20)
     */
    private $hash;
	
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = true;
	
	/**
     * @ORM\OneToMany(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\Country", mappedBy="outLine")
	 * @ORM\OrderBy({"name" = "ASC"})
     */
    private $countries;
	
	/**
     * @ORM\OneToOne(targetEntity="\VoIP\PBX\RealTimeBundle\Entity\SipPeer", inversedBy="subscription")
	 * @ORM\JoinColumn(name="ast_sippeer_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $astPeer;
	
	/**
	 * @ORM\PrePersist
	 */
	public function prePersist()
	{
		$this->createdAt = new \DateTime();
	    $this->updatedAt = new \DateTime();
		if (!$this->hash) $this->generateHash();
		$this->genRegistrationCode();
	}
	
	/**
	 * @ORM\PreUpdate
	 */
	public function preUpdate()
	{
	    $this->updatedAt = new \DateTime();
		if (!$this->hash) $this->generateHash();
		$this->genRegistrationCode();
	}

	public function generateHash()
	{
		$this->hash = hexdec(hash('crc32b', uniqid('', true)));
	}
	
	public function getDepth()
	{
		if (!($firstItem = $this->getDialPlanFirstItem())) return 0;
		else return $firstItem->getDepth() + 1;
	}
	
	public function genRegistrationCode()
	{
		switch ($this->type) {
			case 'hoiio':
				$this->setRegistrationCode($this->username.':'.$this->secret.'@'.$this->host.'/'.$this->hash);
				break;
			
			default:
				$this->setRegistrationCode($this->username.':'.$this->secret.'@'.$this->host.'/'.$this->hash);
				break;
		}
	}
	
	public function getStatus()
	{
		// TEST HAS_PHONE
		if (count($this->getEmployees()) == 0) {
			return 'warning';
		}
		// TEST CONNECTED_EMPLOYEE
		$test = false;
		foreach ($this->getEmployees() as $employee) {
			if ($employee->getIsActive() && count($employee->getPhones()) > 0) {
				$test = true;
			}
		}
		if (!$test) return 'warning';
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

    /**
     * Set registrationCode
     *
     * @param string $registrationCode
     * @return Subscription
     */
    public function setRegistrationCode($registrationCode)
    {
        $this->registrationCode = $registrationCode;

        return $this;
    }

    /**
     * Get registrationCode
     *
     * @return string 
     */
    public function getRegistrationCode()
    {
        return $this->registrationCode;
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
     * Set voicemail
     *
     * @param \VoIP\Company\VoicemailBundle\Entity\Voicemail $voicemail
     * @return Subscription
     */
    public function setVoicemail(\VoIP\Company\VoicemailBundle\Entity\Voicemail $voicemail = null)
    {
        $this->voicemail = $voicemail;

        return $this;
    }

    /**
     * Get voicemail
     *
     * @return \VoIP\Company\VoicemailBundle\Entity\Voicemail 
     */
    public function getVoicemail()
    {
        return $this->voicemail;
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
     * Set activatedUntil
     *
     * @param \DateTime $activatedUntil
     * @return Subscription
     */
    public function setActivatedUntil($activatedUntil)
    {
        $this->activatedUntil = $activatedUntil;

        return $this;
    }

    /**
     * Get activatedUntil
     *
     * @return \DateTime 
     */
    public function getActivatedUntil()
    {
        return $this->activatedUntil;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Subscription
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
     * Set rateIn
     *
     * @param float $rateIn
     * @return Subscription
     */
    public function setRateIn($rateIn)
    {
        $this->rateIn = $rateIn;

        return $this;
    }

    /**
     * Get rateIn
     *
     * @return float 
     */
    public function getRateIn()
    {
        return $this->rateIn;
    }

    /**
     * Set rateOut
     *
     * @param float $rateOut
     * @return Subscription
     */
    public function setRateOut($rateOut)
    {
        $this->rateOut = $rateOut;

        return $this;
    }

    /**
     * Get rateOut
     *
     * @return float 
     */
    public function getRateOut()
    {
        return $this->rateOut;
    }

    /**
     * Set rateOutOperator
     *
     * @param float $rateOutOperator
     * @return Subscription
     */
    public function setRateOutOperator($rateOutOperator)
    {
        $this->rateOutOperator = $rateOutOperator;

        return $this;
    }

    /**
     * Get rateOutOperator
     *
     * @return float 
     */
    public function getRateOutOperator()
    {
        return $this->rateOutOperator;
    }

    /**
     * Set rateInFactor
     *
     * @param float $rateInFactor
     * @return Subscription
     */
    public function setRateInFactor($rateInFactor)
    {
        $this->rateInFactor = $rateInFactor;

        return $this;
    }

    /**
     * Get rateInFactor
     *
     * @return float 
     */
    public function getRateInFactor()
    {
        return $this->rateInFactor;
    }

    /**
     * Set isEditable
     *
     * @param boolean $isEditable
     * @return Subscription
     */
    public function setIsEditable($isEditable)
    {
        $this->isEditable = $isEditable;

        return $this;
    }

    /**
     * Get isEditable
     *
     * @return boolean 
     */
    public function getIsEditable()
    {
        return $this->isEditable;
    }

    /**
     * Set internRate
     *
     * @param float $internRate
     * @return OutLine
     */
    public function setInternRate($internRate)
    {
        $this->internRate = $internRate;

        return $this;
    }

    /**
     * Get internRate
     *
     * @return float 
     */
    public function getInternRate()
    {
        return $this->internRate;
    }
}
