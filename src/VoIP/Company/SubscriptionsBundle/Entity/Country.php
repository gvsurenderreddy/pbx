<?php

namespace VoIP\Company\SubscriptionsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="structure_country")
 * @ORM\Entity
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
     * @var integer
     *
     * @ORM\Column(name="calling_code", type="integer")
     */
    private $callingCode;

    /**
     * @var string
     *
     * @ORM\Column(name="calling_code_str", type="string", length=4)
     */
    private $callingCodeStr;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128)
     */
    private $name;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\OutLine", inversedBy="countries")
	 * @ORM\JoinColumn(name="out_line_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $outLine;


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
     * Set callingCode
     *
     * @param integer $callingCode
     * @return Country
     */
    public function setCallingCode($callingCode)
    {
        $this->callingCode = $callingCode;

        return $this;
    }

    /**
     * Get callingCode
     *
     * @return integer 
     */
    public function getCallingCode()
    {
        return $this->callingCode;
    }

    /**
     * Set callingCodeStr
     *
     * @param string $callingCodeStr
     * @return Country
     */
    public function setCallingCodeStr($callingCodeStr)
    {
        $this->callingCodeStr = $callingCodeStr;

        return $this;
    }

    /**
     * Get callingCodeStr
     *
     * @return string 
     */
    public function getCallingCodeStr()
    {
        return $this->callingCodeStr;
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

    /**
     * Set outLine
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\OutLine $outLine
     * @return Country
     */
    public function setOutLine(\VoIP\Company\SubscriptionsBundle\Entity\OutLine $outLine = null)
    {
        $this->outLine = $outLine;

        return $this;
    }

    /**
     * Get outLine
     *
     * @return \VoIP\Company\SubscriptionsBundle\Entity\OutLine 
     */
    public function getOutLine()
    {
        return $this->outLine;
    }
}
