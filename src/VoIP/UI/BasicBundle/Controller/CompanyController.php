<?php

namespace VoIP\UI\BasicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use VoIP\Company\StructureBundle\Entity\Company;
use VoIP\Company\StructureBundle\Entity\Phone;
use VoIP\Company\StructureBundle\Entity\Employee;
use VoIP\Company\SubscriptionsBundle\Entity\Subscription;
use VoIP\Company\VoicemailBundle\Entity\Voicemail;
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
     * @Template()
     */
    public function menuAction($route, $hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
		    'SELECT c
		    FROM VoIPCompanyStructureBundle:Company c
			LEFT JOIN c.phones p
			LEFT JOIN c.subscriptions s
			WHERE c.hash = :hash OR p.hash = :hash OR s.hash = :hash'
		)->setParameters(array(
			'hash' => $hash
		));

		$company = $query->getSingleResult();
        if (!$company) throw $this->createNotFoundException('Unable to find Company entity.');
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		$query = $em->createQuery(
		    'SELECT COUNT(m)
		    FROM VoIPCompanyVoicemailBundle:Message m
			LEFT JOIN m.voicemail v
			LEFT JOIN v.subscription s
			LEFT JOIN s.company c
			WHERE c.id = :companyId AND m.readAt is NULL'
		)->setParameters(array(
			'companyId' => $company->getId()
		));

		$newMessages = $query->getSingleScalarResult();
        return array(
			'company' => $company,
			'newmessages' => $newMessages,
			'route' => $route
		);
    }
	
    /**
     * @Route("/{hash}/new-phone", name="ui_company_newphone")
     * @Template()
	 * @Method("GET")
     */
    public function newPhoneAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository('VoIPCompanyStructureBundle:Company')->findOneBy(array(
			'hash' => $hash
		));
        if (!$company) throw $this->createNotFoundException('Unable to find Office entity.');
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
        return array(
			'company' => $company
		);
    }
	
    /**
     * @Route("/{hash}/new-phone")
     * @Template()
	 * @Method("POST")
     */
    public function createPhoneAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository('VoIPCompanyStructureBundle:Company')->findOneBy(array(
			'hash' => $hash
		));
        if (!$company) throw $this->createNotFoundException('Unable to find Office entity.');
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		
		$request = $this->getRequest();
		$phoneName = $request->get('phonename');
		$type = $request->get('type');
		
		$phone = new Phone();
		$phone->setType($type);
		$phone->setName($phoneName);
		$phone->setCompany($company);
		
		$em->persist($phone);
		
		$sync = new Sync();
		
		$em->flush();
		
		$astPeer = $sync->phoneToPeer($phone);
		$em->persist($astPeer);
		
		$phone->setAstPeer($astPeer);
		
		$em->flush();
		
		if ($phone->getType() == 'ciscophone') {
			return $this->redirect($this->generateUrl('ui_phone_configure', array(
				'hash' => $phone->getHash()
			)));
		} else {
			return $this->redirect($this->generateUrl('ui_company', array(
				'hash' => $company->getHash()
			)));
		}
		
		
    }
	
    /**
     * @Route("/{hash}/new-employee", name="ui_company_newemployee")
     * @Template()
	 * @Method("GET")
     */
    public function newEmployeeAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository('VoIPCompanyStructureBundle:Company')->findOneBy(array(
			'hash' => $hash
		));
        if (!$company) throw $this->createNotFoundException('Unable to find Office entity.');
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
        return array(
			'company' => $company
		);
    }
	
    /**
     * @Route("/{hash}/new-employee")
     * @Template()
	 * @Method("POST")
     */
    public function createEmployeeAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository('VoIPCompanyStructureBundle:Company')->findOneBy(array(
			'hash' => $hash
		));
        if (!$company) throw $this->createNotFoundException('Unable to find Office entity.');
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		
		$request = $this->getRequest();
		$extension = $request->get('extension');
		$name = $request->get('name');
		
		if ($extension < 100 || $extension > 999) {
			throw $this->createNotFoundException('Extension range');
		}
		
		$employee = new Employee();
		$employee->setName($name);
		$employee->setExtension($extension);
		$employee->setCompany($company);
		
		$em->persist($employee);

		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
    /**
     * @Route("/{hash}/new-subscription", name="ui_company_newsubscription")
     * @Template()
	 * @Method("GET")
     */
    public function newSubscriptionAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository('VoIPCompanyStructureBundle:Company')->findOneBy(array(
			'hash' => $hash
		));
        if (!$company) throw $this->createNotFoundException('Unable to find Company entity.');
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		$countries = $em->getRepository('VoIPCompanySubscriptionsBundle:Country')->findBy(array(), array(
			'name' => 'ASC'
		));
		$employees = $em->getRepository('VoIPCompanyStructureBundle:Employee')->findBy(array(
			'company' => $company
		), array(
			'name' => 'ASC'
		));
		$subscriptions = $company->getSubscriptions();
		$usedPrefixs = array();
		foreach ($subscriptions as $subscription) {
			$usedPrefixs[] = $subscription->getPrefix();
		}
		$prefixs = array();
		for ($i=1; $i < 10; $i++) { 
			if (!in_array($i, $usedPrefixs)) {
				$prefixs[] = $i;
			}
		}
        return array(
			'company' => $company,
			'countries' => $countries,
			'employees' => $employees,
			'prefixs' => $prefixs,
		);
    }
	
    /**
     * @Route("/{hash}/new-subscription")
     * @Template()
	 * @Method("POST")
     */
    public function createSubscriptionAction($hash)
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
		$type = $request->get('type');
		$number = $request->get('number');
		$username = $request->get('username');
		$secret = $request->get('secret');
		$host = 'siptrunk.hoiio.com';//$host = $request->get('host');
		$prefix = $request->get('prefix');
		$receive = true;//$request->get('receive') === 'on';
		//$countries = $request->get('countries');
		$employees = $request->get('employees');
		
		$subscription = new Subscription();
		$subscription->setName($name);
		$subscription->setType($type);
		$subscription->setNumber($number);
		$subscription->setUsername($username);
		$subscription->setSecret($secret);
		$subscription->setHost($host);
		$subscription->setPrefix($prefix);
		$subscription->setReceiveCall($receive);
		$subscription->setCompany($company);
		/*
		if ($countries) {
			foreach ($countries as $countryId) {
				$country = $em->getRepository('VoIPCompanySubscriptionsBundle:Country')->find($countryId);
				if (!$country) throw $this->createNotFoundException('Unable to find Country entity.');
				$subscription->addCountry($country);
				$country->addSubscription($subscription);
			}
		}
		*/
		if ($employees) {
			foreach ($employees as $employeeId) {
				$employee = $em->getRepository('VoIPCompanyStructureBundle:Employee')->find($employeeId);
				if (!$employee) throw $this->createNotFoundException('Unable to find Employee entity.');
				$subscription->addEmployee($employee);
				$employee->addSubscription($subscription);
			}
		}
		
		$em->persist($subscription);
		$em->flush();
		
		$voicemail = new Voicemail();
		$voicemail->setSubscription($subscription);
		$subscription->setVoicemail($voicemail);
		$em->persist($voicemail);
		$em->flush();
		
		$sync = new Sync();
		
		$astPeer = $sync->subscriptionToPeer($subscription);
		$em->persist($astPeer);
		$subscription->setAstPeer($astPeer);
		$em->flush();
		
		$astVoicemail = $sync->voicemailToVoicemail($voicemail);
		$em->persist($astVoicemail);
		$voicemail->setAstVoicemail($astVoicemail);
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
		
		foreach ($company->getPhones() as $phone) {
			if ($phone->getAstPeer()) $em->remove($phone->getAstPeer());
			$em->remove($phone);
			$em->remove($office);
		}
		foreach ($company->getSubscriptions() as $subscription) {
			if ($subscription->getAstPeer()) $em->remove($subscription->getAstPeer());
			$em->remove($subscription);
		}
		$em->remove($company);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_index'));
    }
	
    /**
     * @Route("/{hash}/mailbox", name="ui_company_mailbox")
     * @Template()
	 * @Method("GET")
     */
    public function mailboxAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository('VoIPCompanyStructureBundle:Company')->findOneBy(array(
			'hash' => $hash
		));
        if (!$company) throw $this->createNotFoundException('Unable to find Company entity.');
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		$repository = $this->getDoctrine()->getRepository('VoIPCompanyVoicemailBundle:Message');

		$query = $repository->createQueryBuilder('m')
			->leftJoin('m.voicemail', 'v')
			->leftJoin('v.subscription', 's')
			->leftJoin('s.company', 'c')
		    ->where('c.hash = :hash')
			->andWhere('m.archivedAt IS NULL')
		    ->setParameter('hash', $hash)
		    ->orderBy('m.createdAt', 'ASC')
		    ->getQuery();

		$messages = $query->getResult();
        return array(
			'company' => $company,
			'messages' => $messages
		);
    }
	
    /**
     * @Route("/{hash}/reports", name="ui_company_cdr")
     * @Template()
	 * @Method("GET")
     */
    public function cdrAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository('VoIPCompanyStructureBundle:Company')->findOneBy(array(
			'hash' => $hash
		));
        if (!$company) throw $this->createNotFoundException('Unable to find Company entity.');
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		$repository = $this->getDoctrine()->getRepository('VoIPPBXCDRBundle:CDR');

		$query = $repository->createQueryBuilder('cdr')
			->leftJoin('cdr.company', 'c')
		    ->where('c.hash = :hash')
		    ->setParameter('hash', $hash)
		    ->orderBy('cdr.start', 'ASC')
		    ->getQuery();

		$cdrs = $query->getResult();
        return array(
			'company' => $company,
			'cdrs' => $cdrs
		);
    }
	
    
	
	
}
