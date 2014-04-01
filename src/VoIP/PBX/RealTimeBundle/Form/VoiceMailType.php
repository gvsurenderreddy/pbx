<?php

namespace VoIP\PBX\RealTimeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VoiceMailType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('context')
            ->add('mailbox')
            ->add('password')
            ->add('fullname')
            ->add('email')
            ->add('pager')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VoIP\PBX\RealTimeBundle\Entity\VoiceMail'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'voip_pbx_realtimebundle_voicemail';
    }
}
