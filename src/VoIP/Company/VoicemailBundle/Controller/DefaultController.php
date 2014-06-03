<?php

namespace VoIP\Company\VoicemailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use VoIP\Company\VoicemailBundle\Entity\Message;

class DefaultController extends Controller
{
    /**
     * @Route("/vm")
     * @Template()
	 * @Method("POST")
     */
    public function createAction()
    {
		$em = $this->getDoctrine()->getManager();
		
		$request = $this->getRequest();
		$file = $request->files->get('file');
		$voicemailHash = $request->get('mailbox');
		
        $voicemail = $em->getRepository('VoIPCompanyVoicemailBundle:Voicemail')->findOneBy(array(
        	'hash' => $voicemailHash
        ));
		
		$message = new Message();
		
		$message->setCreatedAt(new \DateTime());
		$message->setVoicemail($voicemail);
		
        $fileName = sha1(uniqid(mt_rand(), true)).'.ogg';
		$filePath = __DIR__.'/../../../../../web/tmp/';
        $file->move($filePath, $fileName);
		$s3 = $this->container->get('aws_s3');
		$s3->create_object('fortyeight', 'vm/'.$fileName, array(
			'fileUpload' => $filePath.$fileName,
			'acl' => \AmazonS3::ACL_PUBLIC,
			'headers' => array(
				'Cache-Control'    => 'max-age=8000000',
				'Content-Language' => 'en-US',
				'Expires'          => 'Tue, 01 Jan 2030 03:54:42 GMT',
			)
		));
		
		$message->setFilePath($fileName);
		$message->setVoicemailHash($voicemailHash);
		
		$em->persist($message);
		$em->flush();
		
		$company = $voicemail->getSubscription()->getCompany();
		foreach ($company->getUsers() as $user) {
		    $message = \Swift_Message::newInstance()
		        ->setSubject('Someone tried to reach you. (S)he let you a voicemail.')
		        ->setFrom('bot@fortyeight.co')
		        ->setTo($user->getEmail())
		        ->setBody(
		            $this->renderView(
		                'VoIPCompanyVoicemailBundle:Default:mail.html.twig',
		                array('name' => $user->getUsername())
		            )
		        )
				->setContentType("text/html")
		    ;
		    $this->get('mailer')->send($message); 
		}
		
		$response = new JsonResponse();
		$response->setData(array());
        return $response;
    }
    /**
     * @Route("/vm/mail")
     * @Template()
     */
    public function mailAction()
    {
		return array(
			'name' => 'Adrien'
		);
    }
}
