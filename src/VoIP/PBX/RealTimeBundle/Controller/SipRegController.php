<?php

namespace VoIP\PBX\RealTimeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use VoIP\PBX\RealTimeBundle\Entity\SipReg;
use VoIP\PBX\RealTimeBundle\Form\SipRegType;

/**
 * SipReg controller.
 *
 * @Route("/admin/sipreg")
 */
class SipRegController extends Controller
{

    /**
     * Lists all SipReg entities.
     *
     * @Route("/", name="sipreg")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VoIPPBXRealTimeBundle:SipReg')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new SipReg entity.
     *
     * @Route("/", name="sipreg_create")
     * @Method("POST")
     * @Template("VoIPPBXRealTimeBundle:SipReg:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new SipReg();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sipreg_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a SipReg entity.
    *
    * @param SipReg $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(SipReg $entity)
    {
        $form = $this->createForm(new SipRegType(), $entity, array(
            'action' => $this->generateUrl('sipreg_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new SipReg entity.
     *
     * @Route("/new", name="sipreg_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new SipReg();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a SipReg entity.
     *
     * @Route("/{id}", name="sipreg_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:SipReg')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SipReg entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SipReg entity.
     *
     * @Route("/{id}/edit", name="sipreg_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:SipReg')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SipReg entity.');
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
    * Creates a form to edit a SipReg entity.
    *
    * @param SipReg $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SipReg $entity)
    {
        $form = $this->createForm(new SipRegType(), $entity, array(
            'action' => $this->generateUrl('sipreg_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing SipReg entity.
     *
     * @Route("/{id}", name="sipreg_update")
     * @Method("PUT")
     * @Template("VoIPPBXRealTimeBundle:SipReg:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:SipReg')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SipReg entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sipreg_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SipReg entity.
     *
     * @Route("/{id}", name="sipreg_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VoIPPBXRealTimeBundle:SipReg')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SipReg entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sipreg'));
    }

    /**
     * Creates a form to delete a SipReg entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sipreg_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
