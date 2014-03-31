<?php

namespace VoIP\PBX\RealTimeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use VoIP\PBX\RealTimeBundle\Entity\VoiceMail;
use VoIP\PBX\RealTimeBundle\Form\VoiceMailType;

/**
 * VoiceMail controller.
 *
 * @Route("/admin/voicemail")
 */
class VoiceMailController extends Controller
{

    /**
     * Lists all VoiceMail entities.
     *
     * @Route("/", name="voicemail")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VoIPPBXRealTimeBundle:VoiceMail')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new VoiceMail entity.
     *
     * @Route("/", name="voicemail_create")
     * @Method("POST")
     * @Template("VoIPPBXRealTimeBundle:VoiceMail:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new VoiceMail();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('voicemail_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a VoiceMail entity.
    *
    * @param VoiceMail $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(VoiceMail $entity)
    {
        $form = $this->createForm(new VoiceMailType(), $entity, array(
            'action' => $this->generateUrl('voicemail_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new VoiceMail entity.
     *
     * @Route("/new", name="voicemail_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new VoiceMail();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a VoiceMail entity.
     *
     * @Route("/{id}", name="voicemail_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:VoiceMail')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VoiceMail entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing VoiceMail entity.
     *
     * @Route("/{id}/edit", name="voicemail_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:VoiceMail')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VoiceMail entity.');
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
    * Creates a form to edit a VoiceMail entity.
    *
    * @param VoiceMail $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(VoiceMail $entity)
    {
        $form = $this->createForm(new VoiceMailType(), $entity, array(
            'action' => $this->generateUrl('voicemail_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing VoiceMail entity.
     *
     * @Route("/{id}", name="voicemail_update")
     * @Method("PUT")
     * @Template("VoIPPBXRealTimeBundle:VoiceMail:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:VoiceMail')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VoiceMail entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('voicemail_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a VoiceMail entity.
     *
     * @Route("/{id}", name="voicemail_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VoIPPBXRealTimeBundle:VoiceMail')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find VoiceMail entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('voicemail'));
    }

    /**
     * Creates a form to delete a VoiceMail entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('voicemail_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
