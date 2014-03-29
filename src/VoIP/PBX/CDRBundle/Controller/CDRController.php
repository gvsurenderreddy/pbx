<?php

namespace VoIP\PBX\CDRBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use VoIP\PBX\CDRBundle\Entity\CDR;

/**
 * CDR controller.
 *
 * @Route("/admin/cdr")
 */
class CDRController extends Controller
{

    /**
     * Lists all CDR entities.
     *
     * @Route("/", name="cdr")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VoIPPBXCDRBundle:CDR')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a CDR entity.
     *
     * @Route("/{id}", name="cdr_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXCDRBundle:CDR')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CDR entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
}
