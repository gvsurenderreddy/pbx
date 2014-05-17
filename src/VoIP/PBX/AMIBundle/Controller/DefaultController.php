<?php

namespace VoIP\PBX\AMIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route("/ami")
 */

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
	 * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
	    $ci = curl_init();
		curl_setopt($ci, CURLOPT_USERPWD, 'mark:secret');
		curl_setopt($ci, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST); 
	    curl_setopt($ci, CURLOPT_URL, 'http://pbx.fortyeight.co:8088/arawman?action=Sippeers');
	    curl_setopt($ci, CURLOPT_PORT, 8088);
	    curl_setopt($ci, CURLOPT_TIMEOUT, 200);
	    curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ci, CURLOPT_FORBID_REUSE, 0);
		curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ci, CURLOPT_POSTFIELDS, array());
	    $data = curl_exec($ci);
		$data = explode("\r\n\r\n", $data);
		$header = $this->parseDoublePoints($data[0]);
		$footer = $this->parseDoublePoints($data[count($data) - 2]);
		$body = array();
		for ($i=1; $i < count($data) - 2; $i++) { 
			$body[] = $this->parseDoublePoints($data[$i]);
		}
		$sips = array();
		foreach ($body as $b) {
			$sips[$b['ObjectName']] = strpos($b['Status'],'OK') !== false;
		}
		$response = new JsonResponse();
		$response->setData(array(
			'header' => $header,
		    'body' => $body,
			'footer' => $footer,
			'sips' => $sips
		));
		return $response;
    }
	
	public function parseDoublePoints($text)
	{
		$tmp = explode("\r\n", $text);
		$res = array();
		foreach ($tmp as $key => $value) {
			$tmp = explode(": ", $value);
			$res[$tmp[0]] = $tmp[1];
		}
		return $res;
	}
}
