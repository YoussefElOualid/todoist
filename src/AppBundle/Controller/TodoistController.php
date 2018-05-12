<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todoist;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Todoist controller.
 *
 * @Route("todoist")
 */
class TodoistController extends Controller
{
    /**
     * Lists all todoist entities.
     *
     * @Route("/", name="todoist_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $todoists = $em->getRepository('AppBundle:Todoist')->findAll();

        return $this->render('todoist/index.html.twig', array(
            'todoists' => $todoists,
        ));
    }

    /**
     * Creates a new todoist entity.
     *
     * @Route("/new", name="todoist_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $todoist = new Todoist();
        $form = $this->createForm('AppBundle\Form\TodoistType', $todoist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($todoist);
            $em->flush();

            return $this->redirectToRoute('todoist_show', array('id' => $todoist->getId()));
        }

        return $this->render('todoist/new.html.twig', array(
            'todoist' => $todoist,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a todoist entity.
     *
     * @Route("/{id}", name="todoist_show")
     * @Method("GET")
     */
    public function showAction(Todoist $todoist)
    {
        $deleteForm = $this->createDeleteForm($todoist);

        return $this->render('todoist/show.html.twig', array(
            'todoist' => $todoist,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing todoist entity.
     *
     * @Route("/{id}/edit", name="todoist_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Todoist $todoist)
    {
        $deleteForm = $this->createDeleteForm($todoist);
        $editForm = $this->createForm('AppBundle\Form\TodoistType', $todoist);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('todoist_edit', array('id' => $todoist->getId()));
        }

        return $this->render('todoist/edit.html.twig', array(
            'todoist' => $todoist,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a todoist entity.
     *
     * @Route("/{id}", name="todoist_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Todoist $todoist)
    {
        $form = $this->createDeleteForm($todoist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($todoist);
            $em->flush();
        }

        return $this->redirectToRoute('todoist_index');
    }

    /**
     * Creates a form to delete a todoist entity.
     *
     * @param Todoist $todoist The todoist entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Todoist $todoist)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('todoist_delete', array('id' => $todoist->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
