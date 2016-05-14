<?php

namespace DGAbgSistemaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtlEmpresaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreEmpresa')
            ->add('nit')
            ->add('servicios')
            ->add('fechaFundacion', 'date')
            ->add('fotoPerfil')
            ->add('descripcion')
            ->add('direccion')
            ->add('sitioWeb')
            ->add('correoelectronico')
            ->add('telefono')
            ->add('movil')
            ->add('fax')
            ->add('ctlCiudad')
            ->add('ctlTipoEmpresa')
            ->add('abgPersona')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DGAbgSistemaBundle\Entity\CtlEmpresa'
        ));
    }
}
