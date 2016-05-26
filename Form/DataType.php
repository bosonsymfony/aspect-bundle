<?php

namespace UCI\Boson\AspectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DataType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bundle')
            ->add('nombreAspecto')
            ->add('nombreAspectoAnterior')
            ->add('controllerAction')
            ->add('type')
            ->add('serviceName')
            ->add('method')
            ->add('order')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UCI\Boson\AspectBundle\Entity\Data'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'uci_boson_aspectbundle_data';
    }
}
