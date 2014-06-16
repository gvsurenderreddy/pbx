<?php

namespace VoIP\UI\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use VoIP\Company\SubscriptionsBundle\Entity\OutLine;
use VoIP\PBX\RealTimeBundle\Extra\Sync;

/**
 * @Route("/admin/out-lines")
 */

class OutLinesController extends Controller
{
    /**
     * @Route("/", name="outlines")
     * @Template()
     */
    public function indexAction()
    {
		$em = $this->getDoctrine()->getManager();
		$outLines = $em->getRepository('VoIPCompanySubscriptionsBundle:OutLine')->findBy(array(), array(
			'username' => 'ASC'
		));
        return array(
        	'outlines' => $outLines,
        );
    }
    /**
     * @Route("/new", name="outline_new")
     * @Template()
     * @Method("GET")
     */
    public function newAction()
    {
        return array();
    }
    /**
     * @Route("/edit/{id}", name="outline_edit")
     * @Template("VoIPUIAdminBundle:OutLines:new.html.twig")
     * @Method("GET")
     */
    public function editAction($id)
    {
		$em = $this->getDoctrine()->getManager();
		$outLine = $em->getRepository('VoIPCompanySubscriptionsBundle:OutLine')->find($id);
		if (!$outLine) throw $this->createNotFoundException('Unable to find OutLine entity.');
        return array(
        	'outline' => $outLine
        );
    }
    /**
     * @Route("/create", name="outline_create")
     * @Template()
     * @Method("POST")
     */
    public function createAction()
    {
		$em = $this->getDoctrine()->getManager();
		
		$outLine = new OutLine();
		
		$request = $this->getRequest();
		$type = $request->get('type');
		$username = $request->get('username');
		$secret = $request->get('secret');
		$host = $request->get('host');
		$isPublic = $request->get('ispublic');
		$showNumber = $request->get('shownumber');
		$name = $request->get('name');
		$precision = $request->get('precision');
		
		$outLine->setName($name);
		$outLine->setType($type);
		$outLine->setUsername($username);
		$outLine->setSecret($secret);
		$outLine->setHost($host);
		$outLine->setIsPublic($isPublic);
		$outLine->setShowNumber($showNumber);
		$outLine->setPrecision($precision);
		
		$em->persist($outLine);
		$em->flush();
		
		$sync = new Sync();
		
		$astPeer = $sync->outLineToPeer($outLine);
		$em->persist($astPeer);
		$outLine->setAstPeer($astPeer);
		$em->flush();
		
		return $this->redirect($this->generateUrl('outlines'));
    }
    /**
     * @Route("/update/{id}", name="outline_update")
     * @Template()
     * @Method("POST")
     */
    public function updateAction($id)
    {
		$em = $this->getDoctrine()->getManager();
		
		$outLine = $em->getRepository('VoIPCompanySubscriptionsBundle:OutLine')->find($id);
		if (!$outLine) throw $this->createNotFoundException('Unable to find OutLine entity.');
		
		$request = $this->getRequest();
		$type = $request->get('type');
		$username = $request->get('username');
		$secret = $request->get('secret');
		$host = $request->get('host');
		$isPublic = $request->get('ispublic');
		$showNumber = $request->get('shownumber');
		$name = $request->get('name');
		$precision = $request->get('precision');
		
		$outLine->setName($name);
		$outLine->setType($type);
		$outLine->setUsername($username);
		$outLine->setSecret($secret);
		$outLine->setHost($host);
		$outLine->setIsPublic($isPublic);
		$outLine->setShowNumber($showNumber);
		$outLine->setPrecision($precision);
		
		$em->persist($outLine);
		$em->flush();
		
		$sync = new Sync();
		
		$astPeer = $sync->outLineToPeer($outLine);
		$em->persist($astPeer);
		$outLine->setAstPeer($astPeer);
		$em->flush();
		
		return $this->redirect($this->generateUrl('outlines'));
    }
    /**
     * @Route("/rates/{id}", name="outline_rates")
     * @Template()
     * @Method("GET")
     */
    public function ratesAction($id)
    {
		$em = $this->getDoctrine()->getManager();
		$outLine = $em->getRepository('VoIPCompanySubscriptionsBundle:OutLine')->find($id);
		if (!$outLine) throw $this->createNotFoundException('Unable to find OutLine entity.');
		$rates = $em->getRepository('VoIPPBXBillBundle:Rate')->findBy(array(), array(
			'name' => 'ASC'
		));
        return array(
        	'outline' => $outLine,
			'rates' => $rates
        );
    }
    /**
     * @Route("/rates/{id}")
     * @Template()
     * @Method("POST")
     */
    public function ratesPostAction($id)
    {
		$em = $this->getDoctrine()->getManager();
		$outLine = $em->getRepository('VoIPCompanySubscriptionsBundle:OutLine')->find($id);
		if (!$outLine) throw $this->createNotFoundException('Unable to find OutLine entity.');
		$request = $this->getRequest();
		$rateIds = $request->get('rates');
		foreach ($outLine->getRates() as $rate) {
			$outLine->removeRate($rate);
			$rate->removeOutLine($outLine);
		}
		if (count($rateIds) > 0) {
			foreach ($rateIds as $id) {
				$rate = $em->getRepository('VoIPPBXBillBundle:Rate')->find($id);
				if (!$rate) throw $this->createNotFoundException('Unable to find Rate entity.');
				$outLine->addRate($rate);
				$rate->addOutLine($outLine);
			}
		} else {
			$rates = $em->getRepository('VoIPPBXBillBundle:Rate')->findAll();
			foreach ($rates as $rate) {
				$outLine->addRate($rate);
				$rate->addOutLine($outLine);
			}
		}
		
		$em->flush();
        return $this->redirect($this->generateUrl('outlines'));
    }
}
