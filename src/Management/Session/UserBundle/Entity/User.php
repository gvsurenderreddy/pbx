<?php

namespace Management\Session\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

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
}
