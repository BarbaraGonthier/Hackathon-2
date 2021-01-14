<?php

namespace App\Form;

use App\Entity\Filter;
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Filter::class
        ]);
    }
}
