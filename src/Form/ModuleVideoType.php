<?php
namespace App\Form;

use App\Entity\ModuleVideo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('videoKey', null, array('label' => "Video Key"))
            ->add('videoUrl', null, array('label' => "Url vídeo"))                                    
            ->add('position', null, array('label' => "Posición"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ModuleVideo::class,
        ]);
    }
}
