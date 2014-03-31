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
     * @Route("/{hash}/edit", name="ui_phone_edit")
     * @Template()
	 * @Method("GET")
     */
    public function editAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
        return array(
			'phone' => $phone
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
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		
		$request = $this->getRequest();
		$extension = $request->get('extension');
		$name = $request->get('name');
		$type = $request->get('type');
		
		if ($extension < 100 || $extension > 999) {
			throw $this->createNotFoundException('Extension range');
		}
		foreach ($company->getPhones() as $p) {
			if ($p->getId() != $phone->getId() && $p->getExtension() == $extension) throw $this->createNotFoundException('Not unique extension.');
		}
		
		$phone->setName($name);
		$phone->setExtension($extension);
		$phone->setType($type);
		
		$em->flush();
			
		$sync = new Sync();
		
		$astPeer = $sync->phoneToPeer($phone);
		$phone->setAstPeer($astPeer);
		
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
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
		$company = $phone->getCompany();
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		
		if ($phone->getAstPeer()) $em->remove($phone->getAstPeer());
		$em->remove($phone);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
    /**
     * @Route("/{hash}/credentials", name="ui_phone_credentials")
     * @Template()
	 * @Method("GET")
     */
    public function credentialsAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		
		return array(
			'phone' => $phone,
		);
    }
}
