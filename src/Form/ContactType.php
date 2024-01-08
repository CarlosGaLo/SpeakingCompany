<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => "Nombre"))
            ->add('phone', null, array('label' => "Teléfono", 'attr' => array('placeholder' => 'Ej: 654354222')))
            ->add('email', null, array('label' => "E-mail"))                      
            ->add('remarks', null, array('label' => "Comentario"))            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
