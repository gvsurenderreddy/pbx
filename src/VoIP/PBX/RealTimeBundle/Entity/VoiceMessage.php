<?php

namespace VoIP\PBX\RealTimeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VoiceMail
 *
 * @ORM\Table(name="voicemessages")
 * @ORM\Entity
 */
class VoiceMessage
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
     * @var integer
     *
     * @ORM\Column(name="msgnum", type="integer")
     */
    private $msgnum;

    /**
     * @var string
     *
     * @ORM\Column(name="dir", type="string", length=80, nullable=true)
     */
    private $dir;

    /**
     * @var string
     *
     * @ORM\Column(name="context", type="string", length=40, nullable=true)
     */
    private $context;

    /**
     * @var string
     *
     * @ORM\Column(name="macrocontext", type="string", length=80, nullable=true)
     */
    private $macrocontext;
	
    /**
     * @var string
     *
     * @ORM\Column(name="callerid", type="string", length=80, nullable=true)
     */
    private $callerid;

    /**
     * @var string
     *
     * @ORM\Column(name="origtime", type="string", length=40, nullable=true)
     */
    private $origtime;

    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="string", length=20, nullable=true)
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="mailboxuser", type="string", length=80, nullable=true)
     */
    private $mailboxuser;

    /**
     * @var string
     *
     * @ORM\Column(name="mailboxcontext", type="string", length=80, nullable=true)
     */
    private $mailboxcontext;
	
    /**
     * @var text
     *
     * @ORM\Column(name="recording", type="text", nullable=true)
     */
    private $recording;

    /**
     * @var string
     *
     * @ORM\Column(name="flag", type="string", length=127, nullable=true)
     */
    private $flag;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="msg_id", type="integer", nullable=true)
     */
    private $msgId;

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
     * Set msgnum
     *
     * @param integer $msgnum
     * @return VoiceMessage
     */
    public function setMsgnum($msgnum)
    {
        $this->msgnum = $msgnum;

        return $this;
    }

    /**
     * Get msgnum
     *
     * @return integer 
     */
    public function getMsgnum()
    {
        return $this->msgnum;
    }

    /**
     * Set dir
     *
     * @param string $dir
     * @return VoiceMessage
     */
    public function setDir($dir)
    {
        $this->dir = $dir;

        return $this;
    }

    /**
     * Get dir
     *
     * @return string 
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Set context
     *
     * @param string $context
     * @return VoiceMessage
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
     * Set macrocontext
     *
     * @param string $macrocontext
     * @return VoiceMessage
     */
    public function setMacrocontext($macrocontext)
    {
        $this->macrocontext = $macrocontext;

        return $this;
    }

    /**
     * Get macrocontext
     *
     * @return string 
     */
    public function getMacrocontext()
    {
        return $this->macrocontext;
    }

    /**
     * Set callerid
     *
     * @param string $callerid
     * @return VoiceMessage
     */
    public function setCallerid($callerid)
    {
        $this->callerid = $callerid;

        return $this;
    }

    /**
     * Get callerid
     *
     * @return string 
     */
    public function getCallerid()
    {
        return $this->callerid;
    }

    /**
     * Set origtime
     *
     * @param string $origtime
     * @return VoiceMessage
     */
    public function setOrigtime($origtime)
    {
        $this->origtime = $origtime;

        return $this;
    }

    /**
     * Get origtime
     *
     * @return string 
     */
    public function getOrigtime()
    {
        return $this->origtime;
    }

    /**
     * Set duration
     *
     * @param string $duration
     * @return VoiceMessage
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return string 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set mailboxuser
     *
     * @param string $mailboxuser
     * @return VoiceMessage
     */
    public function setMailboxuser($mailboxuser)
    {
        $this->mailboxuser = $mailboxuser;

        return $this;
    }

    /**
     * Get mailboxuser
     *
     * @return string 
     */
    public function getMailboxuser()
    {
        return $this->mailboxuser;
    }

    /**
     * Set mailboxcontext
     *
     * @param string $mailboxcontext
     * @return VoiceMessage
     */
    public function setMailboxcontext($mailboxcontext)
    {
        $this->mailboxcontext = $mailboxcontext;

        return $this;
    }

    /**
     * Get mailboxcontext
     *
     * @return string 
     */
    public function getMailboxcontext()
    {
        return $this->mailboxcontext;
    }

    /**
     * Set recording
     *
     * @param string $recording
     * @return VoiceMessage
     */
    public function setRecording($recording)
    {
        $this->recording = $recording;

        return $this;
    }

    /**
     * Get recording
     *
     * @return string 
     */
    public function getRecording()
    {
        return $this->recording;
    }

    /**
     * Set flag
     *
     * @param string $flag
     * @return VoiceMessage
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get flag
     *
     * @return string 
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Set msgId
     *
     * @param integer $msgId
     * @return VoiceMessage
     */
    public function setMsgId($msgId)
    {
        $this->msgId = $msgId;

        return $this;
    }

    /**
     * Get msgId
     *
     * @return integer 
     */
    public function getMsgId()
    {
        return $this->msgId;
    }
}
