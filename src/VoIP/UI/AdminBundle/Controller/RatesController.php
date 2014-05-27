<?php

namespace VoIP\UI\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use VoIP\Company\SubscriptionsBundle\Entity\OutGroup;
use VoIP\PBX\RealTimeBundle\Extra\Sync;

/**
 * @Route("/admin/rates")
 */

class RatesController extends Controller
{
    /**
     * @Route("/", name="rates")
     * @Template()
     */
    public function indexAction()
    {
		$em = $this->getDoctrine()->getManager();
		$rates = $em->getRepository('VoIPPBXBillBundle:Rate')->findBy(array(), array(
			'name' => 'ASC'
		));
        return array(
        	'rates' => $rates,
        );
    }
    /**
     * @Route("/new", name="rate_new")
     * @Template()
     * @Method("GET")
     */
    public function newAction()
    {
        return array();
    }
    /**
     * @Route("/edit/{id}", name="rate_edit")
     * @Template("VoIPUIAdminBundle:Rates:new.html.twig")
     * @Method("GET")
     */
    public function editAction($id)
    {
		$em = $this->getDoctrine()->getManager();
		$outGroup = $em->getRepository('VoIPCompanySubscriptionsBundle:OutGroup')->find($id);
		if (!$outGroup) throw $this->createNotFoundException('Unable to find OutGroup entity.');
        return array(
        	'outgroup' => $outGroup
        );
    }
    /**
     * @Route("/create", name="rate_create")
     * @Template()
     * @Method("POST")
     */
    public function createAction()
    {
		$em = $this->getDoctrine()->getManager();
		
		$outGroup = new OutGroup();
		
		$request = $this->getRequest();
		$isPublic = $request->get('ispublic');
		
		$outGroup->setIsPublic($isPublic);
		
		$em->persist($outGroup);
		$em->flush();
		
		return $this->redirect($this->generateUrl('outgroups'));
    }
    /**
     * @Route("/update/{id}", name="rate_update")
     * @Template()
     * @Method("POST")
     */
    public function updateAction($id)
    {
		$em = $this->getDoctrine()->getManager();
		
		$outGroup = $em->getRepository('VoIPCompanySubscriptionsBundle:OutGroup')->find($id);
		if (!$outGroup) throw $this->createNotFoundException('Unable to find OutGroup entity.');
		
		$request = $this->getRequest();
		$isPublic = $request->get('ispublic');
		
		$outGroup->setIsPublic($isPublic);
		
		$em->persist($outGroup);
		$em->flush();
		
		return $this->redirect($this->generateUrl('outgroups'));
    }
    /**
     * @Route("/companies/{id}", name="rate_companies")
     * @Template()
     * @Method("GET")
     */
    public function companiesAction($id)
    {
		$em = $this->getDoctrine()->getManager();
		$outGroup = $em->getRepository('VoIPCompanySubscriptionsBundle:OutGroup')->find($id);
		if (!$outGroup) throw $this->createNotFoundException('Unable to find OutGroup entity.');
		$companies = $em->getRepository('VoIPCompanyStructureBundle:Company')->findBy(array(), array(
			'name' => 'ASC'
		));
        return array(
        	'outgroup' => $outGroup,
			'companies' => $companies
        );
    }
    /**
     * @Route("/companies/{id}")
     * @Template()
     * @Method("POST")
     */
    public function companiesPostAction($id)
    {
		$em = $this->getDoctrine()->getManager();
		$outGroup = $em->getRepository('VoIPCompanySubscriptionsBundle:OutGroup')->find($id);
		if (!$outGroup) throw $this->createNotFoundException('Unable to find OutGroup entity.');
		$request = $this->getRequest();
		$companyIds = $request->get('companies');
		foreach ($companyIds as $id) {
			$company = $em->getRepository('VoIPCompanyStructureBundle:Company')->find($id);
			if (!$company) throw $this->createNotFoundException('Unable to find Company entity.');
			$company->setOutGroup($outGroup);
		}
		$em->flush();
        return $this->redirect($this->generateUrl('outgroups'));
    }
    /**
     * @Route("/outlines/{id}", name="rate_outlines")
     * @Template()
     * @Method("GET")
     */
    public function outlinesAction($id)
    {
		$em = $this->getDoctrine()->getManager();
		$outGroup = $em->getRepository('VoIPCompanySubscriptionsBundle:OutGroup')->find($id);
		if (!$outGroup) throw $this->createNotFoundException('Unable to find OutGroup entity.');
		$outLines = $em->getRepository('VoIPCompanySubscriptionsBundle:OutLine')->findBy(array(), array(
			'name' => 'ASC'
		));
        return array(
        	'outgroup' => $outGroup,
			'outlines' => $outLines
        );
    }
    /**
     * @Route("/outlines/{id}")
     * @Template()
     * @Method("POST")
     */
    public function outlinesPostAction($id)
    {
		$em = $this->getDoctrine()->getManager();
		$outGroup = $em->getRepository('VoIPCompanySubscriptionsBundle:OutGroup')->find($id);
		if (!$outGroup) throw $this->createNotFoundException('Unable to find OutGroup entity.');
		$request = $this->getRequest();
		$outlineIds = $request->get('outlines');
		foreach ($outGroup->getOutLines() as $outLine) {
			$outLine->removeOutGroup($outGroup);
			$outGroup->removeOutLine($outLine);
		}
		foreach ($outlineIds as $id) {
			$outLine = $em->getRepository('VoIPCompanySubscriptionsBundle:OutLine')->find($id);
			if (!$outLine) throw $this->createNotFoundException('Unable to find OutLine entity.');
			$outLine->addOutGroup($outGroup);
			$outGroup->addOutLine($outLine);
		}
		$em->flush();
        return $this->redirect($this->generateUrl('outgroups'));
    }
}
