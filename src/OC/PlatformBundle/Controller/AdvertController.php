<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller
{
    public function indexAction()
    {
        $url = $this->generateUrl(
                'oc_platform_view', // 1 Nom de la route
                array('id' => 5) // 2 tableau avec les parametres
                );
        return new Response('<br>Et l\'url est :'.$url);
    }
    public function viewAction($id){
        
        return new Response('truc avec l\'id'.$id);
    }
    public function viewSlugAction($slug, $year, $_format){
        return new Response(
            "On pourrait afficher l'annonce correspondant au slug '".$slug."', créée en ".$year." et au format ".$_format.".");
    }
}