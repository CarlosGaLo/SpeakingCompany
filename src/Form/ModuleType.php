<?php
namespace App\Form;

use App\Entity\Module;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Validator\Constraints\File;

class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => "Título"))
            ->add('description', null, array('label' => "Descripción breve"))                                    
             
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
                
            ->add('moduleVideos', CollectionType::class, array(
                    'entry_type' => ModuleVideoType::class,
                    'allow_add'	=> true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'label'=>'Vídeos'
            ))
            
            ->add('moduleTexts', CollectionType::class, array(
                    'entry_type' => ModuleTextType::class,
                    'allow_add'	=> true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'label'=>'Textos'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Module::class,
        ]);
    }
}
