<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => "Nombre"))
            ->add('phone', null, array('label' => "Teléfono", 'attr' => array('placeholder' => 'Ej: 654354222')))
            ->add('email', null, array('label' => "E-mail"))
            ->add('twitter', null, array('label' => "Twitter"))
            ->add('facebook', null, array('label' => "Facebook"))
            ->add('instagram', null, array('label' => "Instagram"))
            ->add('linkedIn', null, array('label' => "LinkedIn"))
            ->add('title', null, array('label' => "Cargo"))
            ->add('curriculum', null, array('label' => "Currículum"))
            ->add('roles', ChoiceType::class, array(                
                'label' => 'Permisos',
                'choices' => array(
                    'Administrador' => 'ROLE_ADMIN'
                    ),
                'multiple' => false,
                'expanded' => false,
                'required' => true
            ))
            ->add('photo', FileType::class, [
                'label' => 'Foto',
                'mapped' => false,
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',                            
                        ],
                        'mimeTypesMessage' => 'Por favor, sube una imagen válida',
                    ])
                ],
            ])
        ;
        
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                     // transform the array to a string
                     return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                     // transform the string back to an array
                     return [$rolesString];
                }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
