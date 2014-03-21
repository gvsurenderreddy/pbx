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
            ->add('secret')
            ->add('context')
            ->add('host')
            ->add('nat')
            ->add('type')
            ->add('canreinvite')
            ->add('directrtpsetup')
            ->add('disallow')
            ->add('allow')
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
