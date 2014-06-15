<?php

namespace VoIP\UI\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use VoIP\Company\SubscriptionsBundle\Entity\OutGroup;
use VoIP\PBX\RealTimeBundle\Extra\Sync;
use Symfony\Component\HttpFoundation\JsonResponse;
use VoIP\PBX\BillBundle\Entity\Rate;

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
    /**
     * @Route("/csv", name="rate_csv")
     * @Template()
     * @Method("GET")
     */
    public function csvAction()
    {
		return array();
    }
    /**
     * @Route("/csv")
     * @Template()
     * @Method("POST")
     */
    public function csvPostAction()
    {
		$request = $this->getRequest();
		$em = $this->getDoctrine()->getManager();
		$csv = $request->files->get('file');
		$string = file_get_contents($csv);
		$tmp = explode("\n", $string);
		unset($tmp[0]);
		$data = array();
		foreach ($tmp as $t) {
			$row = explode(',', $t);
			if (isset($row[2])) {
				$rate = $em->getRepository('VoIPPBXBillBundle:Rate')->findOneBy(array(
					'code' => $row[0],
					'prefix' => $row[2]
				));
				if (!$rate) {
					$rate = new Rate();
					$rate->setCode($row[0]);
					$rate->setName($row[1]);
					$rate->setPrefix($row[2]);
					$rate->setRateIn(0);
					$rate->setPrecision(strlen($row[2]));
					$rate->setRate($row[3]);
					$rate->setUpdatedAt(new \DateTime());
					$em->persist($rate);
				} else {
					$rate->setRate($row[3]);
					$rate->setUpdatedAt(new \DateTime());
				}
				
				$row[4] = $rate->getId();
				$data[] = $row;
			}
			
		}
		$em->flush();
		$now = new \DateTime();
		return $this->redirect($this->generateUrl('rate_csv', array(
			'updated' => $now->format('Y-m-d_H-i-s')
		)));
    }
}
