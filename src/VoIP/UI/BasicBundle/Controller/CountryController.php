<?php

namespace VoIP\UI\BasicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use VoIP\Company\StructureBundle\Entity\Company;
use VoIP\PBX\RealTimeBundle\Extra\Sync;
use Symfony\Component\HttpFoundation\JsonResponse;
use VoIP\Company\SubscriptionsBundle\Entity\Country;

/**
 * @Route("/settings/country")
 */

class CountryController extends Controller
{
    /**
     * @Route("/update", name="country_update")
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
	 * @Method("GET")
     */
    public function updateAction()
    {
		return array();
    }

    /**
     * @Route("/update")
     * @Security("has_role('ROLE_ADMIN')")
	 * @Method("POST")
     */
    public function refreshAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$request = $this->getRequest();
    	$fileBag = $request->files;
    	$file = $fileBag->get('file');

    	$array = json_decode(file_get_contents($file), true);

    	var_dump($array);
		//exit();
    	foreach ($array as $row) {
    		foreach ($row['callingCode'] as $callingCode) {
    			$country = new Country();
	    		$country->setName($row['name']['common']);
	    		$country->setIsoCode2($row['cca2']);
	    		$country->setIsoCode3($row['cca3']);
	    		$country->setCallCode($callingCode);
	    		$em->persist($country);
    		}
    	}
    	$em->flush();

		return $this->redirect($this->generateUrl('ui_company'));
    }
}
