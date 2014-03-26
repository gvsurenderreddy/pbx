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
 * @Route("/private/o")
 */

class OfficeController extends Controller
{	
    /**
     * @Route("/{hash}/new-phone", name="ui_office_newphone")
     * @Template()
	 * @Method("GET")
     */
    public function newPhoneAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$office = $em->getRepository('VoIPCompanyStructureBundle:Office')->findOneBy(array(
			'hash' => $hash
		));
        if (!$office) throw $this->createNotFoundException('Unable to find Office entity.');
		$company = $office->getCompany();
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
        return array(
			'office' => $office
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
		$office = $em->getRepository('VoIPCompanyStructureBundle:Office')->findOneBy(array(
			'hash' => $hash
		));
        if (!$office) throw $this->createNotFoundException('Unable to find Office entity.');
		$company = $office->getCompany();
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		
		$request = $this->getRequest();
		$extension = $request->get('extension');
		$name = $request->get('name');
		$type = $request->get('type');
		
		foreach ($company->getOffices() as $office) {
			foreach ($office->getPhones() as $phone) {
				if ($phone->getExtension() == $extension) throw $this->createNotFoundException('Not unique extension.');
			}
		}
		
		$phone = new Phone();
		$phone->setName($name);
		$phone->setExtension($extension);
		$phone->setType($type);
		$phone->setOffice($office);
		
		$em->persist($phone);
		
		$sync = new Sync();
		
		$em->flush();
		
		$astPeer = $sync->phoneToPeer($phone);
		$astExtension = $sync->phoneToExtension($phone);
	
		$em->persist($astPeer);
		$em->persist($astExtension);
		
		$phone->setAstPeer($astPeer);
		$phone->setAstExtension($astExtension);
		
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
    /**
     * @Route("/{hash}/delete", name="ui_office_delete")
     * @Template()
	 * @Method("GET")
     */
    public function deleteAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$office = $em->getRepository('VoIPCompanyStructureBundle:Office')->findOneBy(array(
			'hash' => $hash
		));
        if (!$office) throw $this->createNotFoundException('Unable to find Office entity.');
		$company = $office->getCompany();
		if (!$user->hasCompany($company)) throw $this->createNotFoundException('No authorization.');
		
		foreach ($office->getPhones() as $phone) {
			if ($phone->getAstPeer()) $em->remove($phone->getAstPeer());
			if ($phone->getAstExtension()) $em->remove($phone->getAstExtension());
			$em->remove($phone);
		}
		
		$em->remove($office);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
}
