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
     * @Route("/", name="admin")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
	
    /**
     * @Route("/subscriptions", name="admin_subscriptions")
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
	
    /**
     * @Route("/clients", name="admin_clients")
     * @Template()
     */
    public function clientsAction()
    {
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
		    'SELECT c
		    FROM VoIPCompanyStructureBundle:Company c
		    ORDER BY c.name ASC'
		);

		$companies = $query->getResult();
        return array(
        	'companies' => $companies
        );
    }
    /**
     * @Route("/company/{reference}", name="admin_company")
     * @Template()
     */
    public function companyAction($reference)
    {
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository('VoIPCompanyStructureBundle:Company')->findOneBy(array(
			'hash' => $reference
		));
		if (!$company) throw $this->createNotFoundException('Unable to find $company entity.');

        return array(
        	'company' => $company
        );
    }
}
