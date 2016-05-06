<?php
// src/OC/PlatformBundle/Form/AdvertEditType.php

namespace OC\PlatformBundle\Form;

use OC\PlatformBundle\Form\AdvertType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdvertEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('date');
    }

    public function getName()
    {
        return 'oc_platformbundle_advert_edit';
    }

    public function getParent()
    {
        return AdvertType::class;
    }
}