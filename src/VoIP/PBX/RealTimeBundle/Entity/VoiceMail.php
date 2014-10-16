<?php

namespace VoIP\PBX\RealTimeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VoiceMail
 *
 * @ORM\Table(name="voicemail")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class VoiceMail
{
    /**
     * @var string
     *
     * @ORM\Column(name="mailbox", type="string", length=8, unique=true)
     * @ORM\Id
     */
    private $id;
	
    /**
     * @var string
     *
     * @ORM\Column(name="context", type="string", length=40)
     */
    private $context = 'internal';

    /**
     * @var integer
     *
     * @ORM\Column(name="password", type="integer")
     */
    private $password = '1234';

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=150, nullable=true)
     */
    private $fullname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=128, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="pager", type="string", length=128, nullable=true)
     */
    private $pager;
	
	/**
     * @ORM\OneToOne(targetEntity="\VoIP\Company\StructureBundle\Entity\Company", mappedBy="voicemail")
     */
    private $company;
	
	/**
	 * @ORM\PrePersist
	 */
	public function prePersist()
	{
		$this->setId(hash('crc32b', uniqid('', true)));
	}

    /**
     * Set id
     *
     * @param string $id
     * @return VoiceMail
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set context
     *
     * @param string $context
     * @return VoiceMail
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string 
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set password
     *
     * @param integer $password
     * @return VoiceMail
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return integer 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     * @return VoiceMail
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string 
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return VoiceMail
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set pager
     *
     * @param string $pager
     * @return VoiceMail
     */
    public function setPager($pager)
    {
        $this->pager = $pager;

        return $this;
    }

    /**
     * Get pager
     *
     * @return string 
     */
    public function getPager()
    {
        return $this->pager;
    }

    /**
     * Set company
     *
     * @param \VoIP\Company\StructureBundle\Entity\Company $company
     * @return VoiceMail
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
}
