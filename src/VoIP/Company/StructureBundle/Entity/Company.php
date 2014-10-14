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
     * @var string
	 *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;
	
    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=8, unique=true)
     */
    private $hash;
	
    /**
     * @var string
     *
     * @ORM\Column(name="image_url", type="string", length=255, nullable=true)
     */
    private $imageUrl;
	
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
     * @ORM\OneToMany(targetEntity="\VoIP\Company\DynIPBundle\Entity\DynIP", mappedBy="company")
	 * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private $dynIPs;
	
	/**
     * @ORM\OneToMany(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\NumberRequest", mappedBy="company")
	 * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private $numberRequests;
	
	
	/**
	 * @ORM\PrePersist
	 */
	public function prePersist()
	{
		$this->setCreatedAt(new \DateTime());
	    $this->setUpdatedAt(new \DateTime());
		$this->setHash(hash('crc32b', uniqid('', true)));
	}
	
	/**
	 * @ORM\PreUpdate
	 */
	public function preUpdate()
	{
	    $this->setUpdatedAt(new \DateTime());
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
        $this->phones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->voicemails = new \Doctrine\Common\Collections\ArrayCollection();
        $this->employees = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subscriptions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reports = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dynIPs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set imageUrl
     *
     * @param string $imageUrl
     * @return Company
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
     * Add dynIPs
     *
     * @param \VoIP\Company\DynIPBundle\Entity\DynIP $dynIPs
     * @return Company
     */
    public function addDynIP(\VoIP\Company\DynIPBundle\Entity\DynIP $dynIPs)
    {
        $this->dynIPs[] = $dynIPs;

        return $this;
    }

    /**
     * Remove dynIPs
     *
     * @param \VoIP\Company\DynIPBundle\Entity\DynIP $dynIPs
     */
    public function removeDynIP(\VoIP\Company\DynIPBundle\Entity\DynIP $dynIPs)
    {
        $this->dynIPs->removeElement($dynIPs);
    }

    /**
     * Get dynIPs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDynIPs()
    {
        return $this->dynIPs;
    }

    /**
     * Add numberRequests
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\NumberRequest $numberRequests
     * @return Company
     */
    public function addNumberRequest(\VoIP\Company\SubscriptionsBundle\Entity\NumberRequest $numberRequests)
    {
        $this->numberRequests[] = $numberRequests;

        return $this;
    }

    /**
     * Remove numberRequests
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\NumberRequest $numberRequests
     */
    public function removeNumberRequest(\VoIP\Company\SubscriptionsBundle\Entity\NumberRequest $numberRequests)
    {
        $this->numberRequests->removeElement($numberRequests);
    }

    /**
     * Get numberRequests
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNumberRequests()
    {
        return $this->numberRequests;
    }
}
