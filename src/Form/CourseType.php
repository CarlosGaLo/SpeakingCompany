<?php
namespace App\Form;

use App\Entity\Course;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Validator\Constraints\File;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => "Título"))
            ->add('description', null, array('label' => "Descripción"))
            ->add('level', ChoiceType::class, array(                 
                'label' => 'Nivel',
                'choices' => array(
                    'Básico' => '2',
                    'Avanzado' => '5',
                    'Experto' => '10',
                    ),
                'multiple' => false,
                'expanded' => false,
                'required' => true
            ))            
            ->add('price', null, array('label' => "Precio base"))            
            ->add('headerImage', FileType::class, [
                'label' => 'Imagen de cabecera',
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
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
