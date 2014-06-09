<?php

namespace VoIP\Company\StructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Company
 *
 * @ORM\Table(name="structure_company")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Company
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
     * @ORM\Column(name="credit_updated_at", type="datetime")
     */
    private $creditUpdatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = true;
	
    /**
     * @var float
     *
     * @ORM\Column(name="credit", type="float")
     */
    private $credit = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="context", type="string", length=255, unique=true)
     */
    private $context;
	
    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=8, unique=true)
     */
    private $hash;
	
	/**
     * @ORM\OneToMany(targetEntity="\VoIP\Company\StructureBundle\Entity\Phone", mappedBy="company")
	 * @ORM\OrderBy({"name" = "ASC"})
     */
    private $phones;
	
	/**
     * @ORM\OneToMany(targetEntity="\VoIP\Company\VoicemailBundle\Entity\Voicemail", mappedBy="company")
	 * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private $voicemails;
	
	/**
     * @ORM\OneToMany(targetEntity="\VoIP\Company\StructureBundle\Entity\Employee", mappedBy="company")
	 * @ORM\OrderBy({"extension" = "ASC"})
     */
    private $employees;
	
	/**
     * @ORM\OneToMany(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\Subscription", mappedBy="company")
	 * @ORM\OrderBy({"number" = "ASC"})
     */
    private $subscriptions;
	
	/**
     * @ORM\OneToMany(targetEntity="\Management\Session\UserBundle\Entity\User", mappedBy="company")
	 * @ORM\OrderBy({"username" = "ASC"})
     */
    private $users;
	
	/**
     * @ORM\OneToMany(targetEntity="\VoIP\PBX\CDRBundle\Entity\CDR", mappedBy="company")
	 * @ORM\OrderBy({"start" = "ASC"})
     */
    private $reports;

	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\OutGroup", inversedBy="companies")
	 * @ORM\JoinColumn(name="outgroup_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $outGroup;
	
	/**
	 * @ORM\PrePersist
	 */
	public function prePersist()
	{
		$this->createdAt = new \DateTime();
	    $this->updatedAt = new \DateTime();
		$this->creditUpdatedAt = new \DateTime();
		if (!$this->context) $this->generateContext();
		if (!$this->hash) $this->generateHash();
	}
	
	/**
	 * @ORM\PreUpdate
	 */
	public function preUpdate()
	{
	    $this->updatedAt = new \DateTime();
		if (!$this->context) $this->generateContext();
		if (!$this->hash) $this->generateHash();
	}
	
	public function generateContext()
	{
		$this->context = hash('crc32b', uniqid('', true));
	}
	
	public function generateHash()
	{
		$this->hash = hash('crc32b', uniqid('', true));
	}
	
	public function getActiveEmployees()
	{
		return $this->getEmployees()->filter(function($e){
			return $e->getIsActive();
		});
	}
	public function getActivePhones()
	{
		return $this->getPhones()->filter(function($e){
			return $e->getIsActive();
		});
	}
	public function getActiveSubscriptions()
	{
		return $this->getSubscriptions()->filter(function($e){
			return $e->getIsActive();
		});
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * Set email
     *
     * @param string $email
     * @return Company
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set context
     *
     * @param string $context
     * @return Company
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
     * Constructor
     */
    public function __construct()
    {
        $this->offices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Company
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
     * Add subscriptions
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\Subscription $subscriptions
     * @return Company
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
     * Add phones
     *
     * @param \VoIP\Company\StructureBundle\Entity\Phone $phones
     * @return Company
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
     * Add employees
     *
     * @param \VoIP\Company\StructureBundle\Entity\Employee $employees
     * @return Company
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
     * Add messages
     *
     * @param \VoIP\Company\VoicemailBundle\Entity\Message $messages
     * @return Company
     */
    public function addMessage(\VoIP\Company\VoicemailBundle\Entity\Message $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \VoIP\Company\VoicemailBundle\Entity\Message $messages
     */
    public function removeMessage(\VoIP\Company\VoicemailBundle\Entity\Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Add voicemails
     *
     * @param \VoIP\Company\VoicemailBundle\Entity\Voicemail $voicemails
     * @return Company
     */
    public function addVoicemail(\VoIP\Company\VoicemailBundle\Entity\Voicemail $voicemails)
    {
        $this->voicemails[] = $voicemails;

        return $this;
    }

    /**
     * Remove voicemails
     *
     * @param \VoIP\Company\VoicemailBundle\Entity\Voicemail $voicemails
     */
    public function removeVoicemail(\VoIP\Company\VoicemailBundle\Entity\Voicemail $voicemails)
    {
        $this->voicemails->removeElement($voicemails);
    }

    /**
     * Get voicemails
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVoicemails()
    {
        return $this->voicemails;
    }

    /**
     * Add reports
     *
     * @param \VoIP\PBX\CDRBundle\Entity\CDR $reports
     * @return Company
     */
    public function addReport(\VoIP\PBX\CDRBundle\Entity\CDR $reports)
    {
        $this->reports[] = $reports;

        return $this;
    }

    /**
     * Remove reports
     *
     * @param \VoIP\PBX\CDRBundle\Entity\CDR $reports
     */
    public function removeReport(\VoIP\PBX\CDRBundle\Entity\CDR $reports)
    {
        $this->reports->removeElement($reports);
    }

    /**
     * Get reports
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReports()
    {
        return $this->reports;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Company
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
     * Set credit
     *
     * @param float $credit
     * @return Company
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return float 
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set creditUpdatedAt
     *
     * @param \DateTime $creditUpdatedAt
     * @return Company
     */
    public function setCreditUpdatedAt($creditUpdatedAt)
    {
        $this->creditUpdatedAt = $creditUpdatedAt;

        return $this;
    }

    /**
     * Get creditUpdatedAt
     *
     * @return \DateTime 
     */
    public function getCreditUpdatedAt()
    {
        return $this->creditUpdatedAt;
    }

    /**
     * Add users
     *
     * @param \Management\Session\UserBundle\Entity\User $users
     * @return Company
     */
    public function addUser(\Management\Session\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Management\Session\UserBundle\Entity\User $users
     */
    public function removeUser(\Management\Session\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set outGroup
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\OutGroup $outGroup
     * @return Company
     */
    public function setOutGroup(\VoIP\Company\SubscriptionsBundle\Entity\OutGroup $outGroup = null)
    {
        $this->outGroup = $outGroup;

        return $this;
    }

    /**
     * Get outGroup
     *
     * @return \VoIP\Company\SubscriptionsBundle\Entity\OutGroup 
     */
    public function getOutGroup()
    {
        return $this->outGroup;
    }
}
