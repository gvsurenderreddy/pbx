<?php

namespace VoIP\UI\BasicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use VoIP\Company\StructureBundle\Entity\Company;
use VoIP\PBX\RealTimeBundle\Extra\Sync;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/private")
 */

class DefaultController extends Controller
{
    /**
     * @Route("/", name="ui_index")
     * @Template()
     */
    public function indexAction()
    {
		$user = $this->getUser();
		$companies = $user->getCompanies();
		if (count($companies)) {
			$company = $companies[0];
			return $this->redirect($this->generateUrl('ui_company', array(
				'hash' => $company->getHash()
			)));
		}
        return array(
			'user' => $user
		);
    }
	
    /**
     * @Route("/new-company", name="ui_new_company")
     * @Template()
	 * @Method("GET")
     */
    public function newCompanyAction()
    {
        return array();
    }
	
    /**
     * @Route("/new-company")
	 * @Method("POST")
     */
    public function createCompanyAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$name = $request->get('name');
		// Validator
		$user = $this->getUser();
		$company = new Company();
		$company->setName($name);
		$user->addCompany($company);
		$company->addUser($user);
		$em->persist($company);
		$em->flush();
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
    /**
     * @Route("/test", name="ui_new_company")
     * @Template()
	 * @Method("GET")
     */
    public function testAction()
    {
		$data = array();
		exec('sudo asterisk -x "sip show peers"', $data);
		$response = new JsonResponse();
		$response->setData($data);
        return $response;
    }
}
