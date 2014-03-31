<?php

namespace VoIP\PBX\RealTimeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use VoIP\PBX\RealTimeBundle\Entity\VoiceMessage;
use VoIP\PBX\RealTimeBundle\Form\VoiceMessageType;

/**
 * VoiceMessage controller.
 *
 * @Route("/admin/voicemessage")
 */
class VoiceMessageController extends Controller
{

    /**
     * Lists all VoiceMessage entities.
     *
     * @Route("/", name="voicemessage")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VoIPPBXRealTimeBundle:VoiceMessage')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new VoiceMessage entity.
     *
     * @Route("/", name="voicemessage_create")
     * @Method("POST")
     * @Template("VoIPPBXRealTimeBundle:VoiceMessage:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new VoiceMessage();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('voicemessage_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a VoiceMessage entity.
    *
    * @param VoiceMessage $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(VoiceMessage $entity)
    {
        $form = $this->createForm(new VoiceMessageType(), $entity, array(
            'action' => $this->generateUrl('voicemessage_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new VoiceMessage entity.
     *
     * @Route("/new", name="voicemessage_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new VoiceMessage();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a VoiceMessage entity.
     *
     * @Route("/{id}", name="voicemessage_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:VoiceMessage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VoiceMessage entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing VoiceMessage entity.
     *
     * @Route("/{id}/edit", name="voicemessage_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:VoiceMessage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VoiceMessage entity.');
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
    * Creates a form to edit a VoiceMessage entity.
    *
    * @param VoiceMessage $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(VoiceMessage $entity)
    {
        $form = $this->createForm(new VoiceMessageType(), $entity, array(
            'action' => $this->generateUrl('voicemessage_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing VoiceMessage entity.
     *
     * @Route("/{id}", name="voicemessage_update")
     * @Method("PUT")
     * @Template("VoIPPBXRealTimeBundle:VoiceMessage:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:VoiceMessage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VoiceMessage entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('voicemessage_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a VoiceMessage entity.
     *
     * @Route("/{id}", name="voicemessage_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VoIPPBXRealTimeBundle:VoiceMessage')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find VoiceMessage entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('voicemessage'));
    }

    /**
     * Creates a form to delete a VoiceMessage entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('voicemessage_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
