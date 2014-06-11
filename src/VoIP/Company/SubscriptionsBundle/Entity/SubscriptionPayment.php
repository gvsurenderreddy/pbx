<?php

namespace VoIP\Company\SubscriptionsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubscriptionPayment
 *
 * @ORM\Table(name="structure_subscription_payment")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class SubscriptionPayment
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
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\Subscription", inversedBy="payments")
	 * @ORM\JoinColumn(name="subscription_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $subscription;


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
     * @return SubscriptionPayment
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
     * Set price
     *
     * @param float $price
     * @return SubscriptionPayment
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set subscription
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\Subscription $subscription
     * @return SubscriptionPayment
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
