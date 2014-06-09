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

/**
 * @Route("/p")
 */

class PhoneController extends Controller
{	
	
    /**
     * @Route("/{hash}/edit", name="ui_phone_edit")
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
     * @Route("/{hash}/edit")
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
		
		$phone->setName($name);
		$phone->setType($type);
		
		$em->flush();
			
		$sync = new Sync();
		
		$astPeer = $sync->phoneToPeer($phone);
		$phone->setAstPeer($astPeer);
		
		$em->persist($astPeer);
		
		$em->flush();
		
		if (strpos($phone->getType(), 'cisco.') !== false) {
			return $this->redirect($this->generateUrl('ui_phone_configure', array(
				'hash' => $phone->getHash()
			)));
		} else {
			return $this->redirect($this->generateUrl('ui_company', array(
				'hash' => $company->getHash()
			)));
		}
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
    /**
     * @Route("/{hash}/buddies", name="ui_phone_employees")
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
     * @Route("/{hash}/buddies")
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
     * @Route("/{hash}/attribute/{hash2}", name="ui_phone_attribute")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function attributeAction($hash, $hash2)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$employee = $em->getRepository('VoIPCompanyStructureBundle:Employee')->findOneBy(array(
			'hash' => $hash2
		));
        if (!$employee) throw $this->createNotFoundException('Unable to find Employee entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		if ($prevPhone = $employee->getPhone()) $prevPhone->setEmployee(null);
		$em->flush();
		
		$phone->setEmployee($employee);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
    /**
     * @Route("/{hash}/unattribute", name="ui_phone_unattribute")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function unattributeAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$phone->setEmployee(null);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
    /**
     * @Route("/{hash}/renew", name="ui_phone_renew")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function renewAction($hash)
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
			'phone' => $phone
		);
    }
	
    /**
     * @Route("/{hash}/add-credit", name="ui_phone_credit")
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function addCreditAction($hash)
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
		$period = $request->get('period');
		
		$now = new \DateTime();
		
		if (!$phone->getActivatedUntil() || $now > $phone->getActivatedUntil()) {
			$date = $now;
		} else {
			$date = $phone->getActivatedUntil();
		}
		
		switch ($period) {
			case 'month':
				$date->modify('+1 month');
				break;
			case 'year':
				$date->modify('+1 year');
				break;
		}
		
		
		$phone->setActivatedUntil($date);
		
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company'));
    }
	
    /**
     * @Route("/{hash}/delete", name="ui_phone_delete")
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
		
		if ($peer = $phone->getAstPeer()) {
			$phone->setAstPeer(null);
			$em->remove($peer);
		}
		$phone->setIsActive(false);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company'));
    }
	
    /**
     * @Route("/{hash}/credentials", name="ui_phone_credentials")
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
     * @Route("/{hash}/setup", name="ui_phone_setup")
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
     * @Route("/{hash}/configure", name="ui_phone_configure")
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
     * @Route("/{hash}/configure.js", name="ui_phone_configure_js")
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
