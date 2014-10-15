<?php

namespace VoIP\UI\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use VoIP\Company\SubscriptionsBundle\Entity\Subscription;

/**
 * @Route("/admin/subscriptions")
 */

class SubscriptionsController extends Controller
{
    /**
     * @Route("/", name="admin_subscriptions")
     * @Template()
     */
    public function indexAction()
    {
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
		    'SELECT s
		    FROM VoIPCompanySubscriptionsBundle:Subscription s
		    ORDER BY s.createdAt ASC'
		);
		$subscriptions = $query->getResult();
        return array(
        	'subscriptions' => $subscriptions
        );
    }
	
    /**
     * @Route("/new", name="admin_subscriptions_new")
     * @Template()
	 * @Method("GET")
     */
    public function newAction()
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
     * @Route("/new")
     * @Method("POST")
     */
    public function createAction()
    {
		$request = $this->getRequest();
		$name = $request->get('name');
		$did = $request->get('did');
		$companyID = $request->get('company');
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository('VoIPCompanyStructureBundle:Company')->find($companyID);
		if (!$company) throw $this->createNotFoundException('Unable to find $company entity.');
		$subscription = new Subscription();
		$subscription->setName($name);
		$subscription->setDid($did);
		$subscription->setCompany($company);
		$em->persist($subscription);
		$em->flush();
        return $this->redirect($this->generateUrl('admin_subscriptions'));
    }
}
