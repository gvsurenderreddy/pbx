<?php

namespace VoIP\UI\BasicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use VoIP\Company\DynIPBundle\Entity\DynIP;
use VoIP\Company\StructureBundle\Entity\Company;
use VoIP\Company\StructureBundle\Entity\Phone;
use VoIP\Company\StructureBundle\Entity\Employee;
use VoIP\Company\SubscriptionsBundle\Entity\Subscription;
use VoIP\Company\VoicemailBundle\Entity\Voicemail;
use VoIP\PBX\RealTimeBundle\Extra\Sync;
use VoIP\UI\BasicBundle\Extra\Image;
use Symfony\Component\HttpFoundation\JsonResponse;

class CompanyController extends Controller
{
	
    /**
     * @Route("/", name="ui_company")
     * @Template()
	 * @Method("GET")
     */
    public function companyAction()
    {
		if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
			return $this->redirect('http://fortyeight.co/');
		}
		$user = $this->getUser();
		if (!$user->getConditionsAccepted()) {
			return $this->render('VoIPUIBasicBundle:Company:conditions.html.twig');
		} elseif (!$user->getCompany()) {
			return $this->render('VoIPUIBasicBundle:Company:new.html.twig');
		}
        return array();
    }
	
    /**
     * @Route("/new", name="ui_company_new")
     * @Template()
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
     */
    public function newAction()
    {
		$em = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		$request = $this->getRequest();
		
		$name = $request->get('name');
		
		$company = new Company();
		$company->setName($name);
		$em->persist($company);
		
		$user->setCompany($company);
		
		$em->flush();
		
		$this->get('session')->getFlashBag()->add(
            'notice',
            'Your changes were saved!'
        );
		
        return $this->redirect($this->generateUrl('ui_company'));
    }
	
    /**
     * @Template()
	 * @Security("has_role('ROLE_USER')")
     */
    public function menuAction($route)
    {
		$user = $this->getUser();
		$company = $user->getCompany();
		/*
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

		$newMessages = $query->getSingleScalarResult();*/
        return array(
			'company' => $company,
			'newmessages' => 0,
			'route' => $route
		);
    }
	
    /**
     * @Template()
	 * @Security("has_role('ROLE_USER')")
     */
    public function navAction($route)
    {
		$user = $this->getUser();
		$company = $user->getCompany();
		/*$em = $this->getDoctrine()->getManager();
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

		$newMessages = $query->getSingleScalarResult();*/
        return array(
			'company' => $company,
			'newmessages' => 0,//$newMessages,
			'route' => $route
		);
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
			unset($extensions[($e->getExtension() - 100)]);
		}
        return array(
			'company' => $company,
			'phones' => $phones,
			'extensions' => array_values($extensions),
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
		
		if ($extension < 100 || $extension > 999) {
			throw $this->createNotFoundException('Extension range');
		}
		
		$employee = new Employee();
		$employee->setName($name);
		$employee->setExtension($extension);
		$employee->setCompany($company);
		
		$em->persist($employee);

		$em->flush();
		
		$this->get('session')->getFlashBag()->add(
            'notice',
            'Your changes were saved!'
        );
		
		return $this->redirect($this->generateUrl('ui_company'));
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
		
	    $message = \Swift_Message::newInstance()
	        ->setSubject('We received your request for a new number.')
	        ->setFrom('bot@fortyeight.co')
	        ->setTo($user->getEmail())
			->setBcc('adrien@fortyeight.co')
	        ->setBody(
	            $this->renderView(
	                'VoIPUIBasicBundle:Mails:request.html.twig',
	                array(
						'name' => $user->getUsername(),
						'country' => $country
					)
	            )
	        )
			->setContentType("text/html")
	    ;
	    $this->get('mailer')->send($message);
		
		$this->get('session')->getFlashBag()->add(
            'notice',
            'You request has been sent to our team. We will contact you in the next hours.'
        );
		
		return $this->redirect($this->generateUrl('ui_company', array('m' => 'number')));
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
     * @Route("/bill", name="ui_company_bill")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function billAction()
    {
		$user = $this->getUser();
		$company = $user->getCompany();
		
		$request = $this->getRequest();
		$query = $request->query;
		
		$now = new \DateTime();
		
		if (!$month = $query->get('p')) {
			$month = $now->format('Y-m');
			$complete = false;
		} else {
			$complete = ($month != $now->format('Y-m'));
		}
		
		
		$start = new \DateTime($month.'-01');
		$end = new \DateTime($start->format('Y-m-d')); $end->modify('+1 month');
		
		$em = $this->getDoctrine()->getManager();

		$query = $em->createQuery(
		    'SELECT cdr
		    FROM VoIPPBXCDRBundle:CDR cdr
			LEFT JOIN cdr.company c
			WHERE c.id = :companyId AND cdr.end >= :start AND cdr.end < :end'
		)->setParameters(array(
			'companyId' => $company->getId(),
			'start' => $start,
			'end' => $end
		));
		
		$cdrs = $query->getResult();
		
		$commumication = 0;
		foreach ($cdrs as $cdr) {
			$commumication += $cdr->getPrice();
		}
		
		$query = $em->createQuery(
		    'SELECT p
		    FROM VoIPCompanyStructureBundle:EmployeePayment p
			LEFT JOIN p.employee e
			LEFT JOIN e.company c
			WHERE c.id = :companyId AND p.createdAt < :end AND p.createdAt >= :start'
		)->setParameters(array(
			'companyId' => $company->getId(),
			'start' => $start,
			'end' => $end
		));
		
		$ps = $query->getResult();
		
		$subEmployees = 0;
		foreach ($ps as $p) $subEmployees += $p->getPrice();
		
		$query = $em->createQuery(
		    'SELECT p
		    FROM VoIPCompanySubscriptionsBundle:SubscriptionPayment p
			LEFT JOIN p.subscription s
			LEFT JOIN s.company c
			WHERE c.id = :companyId AND p.createdAt < :end AND p.createdAt >= :start'
		)->setParameters(array(
			'companyId' => $company->getId(),
			'start' => $start,
			'end' => $end
		));
		
		$ps = $query->getResult();
		
		$subSubcriptions = 0;
		foreach ($ps as $p) $subSubcriptions += $p->getPrice();
		
		$months = array();
		
		$tmpM = new \DateTime($now->format('Y-m-01'));
		$months[] = array(
			'name' => $tmpM->format('F Y'),
			'value' => $tmpM->format('Y-m')
		);
		while ($company->getCreatedAt() < $tmpM) {
			$tmpM->modify('-1 month');
			$months[] = array(
				'name' => $tmpM->format('F Y'),
				'value' => $tmpM->format('Y-m')
			);
		}
		
		$total = $commumication + $subEmployees + $subSubcriptions;
		
		
		
        return array(
			'company' => $company,
			'communication' => $commumication,
			'employees' => $subEmployees,
			'subscriptions' => $subSubcriptions,
			'months' => $months,
			'month' => $month,
			'complete' => $complete,
			'total' => $total
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
		$company->setIsTrial(false);
		$em->flush();
		if ($url) {
			return $this->redirect($url);
		} else {
			return $this->redirect($this->generateUrl('ui_company'));
		}
		
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

    /**
     * @Route("/settings/info", name="ui_company_parameters_info")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function infoAction()
    {
		$user = $this->getUser();
		$company = $this->getUser()->getCompany();
        return array(
			'company' => $company,
		);
    }
	
    /**
     * @Route("/settings/info")
     * @Template()
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
     */
    public function updateInfoAction()
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$company = $this->getUser()->getCompany();
		$request = $this->getRequest();
		
		$name = $request->get('name');
		
		$company->setName($name);
		
		$em->flush();
		
		$this->get('session')->getFlashBag()->add(
            'notice',
            'Your changes were saved!'
        );
		
		return $this->redirect($this->generateUrl('ui_company'));
    }
	
    /**
     * @Route("/settings/image", name="ui_company_parameters_image")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function imageAction()
    {
		$user = $this->getUser();
		$company = $this->getUser()->getCompany();
        return array(
			'company' => $company,
		);
    }
	
    /**
     * @Route("/settings/image")
     * @Template()
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
     */
    public function updateImageAction()
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$company = $this->getUser()->getCompany();
		$request = $this->getRequest();
		
		if ($imageFile = $request->files->get('image')) {
			$image = new Image($imageFile, array('256'), 'companies/images', $this->container);
			$company->setImageUrl($image->getPaths('256'));
		}
		
		$em->flush();
		
		$this->get('session')->getFlashBag()->add(
            'notice',
            'Your changes were saved!'
        );
		
		return $this->redirect($this->generateUrl('ui_company'));
    }
	
    /**
     * @Route("/dynamic-network", name="ui_company_dynamic")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function dynamicAction()
    {
		$user = $this->getUser();
		$company = $this->getUser()->getCompany();
		$ip = $this->container->get('request')->getClientIp();
		if ($ip == '::1') $ip = '182.19.255.8';
		
		$em = $this->getDoctrine()->getManager();
		$dynIP = $em->getRepository('VoIPCompanyDynIPBundle:DynIP')->findOneBy(array(
			'ip' => $ip
		));
		if ($dynIP) $ip = null;
		
        return array(
			'company' => $company,
			'ip' => $ip
		);
    }
	
    /**
     * @Route("/dynamic-network/add-ip", name="ui_company_dynamic_addip")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function dynamicAddIPAction()
    {
		$user = $this->getUser();
		$company = $this->getUser()->getCompany();
		
		$request = $this->getRequest();
		$query = $request->query;
		
		$ip = $query->get('ip');
		$title = $query->get('title');
		
		if (!$ip) $ip = $this->container->get('request')->getClientIp();
		
		if ($ip == '::1') $ip = '182.19.255.8';
		
		$em = $this->getDoctrine()->getManager();
		$dynIP = $em->getRepository('VoIPCompanyDynIPBundle:DynIP')->findOneBy(array(
			'ip' => $ip
		));
		if (!$dynIP) {
			$dynIP = new DynIP();
			$dynIP->setCompany($company);
			$dynIP->setIp($ip);
			$dynIP->setTitle($title);
			$em->persist($dynIP);
			$em->flush();
			
			$ec2 = $this->container->get('aws_ec2');
			$ec2->set_region(\AmazonEC2::REGION_SINGAPORE);
			$authorizeResp = $ec2->authorize_security_group_ingress(array(
				'GroupId' => 'sg-8d9c5de8',
				'IpPermissions' => array(
					array(
						'IpProtocol' => 'udp',
						'FromPort' => '5060',
						'ToPort' => '5060',
						'IpRanges' => array(
							array('CidrIp' => $ip.'/32'),
						)
					),
					array(
						'IpProtocol' => 'udp',
						'FromPort' => '10000',
						'ToPort' => '30000',
						'IpRanges' => array(
							array('CidrIp' => $ip.'/32'),
						)
					)
				)
			));
		}
		
		$this->get('session')->getFlashBag()->add(
            'notice',
            'Your changes were saved!'
        );
		
		return $this->redirect($this->generateUrl('ui_company_dynamic'));
    }
	
    /**
     * @Route("/dynamic-network/remove-ip/{hash}", name="ui_company_dynamic_removeip")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function dynamicRemoveIPAction($hash)
    {
		$user = $this->getUser();
		$company = $this->getUser()->getCompany();
		
		$em = $this->getDoctrine()->getManager();
		$dynIP = $em->getRepository('VoIPCompanyDynIPBundle:DynIP')->findOneBy(array(
			'hash' => $hash,
			'company' => $company
		));
		if ($dynIP) {
			$ip = $dynIP->getIp();
			
			$em->remove($dynIP);
			$em->flush();
			
			$ec2 = $this->container->get('aws_ec2');
			$ec2->set_region(\AmazonEC2::REGION_SINGAPORE);
			$revokeResp = $ec2->revoke_security_group_ingress(array(
				'GroupId' => 'sg-8d9c5de8',
				'IpPermissions' => array(
					array(
						'IpProtocol' => 'udp',
						'FromPort' => '5060',
						'ToPort' => '5060',
						'IpRanges' => array(
							array('CidrIp' => $ip.'/32'),
						)
					),
					array(
						'IpProtocol' => 'udp',
						'FromPort' => '10000',
						'ToPort' => '30000',
						'IpRanges' => array(
							array('CidrIp' => $ip.'/32'),
						)
					)
				)
			));
		}
		
		$this->get('session')->getFlashBag()->add(
            'notice',
            'Your changes were saved!'
        );
			
		return $this->redirect($this->generateUrl('ui_company_dynamic'));
    }
	
    /**
     * @Route("/dynamic-network/update-ip/{hash}", name="ui_company_dynamic_updateip")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function dynamicUpdateIPAction($hash)
    {
		$request = $this->getRequest();
		$query = $request->query;
		$user = $this->getUser();
		$company = $this->getUser()->getCompany();
		
		$em = $this->getDoctrine()->getManager();
		$dynIP = $em->getRepository('VoIPCompanyDynIPBundle:DynIP')->findOneBy(array(
			'hash' => $hash,
			'company' => $company
		));
		if ($dynIP) {
			$ip = $dynIP->getIp();
			
			$ec2 = $this->container->get('aws_ec2');
			$ec2->set_region(\AmazonEC2::REGION_SINGAPORE);
			$revokeResp = $ec2->revoke_security_group_ingress(array(
				'GroupId' => 'sg-8d9c5de8',
				'IpPermissions' => array(
					array(
						'IpProtocol' => 'udp',
						'FromPort' => '5060',
						'ToPort' => '5060',
						'IpRanges' => array(
							array('CidrIp' => $ip.'/32'),
						)
					),
					array(
						'IpProtocol' => 'udp',
						'FromPort' => '10000',
						'ToPort' => '30000',
						'IpRanges' => array(
							array('CidrIp' => $ip.'/32'),
						)
					)
				)
			));
		
			$ip = $query->get('ip');
		
			if (!$ip) $ip = $this->container->get('request')->getClientIp();
		
			if ($ip == '::1') $ip = '182.19.255.8';
			
			$dynIP->setIp($ip);
			$em->flush();
			
			$authorizeResp = $ec2->authorize_security_group_ingress(array(
				'GroupId' => 'sg-8d9c5de8',
				'IpPermissions' => array(
					array(
						'IpProtocol' => 'udp',
						'FromPort' => '5060',
						'ToPort' => '5060',
						'IpRanges' => array(
							array('CidrIp' => $ip.'/32'),
						)
					),
					array(
						'IpProtocol' => 'udp',
						'FromPort' => '10000',
						'ToPort' => '30000',
						'IpRanges' => array(
							array('CidrIp' => $ip.'/32'),
						)
					)
				)
			));
		}
		
		$this->get('session')->getFlashBag()->add(
            'notice',
            'Your changes were saved!'
        );
			
		return $this->redirect($this->generateUrl('ui_company_dynamic'));
    }
	
    /**
     * @Route("/dynamic-ip/{token}", name="dynamicip")
     * @Template()
	 * @Method("GET")
     */
    public function dynamicIPAction($token)
    {
		$request = $this->getRequest();
		$query = $request->query;
		
		$em = $this->getDoctrine()->getManager();
		$dynIP = $em->getRepository('VoIPCompanyDynIPBundle:DynIP')->findOneBy(array(
			'token' => $token
		));
		$response = new JsonResponse();
		if ($dynIP) {
			
			$newip = $query->get('ip');
	
			if (!$newip) $newip = $this->container->get('request')->getClientIp();
	
			if ($newip == '::1') $newip = '182.19.255.8';
			
			if ($newip != $dynIP->getIp()) {
				$ip = $dynIP->getIp();
			
				$ec2 = $this->container->get('aws_ec2');
				$ec2->set_region(\AmazonEC2::REGION_SINGAPORE);
				$revokeResp = $ec2->revoke_security_group_ingress(array(
					'GroupId' => 'sg-8d9c5de8',
					'IpPermissions' => array(
						array(
							'IpProtocol' => 'udp',
							'FromPort' => '5060',
							'ToPort' => '5060',
							'IpRanges' => array(
								array('CidrIp' => $ip.'/32'),
							)
						),
						array(
							'IpProtocol' => 'udp',
							'FromPort' => '10000',
							'ToPort' => '30000',
							'IpRanges' => array(
								array('CidrIp' => $ip.'/32'),
							)
						)
					)
				));
			
				$dynIP->setIp($newip);
				$em->flush();
			
				$authorizeResp = $ec2->authorize_security_group_ingress(array(
					'GroupId' => 'sg-8d9c5de8',
					'IpPermissions' => array(
						array(
							'IpProtocol' => 'udp',
							'FromPort' => '5060',
							'ToPort' => '5060',
							'IpRanges' => array(
								array('CidrIp' => $newip.'/32'),
							)
						),
						array(
							'IpProtocol' => 'udp',
							'FromPort' => '10000',
							'ToPort' => '30000',
							'IpRanges' => array(
								array('CidrIp' => $newip.'/32'),
							)
						)
					)
				));
			
				$response->setData(array(
				    'auth' => $authorizeResp->isOK() ? true : false,
					'rev' => $revokeResp->isOK() ? true : false,
					'previp' => $ip,
					'newip' => $newip
				));
			} else {
				$response->setData(array(
				    'auth' => 'no change',
					'rev' => 'no change',
					'ip' => $newip
				));
			}
			
			
		} else {
			$response->setData(array(
			    'dyn-ip' => 'not found'
			));
		}
		return $response;
    }
    
	
	
}
