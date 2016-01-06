<?php
// src/Acme/TaskBundle/Controller/UserController.php
namespace Acme\TaskBundle\Controller;

use Acme\TaskBundle\Entity\User;
use Acme\TaskBundle\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
	public function registerAction(Request $request)
	{
		// Construir el formulario
		$user = new User();
		$form = $this->createForm(UserType::class, $user);
		// Manejar la petición de envío de formulario
		$form->handleRequest($request);
		// Comprobar si se ha hecho un submit correcto del formulario
		if ($form->isSubmitted() && $form->isValid())
		{
			// Crear nuevo usuario
			$password = $this->get('security.password_encoder')
				->encodePassword($user, $user->getPlainPassword());
			$user->setPassword($password);

			// Guardar usuario
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();

			return $this->redirectToRoute('acme_task_index');
		}

		// Mostrar la plantilla de formulario de registro
		return $this->render(
			'AcmeTaskBundle:User:register.html.twig',
			array('form' => $form->createView())
		);
	}
}