<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;

use App\Manager\TaskManager;
use App\Security\TaskVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    private $manager;

    const REDIRECT_ROUTE = 'task_list';

    public function __construct(TaskManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('App:Task')->findAll()]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request)
    {
        $task = new Task();
        $this->denyAccessUnlessGranted(TaskVoter::CREATE, $task);
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setCreatedBy($this->getUser());
            $this->manager->saveAndFlash($task, 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute(self::REDIRECT_ROUTE);
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request)
    {
        $this->denyAccessUnlessGranted(TaskVoter::UPDATE, $task);
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->saveAndFlash($task, 'La tâche a bien été modifiée.');

            return $this->redirectToRoute(self::REDIRECT_ROUTE);
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->manager->saveAndFlash($task, sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute(self::REDIRECT_ROUTE);
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task)
    {
        $this->denyAccessUnlessGranted(TaskVoter::DELETE, $task);
        $this->manager->remove($task);

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute(self::REDIRECT_ROUTE);
    }
}
