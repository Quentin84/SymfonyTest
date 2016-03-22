<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
//Json
use Symfony\Component\HttpFoundation\JsonResponse;

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
        $session = $request->getSession();
        $userID = $session->get('user_id');
        $session->set('user_id', 91);
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
    public function getJsonAction(){
        return new JsonResponse(array('banane', 'framboise', 'chaussette'));
    }
    public function addAction(Request $request){
        $session = $request->getSession();
    
        // Bien sûr, cette méthode devra réellement ajouter l'annonce
    
        // Mais faisons comme si c'était le cas
        $session->getFlashBag()->add('info', 'Annonce bien enregistrée');

        // Le « flashBag » est ce qui contient les messages flash dans la session
        // Il peut bien sûr contenir plusieurs messages :
        $session->getFlashBag()->add('info', 'Oui oui, elle est bien enregistrée !');

        // Puis on redirige vers la page de visualisation de cette annonce
        return $this->redirectToRoute('oc_platform_view', array('id' => 5));
    }

}