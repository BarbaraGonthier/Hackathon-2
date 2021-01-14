<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\Filter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    const ROLES = [
        'Agriculteurs' => 'farmers',
        'Acheteurs' => 'buyers'];
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('role', ChoiceType::class,[
                'choices' => self::ROLES,
                'expanded' => true,
                'label' => false,
                'required' => false,
                'placeholder' => false,
                'error_bubbling' => true,
            ])
            ->add('department', EntityType::class,[
                'class' => Department::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Filter::class
        ]);
    }
}
