<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AdvertController extends Controller
{
    public function indexAction()
    {
        $url = $this->generateUrl(
                'oc_platform_view', // 1 Nom de la route
                array('id' => 5) // 2 tableau avec les parametres
                );
        return new RedirectResponse($url);
    }
    public function viewAction($id, request $request){
        $tag= $request->query->get('tag');
        return $this->render(
                'OCPlatformBundle:Advert:view.html.twig',
                array('id'  => $id)
                );
    }
    public function viewSlugAction($slug, $year, $_format){
        return new Response(
            "On pourrait afficher l'annonce correspondant au slug '".$slug."', créée en ".$year." et au format ".$_format.".");
    }
    public function redirectAction(){
        return $this->redirectToRoute('oc_platform_home');
    }
}