<?php

namespace VoIP\UI\BasicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use VoIP\Company\StructureBundle\Entity\Company;
use VoIP\Company\StructureBundle\Entity\Office;
use VoIP\Company\StructureBundle\Entity\Phone;
use VoIP\Company\SubscriptionsBundle\Entity\DialPlanItem;
use VoIP\PBX\RealTimeBundle\Extra\Sync;

/**
 * @Route("/private/s")
 */

class SubscriptionController extends Controller
{	
	
    /**
     * @Route("/{hash}/edit", name="ui_subscription_edit")
     * @Template()
	 * @Method("GET")
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
		$countries = $em->getRepository('VoIPCompanySubscriptionsBundle:Country')->findBy(array(), array(
			'name' => 'ASC'
		));
        return array(
			'subscription' => $subscription,
			'countries' => $countries
		);
    }
	
    /**
     * @Route("/{hash}/edit")
     * @Template()
	 * @Method("POST")
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
		$host = $request->get('host');
		$prefix = $request->get('prefix');
		$receive = $request->get('receive') === 'on';
		$countries = $request->get('countries');
		
		$subscription->setName($name);
		$subscription->setType($type);
		$subscription->setNumber($number);
		$subscription->setUsername($username);
		$subscription->setSecret($secret);
		$subscription->setHost($host);
		$subscription->setPrefix($prefix);
		$subscription->setReceiveCall($receive);
		$subscription->setCompany($company);
		
		foreach ($subscription->getCountries() as $country) {
			$subscription->removeCountry($country);
		}
		
		if ($countries) {
			foreach ($countries as $countryId) {
				$country = $em->getRepository('VoIPCompanySubscriptionsBundle:Country')->find($countryId);
				if (!$country) throw $this->createNotFoundException('Unable to find Country entity.');
				$subscription->addCountry($country);
				$country->addSubscription($subscription);
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
		
		$item = new DialPlanItem();
		$item->setType($type);
		
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
			$item->setNextItem($subscription->getDialPlanFirstItem());
			$em->flush();
			$subscription->setDialPlanFirstItem($item);
			$em->flush();
		}
		
		
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
}
