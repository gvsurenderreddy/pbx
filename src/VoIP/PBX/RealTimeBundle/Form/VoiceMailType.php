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
            ->add('customerId')
            ->add('context')
            ->add('mailbox')
            ->add('password')
            ->add('fullname')
            ->add('email')
            ->add('pager')
            ->add('tz')
            ->add('attach')
            ->add('saycid')
            ->add('dialout')
            ->add('callback')
            ->add('review')
            ->add('operator')
            ->add('envelope')
            ->add('sayduration')
            ->add('saydurationm')
            ->add('sendvoicemail')
            ->add('deleteMessage')
            ->add('nextaftercmd')
            ->add('forcename')
            ->add('forcegreetings')
            ->add('hidefromdir')
            ->add('stamp')
            ->add('attachfmt')
            ->add('searchcontexts')
            ->add('cidinternalcontexts')
            ->add('exitcontext')
            ->add('volgain')
            ->add('tempgreetwarn')
            ->add('messagewrap')
            ->add('minpassword')
            ->add('vmPassword')
            ->add('vmNewpassword')
            ->add('vmPasschanged')
            ->add('vmReenterpassword')
            ->add('vmMismatch')
            ->add('vmInvalidPassword')
            ->add('vmPlsTryAgain')
            ->add('listenControlForwardKey')
            ->add('listenControlReverseKey')
            ->add('listenControlPauseKey')
            ->add('listenControlRestartKey')
            ->add('listenControlStopKey')
            ->add('backupdeleted')
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
