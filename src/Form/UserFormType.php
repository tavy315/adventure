<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['attr' => ['maxlength' => 150]])
            ->add('kid', null, ['required' => false])
            ->add('name', null, ['attr' => ['maxlength' => 150]])
            ->add('password', null, ['attr' => ['minlength' => 5]])
            ->add('submit', SubmitType::class);
    }
}
