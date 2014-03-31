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
     * @ORM\Column(name="unique_id", type="integer", unique=true)
     */
    private $uniqueId;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_id", type="string", length=10, nullable=true)
     */
    private $customerId;

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
     * @var string
     *
     * @ORM\Column(name="tz", type="string", length=10)
     */
    private $tz = 'central';

    /**
     * @var string
     *
     * @ORM\Column(name="attach", type="string", length=3)
     */
    private $attach = 'yes';

    /**
     * @var string
     *
     * @ORM\Column(name="saycid", type="string", length=3)
     */
    private $saycid = 'yes';

    /**
     * @var string
     *
     * @ORM\Column(name="dialout", type="string", length=10, nullable=true)
     */
    private $dialout;

    /**
     * @var string
     *
     * @ORM\Column(name="callback", type="string", length=10, nullable=true)
     */
    private $callback;

    /**
     * @var string
     *
     * @ORM\Column(name="review", type="string", length=3)
     */
    private $review = 'no';

    /**
     * @var string
     *
     * @ORM\Column(name="operator", type="string", length=3)
     */
    private $operator = 'no';

    /**
     * @var string
     *
     * @ORM\Column(name="envelope", type="string", length=3)
     */
    private $envelope = no;

    /**
     * @var string
     *
     * @ORM\Column(name="sayduration", type="string", length=3)
     */
    private $sayduration = 'no';

    /**
     * @var string
     *
     * @ORM\Column(name="saydurationm", type="integer")
     */
    private $saydurationm = 1;

    /**
     * @var string
     *
     * @ORM\Column(name="sendvoicemail", type="string", length=3)
     */
    private $sendvoicemail = 'no';

    /**
     * @var string
     *
     * @ORM\Column(name="delete", type="string", length=3)
     */
    private $deleteMessage = 'no';

    /**
     * @var string
     *
     * @ORM\Column(name="nextaftercmd", type="string", length=3)
     */
    private $nextaftercmd = 'yes';

    /**
     * @var string
     *
     * @ORM\Column(name="forcename", type="string", length=3)
     */
    private $forcename = 'no';

    /**
     * @var string
     *
     * @ORM\Column(name="forcegreetings", type="string", length=3)
     */
    private $forcegreetings = 'no';

    /**
     * @var string
     *
     * @ORM\Column(name="hidefromdir", type="string", length=3)
     */
    private $hidefromdir = 'yes';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="stamp", type="datetime")
     */
    private $stamp;

    /**
     * @var string
     *
     * @ORM\Column(name="attachfmt", type="string", length=10, nullable=true)
     */
    private $attachfmt;

    /**
     * @var string
     *
     * @ORM\Column(name="searchcontexts", type="string", length=3, nullable=true)
     */
    private $searchcontexts;

    /**
     * @var string
     *
     * @ORM\Column(name="cidinternalcontexts", type="string", length=10, nullable=true)
     */
    private $cidinternalcontexts;

    /**
     * @var string
     *
     * @ORM\Column(name="exitcontext", type="string", length=10, nullable=true)
     */
    private $exitcontext;

    /**
     * @var string
     *
     * @ORM\Column(name="volgain", type="string", length=4, nullable=true)
     */
    private $volgain;

    /**
     * @var string
     *
     * @ORM\Column(name="tempgreetwarn", type="string", length=3)
     */
    private $tempgreetwarn = 'yes';

    /**
     * @var string
     *
     * @ORM\Column(name="messagewrap", type="string", length=3)
     */
    private $messagewrap = 'no';

    /**
     * @var integer
     *
     * @ORM\Column(name="minpassword", type="integer")
     */
    private $minpassword = 4;

    /**
     * @var string
     *
     * @ORM\Column(name="vm_password", type="string", length=10, nullable=true)
     */
    private $vmPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="vm_newpassword", type="string", length=10, nullable=true)
     */
    private $vmNewpassword;

    /**
     * @var string
     *
     * @ORM\Column(name="vm_passchanged", type="string", length=10, nullable=true)
     */
    private $vmPasschanged;

    /**
     * @var string
     *
     * @ORM\Column(name="vm_reenterpassword", type="string", length=10, nullable=true)
     */
    private $vmReenterpassword;

    /**
     * @var string
     *
     * @ORM\Column(name="vm_mismatch", type="string", length=10, nullable=true)
     */
    private $vmMismatch;

    /**
     * @var string
     *
     * @ORM\Column(name="vm_invalid_password", type="string", length=10, nullable=true)
     */
    private $vmInvalidPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="vm_pls_try_again", type="string", length=10, nullable=true)
     */
    private $vmPlsTryAgain;

    /**
     * @var string
     *
     * @ORM\Column(name="listen_control_forward_key", type="string", length=2, nullable=true)
     */
    private $listenControlForwardKey;

    /**
     * @var string
     *
     * @ORM\Column(name="listen_control_reverse_key", type="string", length=1, nullable=true)
     */
    private $listenControlReverseKey;

    /**
     * @var string
     *
     * @ORM\Column(name="listen_control_pause_key", type="string", length=1, nullable=true)
     */
    private $listenControlPauseKey;

    /**
     * @var string
     *
     * @ORM\Column(name="listen_control_restart_key", type="string", length=1, nullable=true)
     */
    private $listenControlRestartKey;

    /**
     * @var string
     *
     * @ORM\Column(name="listen_control_stop_key", type="string", length=13, nullable=true)
     */
    private $listenControlStopKey;

    /**
     * @var string
     *
     * @ORM\Column(name="backupdeleted", type="string", length=3)
     */
    private $backupdeleted = '25';


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
     * Set uniqueId
     *
     * @param integer $uniqueId
     * @return VoiceMail
     */
    public function setUniqueId($uniqueId)
    {
        $this->uniqueId = $uniqueId;

        return $this;
    }

    /**
     * Get uniqueId
     *
     * @return integer 
     */
    public function getUniqueId()
    {
        return $this->uniqueId;
    }

    /**
     * Set customerId
     *
     * @param string $customerId
     * @return VoiceMail
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * Get customerId
     *
     * @return string 
     */
    public function getCustomerId()
    {
        return $this->customerId;
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
     * Set tz
     *
     * @param string $tz
     * @return VoiceMail
     */
    public function setTz($tz)
    {
        $this->tz = $tz;

        return $this;
    }

    /**
     * Get tz
     *
     * @return string 
     */
    public function getTz()
    {
        return $this->tz;
    }

    /**
     * Set attach
     *
     * @param string $attach
     * @return VoiceMail
     */
    public function setAttach($attach)
    {
        $this->attach = $attach;

        return $this;
    }

    /**
     * Get attach
     *
     * @return string 
     */
    public function getAttach()
    {
        return $this->attach;
    }

    /**
     * Set saycid
     *
     * @param string $saycid
     * @return VoiceMail
     */
    public function setSaycid($saycid)
    {
        $this->saycid = $saycid;

        return $this;
    }

    /**
     * Get saycid
     *
     * @return string 
     */
    public function getSaycid()
    {
        return $this->saycid;
    }

    /**
     * Set dialout
     *
     * @param string $dialout
     * @return VoiceMail
     */
    public function setDialout($dialout)
    {
        $this->dialout = $dialout;

        return $this;
    }

    /**
     * Get dialout
     *
     * @return string 
     */
    public function getDialout()
    {
        return $this->dialout;
    }

    /**
     * Set callback
     *
     * @param string $callback
     * @return VoiceMail
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * Get callback
     *
     * @return string 
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Set review
     *
     * @param string $review
     * @return VoiceMail
     */
    public function setReview($review)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * Get review
     *
     * @return string 
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * Set operator
     *
     * @param string $operator
     * @return VoiceMail
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get operator
     *
     * @return string 
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set envelope
     *
     * @param string $envelope
     * @return VoiceMail
     */
    public function setEnvelope($envelope)
    {
        $this->envelope = $envelope;

        return $this;
    }

    /**
     * Get envelope
     *
     * @return string 
     */
    public function getEnvelope()
    {
        return $this->envelope;
    }

    /**
     * Set sayduration
     *
     * @param string $sayduration
     * @return VoiceMail
     */
    public function setSayduration($sayduration)
    {
        $this->sayduration = $sayduration;

        return $this;
    }

    /**
     * Get sayduration
     *
     * @return string 
     */
    public function getSayduration()
    {
        return $this->sayduration;
    }

    /**
     * Set saydurationm
     *
     * @param integer $saydurationm
     * @return VoiceMail
     */
    public function setSaydurationm($saydurationm)
    {
        $this->saydurationm = $saydurationm;

        return $this;
    }

    /**
     * Get saydurationm
     *
     * @return integer 
     */
    public function getSaydurationm()
    {
        return $this->saydurationm;
    }

    /**
     * Set sendvoicemail
     *
     * @param string $sendvoicemail
     * @return VoiceMail
     */
    public function setSendvoicemail($sendvoicemail)
    {
        $this->sendvoicemail = $sendvoicemail;

        return $this;
    }

    /**
     * Get sendvoicemail
     *
     * @return string 
     */
    public function getSendvoicemail()
    {
        return $this->sendvoicemail;
    }

    /**
     * Set deleteMessage
     *
     * @param string $deleteMessage
     * @return VoiceMail
     */
    public function setDeleteMessage($deleteMessage)
    {
        $this->deleteMessage = $deleteMessage;

        return $this;
    }

    /**
     * Get deleteMessage
     *
     * @return string 
     */
    public function getDeleteMessage()
    {
        return $this->deleteMessage;
    }

    /**
     * Set nextaftercmd
     *
     * @param string $nextaftercmd
     * @return VoiceMail
     */
    public function setNextaftercmd($nextaftercmd)
    {
        $this->nextaftercmd = $nextaftercmd;

        return $this;
    }

    /**
     * Get nextaftercmd
     *
     * @return string 
     */
    public function getNextaftercmd()
    {
        return $this->nextaftercmd;
    }

    /**
     * Set forcename
     *
     * @param string $forcename
     * @return VoiceMail
     */
    public function setForcename($forcename)
    {
        $this->forcename = $forcename;

        return $this;
    }

    /**
     * Get forcename
     *
     * @return string 
     */
    public function getForcename()
    {
        return $this->forcename;
    }

    /**
     * Set forcegreetings
     *
     * @param string $forcegreetings
     * @return VoiceMail
     */
    public function setForcegreetings($forcegreetings)
    {
        $this->forcegreetings = $forcegreetings;

        return $this;
    }

    /**
     * Get forcegreetings
     *
     * @return string 
     */
    public function getForcegreetings()
    {
        return $this->forcegreetings;
    }

    /**
     * Set hidefromdir
     *
     * @param string $hidefromdir
     * @return VoiceMail
     */
    public function setHidefromdir($hidefromdir)
    {
        $this->hidefromdir = $hidefromdir;

        return $this;
    }

    /**
     * Get hidefromdir
     *
     * @return string 
     */
    public function getHidefromdir()
    {
        return $this->hidefromdir;
    }

    /**
     * Set stamp
     *
     * @param \DateTime $stamp
     * @return VoiceMail
     */
    public function setStamp($stamp)
    {
        $this->stamp = $stamp;

        return $this;
    }

    /**
     * Get stamp
     *
     * @return \DateTime 
     */
    public function getStamp()
    {
        return $this->stamp;
    }

    /**
     * Set attachfmt
     *
     * @param string $attachfmt
     * @return VoiceMail
     */
    public function setAttachfmt($attachfmt)
    {
        $this->attachfmt = $attachfmt;

        return $this;
    }

    /**
     * Get attachfmt
     *
     * @return string 
     */
    public function getAttachfmt()
    {
        return $this->attachfmt;
    }

    /**
     * Set searchcontexts
     *
     * @param string $searchcontexts
     * @return VoiceMail
     */
    public function setSearchcontexts($searchcontexts)
    {
        $this->searchcontexts = $searchcontexts;

        return $this;
    }

    /**
     * Get searchcontexts
     *
     * @return string 
     */
    public function getSearchcontexts()
    {
        return $this->searchcontexts;
    }

    /**
     * Set cidinternalcontexts
     *
     * @param string $cidinternalcontexts
     * @return VoiceMail
     */
    public function setCidinternalcontexts($cidinternalcontexts)
    {
        $this->cidinternalcontexts = $cidinternalcontexts;

        return $this;
    }

    /**
     * Get cidinternalcontexts
     *
     * @return string 
     */
    public function getCidinternalcontexts()
    {
        return $this->cidinternalcontexts;
    }

    /**
     * Set exitcontext
     *
     * @param string $exitcontext
     * @return VoiceMail
     */
    public function setExitcontext($exitcontext)
    {
        $this->exitcontext = $exitcontext;

        return $this;
    }

    /**
     * Get exitcontext
     *
     * @return string 
     */
    public function getExitcontext()
    {
        return $this->exitcontext;
    }

    /**
     * Set volgain
     *
     * @param string $volgain
     * @return VoiceMail
     */
    public function setVolgain($volgain)
    {
        $this->volgain = $volgain;

        return $this;
    }

    /**
     * Get volgain
     *
     * @return string 
     */
    public function getVolgain()
    {
        return $this->volgain;
    }

    /**
     * Set tempgreetwarn
     *
     * @param string $tempgreetwarn
     * @return VoiceMail
     */
    public function setTempgreetwarn($tempgreetwarn)
    {
        $this->tempgreetwarn = $tempgreetwarn;

        return $this;
    }

    /**
     * Get tempgreetwarn
     *
     * @return string 
     */
    public function getTempgreetwarn()
    {
        return $this->tempgreetwarn;
    }

    /**
     * Set messagewrap
     *
     * @param string $messagewrap
     * @return VoiceMail
     */
    public function setMessagewrap($messagewrap)
    {
        $this->messagewrap = $messagewrap;

        return $this;
    }

    /**
     * Get messagewrap
     *
     * @return string 
     */
    public function getMessagewrap()
    {
        return $this->messagewrap;
    }

    /**
     * Set minpassword
     *
     * @param integer $minpassword
     * @return VoiceMail
     */
    public function setMinpassword($minpassword)
    {
        $this->minpassword = $minpassword;

        return $this;
    }

    /**
     * Get minpassword
     *
     * @return integer 
     */
    public function getMinpassword()
    {
        return $this->minpassword;
    }

    /**
     * Set vmPassword
     *
     * @param string $vmPassword
     * @return VoiceMail
     */
    public function setVmPassword($vmPassword)
    {
        $this->vmPassword = $vmPassword;

        return $this;
    }

    /**
     * Get vmPassword
     *
     * @return string 
     */
    public function getVmPassword()
    {
        return $this->vmPassword;
    }

    /**
     * Set vmNewpassword
     *
     * @param string $vmNewpassword
     * @return VoiceMail
     */
    public function setVmNewpassword($vmNewpassword)
    {
        $this->vmNewpassword = $vmNewpassword;

        return $this;
    }

    /**
     * Get vmNewpassword
     *
     * @return string 
     */
    public function getVmNewpassword()
    {
        return $this->vmNewpassword;
    }

    /**
     * Set vmPasschanged
     *
     * @param string $vmPasschanged
     * @return VoiceMail
     */
    public function setVmPasschanged($vmPasschanged)
    {
        $this->vmPasschanged = $vmPasschanged;

        return $this;
    }

    /**
     * Get vmPasschanged
     *
     * @return string 
     */
    public function getVmPasschanged()
    {
        return $this->vmPasschanged;
    }

    /**
     * Set vmReenterpassword
     *
     * @param string $vmReenterpassword
     * @return VoiceMail
     */
    public function setVmReenterpassword($vmReenterpassword)
    {
        $this->vmReenterpassword = $vmReenterpassword;

        return $this;
    }

    /**
     * Get vmReenterpassword
     *
     * @return string 
     */
    public function getVmReenterpassword()
    {
        return $this->vmReenterpassword;
    }

    /**
     * Set vmMismatch
     *
     * @param string $vmMismatch
     * @return VoiceMail
     */
    public function setVmMismatch($vmMismatch)
    {
        $this->vmMismatch = $vmMismatch;

        return $this;
    }

    /**
     * Get vmMismatch
     *
     * @return string 
     */
    public function getVmMismatch()
    {
        return $this->vmMismatch;
    }

    /**
     * Set vmInvalidPassword
     *
     * @param string $vmInvalidPassword
     * @return VoiceMail
     */
    public function setVmInvalidPassword($vmInvalidPassword)
    {
        $this->vmInvalidPassword = $vmInvalidPassword;

        return $this;
    }

    /**
     * Get vmInvalidPassword
     *
     * @return string 
     */
    public function getVmInvalidPassword()
    {
        return $this->vmInvalidPassword;
    }

    /**
     * Set vmPlsTryAgain
     *
     * @param string $vmPlsTryAgain
     * @return VoiceMail
     */
    public function setVmPlsTryAgain($vmPlsTryAgain)
    {
        $this->vmPlsTryAgain = $vmPlsTryAgain;

        return $this;
    }

    /**
     * Get vmPlsTryAgain
     *
     * @return string 
     */
    public function getVmPlsTryAgain()
    {
        return $this->vmPlsTryAgain;
    }

    /**
     * Set listenControlForwardKey
     *
     * @param string $listenControlForwardKey
     * @return VoiceMail
     */
    public function setListenControlForwardKey($listenControlForwardKey)
    {
        $this->listenControlForwardKey = $listenControlForwardKey;

        return $this;
    }

    /**
     * Get listenControlForwardKey
     *
     * @return string 
     */
    public function getListenControlForwardKey()
    {
        return $this->listenControlForwardKey;
    }

    /**
     * Set listenControlReverseKey
     *
     * @param string $listenControlReverseKey
     * @return VoiceMail
     */
    public function setListenControlReverseKey($listenControlReverseKey)
    {
        $this->listenControlReverseKey = $listenControlReverseKey;

        return $this;
    }

    /**
     * Get listenControlReverseKey
     *
     * @return string 
     */
    public function getListenControlReverseKey()
    {
        return $this->listenControlReverseKey;
    }

    /**
     * Set listenControlPauseKey
     *
     * @param string $listenControlPauseKey
     * @return VoiceMail
     */
    public function setListenControlPauseKey($listenControlPauseKey)
    {
        $this->listenControlPauseKey = $listenControlPauseKey;

        return $this;
    }

    /**
     * Get listenControlPauseKey
     *
     * @return string 
     */
    public function getListenControlPauseKey()
    {
        return $this->listenControlPauseKey;
    }

    /**
     * Set listenControlRestartKey
     *
     * @param string $listenControlRestartKey
     * @return VoiceMail
     */
    public function setListenControlRestartKey($listenControlRestartKey)
    {
        $this->listenControlRestartKey = $listenControlRestartKey;

        return $this;
    }

    /**
     * Get listenControlRestartKey
     *
     * @return string 
     */
    public function getListenControlRestartKey()
    {
        return $this->listenControlRestartKey;
    }

    /**
     * Set listenControlStopKey
     *
     * @param string $listenControlStopKey
     * @return VoiceMail
     */
    public function setListenControlStopKey($listenControlStopKey)
    {
        $this->listenControlStopKey = $listenControlStopKey;

        return $this;
    }

    /**
     * Get listenControlStopKey
     *
     * @return string 
     */
    public function getListenControlStopKey()
    {
        return $this->listenControlStopKey;
    }

    /**
     * Set backupdeleted
     *
     * @param string $backupdeleted
     * @return VoiceMail
     */
    public function setBackupdeleted($backupdeleted)
    {
        $this->backupdeleted = $backupdeleted;

        return $this;
    }

    /**
     * Get backupdeleted
     *
     * @return string 
     */
    public function getBackupdeleted()
    {
        return $this->backupdeleted;
    }
}
