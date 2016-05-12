<?php
/**
 * Created by PhpStorm.
 * User: Quentin
 * Date: 12/05/2016
 * Time: 15:18
 */

namespace OC\PlatformBundle\Twig;

use OC\PlatformBundle\Antispam\OCAntispam;


class AntispamExtension extends \Twig_Extension
{
    private $ocAntispam;
    public function __construct(OCAntispam $ocAntispam)
    {
        $this->ocAntispam = $ocAntispam;
    }

    public function checkIfArgumentIsSpam($text){
        return $this->ocAntispam->isSpam($text);
    }
    public function getFunctions(){
        return array(
            new \Twig_SimpleFunction('checkIfSpam', array($this, 'checkIfArgumentIsSpam')
        ));
    }

    public function getName()
    {
        return 'OCAntispam';
    }
}