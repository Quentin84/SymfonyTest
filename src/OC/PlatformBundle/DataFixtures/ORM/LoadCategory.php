<?php
/**
 * Created by PhpStorm.
 * User: Quentin
 * Date: 10/04/2016
 * Time: 17:03
 */

namespace OC\PlatformBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Category;

class LoadCategory implements FixtureInterface
{
    public function load(ObjectManager $manager){
        $noms = [
            'Developpement web',
            'Developpement mobile',
            'Developpement pas mobile',
            'intégration',
            'Cuisine de classes'
        ];
        foreach ($noms as $nom) {
            $category = new Category();
            $category->setName($nom);

            $manager->persist($category);

        }
        $manager->flush();
    }
}