<?php
 
namespace App\Form;

use App\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Validator\Constraints\File;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => "Nombre"))            
            ->add('remark', null, array('label' => "Descripción"))                        
            ->add('activityDate', DateType::class, array('widget' => 'single_text', 'html5' => true, 'label' => 'Fecha Inicio'))
            ->add('dateText', null, array('label' => "Texto Fecha", 'attr' => array('placeholder' => 'Ejemplo: Verano ' . date('Y'))))            
            ->add('link', UrlType::class, array('label' => "Enlace"))
            ->add('image', FileType::class, [
                'label' => 'Imagen',
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
            'data_class' => Activity::class,
        ]);
    }
}
