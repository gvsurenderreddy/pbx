<?php

namespace VoIP\UI\BasicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use VoIP\Company\StructureBundle\Entity\Company;
use VoIP\Company\StructureBundle\Entity\Office;
use VoIP\PBX\RealTimeBundle\Extra\Sync;

/**
 * @Route("/private/c")
 */

class CompanyController extends Controller
{
	
    /**
     * @Route("/{hash}", name="ui_company")
     * @Template()
	 * @Method("GET")
     */
    public function companyAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository('VoIPCompanyStructureBundle:Company')->findOneBy(array(
			'hash' => $hash
		));
        if (!$company) throw $this->createNotFoundException('Unable to find Company entity.');
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
        return array(
			'company' => $company
		);
    }
	
    /**
     * @Route("/{hash}/new-office", name="ui_company_newoffice")
     * @Template()
	 * @Method("GET")
     */
    public function newOfficeAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository('VoIPCompanyStructureBundle:Company')->findOneBy(array(
			'hash' => $hash
		));
        if (!$company) throw $this->createNotFoundException('Unable to find Company entity.');
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
        return array(
			'company' => $company
		);
    }
	
    /**
     * @Route("/{hash}/new-office")
     * @Template()
	 * @Method("POST")
     */
    public function createOfficeAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository('VoIPCompanyStructureBundle:Company')->findOneBy(array(
			'hash' => $hash
		));
        if (!$company) throw $this->createNotFoundException('Unable to find Company entity.');
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		
		$request = $this->getRequest();
		$name = $request->get('name');
		
		$office = new Office();
		$office->setName($name);
		$office->setCompany($company);
		
		$em->persist($office);
		
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
    /**
     * @Route("/{hash}/delete", name="ui_company_delete")
     * @Template()
	 * @Method("GET")
     */
    public function deleteAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository('VoIPCompanyStructureBundle:Company')->findOneBy(array(
			'hash' => $hash
		));
        if (!$company) throw $this->createNotFoundException('Unable to find Company entity.');
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		
		foreach ($company->getOffices() as $office) {
			foreach ($office->getPhones() as $phone) {
				if ($phone->getAstPeer()) $em->remove($phone->getAstPeer());
				if ($phone->getAstExtension()) $em->remove($phone->getAstExtension());
				$em->remove($phone);
			}
			$em->remove($office);
		}
		$em->remove($company->getAstContextExtensionConf());
		$em->remove($company);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_index'));
    }
}
