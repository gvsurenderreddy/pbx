<?php

namespace VoIP\PBX\RealTimeBundle\Extra;

use VoIP\PBX\RealTimeBundle\Entity\SipPeer;
use VoIP\PBX\RealTimeBundle\Entity\Extension;
use VoIP\PBX\RealTimeBundle\Entity\Conf;

class Sync {
	
	function __construct() {
		// Construction
	}
	
	public function companyToContextExtensionConf($company)
	{
		if (!$conf = $company->getAstContextExtensionConf()) $conf = new Conf();
		$conf->setCatMetric(0);
		$conf->setVarMetric(0);
		$conf->setFilename('extensions.conf');
		$conf->setCategory($company->getContext());
		$conf->setVarName('switch');
		$conf->setVarVal('Realtime/@');
		return $conf;
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