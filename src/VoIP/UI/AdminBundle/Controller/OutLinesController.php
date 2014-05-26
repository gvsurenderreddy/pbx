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
		$countries = $em->getRepository('VoIPCompanySubscriptionsBundle:Country')->findBy(array(), array(
			'name' => 'ASC'
		));
		$outLines = $em->getRepository('VoIPCompanySubscriptionsBundle:OutLine')->findBy(array(), array(
			'username' => 'ASC'
		));
        return array(
        	'outlines' => $outLines,
			'countries' => $countries
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
		
		$outLine->setType($type);
		$outLine->setUsername($username);
		$outLine->setSecret($secret);
		$outLine->setHost($host);
		
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
		
		$outLine->setType($type);
		$outLine->setUsername($username);
		$outLine->setSecret($secret);
		$outLine->setHost($host);
		
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
     * @Route("/countries/{id}", name="outline_countries")
     * @Template()
     * @Method("GET")
     */
    public function countriesAction($id)
    {
		$em = $this->getDoctrine()->getManager();
		$outLine = $em->getRepository('VoIPCompanySubscriptionsBundle:OutLine')->find($id);
		if (!$outLine) throw $this->createNotFoundException('Unable to find OutLine entity.');
		$countries = $em->getRepository('VoIPCompanySubscriptionsBundle:Country')->findBy(array(), array(
			'name' => 'ASC'
		));
        return array(
        	'outline' => $outLine,
			'countries' => $countries
        );
    }
    /**
     * @Route("/countries/{id}")
     * @Template()
     * @Method("POST")
     */
    public function countriesPostAction($id)
    {
		$em = $this->getDoctrine()->getManager();
		$outLine = $em->getRepository('VoIPCompanySubscriptionsBundle:OutLine')->find($id);
		if (!$outLine) throw $this->createNotFoundException('Unable to find OutLine entity.');
		$request = $this->getRequest();
		$countryIds = $request->get('countries');
		foreach ($countryIds as $id) {
			$country = $em->getRepository('VoIPCompanySubscriptionsBundle:Country')->find($id);
			if (!$country) throw $this->createNotFoundException('Unable to find Country entity.');
			$country->setOutLine($outLine);
		}
		$em->flush();
        return $this->redirect($this->generateUrl('outlines'));
    }
}
