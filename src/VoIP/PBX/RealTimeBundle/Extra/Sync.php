<?php

namespace VoIP\PBX\RealTimeBundle\Extra;

use VoIP\PBX\RealTimeBundle\Entity\SipPeer;
use VoIP\PBX\RealTimeBundle\Entity\Extension;
use VoIP\PBX\RealTimeBundle\Entity\Conf;

class Sync {
	
	function __construct() {
		// Construction
	}
	
	public function subscriptionToPeer($subscription)
	{
		if (!$peer = $subscription->getAstPeer()) $peer = new SipPeer();
		$peer->setName($subscription->getUsername());
		$peer->setSecret($subscription->getSecret());
		$peer->setHost($subscription->getHost());
		$peer->setType('peer');
		$peer->setAllow('gsm');
		$peer->setDefaultUser($subscription->getUsername());
		$peer->setInsecure('invite');
		$peer->setDtfmode('rfc2833');
		return $peer;
	}
	
	public function phoneToPeer($phone)
	{
		if (!$sippeer = $phone->getAstPeer()) $sippeer = new SipPeer();
		$sippeer->setName($phone->getHash());
		$sippeer->setSecret($sippeer->getSecret() ? $sippeer->getSecret() : hash('sha1', uniqid('', true)));
		$sippeer->setContext('internal');
		$sippeer->setHost('dynamic');
		$sippeer->setNat('force_rport,comedia');
		$sippeer->setType('friend');
		$sippeer->setAllow('gsm');
		$sippeer->setPermit('0.0.0.0/0.0.0.0');
		$sippeer->setDynamic('yes');
		$sippeer->setQualify(200);
		$sippeer->setDirectmedia('no');
		$sippeer->setDisallow(null);
		$sippeer->setFromUser($phone->getExtension());
		$sippeer->setDefaultUser($phone->getHash());
		return $sippeer;
	}
	
	public function phoneToExtension($phone)
	{
		if (!$extension = $phone->getAstExtension()) $extension = new Extension();
		$extension->setContext($phone->getOffice()->getCompany()->getContext());
		$extension->setExten($phone->getExtension());
		$extension->setPriority(1);
		$extension->setApp('Dial');
		$extension->setAppdata('SIP/'.$phone->getHash());
		return $extension;
	}
}