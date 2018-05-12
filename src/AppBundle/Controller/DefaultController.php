<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todoist;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Todoist::class);
        $repository = $repository->findAll();
        $categorie  = [];
            foreach ($repository as $e) $categorie[] = $e->getCategorie();

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'data'=>$repository,
            'categorie'=> array_unique($categorie)
        ]);
    }

    /**
     * @Route("/remove", name="remove")
     */
    public function remove(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $todoist = $entityManager->getRepository(Todoist::class)->find($request->query->get('id'));
        $entityManager->remove($todoist);
        $entityManager->flush();

        return new Response();
    }

    /**
     * @Route("/update", name="update")
     */
    public function update(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $todoist = $entityManager->getRepository(Todoist::class)->find($request->query->get('id'));
        $todoist->setvalue($request->query->get('value'));
        $entityManager->flush();

        return new Response();
    }

    /**
     * @Route("/complete", name="complete")
     */
    public function complete(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $todoist = $entityManager->getRepository(Todoist::class)->find($request->query->get('id'));
        $todoist->setIsCompleted('1');
        $entityManager->flush();
        return new Response();
    }

        /**
         * @Route("/addTodo", name="addTodo")
         */
    public function ajaxAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $todoist = new Todoist();
        $todoist->setCategorie($request->query->get('categorie'));
        $todoist->setValue($request->query->get('value'));
        $todoist->setIsCompleted(0);

        $entityManager->persist($todoist);
        $entityManager->flush();

        echo json_encode($todoist->getTodoist());
        return new Response();
    }
}
