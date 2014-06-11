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
     * @var \DateTime
     *
     * @ORM\Column(name="activated_until", type="datetime", nullable=true)
     */
    private $activatedUntil;
	
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = true;
	
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_editable", type="boolean")
     */
    private $isEditable = true;
	
    /**
     * @var string
     *
     * @ORM\Column(name="vm_file", length=128, nullable=true)
     */
    private $vmFile;
	
    /**
     * @var boolean
     *
     * @ORM\Column(name="record_vm", type="boolean")
     */
    private $recordVM = true;
	
    /**
     * @var float
     *
     * @ORM\Column(name="rate_out", type="float")
     */
    private $rateOut = 0.01;
	
    /**
     * @var float
     *
     * @ORM\Column(name="rate_out_operator", type="float")
     */
    private $rateOutOperator = 0.00;
	
    /**
     * @var string
     *
     * @ORM\Column(name="registration_code", type="string", length=255, nullable=true)
     */
    private $registrationCode;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="license", type="integer")
     */
    private $license = 15;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Company", inversedBy="subscriptions")
	 * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $company;
	
	/**
     * @ORM\OneToOne(targetEntity="\VoIP\PBX\RealTimeBundle\Entity\SipPeer", inversedBy="subscription")
	 * @ORM\JoinColumn(name="ast_sippeer_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $astPeer;
	
	/**
     * @ORM\OneToOne(targetEntity="\VoIP\Company\VoicemailBundle\Entity\Voicemail", inversedBy="subscription")
	 * @ORM\JoinColumn(name="voicemail_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $voicemail;
	
	/**
	 * @ORM\ManyToMany(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\Country", inversedBy="subscriptions")
	 * @ORM\JoinTable(name="structure_subscription_has_country",
	 *      joinColumns={@ORM\JoinColumn(name="subscription_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="country_id", referencedColumnName="id")}
	 *      )
	 */
	protected $countries;
	
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
     * @ORM\OneToOne(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\DialPlanItem", inversedBy="subscription")
	 * @ORM\JoinColumn(name="dialplan_firstitem_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $dialPlanFirstItem;
	
	/**
     * @ORM\OneToMany(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\SubscriptionPayment", mappedBy="subscription")
	 * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private $payments;
	
	public function getDayLeft()
	{
		$now = new \DateTime();
		if (!$date = $this->getActivatedUntil()) return '0 day';
		else {
			$days = (int)(($date->getTimestamp() - $now->getTimestamp()) / (60 * 60 * 24));
			if ($days <= 0) return '0 day';
			if ($days == 1) return '1 day';
			return $days . ' days';
		}
	}
	
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
	
	public function isValidDetails()
	{
		if (!$date = $this->getActivatedUntil()) return 0;
		$now = new \DateTime();
		if ($date < $now) return 1;
		$now->modify('+5 days');
		if ($date < $now) return 2;
		return 3;
	}
	public function isValid()
	{
		$now = new \DateTime();
		$date = $this->getActivatedUntil();
		return ($date && $date > $now);
	}
	public function isValidStatus()
	{
		switch ($this->isValidDetails()) {
			case 0:
				return 'status-danger';
				break;
			case 1:
				return 'status-danger';
				break;
			case 2:
				return 'status-warning';
				break;
			default:
				return 'status-success';
				break;
		}
	}
	
	public function getRealTime($start, $end)
	{
		if ($this->getCreatedAt() >= $end) return 0;
		if ($this->getCanceledAt() && $this->getCanceledAt() < $start) return 0;
		if ($this->getCreatedAt() > $start) $start = $this->getCreatedAt();
		if ($this->getCanceledAt() && $this->getCanceledAt() < $end) $end = $this->getCanceledAt();
		return  $start->getTimestamp() - $end->getTimestamp();
	}
	
	public function getStringTime($start, $end)
	{
		$total = $start->getTimestamp() - $end->getTimestamp();
		if ($total == 0) return null; 
		$sec = $this->getRealTime($start, $end);
		if ($sec < 3600) null;
		if ($sec < 86400) return ((int)($sec / 3600)).' hours';
		else return ((int)($sec / 86400)).' days';
	}
	public function getRateTime($start, $end)
	{
		$total = $start->getTimestamp() - $end->getTimestamp();
		if ($total == 0) return 0; 
		$sec = $this->getRealTime($start, $end);
		if ($sec < 3600) 0;
		if ($sec < 86400) return $sec/$total;
		else return $sec/$total;
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
     * Constructor
     */
    public function __construct()
    {
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->employees = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set vmFile
     *
     * @param string $vmFile
     * @return Subscription
     */
    public function setVmFile($vmFile)
    {
        $this->vmFile = $vmFile;

        return $this;
    }

    /**
     * Get vmFile
     *
     * @return string 
     */
    public function getVmFile()
    {
        return $this->vmFile;
    }

    /**
     * Set recordVM
     *
     * @param boolean $recordVM
     * @return Subscription
     */
    public function setRecordVM($recordVM)
    {
        $this->recordVM = $recordVM;

        return $this;
    }

    /**
     * Get recordVM
     *
     * @return boolean 
     */
    public function getRecordVM()
    {
        return $this->recordVM;
    }

    /**
     * Set license
     *
     * @param integer $license
     * @return Subscription
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
     * @return Subscription
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
     * @param \VoIP\Company\SubscriptionsBundle\Entity\SubscriptionPayment $payments
     * @return Subscription
     */
    public function addPayment(\VoIP\Company\SubscriptionsBundle\Entity\SubscriptionPayment $payments)
    {
        $this->payments[] = $payments;

        return $this;
    }

    /**
     * Remove payments
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\SubscriptionPayment $payments
     */
    public function removePayment(\VoIP\Company\SubscriptionsBundle\Entity\SubscriptionPayment $payments)
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
