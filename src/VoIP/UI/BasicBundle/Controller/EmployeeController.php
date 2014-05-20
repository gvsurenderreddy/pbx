<?php

namespace VoIP\UI\BasicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use VoIP\Company\StructureBundle\Entity\Company;
use VoIP\Company\StructureBundle\Entity\Office;
use VoIP\Company\StructureBundle\Entity\Phone;
use VoIP\PBX\RealTimeBundle\Extra\Sync;
use Symfony\Component\HttpFoundation\JsonResponse;
use VoIP\UI\BasicBundle\Extra\Image;

/**
 * @Route("/b")
 */

class EmployeeController extends Controller
{	
	
    /**
     * @Route("/{hash}/edit", name="ui_employee_edit")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
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
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		$extensions = range(100, 999);
		foreach ($company->getEmployees() as $e) {
			if ($e->getIsActive() && $e->getId() != $employee->getId()) unset($extensions[($e->getExtension() - 100)]);
		}
        return array(
			'employee' => $employee,
			'company' => $company,
			'phones' => $company->getPhones(),
			'extensions' => $extensions
		);
    }
	
    /**
     * @Route("/{hash}/edit")
     * @Template()
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
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
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$request = $this->getRequest();
		$name = $request->get('name');
		$extension = $request->get('extension');
		$phones = $request->get('phones');
		
		
		
		$employee->setName($name);
		$employee->setExtension($extension);
		
		foreach ($employee->getPhones() as $phone) {
			$employee->removePhone($phone);
			$phone->removeEmployee($employee);
		}
		
		if ($phones) {
			foreach ($phones as $phoneId) {
				$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->find($phoneId);
				if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
				$employee->addPhone($phone);
				$phone->addEmployee($employee);
			}
		}
		
		if ($imageFile = $request->files->get('image')) {
			$image = new Image($imageFile, array('64', '256'), 'buddies/images', $this->container);
			$employee->setImageUrl($image->getPaths('256'));
			$employee->setThumbUrl($image->getPaths('64'));
		}
		
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company'));
    }
	
    /**
     * @Route("/{hash}/delete", name="ui_employee_delete")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
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
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$employee->setIsActive(false);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
}
