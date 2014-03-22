<?php

namespace VoIP\PBX\RealTimeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SipPeer
 *
 * @ORM\Table(name="ast_sippeers")
 * @ORM\Entity
 */
class SipPeer
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
	
    /**
     * @var string
     *
     * @ORM\Column(name="ipaddr", type="text", length=15, nullable=true)
     */
    private $ipaddr;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="port", type="integer", nullable=true)
     */
    private $port;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="regseconds", type="integer", nullable=true)
     */
    private $regseconds;
	
    /**
     * @var string
     *
     * @ORM\Column(name="fullcontact", type="text", length=35, nullable=true)
     */
    private $fullcontact;
	
    /**
     * @var string
     *
     * @ORM\Column(name="regserver", type="text", length=20, nullable=true)
     */
    private $regserver;
	
    /**
     * @var string
     *
     * @ORM\Column(name="useragent", type="text", length=20, nullable=true)
     */
    private $useragent;
	
    /**
     * @var string
     *
     * @ORM\Column(name="lastms", type="string", length=255, nullable=true)
     */
    private $lastms;
	
    /**
     * @var string
     *
     * @ORM\Column(name="host", type="string", length=255)
     */
    private $host = 'dynamic';
	
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10)
     */
    private $type = 'friend';
	
    /**
     * @var string
     *
     * @ORM\Column(name="context", type="string", length=40, nullable=true)
     */
    private $context = 'from-internal';
	
    /**
     * @var string
     *
     * @ORM\Column(name="permit", type="string", length=40, nullable=true)
     */
    private $permit;
	
    /**
     * @var string
     *
     * @ORM\Column(name="deny", type="string", length=40, nullable=true)
     */
    private $deny;
	
    /**
     * @var string
     *
     * @ORM\Column(name="secret", type="string", length=40, nullable=true)
     */
    private $secret;
	
    /**
     * @var string
     *
     * @ORM\Column(name="md5", type="string", length=40, nullable=true)
     */
    private $md5;
	
    /**
     * @var string
     *
     * @ORM\Column(name="remotesecret", type="string", length=40, nullable=true)
     */
    private $remotesecret;
	
    /**
     * @var string
     *
     * @ORM\Column(name="transport", type="string", length=10, nullable=true)
     */
    private $transport;
	
    /**
     * @var string
     *
     * @ORM\Column(name="dtmfmode", type="string", length=20, nullable=true)
     */
    private $dtmfmode;
	
    /**
     * @var string
     *
     * @ORM\Column(name="directmedia", type="string", length=10, nullable=true)
     */
    private $directmedia;
	
    /**
     * @var string
     *
     * @ORM\Column(name="nat", type="string", length=20, nullable=true)
     */
    private $nat = 'route';
	
    /**
     * @var string
     *
     * @ORM\Column(name="callgroup", type="string", length=10, nullable=true)
     */
    private $callgroup;
	
    /**
     * @var string
     *
     * @ORM\Column(name="pickupgroup", type="string", length=10, nullable=true)
     */
    private $pickupgroup;
	
    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=10, nullable=true)
     */
    private $language;
	
    /**
     * @var string
     *
     * @ORM\Column(name="allow", type="string", length=40, nullable=true)
     */
    private $allow;
	
    /**
     * @var string
     *
     * @ORM\Column(name="disallow", type="string", length=40, nullable=true)
     */
    private $disallow;
	
    /**
     * @var string
     *
     * @ORM\Column(name="insecure", type="string", length=40, nullable=true)
     */
    private $insecure;
	
    /**
     * @var string
     *
     * @ORM\Column(name="trustrpid", type="string", length=10, nullable=true)
     */
    private $trustrpid;
	
    /**
     * @var string
     *
     * @ORM\Column(name="progressinband", type="string", length=10, nullable=true)
     */
    private $progressinband;
	
    /**
     * @var string
     *
     * @ORM\Column(name="qualify", type="string", length=40, nullable=true)
     */
    private $qualify;
	
    /**
     * @var string
     *
     * @ORM\Column(name="callbackextension", type="string", length=40, nullable=true)
     */
    private $callbackextension;
	
    /**
     * @var string
     *
     * @ORM\Column(name="dynamic", type="string", length=10, nullable=true)
     */
    private $dynamic;

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
     * Set name
     *
     * @param string $name
     * @return SipPeer
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set ipaddr
     *
     * @param string $ipaddr
     * @return SipPeer
     */
    public function setIpaddr($ipaddr)
    {
        $this->ipaddr = $ipaddr;

        return $this;
    }

    /**
     * Get ipaddr
     *
     * @return string 
     */
    public function getIpaddr()
    {
        return $this->ipaddr;
    }

    /**
     * Set port
     *
     * @param integer $port
     * @return SipPeer
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return integer 
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set regseconds
     *
     * @param integer $regseconds
     * @return SipPeer
     */
    public function setRegseconds($regseconds)
    {
        $this->regseconds = $regseconds;

        return $this;
    }

    /**
     * Get regseconds
     *
     * @return integer 
     */
    public function getRegseconds()
    {
        return $this->regseconds;
    }

    /**
     * Set fullcontact
     *
     * @param string $fullcontact
     * @return SipPeer
     */
    public function setFullcontact($fullcontact)
    {
        $this->fullcontact = $fullcontact;

        return $this;
    }

    /**
     * Get fullcontact
     *
     * @return string 
     */
    public function getFullcontact()
    {
        return $this->fullcontact;
    }

    /**
     * Set regserver
     *
     * @param string $regserver
     * @return SipPeer
     */
    public function setRegserver($regserver)
    {
        $this->regserver = $regserver;

        return $this;
    }

    /**
     * Get regserver
     *
     * @return string 
     */
    public function getRegserver()
    {
        return $this->regserver;
    }

    /**
     * Set useragent
     *
     * @param string $useragent
     * @return SipPeer
     */
    public function setUseragent($useragent)
    {
        $this->useragent = $useragent;

        return $this;
    }

    /**
     * Get useragent
     *
     * @return string 
     */
    public function getUseragent()
    {
        return $this->useragent;
    }

    /**
     * Set lastms
     *
     * @param string $lastms
     * @return SipPeer
     */
    public function setLastms($lastms)
    {
        $this->lastms = $lastms;

        return $this;
    }

    /**
     * Get lastms
     *
     * @return string 
     */
    public function getLastms()
    {
        return $this->lastms;
    }

    /**
     * Set host
     *
     * @param string $host
     * @return SipPeer
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host
     *
     * @return string 
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return SipPeer
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set context
     *
     * @param string $context
     * @return SipPeer
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
     * Set permit
     *
     * @param string $permit
     * @return SipPeer
     */
    public function setPermit($permit)
    {
        $this->permit = $permit;

        return $this;
    }

    /**
     * Get permit
     *
     * @return string 
     */
    public function getPermit()
    {
        return $this->permit;
    }

    /**
     * Set deny
     *
     * @param string $deny
     * @return SipPeer
     */
    public function setDeny($deny)
    {
        $this->deny = $deny;

        return $this;
    }

    /**
     * Get deny
     *
     * @return string 
     */
    public function getDeny()
    {
        return $this->deny;
    }

    /**
     * Set secret
     *
     * @param string $secret
     * @return SipPeer
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Get secret
     *
     * @return string 
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set md5
     *
     * @param string $md5
     * @return SipPeer
     */
    public function setMd5($md5)
    {
        $this->md5 = $md5;

        return $this;
    }

    /**
     * Get md5
     *
     * @return string 
     */
    public function getMd5()
    {
        return $this->md5;
    }

    /**
     * Set remotesecret
     *
     * @param string $remotesecret
     * @return SipPeer
     */
    public function setRemotesecret($remotesecret)
    {
        $this->remotesecret = $remotesecret;

        return $this;
    }

    /**
     * Get remotesecret
     *
     * @return string 
     */
    public function getRemotesecret()
    {
        return $this->remotesecret;
    }

    /**
     * Set transport
     *
     * @param string $transport
     * @return SipPeer
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Get transport
     *
     * @return string 
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Set dtmfmode
     *
     * @param string $dtmfmode
     * @return SipPeer
     */
    public function setDtmfmode($dtmfmode)
    {
        $this->dtmfmode = $dtmfmode;

        return $this;
    }

    /**
     * Get dtmfmode
     *
     * @return string 
     */
    public function getDtmfmode()
    {
        return $this->dtmfmode;
    }

    /**
     * Set directmedia
     *
     * @param string $directmedia
     * @return SipPeer
     */
    public function setDirectmedia($directmedia)
    {
        $this->directmedia = $directmedia;

        return $this;
    }

    /**
     * Get directmedia
     *
     * @return string 
     */
    public function getDirectmedia()
    {
        return $this->directmedia;
    }

    /**
     * Set nat
     *
     * @param string $nat
     * @return SipPeer
     */
    public function setNat($nat)
    {
        $this->nat = $nat;

        return $this;
    }

    /**
     * Get nat
     *
     * @return string 
     */
    public function getNat()
    {
        return $this->nat;
    }

    /**
     * Set callgroup
     *
     * @param string $callgroup
     * @return SipPeer
     */
    public function setCallgroup($callgroup)
    {
        $this->callgroup = $callgroup;

        return $this;
    }

    /**
     * Get callgroup
     *
     * @return string 
     */
    public function getCallgroup()
    {
        return $this->callgroup;
    }

    /**
     * Set pickupgroup
     *
     * @param string $pickupgroup
     * @return SipPeer
     */
    public function setPickupgroup($pickupgroup)
    {
        $this->pickupgroup = $pickupgroup;

        return $this;
    }

    /**
     * Get pickupgroup
     *
     * @return string 
     */
    public function getPickupgroup()
    {
        return $this->pickupgroup;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return SipPeer
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set allow
     *
     * @param string $allow
     * @return SipPeer
     */
    public function setAllow($allow)
    {
        $this->allow = $allow;

        return $this;
    }

    /**
     * Get allow
     *
     * @return string 
     */
    public function getAllow()
    {
        return $this->allow;
    }

    /**
     * Set disallow
     *
     * @param string $disallow
     * @return SipPeer
     */
    public function setDisallow($disallow)
    {
        $this->disallow = $disallow;

        return $this;
    }

    /**
     * Get disallow
     *
     * @return string 
     */
    public function getDisallow()
    {
        return $this->disallow;
    }

    /**
     * Set insecure
     *
     * @param string $insecure
     * @return SipPeer
     */
    public function setInsecure($insecure)
    {
        $this->insecure = $insecure;

        return $this;
    }

    /**
     * Get insecure
     *
     * @return string 
     */
    public function getInsecure()
    {
        return $this->insecure;
    }

    /**
     * Set trustrpid
     *
     * @param string $trustrpid
     * @return SipPeer
     */
    public function setTrustrpid($trustrpid)
    {
        $this->trustrpid = $trustrpid;

        return $this;
    }

    /**
     * Get trustrpid
     *
     * @return string 
     */
    public function getTrustrpid()
    {
        return $this->trustrpid;
    }

    /**
     * Set progressinband
     *
     * @param string $progressinband
     * @return SipPeer
     */
    public function setProgressinband($progressinband)
    {
        $this->progressinband = $progressinband;

        return $this;
    }

    /**
     * Get progressinband
     *
     * @return string 
     */
    public function getProgressinband()
    {
        return $this->progressinband;
    }

    /**
     * Set qualify
     *
     * @param string $qualify
     * @return SipPeer
     */
    public function setQualify($qualify)
    {
        $this->qualify = $qualify;

        return $this;
    }

    /**
     * Get qualify
     *
     * @return string 
     */
    public function getQualify()
    {
        return $this->qualify;
    }

    /**
     * Set callbackextension
     *
     * @param string $callbackextension
     * @return SipPeer
     */
    public function setCallbackextension($callbackextension)
    {
        $this->callbackextension = $callbackextension;

        return $this;
    }

    /**
     * Get callbackextension
     *
     * @return string 
     */
    public function getCallbackextension()
    {
        return $this->callbackextension;
    }

    /**
     * Set dynamic
     *
     * @param string $dynamic
     * @return SipPeer
     */
    public function setDynamic($dynamic)
    {
        $this->dynamic = $dynamic;

        return $this;
    }

    /**
     * Get dynamic
     *
     * @return string 
     */
    public function getDynamic()
    {
        return $this->dynamic;
    }

    /**
     * Set canreinvite
     *
     * @param string $canreinvite
     * @return SipPeer
     */
    public function setCanreinvite($canreinvite)
    {
        $this->canreinvite = $canreinvite;

        return $this;
    }

    /**
     * Get canreinvite
     *
     * @return string 
     */
    public function getCanreinvite()
    {
        return $this->canreinvite;
    }

    /**
     * Set directrtpsetup
     *
     * @param string $directrtpsetup
     * @return SipPeer
     */
    public function setDirectrtpsetup($directrtpsetup)
    {
        $this->directrtpsetup = $directrtpsetup;

        return $this;
    }

    /**
     * Get directrtpsetup
     *
     * @return string 
     */
    public function getDirectrtpsetup()
    {
        return $this->directrtpsetup;
    }
}
