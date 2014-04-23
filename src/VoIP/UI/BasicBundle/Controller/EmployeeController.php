<?php

namespace VoIP\UI\BasicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use VoIP\Company\StructureBundle\Entity\Company;
use VoIP\Company\StructureBundle\Entity\Office;
use VoIP\Company\StructureBundle\Entity\Phone;
use VoIP\PBX\RealTimeBundle\Extra\Sync;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/private/e")
 */

class EmployeeController extends Controller
{	
	
    /**
     * @Route("/{hash}/edit", name="ui_employee_edit")
     * @Template()
	 * @Method("GET")
     */
    public function editAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$employee = $em->getRepository('VoIPCompanyStructureBundle:Employee')->findOneBy(array(
			'hash' => $hash
		));
        if (!$employee) throw $this->createNotFoundException('Unable to find Employee entity.');
		$company = $employee->getCompany();
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
        return array(
			'employee' => $employee,
			'company' => $company
		);
    }
	
    /**
     * @Route("/{hash}/edit")
     * @Template()
	 * @Method("POST")
     */
    public function updateAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$employee = $em->getRepository('VoIPCompanyStructureBundle:Employee')->findOneBy(array(
			'hash' => $hash
		));
        if (!$employee) throw $this->createNotFoundException('Unable to find Employee entity.');
		$company = $employee->getCompany();
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		
		$request = $this->getRequest();
		$name = $request->get('name');
		$extension = $request->get('extension');
		
		$employee->setName($name);
		$employee->setExtension($extension);
		
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
    /**
     * @Route("/{hash}/delete", name="ui_employee_delete")
     * @Template()
	 * @Method("GET")
     */
    public function deleteAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$employee = $em->getRepository('VoIPCompanyStructureBundle:Employee')->findOneBy(array(
			'hash' => $hash
		));
        if (!$employee) throw $this->createNotFoundException('Unable to find Employee entity.');
		$company = $employee->getCompany();
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		
		if ($phone = $employee->getPhone()) $phone->setEmployee(null);
		$em->remove($employee);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
}
