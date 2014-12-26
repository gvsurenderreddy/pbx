<?php

namespace VoIP\Company\SubscriptionsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="structure_country")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Country
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
     * @ORM\Column(name="iso_code_2", type="string", length=2)
     */
    private $isoCode2;

    /**
     * @var string
     *
     * @ORM\Column(name="iso_code_3", type="string", length=3)
     */
    private $isoCode3;

    /**
     * @var string
     *
     * @ORM\Column(name="call_code", type="string", length=20)
     */
    private $callCode;

    /**
     * @ORM\ManyToMany(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\Subscription", mappedBy="countries")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    protected $subscriptions;

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Country
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
     * @return Country
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
     * @return Country
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
     * Set isoCode2
     *
     * @param string $isoCode2
     * @return Country
     */
    public function setIsoCode2($isoCode2)
    {
        $this->isoCode2 = $isoCode2;

        return $this;
    }

    /**
     * Get isoCode2
     *
     * @return string 
     */
    public function getIsoCode2()
    {
        return $this->isoCode2;
    }

    /**
     * Set isoCode3
     *
     * @param string $isoCode3
     * @return Country
     */
    public function setIsoCode3($isoCode3)
    {
        $this->isoCode3 = $isoCode3;

        return $this;
    }

    /**
     * Get isoCode3
     *
     * @return string 
     */
    public function getIsoCode3()
    {
        return $this->isoCode3;
    }

    /**
     * Set callCode
     *
     * @param string $callCode
     * @return Country
     */
    public function setCallCode($callCode)
    {
        $this->callCode = $callCode;

        return $this;
    }

    /**
     * Get callCode
     *
     * @return string 
     */
    public function getCallCode()
    {
        return $this->callCode;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subscriptions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add subscriptions
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\Subscription $subscriptions
     * @return Country
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
}
