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
 * @Route("/private/p")
 */

class PhoneController extends Controller
{	
    /**
     * @Route("/{hash}/delete", name="ui_phone_delete")
     * @Template()
	 * @Method("GET")
     */
    public function deleteAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getOffice()->getCompany();
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		
		if ($phone->getAstPeer()) $em->remove($phone->getAstPeer());
		if ($phone->getAstExtension()) $em->remove($phone->getAstExtension());
		$em->remove($phone);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
}
