<?php

namespace DGAbgSistemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbgFacturacionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaPago', 'date')
            ->add('monto')
            ->add('servicio')
            ->add('abgPersona')
            ->add('abgTipoPago')
            ->add('ctlEmpresa')
            ->add('ctlPromociones')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGAbgSistemaBundle\Entity\AbgFacturacion'
        ));
    }
}
