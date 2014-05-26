<?php

namespace VoIP\UI\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin")
 */

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
	
    /**
     * @Route("/subscriptions")
     * @Template()
     */
    public function subscriptionsAction()
    {
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
		    'SELECT s
		    FROM VoIPCompanySubscriptionsBundle:Subscription s
		    ORDER BY s.hash ASC'
		);

		$subscriptions = $query->getResult();
        return array(
        	'subscriptions' => $subscriptions
        );
    }
}
