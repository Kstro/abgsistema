<?php

namespace DGAbgSistemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdmPromocionesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaInicio', 'date')
            ->add('fechaFin', 'date')
            ->add('monto')
            ->add('descuento')
            ->add('posicion')
            ->add('placas','collection',array(
                    'type' => new AdmImagenPromocionType(),
                    'label'=>' ',
                    'by_reference' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                ))    
//            ->add('estado')
//            ->add('ctlProdServicioAdmin')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGAbgSistemaBundle\Entity\AdmPromociones'
        ));
    }
}
