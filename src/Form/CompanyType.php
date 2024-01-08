<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\File;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => "Nombre"))
            ->add('phone', null, array('label' => "Teléfono", 'attr' => array('placeholder' => 'Ej: 654354222')))
            ->add('email', null, array('label' => "E-mail"))            
//            ->add('twitter', null, array('label' => "Twitter"))
//            ->add('facebook', null, array('label' => "Facebook"))
        ;                
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
