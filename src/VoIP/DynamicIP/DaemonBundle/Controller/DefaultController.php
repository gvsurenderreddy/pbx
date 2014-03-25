<?php

namespace VoIP\DynamicIP\DaemonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/dynamic-ip/{token}")
     * @Template()
     */
    public function indexAction($token)
    {
		// Check the credential
        $em = $this->getDoctrine()->getManager();
        $dynamicIP = $em->getRepository('VoIPDynamicIPDaemonBundle:DynamicIP')->findOneBy(array(
        	'token' => $token
        ));
        if (!$dynamicIP) throw $this->createNotFoundException('Unable to find DynamicIP entity.');
		
		// Get the IP
		$currentIP = $this->container->get('request')->getClientIp();

		// Test the IP (prevention)
		$testIP = preg_match('/^[1-9][0-9]+[1-9]+\.[1-9][0-9]+[1-9]+\.[1-9][0-9]+[1-9]+\.[1-9][0-9]+[1-9]+$/', $currentIP);
		
		if ($testIP) {
			$previousIP = $dynamicIP->getCurrentIP();
			if ($previousIP != $currentIP) {
				$dynamicIP->setCurrentIP($currentIP);
				$dynamicIP->setPreviousIP($previousIP);
				
				$ec2 = $this->container->get('aws_ec2');
				
				if ($previousIP) {
					$revokeResp = $ec2->revoke_security_group_ingress(array(
						'GroupId' => 'sg-2f4a8b7a',
						'IpPermissions' => array(
							'IpProtocol' => 'udp',
							'FromPort' => '0',
							'ToPort' => '65535',
							'IpRanges' => array(
								array('CidrIp' => $previousIP.'/32'),
							)
						)
					));
					$dynamicIP->setRevokeSuccess($revokeResp->isOK());
				}
				$authorizeResp = $ec2->authorize_security_group_ingress(array(
					'GroupId' => 'sg-2f4a8b7a',
					'IpPermissions' => array(
						'IpProtocol' => 'udp',
						'FromPort' => '0',
						'ToPort' => '65535',
						'IpRanges' => array(
							array('CidrIp' => $currentIP.'/32'),
						)
					)
				));
				$dynamicIP->setAuthorizeSuccess($authorizeResp->isOK());
			}
			$em->flush();
		}
		
		$response = new JsonResponse();
		$response->setData(array(
		    'token' => $token,
			'updated_at' => $dynamicIP->getUpdatedAt()->format('Y-m-d H:i:s'),
			'ip' => $currentIP,
			'test_ip' => $testIP
		));
        return $response;
    }
}
