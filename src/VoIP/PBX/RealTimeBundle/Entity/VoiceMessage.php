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


    private $company;
 
    /**
     * @var string
     *
     * @ORM\Column(name="mailboxcontext", type="string", length=80, nullable=true)
     */
    private $mailboxcontext;
	
    /**
     * @var blob
     *
     * @ORM\Column(name="recording", type="blob", nullable=true)
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
}
