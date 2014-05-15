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
 * @Route("/p")
 */

class PhoneController extends Controller
{	
	
    /**
     * @Route("/{hash}/edit", name="ui_phone_edit")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function editAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
        return array(
			'phone' => $phone,
			'company' => $company
		);
    }
	
    /**
     * @Route("/{hash}/edit")
     * @Template()
	 * @Method("POST")
	 * @Security("has_role('ROLE_USER')")
     */
    public function updateAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$request = $this->getRequest();
		$name = $request->get('name');
		$type = $request->get('type');
		
		$prevName = $phone->getName();
		
		$phone->setName($name);
		$phone->setType($type);
		
		$em->flush();
			
		$sync = new Sync();
		
		$astPeer = $sync->phoneToPeer($phone);
		$phone->setAstPeer($astPeer);
		
		$em->persist($astPeer);
		
		$em->flush();
		
		if ($phone->getType() == 'ciscophone' && $prevName != $phone->getName()) {
			return $this->redirect($this->generateUrl('ui_phone_configure', array(
				'hash' => $phone->getHash()
			)));
		} else {
			return $this->redirect($this->generateUrl('ui_company', array(
				'hash' => $company->getHash()
			)));
		}
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
    /**
     * @Route("/{hash}/attribute/{hash2}", name="ui_phone_attribute")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function attributeAction($hash, $hash2)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$employee = $em->getRepository('VoIPCompanyStructureBundle:Employee')->findOneBy(array(
			'hash' => $hash2
		));
        if (!$employee) throw $this->createNotFoundException('Unable to find Employee entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		if ($prevPhone = $employee->getPhone()) $prevPhone->setEmployee(null);
		$em->flush();
		
		$phone->setEmployee($employee);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
    /**
     * @Route("/{hash}/unattribute", name="ui_phone_unattribute")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function unattributeAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$phone->setEmployee(null);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
    /**
     * @Route("/{hash}/delete", name="ui_phone_delete")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		if ($phone->getAstPeer()) $em->remove($phone->getAstPeer());
		$em->remove($phone);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ui_company', array(
			'hash' => $company->getHash()
		)));
    }
	
    /**
     * @Route("/{hash}/credentials", name="ui_phone_credentials")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function credentialsAction($hash)
    {
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		return array(
			'phone' => $phone,
		);
    }
	
    /**
     * @Route("/{hash}/setup", name="ui_phone_setup")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function setupAction($hash)
    {
		$request = $this->getRequest();
		$ip = $request->get('ip');
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$url = 'http://'.$ip.'/admin/bcisco.csc';
	
		$ciscoConf = array(
			'30895' => '1',
			'P30959' => '',
			'63917' => 'SIP',
			'29743' => 'DHCP',
			'29935' => '',
			'29871' => '',
			'30063' => '',
			'30703' => '',
			'30639' => '',
			'30191' => '',
			'30127' => '',
			'31279' => 'Parallel',
			'31471' => '',
			'31407' => '',
			'31599' => '0',
			'12975' => '',
			'13167' => '',
			'13103' => '',
			'13295' => '',
			'12527' => '16384',
			'12463' => '16538',
			'14127' => '101',
			'14319' => '98',
			'14255' => '97',
			'13423' => '96',
			'13359' => '99',
			'9199' => 'none',
			'6127' => '1800',
			'6063' => '30',
			'7855' => '*69',
			'8047' => '*98',
			'559' => '*66',
			'751' => '*86',
			'7983' => '*72',
			'8175' => '*73',
			'8111' => '*90',
			'7279' => '*91',
			'7215' => '*92',
			'7407' => '*93',
			'687' => '*56',
			'879' => '*57',
			'815' => '*71',
			'1007' => '*70',
			'943' => '*67',
			'111' => '*68',
			'47' => '*81',
			'239' => '*82',
			'175' => '*77',
			'367' => '*87',
			'303' => '*78',
			'495' => '*79',
			'2287' => '',
			'2095' => '',
			'2159' => 'GMT-08:00',
			'3823' => '',
			'61743' => '1',
			'1005' => $phone->getName(),
			'57645' => $phone->getName(),
			'1325' => '',
			'4077' => '',
			'3437' => '',
			'63277' => 'Default',
			'3501' => 'None',
			'57709' => 'Auto',
			'63469' => '0',
			'63405' => '300',
			'62573' => 'Background Picture',
			'32685' => '1',
			'31853' => '$USER',
			'32109' => '1',
			'32045' => '$USER',
			'25133' => '1',
			'25325' => '$USER',
			'62253' => 'Vertical First',
			'62445' => 'Per Line',
			'62381' => '2',
			'61549' => 'Scrollable',
			'109' => 'n=Classic-1;w=3;c=1',
			'45' => 'n=Classic-2;w=3;c=2',
			'237' => 'n=Classic-3;w=3;c=3',
			'173' => 'n=Classic-4;w=3;c=4',
			'365' => 'n=Simple-1;w=2;c=1',
			'301' => 'n=Simple-2;w=2;c=2',
			'493' => 'n=Simple-3;w=2;c=3',
			'429' => 'n=Simple-4;w=2;c=4',
			'1645' => 'n=Simple-5;w=2;c=5',
			'1581' => 'n=Office;w=4;c=1',
			'1773' => 'n=Pulse;w=5;c=1',
			'1709' => 'n=Du-dut;w=6;c=1',
			'4013' => '0',
			'3181' => '0',
			'3117' => '0',
			'62701' => '0',
			'62637' => '0',
			'62829' => '0',
			'65197' => '',
			'64557' => '',
			'64621' => 'Enterprise',
			'65517' => '',
			'58157' => '',
			'58349' => '',
			'58285' => '',
			'57453' => '',
			'57389' => '',
			'P57581' => '',
			'63215' => '1',
			'61231' => '0',
			'58159' => '0',
			'64495' => '0',
			'63151' => '5060',
			'64367' => 'none',
			'59311' => '0',
			'59183' => '1',
			'59631' => '',
			'61359' => '0',
			'60463' => '1',
			'60655' => '0',
			'63343' => 'pbx.fortyeight.co',
			'63407' => '1',
			'62959' => '0',
			'62575' => '3600',
			'62895' => '0',
			'62511' => $phone->getAstPeer()->getDefaultuser(),
			'62703' => $phone->getAstPeer()->getDefaultuser(),
			'P62639' => $phone->getAstPeer()->getSecret(),
			'62831' => '0',
			'62767' => '',
			'63727' => 'G711u',
			'63855' => '0',
			'64879' => 'Unspecified',
			'64815' => 'Unspecified',
			'63791' => '0',
			'63663' => 'Auto',
			'55087' => '0',
			'52335' => '0',
			'49263' => '0',
			'55343' => '0',
			'55279' => '5061',
			'56239' => 'none',
			'50415' => '0',
			'50287' => '1',
			'51503' => '',
			'52463' => '0',
			'52591' => '1',
			'52527' => '0',
			'55215' => '',
			'54511' => '1',
			'55855' => '0',
			'54447' => '3600',
			'56047' => '0',
			'54639' => '',
			'54575' => '',
			'P54767' => '',
			'54703' => '0',
			'55919' => '',
			'55599' => 'G711u',
			'55727' => '0',
			'56751' => 'Unspecified',
			'49775' => 'Unspecified',
			'56943' => '0',
			'55791' => 'Auto',
			'46191' => '0',
			'44207' => '0',
			'41135' => '0',
			'47471' => '0',
			'46127' => '5062',
			'47343' => 'none',
			'42287' => '0',
			'42159' => '1',
			'44655' => '',
			'44335' => '0',
			'44463' => '1',
			'37487' => '0',
			'46383' => '1',
			'47983' => '0',
			'46575' => '3600',
			'47919' => '0',
			'46511' => '',
			'47727' => '',
			'P47663' => '',
			'47855' => '0',
			'47791' => '',
			'48751' => 'G711u',
			'48879' => '0',
			'41711' => 'Unspecified',
			'41647' => 'Unspecified',
			'48815' => '0',
			'48687' => 'Auto',
			'38063' => '1',
			'36335' => '0',
			'33263' => '0',
			'39343' => '0',
			'38255' => '5063',
			'39215' => 'none',
			'35439' => '0',
			'34287' => '1',
			'36527' => '',
			'29294' => '0',
			'29422' => '1',
			'29358' => '0',
			'39535' => '1',
			'39855' => '0',
			'39471' => '3600',
			'39023' => '0',
			'39663' => '',
			'39599' => '',
			'P39791' => '',
			'39727' => '0',
			'39919' => '',
			'40623' => 'G711u',
			'40751' => '0',
			'33583' => 'Unspecified',
			'33775' => 'Unspecified',
			'40943' => '0',
			'40815' => 'Auto',
			'30190' => '1',
			'21038' => '0',
			'26158' => '0',
			'32494' => '0',
			'30126' => '5064',
			'32366' => 'none',
			'27310' => '0',
			'27182' => '1',
			'28654' => '',
			'21166' => '0',
			'21294' => '1',
			'21486' => '0',
			'31406' => '1',
			'30958' => '0',
			'31598' => '3600',
			'30894' => '0',
			'31534' => '',
			'31726' => '',
			'P31662' => '',
			'30830' => '0',
			'30766' => '',
			'32750' => 'G711u',
			'31854' => '0',
			'24686' => 'Unspecified',
			'24622' => 'Unspecified',
			'31790' => '0',
			'32686' => 'Auto',
			'23086' => '1',
			'13166' => '0',
			'18286' => '0',
			'24366' => '0',
			'23278' => '5065',
			'24238' => 'none',
			'19438' => '0',
			'19310' => '1',
			'19502' => '',
			'13294' => '0',
			'12398' => '1',
			'12334' => '0',
			'23534' => '1',
			'22830' => '0',
			'23470' => '3600',
			'23022' => '0',
			'22638' => '',
			'22574' => '',
			'P22766' => '',
			'22702' => '0',
			'22894' => '',
			'23598' => 'G711u',
			'23726' => '0',
			'16558' => 'Unspecified',
			'16750' => 'Unspecified',
			'23918' => '0',
			'23790' => 'Auto',
			'15214' => '1',
			'5038' => '0',
			'10158' => '0',
			'15470' => '0',
			'15150' => '5066',
			'16366' => 'none',
			'10286' => '0',
			'11182' => '1',
			'11630' => '',
			'4142' => '0',
			'4270' => '1',
			'4462' => '0',
			'14382' => '1',
			'15982' => '0',
			'14574' => '3600',
			'15918' => '0',
			'14510' => '',
			'14702' => '',
			'P14638' => '',
			'14830' => '0',
			'14766' => '',
			'15726' => 'G711u',
			'15854' => '0',
			'8686' => 'Unspecified',
			'8622' => 'Unspecified',
			'15790' => '0',
			'15662' => 'Auto',
			'7086' => '1',
			'61678' => '0',
			'1262' => '0',
			'7342' => '0',
			'6254' => '5067',
			'7214' => 'none',
			'2414' => '0',
			'2286' => '1',
			'3502' => '',
			'61806' => '0',
			'61934' => '1',
			'61870' => '0',
			'6510' => '1',
			'7854' => '0',
			'6446' => '3600',
			'8046' => '0',
			'6638' => '',
			'6574' => '',
			'P7790' => '',
			'7726' => '0',
			'7918' => '',
			'7598' => 'G711u',
			'558' => '0',
			'1582' => 'Unspecified',
			'1774' => 'Unspecified',
			'750' => '0',
			'622' => 'Auto',
			'63726' => '1',
			'53550' => '0',
			'58670' => '0',
			'65006' => '0',
			'63662' => '5068',
			'64878' => 'none',
			'59822' => '0',
			'59694' => '1',
			'53998' => '',
			'53678' => '0',
			'54830' => '1',
			'55022' => '0',
			'63918' => '1',
			'65518' => '0',
			'65134' => '3600',
			'65454' => '0',
			'65070' => '',
			'65262' => '',
			'P65198' => '',
			'65390' => '0',
			'65326' => '',
			'58094' => 'G711u',
			'58222' => '0',
			'59246' => 'Unspecified',
			'59182' => 'Unspecified',
			'58158' => '0',
			'58030' => 'Auto',
			'55598' => '1',
			'46702' => '0',
			'51822' => '0',
			'49710' => '0',
			'55790' => '5069',
			'56750' => 'none',
			'52974' => '0',
			'52846' => '1',
			'45870' => '',
			'46830' => '0',
			'46958' => '1',
			'46894' => '0',
			'57070' => '1',
			'56366' => '0',
			'57006' => '3600',
			'56558' => '0',
			'57198' => '',
			'57134' => '',
			'P57326' => '',
			'57262' => '0',
			'56430' => '',
			'49966' => 'G711u',
			'50094' => '0',
			'51118' => 'Unspecified',
			'50286' => 'Unspecified',
			'50158' => 'Auto',
			'48750' => '1',
			'38574' => '0',
			'43694' => '0',
			'41838' => '0',
			'48686' => '5070',
			'41710' => 'none',
			'44846' => '0',
			'44718' => '1',
			'36974' => '',
			'38702' => '0',
			'38830' => '1',
			'37998' => '0',
			'48942' => '1',
			'48494' => '0',
			'49134' => '3600',
			'48430' => '0',
			'49070' => '',
			'48238' => '',
			'P48174' => '',
			'48366' => '0',
			'48302' => '',
			'41070' => 'G711u',
			'41198' => '0',
			'42222' => 'Unspecified',
			'42158' => 'Unspecified',
			'41134' => '0',
			'41006' => 'Auto',
			'40622' => '1',
			'30701' => '0',
			'35822' => '0',
			'33710' => '0',
			'40814' => '5071',
			'33582' => 'none',
			'35950' => '0',
			'36846' => '1',
			'28845' => '',
			'29805' => '0',
			'29933' => '1',
			'29869' => '0',
			'40046' => '1',
			'40366' => '0',
			'39982' => '3600',
			'33390' => '0',
			'40174' => '',
			'40110' => '',
			'P40302' => '',
			'40238' => '0',
			'40430' => '',
			'32942' => 'G711u',
			'33070' => '0',
			'34094' => 'Unspecified',
			'34286' => 'Unspecified',
			'33262' => '0',
			'33134' => 'Auto',
			'54509' => '1',
			'55853' => '',
			'56045' => '',
			'55981' => '',
			'56173' => '20',
			'59629' => '',
			'59565' => '',
			'59757' => '',
			'59693' => '',
			'59885' => '',
			'59821' => '',
			'61037' => '',
			'60973' => '',
			'53805' => '1',
			'53997' => '0',
			'53933' => '0',
			'54125' => '0',
			'53741' => '0',
			'53677' => '0',
			'54893' => '1',
			'1389' => 'Speaker',
			'32813' => '12hr',
			'33005' => 'month/day',
			'54957' => 'automatic',
			'55149' => 'source',
			'55085' => 'media',
			'55277' => '1',
			'55213' => '0',
			'54381' => '0',
			'61103' => '1',
			'53231' => '1',
			'44079' => '1',
			'54445' => '1',
			'55405' => '8',
			'55341' => '8',
			'55533' => '10',
			'55469' => '10',
			'35565' => '1',
			'32941' => '8'
		);
		
		return array(
			'conf' => $ciscoConf,
			'url' => $url
		);
    }
	
    /**
     * @Route("/{hash}/configure", name="ui_phone_configure")
     * @Template()
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function configureAction($hash)
    {
		$request = $this->getRequest();
		$ip = $request->get('ip');
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		return array(
			'phone' => $phone,
			'company' => $company
		);
    }
    /**
     * @Route("/{hash}/configure.js", name="ui_phone_configure_js")
	 * @Method("GET")
	 * @Security("has_role('ROLE_USER')")
     */
    public function configureJSAction($hash)
    {
		$request = $this->getRequest();
		$ip = $request->get('ip');
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();
		$phone = $em->getRepository('VoIPCompanyStructureBundle:Phone')->findOneBy(array(
			'hash' => $hash
		));
        if (!$phone) throw $this->createNotFoundException('Unable to find Phone entity.');
		$company = $phone->getCompany();
		if ($user->getCompany()->getId() != $company->getId()) throw $this->createNotFoundException('No authorization.');
		
		$response = new Response($this->renderView(
		    'VoIPUIBasicBundle:Phone:configure.js.twig',
		    array(
				'phone' => $phone,
				'company' => $company
			)
		));
		$response->headers->set('Content-Type', 'text/javascript');
		return $response;
    }
}
