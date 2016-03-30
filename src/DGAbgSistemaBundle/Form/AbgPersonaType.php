<?php

namespace DGAbgSistemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbgPersonaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombres')
            ->add('apellido')
            ->add('genero')
            ->add('fechaIngreso', 'date')
            ->add('dui')
            ->add('nit')
            ->add('correoelectronico')
            ->add('direccion')
            ->add('telefonoFijo')
            ->add('telefonoMovil')
            ->add('descripcion')
            ->add('estado')
            ->add('abgPersonacol')
            ->add('ctlCiudad')
            ->add('ctlEmpresa')
            ->add('abgioma')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGAbgSistemaBundle\Entity\AbgPersona'
        ));
    }
}
