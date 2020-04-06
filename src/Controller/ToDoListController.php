<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name="to_do_list")
     */
    public function index()
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findBy([], ['id' => 'DESC']);

        return $this->render('index.html.twig', ['tasks' => $tasks]);
    }

    /**
     * @Route("/create", name="create_task", methods={"POST"})
     */
    public function create(Request $request)
    {
        if (empty($title = trim($request->request->get('title')))) {
            return $this->redirectToRoute('to_do_list');
        }

        $request->request->get('title');
        $entityManager = $this->getDoctrine()->getManager();

        $task = new Task();
        $task->setTitle($title);

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('to_do_list');
    }

    /**
     * @Route("/switch-status/{id}", name="switch_status")
     *
     * @param mixed $id
     */
    public function switchStatus($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);
        $task->setStatus(!$task->getStatus());
        $entityManager->flush();

        return $this->redirectToRoute('to_do_list');
    }

    /**
     * @Route("/delete/{id}", name="task_delete")
     *
     * @param mixed $id
     */
    public function delete(Task $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        //$entityManager->getRepository(Task::class)->find($id)->remove($id);
        $entityManager->remove($id);
        $entityManager->flush();

        return $this->redirectToRoute('to_do_list');
    }
}