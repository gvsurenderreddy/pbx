<?php

namespace VoIP\PBX\RealTimeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SipReg
 *
 * @ORM\Table(name="ast_sipregs")
 * @ORM\Entity
 */
class SipReg
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
     * @ORM\Column(name="peer", type="string", length=50, nullable=true)
     */
    private $peer;

    /**
     * @var string
     *
     * @ORM\Column(name="transport", type="string", length=50, nullable=true)
     */
    private $transport;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=50, nullable=true)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=50, nullable=true)
     */
    private $domain;

    /**
     * @var string
     *
     * @ORM\Column(name="secret", type="string", length=50, nullable=true)
     */
    private $secret;

    /**
     * @var string
     *
     * @ORM\Column(name="authuser", type="string", length=50, nullable=true)
     */
    private $authuser;

    /**
     * @var string
     *
     * @ORM\Column(name="host", type="string", length=50, nullable=true)
     */
    private $host;

    /**
     * @var string
     *
     * @ORM\Column(name="port", type="string", length=10, nullable=true)
     */
    private $port;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=50, nullable=true)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="expiry", type="string", length=50, nullable=true)
     */
    private $expiry;


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
     * Set peer
     *
     * @param string $peer
     * @return SipReg
     */
    public function setPeer($peer)
    {
        $this->peer = $peer;

        return $this;
    }

    /**
     * Get peer
     *
     * @return string 
     */
    public function getPeer()
    {
        return $this->peer;
    }

    /**
     * Set transport
     *
     * @param string $transport
     * @return SipReg
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
     * Set user
     *
     * @param string $user
     * @return SipReg
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set domain
     *
     * @param string $domain
     * @return SipReg
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string 
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set secret
     *
     * @param string $secret
     * @return SipReg
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
     * Set authuser
     *
     * @param string $authuser
     * @return SipReg
     */
    public function setAuthuser($authuser)
    {
        $this->authuser = $authuser;

        return $this;
    }

    /**
     * Get authuser
     *
     * @return string 
     */
    public function getAuthuser()
    {
        return $this->authuser;
    }

    /**
     * Set host
     *
     * @param string $host
     * @return SipReg
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
     * Set port
     *
     * @param string $port
     * @return SipReg
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return string 
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return SipReg
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set expiry
     *
     * @param string $expiry
     * @return SipReg
     */
    public function setExpiry($expiry)
    {
        $this->expiry = $expiry;

        return $this;
    }

    /**
     * Get expiry
     *
     * @return string 
     */
    public function getExpiry()
    {
        return $this->expiry;
    }
}
