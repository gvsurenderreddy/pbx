<?php

namespace VoIP\Company\VoicemailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="structure_message")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
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
     * @var \DateTime
     *
     * @ORM\Column(name="read_at", type="datetime", nullable=true)
     */
    private $readAt;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="archived_at", type="datetime", nullable=true)
     */
    private $archivedAt;
	
    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=40, unique=true)
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="file_path", type="string", length=255)
     */
    private $filePath;
	
    /**
     * @var string
     *
     * @ORM\Column(name="voicemail_hash", type="string", length=10, nullable=true)
     */
    private $voicemailHash;
	
	/**
     * @ORM\ManyToOne(targetEntity="\VoIP\Company\VoicemailBundle\Entity\Voicemail", inversedBy="messages")
	 * @ORM\JoinColumn(name="voicemail_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $voicemail;
	
	/**
	 * @ORM\PrePersist
	 */
	public function prePersist()
	{
		$this->createdAt = new \DateTime();
		if (!$this->hash) $this->generateHash();
	}
	
	/**
	 * @ORM\PreUpdate
	 */
	public function preUpdate()
	{
		if (!$this->hash) $this->generateHash();
	}
	
	public function generateHash()
	{
		$this->hash = hash('crc32b', uniqid('', true));
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


    /**
     * Set voicemailHash
     *
     * @param string $voicemailHash
     * @return Message
     */
    public function setVoicemailHash($voicemailHash)
    {
        $this->voicemailHash = $voicemailHash;

        return $this;
    }

    /**
     * Get voicemailHash
     *
     * @return string 
     */
    public function getVoicemailHash()
    {
        return $this->voicemailHash;
    }

    /**
     * Set readAt
     *
     * @param \DateTime $readAt
     * @return Message
     */
    public function setReadAt($readAt)
    {
        $this->readAt = $readAt;

        return $this;
    }

    /**
     * Get readAt
     *
     * @return \DateTime 
     */
    public function getReadAt()
    {
        return $this->readAt;
    }

    /**
     * Set archivedAt
     *
     * @param \DateTime $archivedAt
     * @return Message
     */
    public function setArchivedAt($archivedAt)
    {
        $this->archivedAt = $archivedAt;

        return $this;
    }

    /**
     * Get archivedAt
     *
     * @return \DateTime 
     */
    public function getArchivedAt()
    {
        return $this->archivedAt;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Message
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }
}
