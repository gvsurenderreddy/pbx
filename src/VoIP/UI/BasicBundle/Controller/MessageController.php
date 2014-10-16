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
use VoIP\PBX\RealTimeBundle\Extra\Sync;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/messages")
 */

class MessageController extends Controller
{	
    /**
     * @Route("/dl/{id}.wav", name="ui_message_dl")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function dlAction($id)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$message = $em->getRepository('VoIPPBXRealTimeBundle:VoiceMessage')->find('id' => $id);
        if (!$message) throw $this->createNotFoundException('Unable to find Message entity.');
		
		$response = new Response();
		$response->headers->set('Content-Type', 'audio/wav');
		$response->headers->set('Content-Disposition', 'attachment');
		$response->setContent(stream_get_contents($message->getRecording()));
		return $response;
    }
	
	
    /**
     * @Route("/{hash}/read", name="ui_message_read")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function readAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$message = $em->getRepository('VoIPCompanyVoicemailBundle:Message')->findOneBy(array(
			'hash' => $hash
		));
        if (!$message) throw $this->createNotFoundException('Unable to find Message entity.');
		$company = $message->getVoicemail()->getSubscription()->getCompany();
		if (!$user->getCompany()->getId() == $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$message->setReadAt(new \DateTime());
		$em->flush();
		
		$response = new JsonResponse();
		$response->setData(true);
        return $response;
    }
	
    /**
     * @Route("/{hash}/unread", name="ui_message_unread")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function unreadAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$message = $em->getRepository('VoIPCompanyVoicemailBundle:Message')->findOneBy(array(
			'hash' => $hash
		));
        if (!$message) throw $this->createNotFoundException('Unable to find Message entity.');
		$company = $message->getVoicemail()->getSubscription()->getCompany();
		if (!$user->getCompany()->getId() == $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$message->setReadAt(null);
		$em->flush();
		
        return $this->redirect($this->generateUrl('ui_company_mailbox', array(
			'hash' => $company->getHash()
		)));
    }
	
    /**
     * @Route("/{hash}/archive", name="ui_message_archive")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function archiveAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$message = $em->getRepository('VoIPCompanyVoicemailBundle:Message')->findOneBy(array(
			'hash' => $hash
		));
        if (!$message) throw $this->createNotFoundException('Unable to find Message entity.');
		$company = $message->getVoicemail()->getSubscription()->getCompany();
		if (!$user->getCompany()->getId() == $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$message->setArchivedAt(new \DateTime());
		$em->flush();
		
        return $this->redirect($this->generateUrl('ui_company_mailbox', array(
			'hash' => $company->getHash()
		)));
    }
}
