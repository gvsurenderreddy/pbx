<?php

namespace Management\Session\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
	/**
	 * @Route("/test")
	 * @Template()
	 */
	public function testAction()
	{
		$message = \Swift_Message::newInstance()
		->setSubject('Hello Email')
		->setFrom('no-reply@fortyeight.co')
		->setTo('adrien.eudes@gmail.com')
		->setBody('You should see me from the profiler!');

		$this->get('mailer')->send($message);
		return array();
		
	}
}
