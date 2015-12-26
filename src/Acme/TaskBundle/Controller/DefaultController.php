<?php
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

    	$incompleteTasks = $taskRepository->findBy(array('completed' => false));
    	$completedTasks = $taskRepository->findBy(array('completed' => true));
    	$task = new Task();

    	$formOptions = array('action' => $this->generateUrl('acme_task_create'));
   		$form = $this->createForm(TaskType::class, $task, $formOptions);

        return $this->render('AcmeTaskBundle:Default:index.html.twig', array(
        	'completedTasks' => $completedTasks,
        	'incompleteTasks' => $incompleteTasks,
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
            $this->_flashMessage('taskAction', array(
                    'class'     => 'alert alert-success',
                    'message'   => 'Task created correctly.'
            ));
    		return $this->redirectToRoute('acme_task_index');
    	}
    	else
    	{
    		return new Response('Bad submit');
    	}
    }

    public function updateAction(Request $request, $id)
    {
    	$taskRepo = $this->getDoctrine()->getRepository('AcmeTaskBundle:Task');
    	$task = $taskRepo->find($id);
    	if ($task)
    	{
    		$formOptions = array('action' => $this->generateUrl('acme_task_update', array('id' => $id)));
    		$form = $this->createForm(TaskType::class, $task,$formOptions);
    		$form->handleRequest($request);
    		if ($form->isValid())
    		{
    			$now = new \DateTime();
    			$task = $form->getData();
    			$task->setUpdatedAt($now);
    			$task->setCompleted(false);
    			$task->setTitle($task->getTitle());
    			$task->setDescription($task->getDescription());
    			$em = $this->getDoctrine()->getManager();
    			$em->flush();
    			// TODO: Redirect with message
                $this->_flashMessage(
                    'taskAction',
                    array(
                        'class'     => 'alert alert-success',
                        'message'   => "Task with id: " . $task->getId() . " updated correctly"
                ));
    			return $this->redirectToRoute('acme_task_index');
    		}
    		return $this->render('AcmeTaskBundle:Default:update.html.twig', array(
    			'task' => $task,
    			'form' => $form->createView()
    		));
    	}
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
    		$this->_flashMessage(
                'taskAction',
                array(
                    'class'     => 'alert alert-danger',
                    'message'   => 'Task with id: ' . $id . ' has been deleted' 
            ));
    		return $this->redirectToRoute('acme_task_index');
    	}
    	// TODO: Redirect with Errors
    	return $this->redirectToRoute('acme_task_index');
    }

    public function statusAction(Request $request, $status, $id)
    {
    	$doctrine = $this->getDoctrine();
    	$em = $doctrine->getManager();
    	$taskRepo = $doctrine->getRepository('AcmeTaskBundle:Task');
    	$task = $taskRepo->find($id);

    	if ($task)
    	{
    		switch ($status) 
    		{
    			case 'complete':
    				$task->setCompleted(true);
                    $this->_flashMessage(
                        'taskAction',
                        array(
                            'class'     => 'alert alert-success',
                            'message'   =>'Task with id: ' . $id . ' completed'
                    ));
    				break;
    			case 'incomplete':
    				$task->setCompleted(false);
                    $this->_flashMessage(
                        'taskAction',
                        array(
                            'class'     => 'alert alert-warning',
                            'message'   =>'Task with d: ' . $id . ' set as incomplete'
                    ));                    
    				break;
    			default:
    				break;
    		}
    		$em->persist($task);
    		$em->flush();
    	}
    	// TODO: add flash message
    	return $this->redirectToRoute('acme_task_index');
    }

    private function _flashMessage ($name, $options)
    {
        $flashBag = $this->get('session')->getFlashBag();
        $flashBag->add($name, $options);
    }
}
