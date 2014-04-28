<?php

namespace VoIP\PBX\CDRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CallReport
 *
 * @ORM\Table(name="ast_call")
 * @ORM\Entity
 */
class CallReport
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
     * @ORM\Column(name="start", type="datetime")
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime")
     */
    private $end;

    /**
     * @var string
     *
     * @ORM\Column(name="check_sum", type="string", length=128)
     */
    private $checkSum;

    /**
     * @var string
     *
     * @ORM\Column(name="dialer", type="string", length=127)
     */
    private $dialer;

    /**
     * @var string
     *
     * @ORM\Column(name="receiver", type="string", length=127)
     */
    private $receiver;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Company", inversedBy="astCalls")
	 * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $company;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Employee", inversedBy="astCalls")
	 * @ORM\JoinColumn(name="employee_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $employee;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Phone", inversedBy="astCalls")
	 * @ORM\JoinColumn(name="phone_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $phone;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\Subscription", inversedBy="astCalls")
	 * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
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
     * Set start
     *
     * @param \DateTime $start
     * @return CallReport
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
     * Set end
     *
     * @param \DateTime $end
     * @return CallReport
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
     * Set checkSum
     *
     * @param string $checkSum
     * @return CallReport
     */
    public function setCheckSum($checkSum)
    {
        $this->checkSum = $checkSum;

        return $this;
    }

    /**
     * Get checkSum
     *
     * @return string 
     */
    public function getCheckSum()
    {
        return $this->checkSum;
    }

    /**
     * Set dialer
     *
     * @param string $dialer
     * @return CallReport
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
     * @return CallReport
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
     * Set company
     *
     * @param \VoIP\Company\StructureBundle\Entity\Company $company
     * @return CallReport
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
     * Set employee
     *
     * @param \VoIP\Company\StructureBundle\Entity\Employee $employee
     * @return CallReport
     */
    public function setEmployee(\VoIP\Company\StructureBundle\Entity\Employee $employee = null)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \VoIP\Company\StructureBundle\Entity\Employee 
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Set phone
     *
     * @param \VoIP\Company\StructureBundle\Entity\Phone $phone
     * @return CallReport
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



    /**
     * Set subscription
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\Subscription $subscription
     * @return CallReport
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
