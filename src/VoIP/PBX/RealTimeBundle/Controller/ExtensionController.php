<?php

namespace VoIP\PBX\RealTimeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use VoIP\PBX\RealTimeBundle\Entity\Extension;
use VoIP\PBX\RealTimeBundle\Form\ExtensionType;

/**
 * Extension controller.
 *
 * @Route("/extension")
 */
class ExtensionController extends Controller
{

    /**
     * Lists all Extension entities.
     *
     * @Route("/", name="extension")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VoIPPBXRealTimeBundle:Extension')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Extension entity.
     *
     * @Route("/", name="extension_create")
     * @Method("POST")
     * @Template("VoIPPBXRealTimeBundle:Extension:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Extension();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('extension_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Extension entity.
    *
    * @param Extension $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Extension $entity)
    {
        $form = $this->createForm(new ExtensionType(), $entity, array(
            'action' => $this->generateUrl('extension_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Extension entity.
     *
     * @Route("/new", name="extension_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Extension();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Extension entity.
     *
     * @Route("/{id}", name="extension_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:Extension')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Extension entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Extension entity.
     *
     * @Route("/{id}/edit", name="extension_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:Extension')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Extension entity.');
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
    * Creates a form to edit a Extension entity.
    *
    * @param Extension $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Extension $entity)
    {
        $form = $this->createForm(new ExtensionType(), $entity, array(
            'action' => $this->generateUrl('extension_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Extension entity.
     *
     * @Route("/{id}", name="extension_update")
     * @Method("PUT")
     * @Template("VoIPPBXRealTimeBundle:Extension:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:Extension')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Extension entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('extension_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Extension entity.
     *
     * @Route("/{id}", name="extension_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VoIPPBXRealTimeBundle:Extension')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Extension entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('extension'));
    }

    /**
     * Creates a form to delete a Extension entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('extension_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
