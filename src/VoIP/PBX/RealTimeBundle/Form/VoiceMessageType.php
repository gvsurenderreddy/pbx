<?php

namespace VoIP\PBX\RealTimeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VoiceMessageType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dir')
            ->add('msgnum')
            ->add('recording')
            ->add('context')
            ->add('macrocontext')
            ->add('callerid')
            ->add('origtime')
            ->add('duration')
            ->add('mailboxuser')
            ->add('mailboxcontext')
            ->add('messageLabel')
            ->add('isRead')
            ->add('flag')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VoIP\PBX\RealTimeBundle\Entity\VoiceMessage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'voip_pbx_realtimebundle_voicemessage';
    }
}
