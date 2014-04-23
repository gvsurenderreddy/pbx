<?php

namespace VoIP\PBX\RealTimeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VoiceMail
 *
 * @ORM\Table(name="ast_voicemails")
 * @ORM\Entity
 */
class VoiceMail
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
     * @ORM\Column(name="context", type="string", length=40)
     */
    private $context;

    /**
     * @var string
     *
     * @ORM\Column(name="mailbox", type="string", length=40)
     */
    private $mailbox;

    /**
     * @var integer
     *
     * @ORM\Column(name="password", type="integer")
     */
    private $password;

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
     * @ORM\OneToOne(targetEntity="\VoIP\Company\VoicemailBundle\Entity\Voicemail", mappedBy="astVoicemail")
     */
    private $voicemail;

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
     * Set mailbox
     *
     * @param string $mailbox
     * @return VoiceMail
     */
    public function setMailbox($mailbox)
    {
        $this->mailbox = $mailbox;

        return $this;
    }

    /**
     * Get mailbox
     *
     * @return string 
     */
    public function getMailbox()
    {
        return $this->mailbox;
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
     * Set voicemail
     *
     * @param \VoIP\Company\VoicemailBundle\Entity\Voicemail $voicemail
     * @return VoiceMail
     */
    public function setVoicemail(\VoIP\Company\VoicemailBundle\Entity\Voicemail $voicemail = null)
    {
        $this->voicemail = $voicemail;

        return $this;
    }

    /**
     * Get voicemail
     *
     * @return \VoIP\Company\VoicemailBundle\Entity\Voicemail 
     */
    public function getVoicemail()
    {
        return $this->voicemail;
    }
}
