<?php

namespace VoIP\PBX\RealTimeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SipPeer
 *
 * @ORM\Table(name="ast_sippeers")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class SipPeer
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
     * @ORM\Column(name="username", type="string", length=255)
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
     * @ORM\Column(name="context", type="string", length=255)
     */
    private $context = 'from-internal';

    /**
     * @var string
     *
     * @ORM\Column(name="host", type="string", length=255)
     */
    private $host = 'dynamic';

    /**
     * @var string
     *
     * @ORM\Column(name="nat", type="string", length=255)
     */
    private $nat = 'route';

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type = 'friend';

    /**
     * @var string
     *
     * @ORM\Column(name="canreinvite", type="string", length=10)
     */
    private $canreinvite = 'no';

    /**
     * @var string
     *
     * @ORM\Column(name="directrtpsetup", type="string", length=10)
     */
    private $directrtpsetup = 'no';

    /**
     * @var string
     *
     * @ORM\Column(name="disallow", type="string", length=255)
     */
    private $disallow = 'all';

    /**
     * @var string
     *
     * @ORM\Column(name="allow", type="string", length=255)
     */
    private $allow = 'gsm';
	
    /**
     * @var string
     *
     * @ORM\Column(name="lastms", type="string", length=255, nullable=true)
     */
    private $lastms;
	
	/**
	 * @ORM\PrePersist
	 */
	public function prePersist()
	{
		$this->createdAt = new \DateTime();
	    $this->updatedAt = new \DateTime();
	}
	
	/**
	 * @ORM\PreUpdate
	 */
	public function preUpdate()
	{
	    $this->updatedAt = new \DateTime();
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
     * @return SipPeer
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
     * @return SipPeer
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
     * @return SipPeer
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
     * Set host
     *
     * @param string $host
     * @return SipPeer
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
     * Set nat
     *
     * @param string $nat
     * @return SipPeer
     */
    public function setNat($nat)
    {
        $this->nat = $nat;

        return $this;
    }

    /**
     * Get nat
     *
     * @return string 
     */
    public function getNat()
    {
        return $this->nat;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return SipPeer
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
     * Set canreinvite
     *
     * @param string $canreinvite
     * @return SipPeer
     */
    public function setCanreinvite($canreinvite)
    {
        $this->canreinvite = $canreinvite;

        return $this;
    }

    /**
     * Get canreinvite
     *
     * @return string 
     */
    public function getCanreinvite()
    {
        return $this->canreinvite;
    }

    /**
     * Set directrtpsetup
     *
     * @param string $directrtpsetup
     * @return SipPeer
     */
    public function setDirectrtpsetup($directrtpsetup)
    {
        $this->directrtpsetup = $directrtpsetup;

        return $this;
    }

    /**
     * Get directrtpsetup
     *
     * @return string 
     */
    public function getDirectrtpsetup()
    {
        return $this->directrtpsetup;
    }

    /**
     * Set qualify
     *
     * @param string $qualify
     * @return SipPeer
     */
    public function setQualify($qualify)
    {
        $this->qualify = $qualify;

        return $this;
    }

    /**
     * Get qualify
     *
     * @return string 
     */
    public function getQualify()
    {
        return $this->qualify;
    }

    /**
     * Set disallow
     *
     * @param string $disallow
     * @return SipPeer
     */
    public function setDisallow($disallow)
    {
        $this->disallow = $disallow;

        return $this;
    }

    /**
     * Get disallow
     *
     * @return string 
     */
    public function getDisallow()
    {
        return $this->disallow;
    }

    /**
     * Set allow
     *
     * @param string $allow
     * @return SipPeer
     */
    public function setAllow($allow)
    {
        $this->allow = $allow;

        return $this;
    }

    /**
     * Get allow
     *
     * @return string 
     */
    public function getAllow()
    {
        return $this->allow;
    }

    /**
     * Set context
     *
     * @param string $context
     * @return SipPeer
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
     * Set secret
     *
     * @param string $secret
     * @return SipPeer
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
     * Set username
     *
     * @param string $username
     * @return SipPeer
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
     * Set lastms
     *
     * @param string $lastms
     * @return SipPeer
     */
    public function setLastms($lastms)
    {
        $this->lastms = $lastms;

        return $this;
    }

    /**
     * Get lastms
     *
     * @return string 
     */
    public function getLastms()
    {
        return $this->lastms;
    }
}
