<?php

namespace VoIP\Company\VoicemailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="structure_message")
 * @ORM\Entity
 */
class Message
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
     * @var string
     *
     * @ORM\Column(name="file_path", type="string", length=255)
     */
    private $filePath;
	
    /**
     * @var string
     *
     * @ORM\Column(name="test", type="string", length=255, nullable=true)
     */
    private $test;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\VoicemailBundle\Entity\Voicemail", inversedBy="messages")
	 * @ORM\JoinColumn(name="voicemail_id", referencedColumnName="id", onDelete="CASCADE")
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Message
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
     * Set filePath
     *
     * @param string $filePath
     * @return Message
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get filePath
     *
     * @return string 
     */
    public function getFilePath()
    {
        return $this->filePath;
    }


    /**
     * Set voicemail
     *
     * @param \VoIP\Company\VoicemailBundle\Entity\Voicemail $voicemail
     * @return Message
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
