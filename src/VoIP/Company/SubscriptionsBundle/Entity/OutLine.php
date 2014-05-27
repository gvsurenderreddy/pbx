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
     * @var boolean
     *
     * @ORM\Column(name="is_public", type="boolean")
     */
    private $isPublic = false;
	
    /**
     * @var boolean
     *
     * @ORM\Column(name="show_number", type="boolean")
     */
    private $showNumber = false;
	
	/**
     * @ORM\OneToOne(targetEntity="\VoIP\PBX\RealTimeBundle\Entity\SipPeer", inversedBy="subscription")
	 * @ORM\JoinColumn(name="ast_sippeer_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $astPeer;
	
	/**
	 * @ORM\ManyToMany(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\OutGroup", mappedBy="outLines")
	 * @ORM\OrderBy({"hash" = "ASC"})
	 */
	protected $outGroups;
	
	/**
	 * @ORM\ManyToMany(targetEntity="\VoIP\PBX\BillBundle\Entity\Rate", inversedBy="outLines")
	 * @ORM\JoinTable(name="structure_outline_has_rate",
	 *      joinColumns={@ORM\JoinColumn(name="outline_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="rate_id", referencedColumnName="id")}
	 *      )
	 * @ORM\OrderBy({"name" = "ASC"})
	 */
	protected $rates;
	
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
		$this->hash = hexdec(hash('crc32b', uniqid('', true)));
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
        $this->outGroups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rates = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return OutLine
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
     * @return OutLine
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
     * @return OutLine
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
     * @return OutLine
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
     * @return OutLine
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
     * @return OutLine
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
     * Set host
     *
     * @param string $host
     * @return OutLine
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
     * @return OutLine
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
     * @return OutLine
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
     * Set isPublic
     *
     * @param boolean $isPublic
     * @return OutLine
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
     * Set showNumber
     *
     * @param boolean $showNumber
     * @return OutLine
     */
    public function setShowNumber($showNumber)
    {
        $this->showNumber = $showNumber;

        return $this;
    }

    /**
     * Get showNumber
     *
     * @return boolean 
     */
    public function getShowNumber()
    {
        return $this->showNumber;
    }

    /**
     * Set astPeer
     *
     * @param \VoIP\PBX\RealTimeBundle\Entity\SipPeer $astPeer
     * @return OutLine
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
     * Add outGroups
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\OutGroup $outGroups
     * @return OutLine
     */
    public function addOutGroup(\VoIP\Company\SubscriptionsBundle\Entity\OutGroup $outGroups)
    {
        $this->outGroups[] = $outGroups;

        return $this;
    }

    /**
     * Remove outGroups
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\OutGroup $outGroups
     */
    public function removeOutGroup(\VoIP\Company\SubscriptionsBundle\Entity\OutGroup $outGroups)
    {
        $this->outGroups->removeElement($outGroups);
    }

    /**
     * Get outGroups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOutGroups()
    {
        return $this->outGroups;
    }

    /**
     * Add rates
     *
     * @param \VoIP\PBX\BillBundle\Entity\Rate $rates
     * @return OutLine
     */
    public function addRate(\VoIP\PBX\BillBundle\Entity\Rate $rates)
    {
        $this->rates[] = $rates;

        return $this;
    }

    /**
     * Remove rates
     *
     * @param \VoIP\PBX\BillBundle\Entity\Rate $rates
     */
    public function removeRate(\VoIP\PBX\BillBundle\Entity\Rate $rates)
    {
        $this->rates->removeElement($rates);
    }

    /**
     * Get rates
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRates()
    {
        return $this->rates;
    }
}
