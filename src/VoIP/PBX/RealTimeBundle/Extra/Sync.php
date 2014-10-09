<?php

namespace VoIP\PBX\RealTimeBundle\Extra;

use VoIP\PBX\RealTimeBundle\Entity\SipPeer;
use VoIP\PBX\RealTimeBundle\Entity\Extension;
use VoIP\PBX\RealTimeBundle\Entity\Conf;
use VoIP\PBX\RealTimeBundle\Entity\VoiceMail;

class Sync {
	
	public function voicemailToVoicemail($voicemail)
	{
		if (!$vm = $voicemail->getAstVoicemail()) $vm = new VoiceMail();
		$vm->setMailbox($voicemail->getHash());
		$vm->setContext('mailbox');
		$vm->setPassword(rand(1000,9999));
		$vm->setFullname($voicemail->getSubscription()->getCompany()->getName());
		$vm->setEmail('adrien@eudes.co');
		return $vm;
	}
	
	public function outLineToPeer($outLine)
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
		if ($subscription->getType() == 'spa3102') {
			$peer->setDynamic('yes');
		}
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
		$sippeer->setQualify(5000);
		$sippeer->setQualifyfreq(20);
		$sippeer->setDirectmedia('no');
		$sippeer->setDisallow(null);
		$sippeer->setFromUser(null);
		$sippeer->setDefaultUser($phone->getHash());
		return $sippeer;
	}
	
	public function itemToExtension($subscription, $item, $n)
	{
		if (!$extension = $item->getAstExtension()) $extension = new Extension();
		$extension->setContext('public');
		$extension->setExten($subscription->getHash());
		$extension->setPriority($n);
		switch ($item->getType()) {
			case 'extension':
				$app = 'Dial';
				$appData = 'SIP/'.$item->getPhone()->getHash();
				break;
			
			default:
				# code...
				break;
		}
		$extension->setApp($app);
		$extension->setAppdata($appData);
		return $extension;
		
	}
}