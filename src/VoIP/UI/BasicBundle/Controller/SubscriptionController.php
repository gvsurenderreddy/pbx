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
use VoIP\Company\SubscriptionsBundle\Entity\Subscription;
use VoIP\Company\SubscriptionsBundle\Entity\DialPlanItem;
use VoIP\PBX\RealTimeBundle\Extra\Sync;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/s")
 */

class SubscriptionController extends Controller
{	
	/**
     * @Route("/new", name="ui_subscription_new")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function newAction()
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$countries = $em->getRepository('VoIPCompanySubscriptionsBundle:Country')->findBy(array(), array(
			'name' => 'ASC'
		));
		$employees = $em->getRepository('VoIPCompanyStructureBundle:Employee')->findBy(array(
			'company' => $this->getUser()->getCompany()
		), array(
			'name' => 'ASC'
		));
        return array(
			'countries' => $countries,
			'employees' => $employees
		);
    }

    /**
     * @Route("/new")
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
     */
    public function createAction()
    {
		$user = $this->getUser();
		$company = $user->getCompany();
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$requestBag = $request->request;
		$name = $requestBag->get('name');
		$did = $requestBag->get('number');
		$countries = $requestBag->get('countries');
		$employees = $requestBag->get('employees');

		$subscription = new Subscription();
		$subscription->setName($name);
		$subscription->setDid($did);
		$subscription->setCompany($company);

		if ($countries) {
			foreach ($countries as $id) {
				$country = $em->getRepository('VoIPCompanySubscriptionsBundle:Country')->find($id);
				$subscriptions = $country->getSubscriptions()->filter(function($subscription) use ($company){
					return $subscription->getCompany() == $company;
				});
				foreach ($subscriptions as $s) {
					$s->removeCountry($country);
				}
				$subscription->addCountry($country);
			}
		}
		
		if ($employees) {
			foreach ($employees as $id) {
				$employee = $em->getRepository('VoIPCompanyStructureBundle:Employee')->find($id);
				$subscription->addEmployee($employee);
			}
		}
		
		
		$em->persist($subscription);
		$em->flush();
        return $this->redirect($this->generateUrl('ui_company'));
    }

    /**
     * @Route("/{hash}/edit", name="ui_subscription_edit")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function editAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$subscription = $em->getRepository('VoIPCompanySubscriptionsBundle:Subscription')->findOneBy(array(
			'hash' => $hash
		));
        if (!$subscription) throw $this->createNotFoundException('Unable to find Subsciption entity.');
		$company = $subscription->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		$ips = array('54.255.174.110', '54.254.140.140');
        return array(
			'subscription' => $subscription,
			'company' => $company,
			'ips' => $ips
		);
    }
	
    /**
     * @Route("/{hash}/buddies", name="ui_subscription_buddies")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function buddiesAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$subscription = $em->getRepository('VoIPCompanySubscriptionsBundle:Subscription')->findOneBy(array(
			'hash' => $hash
		));
        if (!$subscription) throw $this->createNotFoundException('Unable to find Subsciption entity.');
        return array(
			'subscription' => $subscription,
			'employees' => $user->getCompany()->getEmployees()
		);
    }
	
    /**
     * @Route("/{hash}/buddies")
     * @Template()
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
     */
    public function updateBuddiesAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$subscription = $em->getRepository('VoIPCompanySubscriptionsBundle:Subscription')->findOneBy(array(
			'hash' => $hash
		));
        if (!$subscription) throw $this->createNotFoundException('Unable to find Subsciption entity.');
		$company = $subscription->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$request = $this->getRequest();
		$employees = $request->get('employees');
		
		foreach ($subscription->getEmployees() as $employee) {
			$subscription->removeEmployee($employee);
		}
		
