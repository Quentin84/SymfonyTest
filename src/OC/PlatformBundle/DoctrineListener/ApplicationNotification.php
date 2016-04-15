<?php
/**
 * Created by PhpStorm.
 * User: Quentin
 * Date: 15/04/2016
 * Time: 17:46
 */


namespace OC\PlatformBundle\DoctrineListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use OC\PlatformBundle\Entity\Application;

class ApplicationNotification
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function postPersist(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        if( !$entity instanceof Application){
            return null;
        }

        $message = new \Swift_Message('Nouvelle candidature', 'Vous avez reçu une nouvelle candidature');
        $message->addTo($entity->getAdvert()->getAuthor())
            ->addFrom('admin@devjobs.com');

        $this->mailer->send($message);
    }

}