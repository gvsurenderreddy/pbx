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
     * @ORM\Column(name="company_name", type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(message="Please enter your company name.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max="255",
     *     minMessage="The company name is too short.",
     *     maxMessage="The company name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $companyName;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Company", inversedBy="users")
	 * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $company;


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
     * Set companyName
     *
     * @param string $companyName
     * @return User
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string 
     */
    public function getCompanyName()
    {
        return $this->companyName;
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
}
