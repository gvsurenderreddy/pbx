<?php

namespace VoIP\UI\BasicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use VoIP\Company\StructureBundle\Entity\Company;
use VoIP\Company\StructureBundle\Entity\Phone;
use VoIP\Company\StructureBundle\Entity\Employee;
use VoIP\Company\SubscriptionsBundle\Entity\Subscription;
use VoIP\Company\VoicemailBundle\Entity\Voicemail;
use VoIP\PBX\RealTimeBundle\Extra\Sync;

class CompanyController extends Controller
{
	
    /**
     * @Route("/", name="ui_company", schemes="https")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function companyAction()
    {
		$user = $this->getUser();
		$company = $user->getCompany();
		/*
		if (count($company->getPhones()) == 0) {
			return $this->redirect($this->generateUrl('ui_company_newphone'))	;
		}
		*/
        return array(
			'company' => $company
		);
    }
	
    /**
     * @Template()
	 * @Security("has_role('ROLE_USER')")
     */
    public function menuAction($route)
    {
		$user = $this->getUser();
		$company = $user->getCompany();
		$em = $this->getDoctrine()->getManager();
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
     * @Route("/new-employee", name="ui_company_newemployee")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function newEmployeeAction()
    {
		$user = $this->getUser();
		$company = $user->getCompany();
        return array(
			'company' => $company
		);
    }
	
    /**
     * @Route("/new-employee")
     * @Template()
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
     */
    public function createEmployeeAction()
    {
		$user = $this->getUser();
		$company = $user->getCompany();
		
		$em = $this->getDoctrine()->getManager();
		
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
     * @Route("/new-subscription", name="ui_company_newsubscription")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function newSubscriptionAction()
    {
		$user = $this->getUser();
		$company = $user->getCompany();
		
		$em = $this->getDoctrine()->getManager();
		
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
     * @Route("/new-subscription")
     * @Template()
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
     */
    public function createSubscriptionAction()
    {
		$user = $this->getUser();
		$company = $user->getCompany();
		
		$em = $this->getDoctrine()->getManager();
		
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
     * @Route("/mailbox", name="ui_company_mailbox")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function mailboxAction()
    {
		$user = $this->getUser();
		$company = $user->getCompany();
		
		$repository = $this->getDoctrine()->getRepository('VoIPCompanyVoicemailBundle:Message');

		$query = $repository->createQueryBuilder('m')
			->leftJoin('m.voicemail', 'v')
			->leftJoin('v.subscription', 's')
			->leftJoin('s.company', 'c')
		    ->where('c.hash = :hash')
			->andWhere('m.archivedAt IS NULL')
		    ->setParameter('hash', $company->getHash())
		    ->orderBy('m.createdAt', 'ASC')
		    ->getQuery();

		$messages = $query->getResult();
        return array(
			'company' => $company,
			'messages' => $messages
		);
    }
	
    /**
     * @Route("/reports", name="ui_company_cdr")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function cdrAction()
    {
		$card = 20;
		$request = $this->getRequest();
		$page = $request->query->get('page');
		if (!$page) $page = 1;
		
		$user = $this->getUser();
		$company = $user->getCompany();
		
		$em = $this->getDoctrine()->getManager();

		$query = $em->createQuery(
		    'SELECT cdr
		    FROM VoIPPBXCDRBundle:CDR cdr
			LEFT JOIN cdr.company c
			WHERE c.id = :companyId
			ORDER BY cdr.end DESC'
		)->setParameters(array(
			'companyId' => $company->getId()
		))->setMaxResults($card)->setFirstResult(($page - 1) * $card);

		$cdrs = $query->getResult();
		
        return array(
			'company' => $company,
			'cdrs' => $cdrs,
			'page' => $page
		);
    }
	
    
	
	
}
