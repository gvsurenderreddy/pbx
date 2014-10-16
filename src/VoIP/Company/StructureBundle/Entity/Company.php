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
     * @var string
     *
     * @ORM\Column(name="context", type="string", length=40)
     */
    private $context = 'internal';

    /**
     * @var string
     *
     * @ORM\Column(name="mailbox", type="string", length=8, unique=true)
     */
    private $mailbox;

    /**
     * @var integer
     *
     * @ORM\Column(name="password", type="integer")
     */
    private $password = '1234';

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=50, nullable=true)
     */
    private $fullname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="pager", type="string", length=50, nullable=true)
     */
    private $pager;
	
	/**
     * @ORM\OneToMany(targetEntity="\VoIP\Company\StructureBundle\Entity\Phone", mappedBy="company")
	 * @ORM\OrderBy({"name" = "ASC"})
     */
    private $phones;
	
	/**
     * @ORM\OneToMany(targetEntity="\VoIP\Company\StructureBundle\Entity\Employee", mappedBy="company")
	 * @ORM\OrderBy({"extension" = "ASC"})
     */
    private $employees;
	
	/**
     * @ORM\OneToMany(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\Subscription", mappedBy="company")
	 * @ORM\OrderBy({"name" = "ASC"})
     */
    private $subscriptions;
	
	/**
     * @ORM\OneToMany(targetEntity="\Management\Session\UserBundle\Entity\User", mappedBy="company")
	 * @ORM\OrderBy({"username" = "ASC"})
     */
    private $users;
	
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
		$this->setMailbox(hash('crc32b', uniqid('', true)));
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
}
