<?php

namespace DGAbgSistemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdmImagenPromocionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('imagen')
            //->add('promocion')
            ->add('file',null, array('label'=>'Imagen de publicidad','required'=>false,
                    'attr'=>array('class'=>'imagen'
                        
                    )))     
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGAbgSistemaBundle\Entity\AdmImagenPromocion'
        ));
    }
}
