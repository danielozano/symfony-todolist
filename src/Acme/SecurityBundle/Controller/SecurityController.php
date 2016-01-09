<?php
// src/Acme/SecurityBundle/Controller/SecurityController;
namespace Acme\SecurityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
	public function loginAction(Request $request)
	{
		$authenticationUtils = $this->get('security.authentication_utils');
		$error = $authenticationUtils->getLastAuthenticationError();

		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render(
	       'AcmeTaskBundle:User:login.form.html.twig',
	        array(
	            // last username entered by the user
	            'lastUsername' => $lastUsername,
	            'error'         => $error,
	        )			
		);
	}

	public function loginCheckAction()
	{

	}
}