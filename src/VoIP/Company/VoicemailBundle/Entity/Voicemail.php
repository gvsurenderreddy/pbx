<?php

namespace VoIP\Company\VoicemailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Voicemail
 *
 * @ORM\Table(name="structure_voicemail")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Voicemail
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
     * @ORM\Column(name="hash", type="string", length=40)
     */
    private $hash;
	
	/**
     * @ORM\OneToMany(targetEntity="\VoIP\Company\VoicemailBundle\Entity\Message", mappedBy="voicemail")
	 * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private $messages;
	
	/**
     * @ORM\OneToOne(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\Subscription", mappedBy="voicemail")
     */
    private $subscription;
	
	/**
     * @ORM\OneToOne(targetEntity="\VoIP\PBX\RealTimeBundle\Entity\VoiceMail", inversedBy="voicemail")
	 * @ORM\JoinColumn(name="ast_voicemail_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $astVoicemail;
	
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
     * @return Voicemail
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
     * @return Voicemail
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
     * Set hash
     *
     * @param string $hash
     * @return Voicemail
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
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add messages
     *
     * @param \VoIP\Company\VoicemailBundle\Entity\Message $messages
     * @return Voicemail
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
     * Set astVoicemail
     *
     * @param \VoIP\PBX\RealTimeBundle\Entity\Voicemail $astVoiceMail
     * @return Voicemail
     */
    public function setAstVoicemail(\VoIP\PBX\RealTimeBundle\Entity\VoiceMail $astVoicemail = null)
    {
        $this->astVoicemail = $astVoicemail;

        return $this;
    }

    /**
     * Get astVoicemail
     *
     * @return \VoIP\PBX\RealTimeBundle\Entity\VoiceMail 
     */
    public function getAstVoicemail()
    {
        return $this->astVoicemail;
    }


    /**
     * Set subscription
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\Subscription $subscription
     * @return Voicemail
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
}
