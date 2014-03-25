<?php

namespace VoIP\PBX\RealTimeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use VoIP\PBX\RealTimeBundle\Entity\SipPeer;
use VoIP\PBX\RealTimeBundle\Form\SipPeerType;

/**
 * SipPeer controller.
 *
 * @Route("/admin/sippeer")
 */
class SipPeerController extends Controller
{

    /**
     * Lists all SipPeer entities.
     *
     * @Route("/", name="sippeer")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VoIPPBXRealTimeBundle:SipPeer')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new SipPeer entity.
     *
     * @Route("/", name="sippeer_create")
     * @Method("POST")
     * @Template("VoIPPBXRealTimeBundle:SipPeer:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new SipPeer();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sippeer_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a SipPeer entity.
    *
    * @param SipPeer $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(SipPeer $entity)
    {
        $form = $this->createForm(new SipPeerType(), $entity, array(
            'action' => $this->generateUrl('sippeer_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new SipPeer entity.
     *
     * @Route("/new", name="sippeer_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new SipPeer();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a SipPeer entity.
     *
     * @Route("/{id}", name="sippeer_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:SipPeer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SipPeer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SipPeer entity.
     *
     * @Route("/{id}/edit", name="sippeer_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:SipPeer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SipPeer entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a SipPeer entity.
    *
    * @param SipPeer $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SipPeer $entity)
    {
        $form = $this->createForm(new SipPeerType(), $entity, array(
            'action' => $this->generateUrl('sippeer_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing SipPeer entity.
     *
     * @Route("/{id}", name="sippeer_update")
     * @Method("PUT")
     * @Template("VoIPPBXRealTimeBundle:SipPeer:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:SipPeer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SipPeer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sippeer_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SipPeer entity.
     *
     * @Route("/{id}", name="sippeer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VoIPPBXRealTimeBundle:SipPeer')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SipPeer entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sippeer'));
    }

    /**
     * Creates a form to delete a SipPeer entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sippeer_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
