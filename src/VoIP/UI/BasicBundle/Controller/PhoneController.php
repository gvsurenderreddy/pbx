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
use VoIP\UI\BasicBundle\Extra\Configuration;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PhoneController extends Controller
{	
    /**
     * @Route("/new-phone", name="ui_company_newphone")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function newPhoneAction()
    {
		$user = $this->getUser();
		$company = $user->getCompany();
        return array(
			'company' => $company
		);
    }
	
    /**
     * @Route("/new-phone")
     * @Template()
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
     */
    public function createPhoneAction()
    {
		$user = $this->getUser();
		$company = $user->getCompany();
		$em = $this->getDoctrine()->getManager();
		
		$request = $this->getRequest();
		$phoneName = $request->get('phonename');
		$type = $request->get('type');
		
		$phone = new Phone();
		$phone->setModel($type);
		$phone->setPhoneName($phoneName);
		$phone->setCompany($company);
		
		$phone->setContext('internal');
		$phone->setHost('dynamic');
		$phone->setNat('force_rport,comedia');
		$phone->setType('friend');
		//$phone->setAllow('gsm');
		//$phone->setPermit('0.0.0.0/0.0.0.0');
		$phone->setDynamic('yes');
		$phone->setQualify(5000);
		$phone->setQualifyfreq(20);
		//$phone->setDirectmedia('no');
		
		$em->persist($phone);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_phone_employees', array(
			'hash' => $phone->getHash()
		)));
		
    }
	
    /**
     * @Route("/phone/{hash}/edit", name="ui_phone_edit")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function editAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
        return array(
			'phone' => $phone,
			'company' => $company,
		);
    }
	
    /**
     * @Route("/phone/{hash}/edit")
     * @Template()
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
     */
    public function updateAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$request = $this->getRequest();
		$name = $request->get('name');
		$type = $request->get('type');
		
		$prevName = $phone->getName();
		
		$phone->setPhoneName($name);
		$phone->setModel($type);
		
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company'));
    }
	
    /**
     * @Route("/phone/{hash}/buddies", name="ui_phone_employees")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function employeesAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		$employees = $em->getRepository('VoIPCompanyStructureBundle:Employee')->findBy(array(
			'company' => $company,
			'isActive' => true,
		), array(
			'name' => 'ASC'
		));
        return array(
			'phone' => $phone,
			'employees' => $employees
		);
    }
	
    /**
     * @Route("/phone/{hash}/buddies")
     * @Template()
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
     */
    public function updateEmployeesAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$request = $this->getRequest();
		$employees = $request->get('employees');
		
		foreach ($phone->getEmployees() as $employee) {
			$phone->removeEmployee($employee);
			$employee->removePhone($phone);
		}
		
		if ($employees) {
			foreach ($employees as $employeeId) {
				$employee = $em->getRepository('VoIPCompanyStructureBundle:Employee')->find($employeeId);
				if (!$employee) throw $this->createNotFoundException('Unable to find Employee entity.');
				$phone->addEmployee($employee);
				$employee->addPhone($phone);
			}
		}

		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company'));
    }
	
    /**
     * @Route("/phone/{hash}/delete", name="ui_phone_delete")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');

		$em->remove($phone);
		
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company'));
    }
	
    /**
     * @Route("/phone/{hash}/credentials", name="ui_phone_credentials")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function credentialsAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		return array(
			'phone' => $phone,
		);
    }
	
    /**
     * @Route("/phone/{hash}/setup", name="ui_phone_setup")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function setupAction($hash)
    {
		$request = $this->getRequest();
		$ip = $request->get('ip');
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$conf = new Configuration($ip, $phone);
		
		return array(
			'conf' => $conf->getData(),
			'url' => $conf->getURL()
		);
    }
	
    /**
     * @Route("/phone/{hash}/configure", name="ui_phone_configure")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function configureAction($hash)
    {
		$request = $this->getRequest();
		$ip = $request->get('ip');
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		return array(
			'phone' => $phone,
			'company' => $company
		);
    }
    /**
     * @Route("/phone/{hash}/configure.js", name="ui_phone_configure_js")
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function configureJSAction($hash)
    {
		$request = $this->getRequest();
		$ip = $request->get('ip');
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$response = new Response($this->renderView(
		    'VoIPUIBasicBundle:Phone:configure.js.twig',
		    array(
				'phone' => $phone,
				'company' => $company
			)
		));
		$response->headers->set('Content-Type', 'text/javascript');
		return $response;
    }
}
