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
use VoIP\Company\SubscriptionsBundle\Entity\DialPlanItem;
use VoIP\PBX\RealTimeBundle\Extra\Sync;

/**
 * @Route("/s")
 */

class SubscriptionController extends Controller
{	
	
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
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		$employees = $em->getRepository('VoIPCompanyStructureBundle:Employee')->findBy(array(
			'company' => $company
		), array(
			'name' => 'ASC'
		));
		$countries = $em->getRepository('VoIPCompanySubscriptionsBundle:Country')->findBy(array(), array(
			'name' => 'ASC'
		));
		$usedPrefixs = array();
		foreach ($company->getSubscriptions() as $s) {
			$usedPrefixs[] = $s->getPrefix();
		}
		$prefixs = array();
		for ($i=1; $i < 10; $i++) { 
			if (!in_array($i, $usedPrefixs) || $i == $subscription->getPrefix()) {
				$prefixs[] = $i;
			}
		}
        return array(
			'subscription' => $subscription,
			'countries' => $countries,
			'company' => $company,
			'employees' => $employees,
			'prefixs' => $prefixs,
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
		$subscription = $em->getRepository('VoIPCompanySubscriptionsBundle:Subscription')->findOneBy(array(
			'hash' => $hash
		));
        if (!$subscription) throw $this->createNotFoundException('Unable to find Subsciption entity.');
		$company = $subscription->getCompany();
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
		
		$subscription->setName($name);
		$subscription->setType($type);
		$subscription->setNumber($number);
		$subscription->setUsername($username);
		$subscription->setSecret($secret);
		$subscription->setHost($host);
		$subscription->setPrefix($prefix);
		$subscription->setReceiveCall($receive);
		$subscription->setCompany($company);
		
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
		
		$sync = new Sync();
		$astPeer = $sync->subscriptionToPeer($subscription);
		$subscription->setAstPeer($astPeer);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
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
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		
		if ($subscription->getAstPeer()) $em->remove($subscription->getAstPeer());
		
		$em->remove($subscription);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
    /**
     * @Route("/{hash}/dialplan/add/{previousItemHash}", name="ui_subscription_adddialplan", defaults={"previousItemHash"=NULL})
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function addDialplanItemAction($hash, $previousItemHash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$subscription = $em->getRepository('VoIPCompanySubscriptionsBundle:Subscription')->findOneBy(array(
			'hash' => $hash
		));
        if (!$subscription) throw $this->createNotFoundException('Unable to find Subscription entity.');
		$company = $subscription->getCompany();
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
	
		if ($previousItemHash) {
			$previousItem = $em->getRepository('VoIPCompanySubscriptionsBundle:DialPlanItem')->findOneBy(array(
				'hash' => $previousItemHash
			));
	        if (!$previousItem) throw $this->createNotFoundException('Unable to find DialPlanItem entity.');
		} else $previousItem = null;
		
		return array(
			'subscription' => $subscription,
			'previousitem' => $previousItem,
			'company' => $company,
			'phones' => $company->getPhones(),
		);
    }
	
    /**
     * @Route("/{hash}/dialplan/add/{previousItemHash}", defaults={"previousItemHash"=NULL})
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
     */
    public function createDialplanItemAction($hash, $previousItemHash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$subscription = $em->getRepository('VoIPCompanySubscriptionsBundle:Subscription')->findOneBy(array(
			'hash' => $hash
		));
        if (!$subscription) throw $this->createNotFoundException('Unable to find Subscription entity.');
		$company = $subscription->getCompany();
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		
		if ($previousItemHash) {
			$previousItem = $em->getRepository('VoIPCompanySubscriptionsBundle:DialPlanItem')->findOneBy(array(
				'hash' => $previousItemHash
			));
	        if (!$previousItem) throw $this->createNotFoundException('Unable to find DialPlanItem entity.');
		} else $previousItem = null;
	
		$request = $this->getRequest();
		
		$type = $request->get('type');
		
		$phoneId = $request->get('phone');
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $phoneId
		));
		
		$item = new DialPlanItem();
		$item->setType($type);
		$item->setPhone($phone);
		
		$em->persist($item);
		$em->flush();
		
		if ($previousItem) {
			$tmp = $previousItem->getNextItem();
			$previousItem->setNextItem(null);
			$em->flush();
			$item->setNextItem($tmp);
			$em->flush();
			$previousItem->setNextItem($item);
			$em->flush();
		}
		else {
			$tmp = $subscription->getDialPlanFirstItem();
			$subscription->setDialPlanFirstItem(null);
			$em->flush();
			$item->setNextItem($tmp);
			$em->flush();
			$subscription->setDialPlanFirstItem($item);
			$em->flush();
		}
		
		$this->sync($subscription);
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
	public function sync($subscription)
	{
		if ($item = $subscription->getDialPlanFirstItem()) return $this->syncItem($subscription, $item, 1);
		else return false;
	}
	public function syncItem($subscription, $item, $n)
	{
		$em = $this->getDoctrine()->getManager();
		$sync = new Sync();
		$astExension = $sync->itemToExtension($subscription, $item, $n);
		$item->setAstExtension($astExension);
		$em->persist($astExension);
		$em->flush();
		if ($nextItem = $item->getNextItem()) {
			return $this->syncItem($subscription, $nextItem, $n+1);
		} else return true;
	}
}
