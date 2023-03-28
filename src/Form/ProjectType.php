<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('clientName')
            ->add('startDate')
            ->add('checkPointDate')
            ->add('deliveryDate')
            ->add('tags', EntityType::class, [
                'class' => Tag::class,

                'choice_label' => function(Tag $element) {
                    return "{$element->getName()} (id {$element->getId()})";
                },

                'multiple' => true,
                'expanded' => true,

                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
