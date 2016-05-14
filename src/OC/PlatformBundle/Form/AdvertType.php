<?php

namespace OC\PlatformBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class)
            ->add('title', TextType::class)
            //->add('author', TextType::class)
            ->add('content', CKEditorType::class)
            // mettre return true dans l'entité pour le checkbox
            ->add('published', CheckboxType::class, array('value' => 1, 'required' => false))
            ->add('image', ImageType::class)
            // nom, 'collection' liste de qqc, tableau d'options
            ->add('categories', CollectionType::class, array('entry_type' => CategoryType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true))
            ->add('save', SubmitType::class)
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            $advert = $event->getData();
            if(null == $advert){
                return;
            }
            if(!$advert->getPublished() || null === $advert->getId()){
                $event->getForm()->add('published', CheckboxType::class, array('required' => false));
            }else{
                $event->getForm()->remove('published');
            }

        });
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\PlatformBundle\Entity\Advert'
        ));
    }
}
