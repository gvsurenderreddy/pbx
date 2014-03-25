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
        $em = $this->getDoctrine()->getManager();

        $dynamicIP = $em->getRepository('VoIPDynamicIPDaemonBundle:DynamicIP')->findOneBy(array(
        	'token' => $token
        ));
        if (!$dynamicIP) throw $this->createNotFoundException('Unable to find DynamicIP entity.');
		
		$response = new JsonResponse();
		$response->setData(array(
		    'token' => $token,
			'ip' => $this->container->get('request')->getClientIp()
		));
        return $response;
    }
}
