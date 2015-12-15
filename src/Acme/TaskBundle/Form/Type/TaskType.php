<?php
// src/Acme/TaskBundle/Form/Type/TaskType
namespace Acme\TaskBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TaskType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('title', TextType::class)
			->add('description', TextareaType::class)
			->add('save', SubmitType::class, array('label' => 'Save'));
	}

	public function getName()
	{
		return 'acme_taskbundle_task';
	}
}