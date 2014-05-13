<?php

namespace VoIP\PBX\CDRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CDR
 *
 * @ORM\Table(name="cdr")
 * @ORM\Entity
 */
class CDR
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
     * @var string
     *
     * @ORM\Column(name="accountcode", type="string", length=40, nullable=true)
     */
    private $accountcode;

    /**
     * @var string
     *
     * @ORM\Column(name="src", type="string", length=40, nullable=true)
     */
    private $src;

    /**
     * @var string
     *
     * @ORM\Column(name="dst", type="string", length=40, nullable=true)
     */
    private $dst;

    /**
     * @var string
     *
     * @ORM\Column(name="dcontext", type="string", length=40, nullable=true)
     */
    private $dcontext;

    /**
     * @var string
     *
     * @ORM\Column(name="clid", type="string", length=255, nullable=true)
     */
    private $clid;

    /**
     * @var string
     *
     * @ORM\Column(name="channel", type="string", length=255, nullable=true)
     */
    private $channel;

    /**
     * @var string
     *
     * @ORM\Column(name="dstchannel", type="string", length=255, nullable=true)
     */
    private $dstchannel;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime", nullable=true)
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="answer", type="datetime", nullable=true)
     */
    private $answer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime", nullable=true)
     */
    private $end;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer", nullable=true)
     */
    private $duration;

    /**
     * @var integer
     *
     * @ORM\Column(name="billsec", type="integer", nullable=true)
     */
    private $billsec;

    /**
     * @var string
     *
     * @ORM\Column(name="disposition", type="string", length=128, nullable=true)
     */
    private $disposition;

    /**
     * @var string
     *
     * @ORM\Column(name="amaflags", type="string", length=128, nullable=true)
     */
    private $amaflags;

    /**
     * @var string
     *
     * @ORM\Column(name="userfield", type="string", length=128, nullable=true)
     */
    private $userfield;

    /**
     * @var string
     *
     * @ORM\Column(name="uniqueid", type="string", length=128, nullable=true)
     */
    private $uniqueid;
	
    /**
     * @var string
     *
     * @ORM\Column(name="dialer", type="string", length=128, nullable=true)
     */
    private $dialer;
	
    /**
     * @var string
     *
     * @ORM\Column(name="receiver", type="string", length=128, nullable=true)
     */
    private $receiver;
	
    /**
     * @var float
     *
     * @ORM\Column(name="rate", type="float", nullable=true)
     */
    private $rate;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="credit_updated_at", type="datetime", nullable=true)
     */
    private $creditUpdatedAt;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Employee", inversedBy="astDialerEmployees")
	 * @ORM\JoinColumn(name="dialer_employee_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $dialerEmployee;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Phone", inversedBy="astDialerPhones")
	 * @ORM\JoinColumn(name="dialer_phone_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $dialerPhone;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\Subscription", inversedBy="astDialerSubscriptions")
	 * @ORM\JoinColumn(name="dialer_subscription_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $dialerSubscription;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Employee", inversedBy="astReceiverEmployees")
	 * @ORM\JoinColumn(name="receiver_employee_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $receiverEmployee;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Phone", inversedBy="astReceiverPhones")
	 * @ORM\JoinColumn(name="receiver_phone_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $receiverPhone;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\Subscription", inversedBy="astReceiverSubscriptions")
	 * @ORM\JoinColumn(name="receiver_subscription_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $receiverSubscription;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Company", inversedBy="astCalls")
	 * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $company;
	
	public function getPrice()
	{
		if (($answer = $this->getAnswer()) && ($end = $this->getEnd())) {
			$t1 = $answer->getTimestamp();
			$t2 = $end->getTimestamp();
			return $this->getRate() * ($t2 - $t1) / 60;
		} else {
			return 0;
		}
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
     * Set accountcode
     *
     * @param string $accountcode
     * @return CDR
     */
    public function setAccountcode($accountcode)
    {
        $this->accountcode = $accountcode;

        return $this;
    }

    /**
     * Get accountcode
     *
     * @return string 
     */
    public function getAccountcode()
    {
        return $this->accountcode;
    }

    /**
     * Set src
     *
     * @param string $src
     * @return CDR
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Get src
     *
     * @return string 
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Set dst
     *
     * @param string $dst
     * @return CDR
     */
    public function setDst($dst)
    {
        $this->dst = $dst;

        return $this;
    }

    /**
     * Get dst
     *
     * @return string 
     */
    public function getDst()
    {
        return $this->dst;
    }

    /**
     * Set dcontext
     *
     * @param string $dcontext
     * @return CDR
     */
    public function setDcontext($dcontext)
    {
        $this->dcontext = $dcontext;

        return $this;
    }

    /**
     * Get dcontext
     *
     * @return string 
     */
    public function getDcontext()
    {
        return $this->dcontext;
    }

    /**
     * Set clid
     *
     * @param string $clid
     * @return CDR
     */
    public function setClid($clid)
    {
        $this->clid = $clid;

        return $this;
    }

    /**
     * Get clid
     *
     * @return string 
     */
    public function getClid()
    {
        return $this->clid;
    }

    /**
     * Set channel
     *
     * @param string $channel
     * @return CDR
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return string 
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Set dstchannel
     *
     * @param string $dstchannel
     * @return CDR
     */
    public function setDstchannel($dstchannel)
    {
        $this->dstchannel = $dstchannel;

        return $this;
    }

    /**
     * Get dstchannel
     *
     * @return string 
     */
    public function getDstchannel()
    {
        return $this->dstchannel;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     * @return CDR
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set answer
     *
     * @param \DateTime $answer
     * @return CDR
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return \DateTime 
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return CDR
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return CDR
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set billsec
     *
     * @param integer $billsec
     * @return CDR
     */
    public function setBillsec($billsec)
    {
        $this->billsec = $billsec;

        return $this;
    }

    /**
     * Get billsec
     *
     * @return integer 
     */
    public function getBillsec()
    {
        return $this->billsec;
    }

    /**
     * Set disposition
     *
     * @param string $disposition
     * @return CDR
     */
    public function setDisposition($disposition)
    {
        $this->disposition = $disposition;

        return $this;
    }

    /**
     * Get disposition
     *
     * @return string 
     */
    public function getDisposition()
    {
        return $this->disposition;
    }

    /**
     * Set amaflags
     *
     * @param string $amaflags
     * @return CDR
     */
    public function setAmaflags($amaflags)
    {
        $this->amaflags = $amaflags;

        return $this;
    }

    /**
     * Get amaflags
     *
     * @return string 
     */
    public function getAmaflags()
    {
        return $this->amaflags;
    }

    /**
     * Set userfield
     *
     * @param string $userfield
     * @return CDR
     */
    public function setUserfield($userfield)
    {
        $this->userfield = $userfield;

        return $this;
    }

    /**
     * Get userfield
     *
     * @return string 
     */
    public function getUserfield()
    {
        return $this->userfield;
    }

    /**
     * Set uniqueid
     *
     * @param string $uniqueid
     * @return CDR
     */
    public function setUniqueid($uniqueid)
    {
        $this->uniqueid = $uniqueid;

        return $this;
    }

    /**
     * Get uniqueid
     *
     * @return string 
     */
    public function getUniqueid()
    {
        return $this->uniqueid;
    }

    /**
     * Set dialerEmployee
     *
     * @param \VoIP\Company\StructureBundle\Entity\Employee $dialerEmployee
     * @return CDR
     */
    public function setDialerEmployee(\VoIP\Company\StructureBundle\Entity\Employee $dialerEmployee = null)
    {
        $this->dialerEmployee = $dialerEmployee;

        return $this;
    }

    /**
     * Get dialerEmployee
     *
     * @return \VoIP\Company\StructureBundle\Entity\Employee 
     */
    public function getDialerEmployee()
    {
        return $this->dialerEmployee;
    }

    /**
     * Set dialerPhone
     *
     * @param \VoIP\Company\StructureBundle\Entity\Phone $dialerPhone
     * @return CDR
     */
    public function setDialerPhone(\VoIP\Company\StructureBundle\Entity\Phone $dialerPhone = null)
    {
        $this->dialerPhone = $dialerPhone;

        return $this;
    }

    /**
     * Get dialerPhone
     *
     * @return \VoIP\Company\StructureBundle\Entity\Phone 
     */
    public function getDialerPhone()
    {
        return $this->dialerPhone;
    }

    /**
     * Set dialerSubscription
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\Subscription $dialerSubscription
     * @return CDR
     */
    public function setDialerSubscription(\VoIP\Company\SubscriptionsBundle\Entity\Subscription $dialerSubscription = null)
    {
        $this->dialerSubscription = $dialerSubscription;

        return $this;
    }

    /**
     * Get dialerSubscription
     *
     * @return \VoIP\Company\SubscriptionsBundle\Entity\Subscription 
     */
    public function getDialerSubscription()
    {
        return $this->dialerSubscription;
    }

    /**
     * Set receiverEmployee
     *
     * @param \VoIP\Company\StructureBundle\Entity\Employee $receiverEmployee
     * @return CDR
     */
    public function setReceiverEmployee(\VoIP\Company\StructureBundle\Entity\Employee $receiverEmployee = null)
    {
        $this->receiverEmployee = $receiverEmployee;

        return $this;
    }

    /**
     * Get receiverEmployee
     *
     * @return \VoIP\Company\StructureBundle\Entity\Employee 
     */
    public function getReceiverEmployee()
    {
        return $this->receiverEmployee;
    }

    /**
     * Set receiverPhone
     *
     * @param \VoIP\Company\StructureBundle\Entity\Phone $receiverPhone
     * @return CDR
     */
    public function setReceiverPhone(\VoIP\Company\StructureBundle\Entity\Phone $receiverPhone = null)
    {
        $this->receiverPhone = $receiverPhone;

        return $this;
    }

    /**
     * Get receiverPhone
     *
     * @return \VoIP\Company\StructureBundle\Entity\Phone 
     */
    public function getReceiverPhone()
    {
        return $this->receiverPhone;
    }

    /**
     * Set receiverSubscription
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\Subscription $receiverSubscription
     * @return CDR
     */
    public function setReceiverSubscription(\VoIP\Company\SubscriptionsBundle\Entity\Subscription $receiverSubscription = null)
    {
        $this->receiverSubscription = $receiverSubscription;

        return $this;
    }

    /**
     * Get receiverSubscription
     *
     * @return \VoIP\Company\SubscriptionsBundle\Entity\Subscription 
     */
    public function getReceiverSubscription()
    {
        return $this->receiverSubscription;
    }

    /**
     * Set company
     *
     * @param \VoIP\Company\StructureBundle\Entity\Company $company
     * @return CDR
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
     * Set rate
     *
     * @param float $rate
     * @return CDR
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return float 
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set dialer
     *
     * @param string $dialer
     * @return CDR
     */
    public function setDialer($dialer)
    {
        $this->dialer = $dialer;

        return $this;
    }

    /**
     * Get dialer
     *
     * @return string 
     */
    public function getDialer()
    {
        return $this->dialer;
    }

    /**
     * Set receiver
     *
     * @param string $receiver
     * @return CDR
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Get receiver
     *
     * @return string 
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * Set creditUpdatedAt
     *
     * @param \DateTime $creditUpdatedAt
     * @return CDR
     */
    public function setCreditUpdatedAt($creditUpdatedAt)
    {
        $this->creditUpdatedAt = $creditUpdatedAt;

        return $this;
    }

    /**
     * Get creditUpdatedAt
     *
     * @return \DateTime 
     */
    public function getCreditUpdatedAt()
    {
        return $this->creditUpdatedAt;
    }
}
