<?php

namespace VoIP\PBX\RealTimeBundle\Extra;

use VoIP\PBX\RealTimeBundle\Entity\SipPeer;
use VoIP\PBX\RealTimeBundle\Entity\Extension;
use VoIP\PBX\RealTimeBundle\Entity\Conf;
use VoIP\PBX\RealTimeBundle\Entity\VoiceMail;

class Sync {
	
	public function voicemailToVoicemail($voicemail)
	{
		$fields = array(
			'mailbox' => urlencode($voicemail->getHash()),
			'context' => urlencode('mailbox'),
			'password' => urlencode(rand(1000,9999)),
			'fullname' => urlencode($voicemail->getSubscription()->getCompany()->getName()),
			'email' => urlencode('adrien@eudes.co'),
		);
		$fields_string = '';
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, 'http://api.fortyeight.co/rt/api/vm/edit');
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		$response = json_decode(curl_exec($ch));
		curl_close($ch);
		return null;
	}
	
	public function outLineToPeer($outLine)
	{
		
		$fields = array(
			'name' => urlencode($outLine->getUsername()),
			'secret' => urlencode($outLine->getSecret()),
			'host' => urlencode($outLine->getHost()),
			'type' => urlencode('peer'),
			'allow' => urlencode('gsm'),
			//'dynamic' => urlencode('yes'), //SPA3102
			'defaultuser' => urlencode($outLine->getUsername()),
			'insecure' => urlencode('invite'),
			'dtfmode' => urlencode('rfc2833')
		);
		$fields_string = '';
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, 'http://api.fortyeight.co/rt/api/peer/edit');
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		$response = json_decode(curl_exec($ch));
		curl_close($ch);
		return null;
	}
	
	public function phoneToPeer($phone)
	{
		$fields = array(
			'name' => urlencode($phone->getHash()),
			'secret' => urlencode($phone->getSecret()),
			'host' => urlencode('dynamic'),
			'nat' => urlencode('force_rport,comedia'),
			'type' => urlencode('friend'),
			'allow' => urlencode('gsm'),
			'permit' => urlencode('0.0.0.0/0.0.0.0'),
			'dynamic' => urlencode('yes'),
			'qualify' => urlencode(5000),
			'qualifyfreq' => urlencode(20),
			'directmedia' => urlencode('no'),
			'disallow' => urlencode(null),
			'fromuser' => urlencode(null),
			'defaultuser' => urlencode($phone->getHash()),
			'insecure' => urlencode(null),
			'dtfmode' => urlencode(null)
		);
		$fields_string = '';
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, 'http://api.fortyeight.co/rt/api/peer/edit');
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		$response = json_decode(curl_exec($ch));
		curl_close($ch);
		return null;
	}
	
	public function removePeer($name)
	{
		$fields = array(
			'name' => urlencode($name),
		);
		$fields_string = '';
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, 'http://api.fortyeight.co/rt/api/peer/remove');
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		$response = json_decode(curl_exec($ch));
		curl_close($ch);
		return null;
	}
}