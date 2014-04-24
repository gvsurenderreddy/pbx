<?php

namespace VoIP\Company\SubscriptionsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DialPlanItem
 *
 * @ORM\Table(name="structure_dialplan_item")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class DialPlanItem
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
     * @ORM\Column(name="type", type="string", length=128)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="ring_time", type="integer")
     */
    private $ringTime = 20;

    /**
     * @var string
     *
     * @ORM\Column(name="sound_file", type="string", length=255, nullable=true)
     */
    private $soundFile;

    /**
     * @var string
     *
     * @ORM\Column(name="vm_email", type="string", length=255, nullable=true)
     */
    private $vmEmail;
	
    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255)
     */
    private $hash;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Phone", inversedBy="dialItems")
	 * @ORM\JoinColumn(name="phone_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $phone;
	
	/**
     * @ORM\OneToOne(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\DialPlanItem", inversedBy="previousItem")
	 * @ORM\JoinColumn(name="next_item_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $nextItem;
	
	/**
     * @ORM\OneToOne(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\DialPlanItem", mappedBy="nextItem")
     */
    private $previousItem;
	
	/**
     * @ORM\OneToOne(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\Subscription", mappedBy="dialPlanFirstItem")
     */
    private $subscription;
	
	/**
     * @ORM\OneToOne(targetEntity="\VoIP\PBX\RealTimeBundle\Entity\Extension", inversedBy="dialItem")
	 * @ORM\JoinColumn(name="ast_extension_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $astExtension;
	
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
	
	public function getAbsoluteSubscription()
	{
		if ($subscription = $this->getSubscription()) return $subscription;
		elseif ($previous = $this->getPreviousItem()) return $previous->getAbsoluteSubscription();
		else return null;
	}
	
	public function getAbsolutePriority()
	{
		if ($subscription = $this->getSubscription()) return 1;
		elseif (($previous = $this->getPreviousItem()) && ($previousOrder = $previous->getAbsolutePriority())) return $previousOrder + 1;
		else return null;
	}
	
	public function getDepth()
	{
		if (!($nextItem = $this->getNextItem())) return 0;
		else return $nextItem->getDepth() + 1;
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
     * @return DialPlanItem
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
     * @return DialPlanItem
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
     * Set type
     *
     * @param string $type
     * @return DialPlanItem
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
     * Set ringTime
     *
     * @param integer $ringTime
     * @return DialPlanItem
     */
    public function setRingTime($ringTime)
    {
        $this->ringTime = $ringTime;

        return $this;
    }

    /**
     * Get ringTime
     *
     * @return integer 
     */
    public function getRingTime()
    {
        return $this->ringTime;
    }

    /**
     * Set soundFile
     *
     * @param string $soundFile
     * @return DialPlanItem
     */
    public function setSoundFile($soundFile)
    {
        $this->soundFile = $soundFile;

        return $this;
    }

    /**
     * Get soundFile
     *
     * @return string 
     */
    public function getSoundFile()
    {
        return $this->soundFile;
    }

    /**
     * Set vmEmail
     *
     * @param string $vmEmail
     * @return DialPlanItem
     */
    public function setVmEmail($vmEmail)
    {
        $this->vmEmail = $vmEmail;

        return $this;
    }

    /**
     * Get vmEmail
     *
     * @return string 
     */
    public function getVmEmail()
    {
        return $this->vmEmail;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return DialPlanItem
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
     * Set nextItem
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\DialPlanItem $nextItem
     * @return DialPlanItem
     */
    public function setNextItem(\VoIP\Company\SubscriptionsBundle\Entity\DialPlanItem $nextItem = null)
    {
        $this->nextItem = $nextItem;

        return $this;
    }

    /**
     * Get nextItem
     *
     * @return \VoIP\Company\SubscriptionsBundle\Entity\DialPlanItem 
     */
    public function getNextItem()
    {
        return $this->nextItem;
    }

    /**
     * Set previousItem
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\DialPlanItem $previousItem
     * @return DialPlanItem
     */
    public function setPreviousItem(\VoIP\Company\SubscriptionsBundle\Entity\DialPlanItem $previousItem = null)
    {
        $this->previousItem = $previousItem;

        return $this;
    }

    /**
     * Get previousItem
     *
     * @return \VoIP\Company\SubscriptionsBundle\Entity\DialPlanItem 
     */
    public function getPreviousItem()
    {
        return $this->previousItem;
    }

    /**
     * Set subscription
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\Subscription $subscription
     * @return DialPlanItem
     */
    public function setSubscription(\VoIP\Company\SubscriptionsBundle\Entity\Subscription $subscription = null)
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * Get subscription
     *
     * @return \VoIP\Company\SubscriptionsBundle\Entity\Subscription 
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * Set astExtension
     *
     * @param \VoIP\PBX\RealTimeBundle\Entity\Extension $astExtension
     * @return DialPlanItem
     */
    public function setAstExtension(\VoIP\PBX\RealTimeBundle\Entity\Extension $astExtension = null)
    {
        $this->astExtension = $astExtension;

        return $this;
    }

    /**
     * Get astExtension
     *
     * @return \VoIP\PBX\RealTimeBundle\Entity\Extension 
     */
    public function getAstExtension()
    {
        return $this->astExtension;
    }

    /**
     * Set phone
     *
     * @param \VoIP\Company\StructureBundle\Entity\Phone $phone
     * @return DialPlanItem
     */
    public function setPhone(\VoIP\Company\StructureBundle\Entity\Phone $phone = null)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return \VoIP\Company\StructureBundle\Entity\Phone 
     */
    public function getPhone()
    {
        return $this->phone;
    }
}
