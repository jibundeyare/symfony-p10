<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\SchoolYear;
use App\Entity\Student;
use App\Entity\Tag;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('schoolYear', EntityType::class, [
                'class' => SchoolYear::class,

                'choice_label' => function(SchoolYear $element) {
                    return "{$element->getName()} (id {$element->getId()})";
                },

                'multiple' => false,
                'expanded' => false,
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,

                'choice_label' => function(Tag $element) {
                    return "{$element->getName()} (id {$element->getId()})";
                },

                'multiple' => true,
                'expanded' => true,

                'by_reference' => false,
            ])
            ->add('project', EntityType::class, [
                'class' => Project::class,

                'choice_label' => function(Project $element) {
                    return "{$element->getName()} (id {$element->getId()})";
                },

                'multiple' => false,
                'expanded' => false,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,

                'choice_label' => function(User $element) {
                    return "{$element->getEmail()} (id {$element->getId()})";
                },

                'multiple' => false,
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
