<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

//forms
use OC\PlatformBundle\Form\AdvertType;
use OC\PlatformBundle\Form\AdvertEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OC\PlatformBundle\Entity\Advert;

class AdvertController extends Controller
{
    public function testAction(){
        $advert = new Advert();
        $advert->setDate(new \DateTime());
        $advert->setTitle('abc');
        $advert->setAuthor('a');

        $validator = $this->get('validator');
        $listErrors = $validator->validate($advert);

        if(count($listErrors) > 0){
            return new Response((string) $listErrors);

        }else{
            return new Response('The annonce is valid');

        }

    }
    public function indexAction($page)
  {
      $nbPerPage = 3;
      $repo = $this->getDoctrine()->getManager()->getRepository('OCPlatformBundle:Advert');
      $listAdverts = $repo->getAdverts($page, $nbPerPage);
      $nbPages = ceil(count($listAdverts)/$nbPerPage);
      // On ne sait pas combien de pages il y a
      // Mais on sait qu'une page doit être supérieure ou égale à 1
      if ($page > $nbPages) {
          // On déclenche une exception NotFoundHttpException, cela va afficher
          // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
          throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
      }

      // Ici, on récupére la liste des annonces, puis on la passera au template
      //var_dump($listAdverts);
    return $this->render('OCPlatformBundle:Advert:index.html.twig', ['listAdverts'=> $listAdverts, 'page' => $page, 'nbPages' => $nbPages]);
  }
  public function menuAction($limit = 3) {

      $em = $this->getDoctrine()->getManager();
      $repo = $em->getRepository('OCPlatformBundle:Advert');
      $listAdvert = $repo->findBy(array(),//pas de criteres
                              array('date' => 'desc'), //Ordre desc
                                $limit); //Limite;
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

      $listSkills = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('OCPlatformBundle:AdvertSkill')
                            ->findBy(array('advert'=>$advert));

      // Le render ne change pas, on passait avant un tableau, maintenant un objet
      return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
          'advert' => $advert,
          'listapplication' => $listapplication,
          'listAdvertSkills' => $listSkills
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
      $form = $this->createForm(AdvertType::class, $advert);

      $em = $this->getDoctrine()->getManager();
      if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          //$advert->getImage()->upload(); // Upload de l'image sans utiliser les events doctrine
          $em->persist($advert);
          $em->flush();
          $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
          return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $advert->getId())));

      }
      /*if () {

          $listSkills = $em->getRepository("OCPlatformBundle:Skill")->findAll();
          foreach($listSkills as $skill){
              $advertSkill = new AdvertSkill();
              $advertSkill->setSkill($skill);
              $advertSkill->setAdvert($advert);
              $advertSkill->setLevel('Expert');

              $em->persist($advertSkill);
          }
          // Flush des données
          // Ici, on s'occupera de la création et de la gestion du formulaire


          // Puis on redirige vers la page de visualisation de cettte annonce

    }*/

    // Si on n'est pas en POST, alors on affiche le formulaire
    return $this->render('OCPlatformBundle:Advert:add.html.twig', array('form' => $form->createView()));
  }

  public function editAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();
      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);
      $form = $this->get('form.factory')->create(AdvertEditType::class, $advert);

      //associe la requete à l'objet advert
      $form->handleRequest($request);
      $em = $this->getDoctrine()->getManager();
      if($form->isValid()) {


          $em->flush();

      }
      if (null === $advert) {
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

    return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
      'advert' => $advert,
        'form' => $form->createView()
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

  public function deleteAction($id, Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);
      if(null === $advert){
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

      $form = $this->get('form.factory')->create();
      if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
          $em->remove($advert);
          $em->flush();
          $request->getSession()->getFlashBag()->add('info', 'annonce correctement supprimée');
          return $this->redirectToRoute('oc_platform_home');
      }else {
          return $this->render('OCPlatformBundle:Advert:delete.html.twig', array('advert' => $advert, 'form' => $form->createView()));
      }
  }
}