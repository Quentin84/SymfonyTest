<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OCAntispam
 *
 * @author Quentin
 */
namespace OC\PlatformBundle\Antispam;
class OCAntispam {
        private $mailer;
        private $locale;
        private $minLength;
    public function __construct(\Swift_Mailer $mailer, $minLength) {
        $this->mailer = $mailer;
        $this->minLength = (int) $minLength;
    }
    public function isSpam($text){
        return strlen($text) < $this->minLength;
    }

    public function setLocale($locale){
        $this->locale = $locale;
    }
}
