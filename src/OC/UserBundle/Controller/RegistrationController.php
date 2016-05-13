<?php

namespace OC\UserBundle\Controller;

use OC\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use OC\UserBundle\Entity\User;

class RegistrationController extends BaseController
{
    public function registerAction(request $request){
        parent::registerAction($request);
        if($form->handleRequest($request)->isValid()){
            $role = $form->get('role')->getData();
            $user->setRoles($role);
        }
    }

    public function showAction()
    {
        return $this->render('OCUserBundle:User:show.html.twig', array(
            // ...
        ));
    }

    public function listAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $utilisateurs = $userManager->findUsers();
        return $this->render('OCUserBundle:User:list.html.twig', array(
            'utilisateurs' => $utilisateurs
        ));
    }

    public function updateAction()
    {
        return $this->render('OCUserBundle:User:update.html.twig', array(
            // ...
        ));
    }

    public function deleteAction()
    {
        return $this->render('OCUserBundle:User:delete.html.twig', array(
            // ...
        ));
    }

}
