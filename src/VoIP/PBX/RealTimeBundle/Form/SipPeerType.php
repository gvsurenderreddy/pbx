<?php

namespace VoIP\PBX\RealTimeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SipPeerType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('host')
            ->add('type')
            ->add('context')
            ->add('secret')
            ->add('nat')
            ->add('allow')
            ->add('disallow')
            ->add('dynamic')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VoIP\PBX\RealTimeBundle\Entity\SipPeer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'voip_pbx_realtimebundle_sippeer';
    }
}
