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
		$extension = $request->get('extension');
		$name = $request->get('name');
		$type = $request->get('type');
		
		if ($extension < 100 || $extension > 999) {
			throw $this->createNotFoundException('Extension range');
		}
		
		$employee = new Employee();
		$employee->setName($name);
		$employee->setExtension($extension);
		
		$phone = new Phone();
		$phone->setType($type);
		$phone->setCompany($company);
		
		$phone->setEmployee($employee);
		
		$em->persist($employee);
		$em->persist($phone);
		
		$sync = new Sync();
		
		$em->flush();
		
		$astPeer = $sync->phoneToPeer($phone);
		$em->persist($astPeer);
		
		$phone->setAstPeer($astPeer);
		
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
        return array(
			'company' => $company,
			'countries' => $countries
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
		$host = $request->get('host');
		$prefix = $request->get('prefix');
		$receive = $request->get('receive') === 'on';
		$countries = $request->get('countries');
		
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
		
		if ($countries) {
			foreach ($countries as $countryId) {
				$country = $em->getRepository('VoIPCompanySubscriptionsBundle:Country')->find($countryId);
				if (!$country) throw $this->createNotFoundException('Unable to find Country entity.');
				$subscription->addCountry($country);
				$country->addSubscription($subscription);
			}
		}
		
		$em->persist($subscription);
		
		$em->flush();
		
		$sync = new Sync();
		$astPeer = $sync->subscriptionToPeer($subscription);
		$em->persist($astPeer);
		$subscription->setAstPeer($astPeer);
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
}
