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
		if (!$user->getConditionsAccepted()) {
			return $this->redirect($this->generateUrl('ui_company_conditions'));
		}
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
     * @Route("/new-buddy-phone", name="ui_company_newphoneemployee")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function newPhoneEmployeeAction()
    {
		$user = $this->getUser();
		$company = $user->getCompany();
		$extensions = range(100, 999);
		foreach ($company->getEmployees() as $e) {
			if ($e->getIsActive()) unset($extensions[($e->getExtension() - 100)]);
		}
        return array(
        	'extensions' => $extensions,
        );
    }
	
    /**
     * @Route("/new-buddy-phone")
     * @Template()
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
     */
    public function createPhoneEmployeeAction()
    {
		$user = $this->getUser();
		$company = $user->getCompany();
		$em = $this->getDoctrine()->getManager();
		
		$request = $this->getRequest();
		$name = $request->get('name');
		$extension = $request->get('extension');
		$type = $request->get('type');
		
		$employee = new Employee();
		$employee->setName($name);
		$employee->setExtension($extension);
		$employee->setCompany($company);
		
		$phone = new Phone();
		$phone->setType($type);
		$phone->setName($name."'s phone");
		$phone->setCompany($company);
		
		$phone->addEmployee($employee);
		$employee->addPhone($phone);
		
		$em->persist($employee);
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
     * @Route("/new-buddy", name="ui_company_newemployee")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function newEmployeeAction()
    {
		$user = $this->getUser();
		$company = $user->getCompany();
		$phones = $company->getPhones();
		$extensions = range(100, 999);
		foreach ($company->getEmployees() as $e) {
			if ($e->getIsActive()) unset($extensions[($e->getExtension() - 100)]);
		}
        return array(
			'company' => $company,
			'phones' => $phones,
			'extensions' => $extensions,
		);
    }
	
    /**
     * @Route("/new-buddy")
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
		$phones = $request->get('phones');
		
		if ($extension < 100 || $extension > 999) {
			throw $this->createNotFoundException('Extension range');
		}
		
		$employee = new Employee();
		$employee->setName($name);
		$employee->setExtension($extension);
		$employee->setCompany($company);
		
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
		
		$em->persist($employee);

		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
    /**
     * @Route("/add-existing-number", name="ui_company_newsubscription")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function newSubscriptionAction()
    {
		$user = $this->getUser();
		$company = $user->getCompany();
		
        return array(
			'company' => $company,
		);
    }
	
    /**
     * @Route("/add-existing-number")
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
		$type = $request->get('type');
		$number = $request->get('number');
		$username = $request->get('username');
		$secret = $request->get('secret');
		$file = $request->files->get('announcement');
		$record = $request->get('record');
		
		
		switch ($type) {
			case 'hoiio':
				$host = 'siptrunk.hoiio.com';
				break;
			default:
				$host = 'dynamic';
				break;
		}
		
		$prefix = $request->get('prefix');
		$receive = true;
		$employees = $request->get('employees');
		
		$subscription = new Subscription();
		$subscription->setName($number);
		$subscription->setType($type);
		$subscription->setNumber($number);
		$subscription->setUsername($username);
		$subscription->setSecret($secret);
		$subscription->setHost($host);
		$subscription->setCompany($company);
		if ($employees) {
			foreach ($employees as $employeeId) {
				$employee = $em->getRepository('VoIPCompanyStructureBundle:Employee')->find($employeeId);
				if (!$employee) throw $this->createNotFoundException('Unable to find Employee entity.');
				$subscription->addEmployee($employee);
				$employee->addSubscription($subscription);
			}
		}
		
		if ($file) {
	        $fileName = hash('crc32b', uniqid(mt_rand(), true)).'.mp3';
			$filePath = __DIR__.'/../../../../../web/tmp/';
	        $file->move($filePath, $fileName);
			$s3 = $this->container->get('aws_s3');
			$s3->create_object('fortyeight', 'ging/'.$fileName, array(
				'fileUpload' => $filePath.$fileName,
				'acl' => \AmazonS3::ACL_PUBLIC,
				'headers' => array(
					'Cache-Control'    => 'max-age=8000000',
					'Content-Language' => 'en-US',
					'Expires'          => 'Tue, 01 Jan 2030 03:54:42 GMT',
				)
			));
			$subscription->setVmFile($fileName);
		}
		
		$subscription->setRecordVM($record);
		
		$em->persist($subscription);
		$em->flush();
		
		$voicemail = new Voicemail();
		$voicemail->setSubscription($subscription);
		$subscription->setVoicemail($voicemail);
		$em->persist($voicemail);
		$em->flush();
		
		$sync = new Sync();
		
		/*
		$astPeer = $sync->subscriptionToPeer($subscription);
		$em->persist($astPeer);
		$subscription->setAstPeer($astPeer);
		$em->flush();
		*/
		
		$astVoicemail = $sync->voicemailToVoicemail($voicemail);
		$em->persist($astVoicemail);
		$voicemail->setAstVoicemail($astVoicemail);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
    /**
     * @Route("/request-number", name="ui_company_newrequestnumber")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function newRequestNumberAction()
    {
        return array(
        	'countries' => array(
        		'france' => 'France (+33)',
				'singapore' => 'Singapore (+65)'
        	)
        );
    }
	
    /**
     * @Route("/request-number")
     * @Template()
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
     */
    public function createRequestNumberAction()
    {
		$user = $this->getUser();
		$company = $user->getCompany();
		
		$em = $this->getDoctrine()->getManager();
		
		$request = $this->getRequest();
		$country = $request->get('country');
		
		return $this->redirect($this->generateUrl('ui_company'));
    }
	
    /**
     * @Route("/mailbox", name="ui_company_mailbox")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function mailboxAction()
    {
		$card = 20;
		$request = $this->getRequest();
		$page = $request->query->get('page');
		if (!$page) $page = 1;
		$archive = $request->get('archive');
		$user = $this->getUser();
		$company = $user->getCompany();
		$em = $this->getDoctrine()->getManager();
		if (!$archive) {
			$archive = 0;
			$query = $em->createQuery(
			    'SELECT m
			    FROM VoIPCompanyVoicemailBundle:Message m
				LEFT JOIN m.voicemail v
				LEFT JOIN v.subscription s
				LEFT JOIN s.company c
				WHERE c.hash = :hash AND m.archivedAt IS NULL
				ORDER BY m.createdAt DESC'
			)->setParameters(array(
				'hash' => $company->getHash()
			))->setMaxResults($card)->setFirstResult(($page - 1) * $card);
		} else {
			$query = $em->createQuery(
			    'SELECT m
			    FROM VoIPCompanyVoicemailBundle:Message m
				LEFT JOIN m.voicemail v
				LEFT JOIN v.subscription s
				LEFT JOIN s.company c
				WHERE c.hash = :hash AND m.archivedAt IS NOT NULL
				ORDER BY m.createdAt DESC'
			)->setParameters(array(
				'hash' => $company->getHash()
			))->setMaxResults($card)->setFirstResult(($page - 1) * $card);
		}
		

		$messages = $query->getResult();

		$messages = $query->getResult();
        return array(
			'company' => $company,
			'messages' => $messages,
			'page' => $page,
			'archive' => $archive
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
	
    /**
     * @Route("/top-up", name="ui_company_topup")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function topupAction()
    {
		return array();
    }
    /**
     * @Route("/top-up")
     * @Template()
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
     */
    public function topupPostAction()
    {
		$request = $this->getRequest();
		$credit = (int) $request->get('credit');
		$url = $request->get('url');
		$em = $this->getDoctrine()->getManager();
		$company = $this->getUser()->getCompany();
		$company->setCredit($company->getCredit() + $credit);
		$em->flush();
		if ($url) {
			return $this->redirect($url);
		} else {
			return $this->redirect($this->generateUrl('ui_company'));
		}
		
    }
	
    /**
     * @Route("/conditions", name="ui_company_conditions")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function conditionsAction()
    {
		$user = $this->getUser();
		if ($user->getConditionsAccepted()) {
			return $this->redirect($this->generateUrl('ui_company'));
		}
		return array();
    }
	
    /**
     * @Route("/conditions")
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
     */
    public function conditionsPostAction()
    {
		$em = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		$user->setConditionsAccepted(true);
		$em->flush();
		return $this->redirect($this->generateUrl('ui_company'));
    }
	
    
	
	
}
