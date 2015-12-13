<?php
/**
 * TODO: crear formulario para creación de task
 * TODO: crear nueva task mediante formulario
 * TODO: listar dicha tarea en el listado
 * TODO: permitir ver los detalles de dicha tarea
 * TODO: permitir borrar la tarea
 * TODO: permitir editar la tarea
 * TODO: permitir completar la tarea
 */
namespace Acme\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Acme\TaskBundle\Entity\Task;
use Acme\TaskBundle\Form\Type\TaskType;

class DefaultController extends Controller
{
	/**
	 * TODO: listar tareas existentes si existen, y mostrar formulario para crear nuevas tareas
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
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
    	echo "view $id";
    	die();
    }

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
    	echo "delete $id";
    	die();
    }
}
