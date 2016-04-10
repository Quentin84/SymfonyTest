<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\OCPlatformBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;

class AdvertController extends Controller
{
  public function indexAction($page)
  {
    // On ne sait pas combien de pages il y a
    // Mais on sait qu'une page doit être supérieure ou égale à 1
    if ($page < 1) {
      // On déclenche une exception NotFoundHttpException, cela va afficher
      // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
      throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
    }

    // Ici, on récupérera la liste des annonces, puis on la passera au template

    // Mais pour l'instant, on ne fait qu'appeler le template
    return $this->render('OCPlatformBundle:Advert:index.html.twig', ['listAdverts'=> '']);
  }
  public function menuAction() {
      $listAdvert =  array(
                            array('id' => 2, 'title' => 'Recherche cuiseur de bananes'),
                            array('id' => 3, 'title' => 'CDI mangeur de crevettes'),
                            array('id' => 4, 'title' => 'Freelance stagiaire')
                            );
        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdvert));
  }

    public function viewAction($id)
  {
      // On récupère le repository
      $repository = $this->getDoctrine()
          ->getManager()
          ->getRepository('OCPlatformBundle:Advert')
      ;

      // On récupère l'entité correspondante à l'id $id
      $advert = $repository->find($id);

      // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
      // ou null si l'id $id  n'existe pas, d'où ce if :
      if (null === $advert) {
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

      // Recuperation des éventuelles candidatures
      $listapplication = $this->getDoctrine()
                                ->getManager()
                                ->getRepository('OCPlatformBundle:Application')
                                ->findBy(array('advert' => $advert));

      // Le render ne change pas, on passait avant un tableau, maintenant un objet
      return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
          'advert' => $advert,
          'listapplication' => $listapplication
      ));
  }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
  {
    // La gestion d'un formulaire est particulière, mais l'idée est la suivante :

    // Si la requête est en POST, c'est que le visiteur a soumis le formulaire
      $advert = new Advert();
      $advert->setAuthor('Gronaz');
      $advert->setTitle('Recherche mangeur de mangues');
      $advert->setContent('aaaaah non mais tu le crois ça? Genre c est un métier ça ?');
      $advert->setPublished(false);

      // Création de l'entité Image
      $image = new Image();
      $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
      $image->setAlt('Job de rêve');

      // On lie l'image à l'annonce
      $advert->setImage($image);

      // Ajout de candidatures

      $application = new Application();
      $application->setAuthor('Martin');
      $application->setContent('Besoin de thunes');
      $application->setAdvert($advert);

      $application1 = new Application();
      $application1->setAuthor('Jostophe');
      $application1->setContent('C\'est le plus fort de l\'univers');
      $application1->setAdvert($advert);

      $em = $this->getDoctrine()->getManager();
      $em->persist($advert);

      $em->persist($application);
      $em->persist($application1);

      // Flush des données
      $em->flush();

    if ($request->isMethod('POST')) {
      // Ici, on s'occupera de la création et de la gestion du formulaire

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      // Puis on redirige vers la page de visualisation de cettte annonce
      return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $advert->getId())));
    }

    // Si on n'est pas en POST, alors on affiche le formulaire
    return $this->render('OCPlatformBundle:Advert:add.html.twig');
  }

  public function editAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();
      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);
      if (null === $advert) {
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

      $listCategories = $em->getRepository('OCPlatformBundle:Category')->findAll();

      foreach ($listCategories as $category){
          $advert->addCategory($category);
      }

      $em->flush();

    return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
      'advert' => $advert
    ));
  }
  public function editImageAction($advertId)
    {
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($advertId);

        // On modifie l'URL de l'image par exemple
        $advert->getImage()->setUrl('test.png');

        // On n'a pas besoin de persister l'annonce ni l'image.
        // Rappelez-vous, ces entités sont automatiquement persistées car
        // on les a récupérées depuis Doctrine lui-même

        // On déclenche la modification
        $em->flush();

        return new Response('OK');
    }

  public function deleteAction($id)
  {
    $em = $this->getDoctrine()->getManager();
      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);
      if(null === $advert){
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

      foreach ($advert->getcategories() as $category ){
          $advert->removeCategory($category);
      }
      $em->flush();

    return $this->render('OCPlatformBundle:Advert:delete.html.twig');
  }
}