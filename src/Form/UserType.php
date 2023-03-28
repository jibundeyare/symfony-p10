<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [];

        foreach (User::ROLES as $role) {
            $key = $role;
            $key = str_replace('ROLE_', '', $key);
            $key = strtolower($key);
            $key = ucfirst($key);
            $choices[$key] = $role;
        }

        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices'  => $choices,
                'multiple' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmez votre mot de passe'],
            ])
            ->add('student', EntityType::class, [
                'class' => Student::class,

                'choice_label' => function(Student $element) {
                    return "{$element->getFirstname()} {$element->getLastname()} ({$element->getSchoolYear()?->getName()}, id {$element->getId()})";
                },

                'multiple' => false,
                'expanded' => false,
                // autorise à ne renseigner aucun student
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
