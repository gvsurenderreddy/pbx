<?php

namespace VoIP\Company\StructureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phone
 *
 * @ORM\Table(name="structure_phone")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Phone
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
     * @ORM\Column(name="model", type="string", length=40)
     */
    private $model;
	
    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=8, unique=true)
     */
    private $hash;
	
    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=127)
     */
    private $alias;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = true;
	
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=8)
     */
    private $name;
	
    /**
     * @var string
     *
     * @ORM\Column(name="ipaddr", type="text", length=15, nullable=true)
     */
    private $ipaddr;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="port", type="integer", nullable=true)
     */
    private $port;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="regseconds", type="integer", nullable=true)
     */
    private $regseconds;
	
    /**
     * @var string
     *
     * @ORM\Column(name="fullcontact", type="text", length=35, nullable=true)
     */
    private $fullcontact;
	
    /**
     * @var string
     *
     * @ORM\Column(name="regserver", type="text", length=20, nullable=true)
     */
    private $regserver;
	
    /**
     * @var string
     *
     * @ORM\Column(name="useragent", type="text", length=20, nullable=true)
     */
    private $useragent;
	
    /**
     * @var string
     *
     * @ORM\Column(name="lastms", type="string", length=255, nullable=true)
     */
    private $lastms;
	
    /**
     * @var string
     *
     * @ORM\Column(name="host", type="string", length=255)
     */
    private $host;
	
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10)
     */
    private $type;
	
    /**
     * @var string
     *
     * @ORM\Column(name="context", type="string", length=40, nullable=true)
     */
    private $context;
	
    /**
     * @var string
     *
     * @ORM\Column(name="secret", type="string", length=40, nullable=true)
     */
    private $secret;
	
    /**
     * @var string
     *
     * @ORM\Column(name="nat", type="string", length=20, nullable=true)
     */
    private $nat;
	
    /**
     * @var string
     *
     * @ORM\Column(name="qualify", type="string", length=40, nullable=true)
     */
    private $qualify;
	
    /**
     * @var string
     *
     * @ORM\Column(name="dynamic", type="string", length=10, nullable=true)
     */
    private $dynamic;
	
    /**
     * @var string
     *
     * @ORM\Column(name="qualifyfreq", type="string", length=10, nullable=true)
     */
    private $qualifyfreq;
	
    /**
     * @var string
     *
     * @ORM\Column(name="defaultuser", type="string", length=8, nullable=true)
     */
    private $defaultuser;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Company", inversedBy="phones")
	 * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $company;
	
	/**
	 * @ORM\ManyToMany(targetEntity="\VoIP\Company\StructureBundle\Entity\Employee", mappedBy="phones")
	 * @ORM\OrderBy({"extension" = "ASC"})
	 */
	protected $employees;
	
	/**
	 * @ORM\PrePersist
	 */
	public function prePersist()
	{
		$this->setCreatedAt(new \DateTime());
	    $this->setUpdatedAt(new \DateTime());
		$this->setHash(hash('crc32b', uniqid('', true)));
		$this->setSecret(hash('sha1', uniqid('', true)));
		$this->setName($this->getHash());
		$this->setDefaultuser($this->getName());
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
        $this->employees = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Phone
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
     * @return Phone
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
     * Set canceledAt
     *
     * @param \DateTime $canceledAt
     * @return Phone
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
     * Set model
     *
     * @param string $model
     * @return Phone
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string 
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Phone
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
     * Set alias
     *
     * @param string $alias
     * @return Phone
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Phone
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
     * Set name
     *
     * @param string $name
     * @return Phone
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
     * Set ipaddr
     *
     * @param string $ipaddr
     * @return Phone
     */
    public function setIpaddr($ipaddr)
    {
        $this->ipaddr = $ipaddr;

        return $this;
    }

    /**
     * Get ipaddr
     *
     * @return string 
     */
    public function getIpaddr()
    {
        return $this->ipaddr;
    }

    /**
     * Set port
     *
     * @param integer $port
     * @return Phone
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return integer 
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set regseconds
     *
     * @param integer $regseconds
     * @return Phone
     */
    public function setRegseconds($regseconds)
    {
        $this->regseconds = $regseconds;

        return $this;
    }

    /**
     * Get regseconds
     *
     * @return integer 
     */
    public function getRegseconds()
    {
        return $this->regseconds;
    }

    /**
     * Set fullcontact
     *
     * @param string $fullcontact
     * @return Phone
     */
    public function setFullcontact($fullcontact)
    {
        $this->fullcontact = $fullcontact;

        return $this;
    }

    /**
     * Get fullcontact
     *
     * @return string 
     */
    public function getFullcontact()
    {
        return $this->fullcontact;
    }

    /**
     * Set regserver
     *
     * @param string $regserver
     * @return Phone
     */
    public function setRegserver($regserver)
    {
        $this->regserver = $regserver;

        return $this;
    }

    /**
     * Get regserver
     *
     * @return string 
     */
    public function getRegserver()
    {
        return $this->regserver;
    }

    /**
     * Set useragent
     *
     * @param string $useragent
     * @return Phone
     */
    public function setUseragent($useragent)
    {
        $this->useragent = $useragent;

        return $this;
    }

    /**
     * Get useragent
     *
     * @return string 
     */
    public function getUseragent()
    {
        return $this->useragent;
    }

    /**
     * Set lastms
     *
     * @param string $lastms
     * @return Phone
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

    /**
     * Set host
     *
     * @param string $host
     * @return Phone
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
     * Set type
     *
     * @param string $type
     * @return Phone
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
     * Set context
     *
     * @param string $context
     * @return Phone
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
     * @return Phone
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
     * Set nat
     *
     * @param string $nat
     * @return Phone
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
     * Set qualify
     *
     * @param string $qualify
     * @return Phone
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
     * Set dynamic
     *
     * @param string $dynamic
     * @return Phone
     */
    public function setDynamic($dynamic)
    {
        $this->dynamic = $dynamic;

        return $this;
    }

    /**
     * Get dynamic
     *
     * @return string 
     */
    public function getDynamic()
    {
        return $this->dynamic;
    }

    /**
     * Set qualifyfreq
     *
     * @param string $qualifyfreq
     * @return Phone
     */
    public function setQualifyfreq($qualifyfreq)
    {
        $this->qualifyfreq = $qualifyfreq;

        return $this;
    }

    /**
     * Get qualifyfreq
     *
     * @return string 
     */
    public function getQualifyfreq()
    {
        return $this->qualifyfreq;
    }

    /**
     * Set defaultuser
     *
     * @param string $defaultuser
     * @return Phone
     */
    public function setDefaultuser($defaultuser)
    {
        $this->defaultuser = $defaultuser;

        return $this;
    }

    /**
     * Get defaultuser
     *
     * @return string 
     */
    public function getDefaultuser()
    {
        return $this->defaultuser;
    }

    /**
     * Set company
     *
     * @param \VoIP\Company\StructureBundle\Entity\Company $company
     * @return Phone
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
     * @return Phone
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
}
