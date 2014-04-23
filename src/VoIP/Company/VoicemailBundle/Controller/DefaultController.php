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
		$voicemailHash = (int)$request->files->get('mailbox');
		
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
		$s3->create_object('voiptest', 'vm/'.$fileName, array(
			'fileUpload' => $filePath.$fileName,
			'acl' => \AmazonS3::ACL_PUBLIC,
			'headers' => array(
				'Cache-Control'    => 'max-age=8000000',
				'Content-Language' => 'en-US',
				'Expires'          => 'Tue, 01 Jan 2030 03:54:42 GMT',
			)
		));
		
		$message->setFilePath($fileName);
		
		$em->persist($message);
		$em->flush();
		
		$response = new JsonResponse();
		$response->setData(array());
        return $response;
    }
}
