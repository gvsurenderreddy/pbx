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
	 * @ORM\ManyToMany(targetEntity="\VoIP\Company\StructureBundle\Entity\Company", mappedBy="users")
	 * @ORM\OrderBy({"name" = "ASC"})
	 */
	protected $companies;
	
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


	public function __construct()
    {
        parent::__construct();
    }
	
	public function hasCompany($company)
	{
		$test = false;
		foreach ($this->companies as $c) {
			$test = $test || ($c->getId() == $company->getId());
		}
		return $test;
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
}
