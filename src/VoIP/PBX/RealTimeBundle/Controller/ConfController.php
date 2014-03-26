<?php

namespace VoIP\PBX\RealTimeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use VoIP\PBX\RealTimeBundle\Entity\Conf;
use VoIP\PBX\RealTimeBundle\Form\ConfType;

/**
 * Conf controller.
 *
 * @Route("/admin/conf")
 */
class ConfController extends Controller
{

    /**
     * Lists all Conf entities.
     *
     * @Route("/", name="conf")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VoIPPBXRealTimeBundle:Conf')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Conf entity.
     *
     * @Route("/", name="conf_create")
     * @Method("POST")
     * @Template("VoIPPBXRealTimeBundle:Conf:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Conf();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('conf_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Conf entity.
    *
    * @param Conf $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Conf $entity)
    {
        $form = $this->createForm(new ConfType(), $entity, array(
            'action' => $this->generateUrl('conf_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Conf entity.
     *
     * @Route("/new", name="conf_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Conf();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Conf entity.
     *
     * @Route("/{id}", name="conf_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:Conf')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Conf entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Conf entity.
     *
     * @Route("/{id}/edit", name="conf_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:Conf')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Conf entity.');
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
    * Creates a form to edit a Conf entity.
    *
    * @param Conf $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Conf $entity)
    {
        $form = $this->createForm(new ConfType(), $entity, array(
            'action' => $this->generateUrl('conf_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Conf entity.
     *
     * @Route("/{id}", name="conf_update")
     * @Method("PUT")
     * @Template("VoIPPBXRealTimeBundle:Conf:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoIPPBXRealTimeBundle:Conf')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Conf entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('conf_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Conf entity.
     *
     * @Route("/{id}", name="conf_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VoIPPBXRealTimeBundle:Conf')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Conf entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('conf'));
    }

    /**
     * Creates a form to delete a Conf entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('conf_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
