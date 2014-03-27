<?php

namespace VoIP\UI\BasicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use VoIP\Company\StructureBundle\Entity\Company;
use VoIP\Company\StructureBundle\Entity\Office;
use VoIP\Company\StructureBundle\Entity\Phone;
use VoIP\PBX\RealTimeBundle\Extra\Sync;

/**
 * @Route("/private/s")
 */

class SubscriptionController extends Controller
{	
	
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
}