		if ($employees) {
			foreach ($employees as $employeeId) {
				$employee = $em->getRepository('VoIPCompanyStructureBundle:Employee')->find($employeeId);
				if (!$employee) throw $this->createNotFoundException('Unable to find Employee entity.');
				$subscription->addEmployee($employee);
				$employee->addSubscription($subscription);
			}
		}

		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company'));
    }

    /**
     * @Route("/{hash}/countries", name="ui_subscription_countries")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function countriesAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$subscription = $em->getRepository('VoIPCompanySubscriptionsBundle:Subscription')->findOneBy(array(
			'hash' => $hash
		));
        if (!$subscription) throw $this->createNotFoundException('Unable to find Subsciption entity.');
        $countries = $em->getRepository('VoIPCompanySubscriptionsBundle:Country')->findBy(array(), array(
			'name' => 'ASC'
		));
        return array(
			'subscription' => $subscription,
			'countries' => $countries
		);
    }
	
    /**
     * @Route("/{hash}/countries")
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
     */
    public function updateCountriesAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$subscription = $em->getRepository('VoIPCompanySubscriptionsBundle:Subscription')->findOneBy(array(
			'hash' => $hash
		));
        if (!$subscription) throw $this->createNotFoundException('Unable to find Subsciption entity.');
		$company = $subscription->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$request = $this->getRequest();
		$countries = $request->get('countries');
		
		if ($countries) {
			foreach ($countries as $id) {
				$country = $em->getRepository('VoIPCompanySubscriptionsBundle:Country')->find($id);
				$subscriptions = $country->getSubscriptions()->filter(function($subscription) use ($company){
					return $subscription->getCompany() == $company;
				});
				foreach ($subscriptions as $s) {
					$s->removeCountry($country);
				}
				$subscription->addCountry($country);
			}
		}

		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company'));
    }
	
    /**
     * @Route("/{hash}/delete", name="ui_subscription_delete")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$subscription = $em->getRepository('VoIPCompanySubscriptionsBundle:Subscription')->findOneBy(array(
			'hash' => $hash
		));
        if (!$subscription) throw $this->createNotFoundException('Unable to find Subscription entity.');
		$company = $subscription->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		

		$subscription->setCanceledAt(new \DateTime());
		$subscription->setIsActive(false);
		
		if ($peer = $subscription->getAstPeer()) {
			$subscription->setAstPeer(null);
			$em->remove($peer);
		}
		
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company'));
    }
	
    /**
     * @Route("/{hash}/setup", name="ui_subscription_setup")
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
		$subscription = $em->getRepository('VoIPCompanySubscriptionsBundle:Subscription')->findOneBy(array(
			'hash' => $hash
		));
        if (!$subscription) throw $this->createNotFoundException('Unable to find Subscription entity.');
		$company = $subscription->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$url = 'http://'.$ip.'/admin/voice/bsipura.spa';
	
		$ciscoConf = array(
			'P34415' => '',
			'30191' => '',
			'33135' => '',
			'29999' => '0',
			'27183' => '',
			'27375' => '',
			'27311' => '',
			'27503' => '',
			'26351' => '16384',
			'26287' => '16482',
			'27631' => '100',
			'27567' => '101',
			'26735' => '98',
			'26671' => '97',
			'26863' => '96',
			'26799' => '99',
			'43951' => '1800',
			'43119' => '30',
			'44719' => '*69',
			'38319' => '*07',
			'44911' => '*98',
			'37423' => '*66',
			'37615' => '*86',
			'39535' => '*05',
			'44847' => '*72',
			'45039' => '*73',
			'44975' => '*90',
			'44143' => '*91',
			'44079' => '*92',
			'44271' => '*93',
			'44207' => '*63',
			'44399' => '*83',
			'44335' => '*60',
			'44527' => '*80',
			'44463' => '*64',
			'37487' => '*84',
			'37551' => '*56',
			'37743' => '*57',
			'37679' => '*71',
			'37871' => '*70',
			'37807' => '*67',
			'36975' => '*68',
			'36911' => '*81',
			'37103' => '*82',
			'37039' => '*77',
			'37231' => '*87',
			'37167' => '*78',
			'37359' => '*79',
			'37295' => '*65',
			'38511' => '*85',
			'38447' => '*25',
			'38639' => '*45',
			'38575' => '*26',
			'38767' => '*46',
			'35503' => '',
			'35567' => '',
			'43183' => 'GMT-08:00',
			'43375' => '600',
			'21167' => '0',
			'26159' => '5061',
			'21359' => '',
			'21423' => '1',
			'20975' => '1',
			'20591' => '3600',
			'20911' => '1',
			'20527' => '',
			'20719' => '',
			'P20655' => '',
			'20847' => '0',
			'20783' => '',
			'22127' => '1',
			'22063' => '1',
			'22255' => '1',
			'22191' => '1',
			'22383' => '1',
			'22319' => '1',
			'22511' => '1',
			'22447' => '1',
			'21615' => '1',
			'21551' => '1',
			'21743' => '1',
			'21679' => '1',
			'21871' => '1',
			'21807' => '1',
			'21999' => '1',
			'22767' => '1',
			'21935' => '1',
			'23151' => '1',
			'23087' => '1',
			'23279' => '1',
			'23215' => '1',
			'23407' => '1',
			'23343' => '1',
			'16623' => 'G711u',
			'16879' => '0',
			'16751' => '0',
			'18287' => '1',
			'16559' => 'Auto',
			'687' => '1',
			'35823' => '5060',
			'879' => 'pbx.fortyeight.co',
			'943' => '1',
			'495' => '1',
			'111' => '3600',
			'431' => '1',
			'47' => $subscription->getUsername(),
			'239' => $subscription->getUsername(),
			'P175' => $subscription->getSecret(),
			'367' => '0',
			'303' => '',
			'61679' => 'G711u',
			'61935' => '0',
			'61807' => '0',
			'63343' => '1',
			'61615' => 'Auto',
			'17775' => '',
			'17711' => '',
			'17903' => '',
			'17839' => '20',
			'20335' => '',
			'19055' => '',
			'20271' => '',
			'18991' => '',
			'20463' => '',
			'19183' => '',
			'20399' => '',
			'19119' => '',
			'19567' => '',
			'19311' => '',
			'19503' => '',
			'19247' => '',
			'19695' => '',
			'19439' => '',
			'19631' => '',
			'19375' => '',
			'13935' => '',
			'12719' => '',
			'13871' => '',
			'14063' => '',
			'12399' => '1',
			'12335' => '0',
			'12527' => '0',
			'12463' => '0',
			'12655' => '1',
			'12591' => '1',
			'12783' => '1',
			'14127' => '0',
			'10351' => '0',
			'24175' => '',
			'24111' => '',
			'24303' => '',
			'24239' => '',
			'24431' => '',
			'24367' => '',
			'24559' => '',
			'24495' => '',
			'17263' => '1',
			'17199' => '1',
			'17391' => '8',
			'17327' => '7',
			'14447' => '0',
			'14575' => '0',
			'14383' => '0',
			'65391' => '',
			'64111' => '',
			'65327' => '',
			'64047' => '',
			'65519' => '',
			'64239' => '',
			'65455' => '',
			'64175' => '',
			'64623' => '',
			'64367' => '',
			'64559' => '',
			'64303' => '',
			'64751' => '',
			'64495' => '',
			'64687' => '',
			'64431' => '',
			'3695' => '',
			'3631' => '',
			'3823' => '',
			'3759' => '',
			'3951' => '',
			'3887' => '',
			'4079' => '',
			'4015' => '',
			'62319' => '1'
		);
		
		return array(
			'conf' => $ciscoConf,
			'url' => $url
		);
    }
	
    /**
     * @Route("/{hash}/configure", name="ui_subscription_configure")
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
		$subscription = $em->getRepository('VoIPCompanySubscriptionsBundle:Subscription')->findOneBy(array(
			'hash' => $hash
		));
        if (!$subscription) throw $this->createNotFoundException('Unable to find Subscription entity.');
		$company = $subscription->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		return array(
			'subscription' => $subscription,
			'company' => $company
		);
    }
    /**
     * @Route("/{hash}/configure.js", name="ui_subscription_configure_js")
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function configureJSAction($hash)
    {
		$request = $this->getRequest();
		$ip = $request->get('ip');
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$subscription = $em->getRepository('VoIPCompanySubscriptionsBundle:Subscription')->findOneBy(array(
			'hash' => $hash
		));
        if (!$subscription) throw $this->createNotFoundException('Unable to find Subscription entity.');
		$company = $subscription->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$response = new Response($this->renderView(
		    'VoIPUIBasicBundle:Subscription:configure.js.twig',
		    array(
				'subscription' => $subscription,
				'company' => $company
			)
		));
		$response->headers->set('Content-Type', 'text/javascript');
		return $response;
    }
}
