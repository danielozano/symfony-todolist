<?php
/**
 * TODO: permitir editar la tarea
 * TODO: permitir completar la tarea
 */
namespace Acme\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Acme\TaskBundle\Entity\Task;
use Acme\TaskBundle\Form\Type\TaskType;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {

    	$taskRepository = $this->getDoctrine()->getRepository('AcmeTaskBundle:Task');
    	$tasks = $taskRepository->findAll();
    	$task = new Task();

    	$formOptions = array('action' => $this->generateUrl('acme_task_create'));
   		$form = $this->createForm(TaskType::class, $task, $formOptions);

        return $this->render('AcmeTaskBundle:Default:index.html.twig', array(
        	'tasks' => $tasks,
        	'form' 	=> $form->createView()
        ));
    }

    public function viewAction($id)
    {
    	$taskRepo = $this->getDoctrine()->getRepository('AcmeTaskBundle:Task');
    	$task = $taskRepo->find($id);
    	
    	return $this->render('AcmeTaskBundle:Default:view.html.twig', array(
    		'task' => $task
    	));
    }
    /**
     * TODO: Move the datetime creation to doctrine persist routine
     * Task API create task object and persist on Database
     * @param  Request $request
     * @return
     */
    public function createAction(Request $request)
    {
    	$task = new Task();
    	$form = $this->createForm(TaskType::class, $task);

    	$form->handleRequest($request);

    	if ($form->isValid())
    	{
    		$now = new \DateTime();
    		$task = $form->getData();
    		$task->setCreatedAt($now);
    		$task->setUpdatedAt($now);
    		$task->setCompleted(false);

    		$em = $this->getDoctrine()->getManager();
    		$em->persist($task);
    		$em->flush();
    		return $this->redirectToRoute('acme_task_index');
    	}
    	else
    	{
    		return new Response('Bad submit');
    	}
    }

    public function updateAction($id)
    {
    	echo "update $id";
    	die();
    }

    public function deleteAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$taskRepo = $this->getDoctrine()->getRepository('AcmeTaskBundle:Task');
    	$task = $taskRepo->find($id);

    	if ($task)
    	{
    		$em->remove($task);
    		$em->flush();
    		// TODO: Redirect with Ok Message
    		return $this->redirectToRoute('acme_task_index');
    	}
    	// TODO: Redirect with Errors
    	return $this->redirectToRoute('acme_task_index');
    }
}
