<?php

namespace VoIP\UI\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
    /**
     * @Route("/company/{reference}/edit", name="admin_company_edit")
	 * @Method("GET")
     * @Template()
     */
    public function editCompanyAction($reference)
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
    /**
     * @Route("/company/{reference}/edit")
	 * @Method("POST")
     * @Template()
     */
    public function updateCompanyAction($reference)
    {
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository('VoIPCompanyStructureBundle:Company')->findOneBy(array(
			'hash' => $reference
		));
		if (!$company) throw $this->createNotFoundException('Unable to find $company entity.');
		$request = $this->getRequest();
		$master = $request->get('master');
		$company->setIsMaster($master);
		$em->flush();

        return $this->redirect($this->generateUrl('admin_company', array(
        	'reference' => $reference
        )));
    }
}
