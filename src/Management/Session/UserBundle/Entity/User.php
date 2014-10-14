<?php

namespace Management\Session\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
    /**
     * @var boolean
     *
     * @ORM\Column(name="conditions_accepted", type="boolean")
     */
    protected $conditionsAccepted = false;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Company", inversedBy="users")
	 * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $company;
	
	/**
     * @ORM\OneToMany(targetEntity="\VoIP\Company\SubscriptionsBundle\Entity\NumberRequest", mappedBy="requestedBy")
	 * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private $numberRequests;


	public function __construct()
    {
        parent::__construct();
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
     * Add companies
     *
     * @param \VoIP\Company\StructureBundle\Entity\Company $companies
     * @return User
     */
    public function addCompany(\VoIP\Company\StructureBundle\Entity\Company $companies)
    {
        $this->companies[] = $companies;

        return $this;
    }

    /**
     * Remove companies
     *
     * @param \VoIP\Company\StructureBundle\Entity\Company $companies
     */
    public function removeCompany(\VoIP\Company\StructureBundle\Entity\Company $companies)
    {
        $this->companies->removeElement($companies);
    }

    /**
     * Get companies
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * Set company
     *
     * @param \VoIP\Company\StructureBundle\Entity\Company $company
     * @return User
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
     * Set conditionsAccepted
     *
     * @param boolean $conditionsAccepted
     * @return User
     */
    public function setConditionsAccepted($conditionsAccepted)
    {
        $this->conditionsAccepted = $conditionsAccepted;

        return $this;
    }

    /**
     * Get conditionsAccepted
     *
     * @return boolean 
     */
    public function getConditionsAccepted()
    {
        return $this->conditionsAccepted;
    }

    /**
     * Add numberRequests
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\NumberRequest $numberRequests
     * @return User
     */
    public function addNumberRequest(\VoIP\Company\SubscriptionsBundle\Entity\NumberRequest $numberRequests)
    {
        $this->numberRequests[] = $numberRequests;

        return $this;
    }

    /**
     * Remove numberRequests
     *
     * @param \VoIP\Company\SubscriptionsBundle\Entity\NumberRequest $numberRequests
     */
    public function removeNumberRequest(\VoIP\Company\SubscriptionsBundle\Entity\NumberRequest $numberRequests)
    {
        $this->numberRequests->removeElement($numberRequests);
    }

    /**
     * Get numberRequests
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNumberRequests()
    {
        return $this->numberRequests;
    }
}
