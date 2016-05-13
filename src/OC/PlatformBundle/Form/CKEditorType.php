<?php
/**
 * Created by PhpStorm.
 * User: Quentin
 * Date: 13/05/2016
 * Time: 15:44
 */

namespace OC\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;



class CKEditorType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        //ajoute par défaut la classe ck editor au champ
        $resolver->setDefaults(array(
            'attr' => array('class' => 'ckeditor')
        ));
    }
    // Parent pour l'héritage
    public function getParent()
    {
        return TextareaType::class;
    }
}