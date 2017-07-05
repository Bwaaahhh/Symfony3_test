<?php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\OCPlatformBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\DateTime;

class AdvertController extends Controller
{

    public function indexAction()
    {
        $listAdverts = array(
            array(
                'title'=>'Recherche développeur Symfony',
                'id'=>1,
                'author'=>'Thomas',
                'content'=>'nous recherchons un développeur Symfony débutant sur Besancon. Lorem ...',
                'date'=>new \DateTime()
            ),
            array(
                'title'=>'Mission de webmaster',
                'id'=>2,
                'author'=>'Cyril',
                'content'=>'Nous recherchons un webmaster capable de maintenir notre site internet. Lorem... ',
                'date'=>new \DateTime()
            ),
            array(
                'title'=>'Offre de stage webdesigner',
                'id'=>3,
                'author'=>'Adeline',
                'content'=>'Nous proposons un poste pour webdesigner. Lorem ... ',
                'date'=>new \DateTime()
            )
        );
        return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
            'listAdverts'=>$listAdverts
        ));
    }

    public function menuAction($limit)
    {
        $listAdverts=array(
            array('id'=>2, 'title'=>'Recherche développeur Symfony'),
            array('id'=>5, 'title'=>'Mission de Webmaster'),
            array('id'=>9, 'title'=>'Offre de stage webdesigner')
        );
        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
            'listAdverts' => $listAdverts
        ));
    }

    public function viewAction($id)
    {
        $repository=$this->getDoctrine()
            ->getManager()
            ->getRepository('OCPlatformBundle:Advert');

        $advert=$repository->find($id);

        if(null === $advert){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas. ");
        }

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'advert'=>$advert
        ));
    }

    public function addAction(Request $request)
    {
        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony.');
        $advert->setAuthor('Thomas');
        $advert->setContent('Nous recherchons un développeur Symfony débutant sur Besancon. Lorem ...');

        $em=$this->getDoctrine()->getManager();
        $em->persist($advert);
        $em->flush();

        if($request->isMethod('POST')){
            $request->get('session')->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

        return $this->redirectToRoute('oc_platform_view',
            array('id'=>$advert->getId()));
        }
        return $this->render('OCPlatformBundle:Advert:add.html.twig');
    }

    public function editAction($id, Request $request)
    {
        $advert = array(
            'title' => 'Recherche développeur Symfony',
            'id' => $id,
            'author' => 'Thomas',
            'content' => 'nous recherchons un développeur Symfony débutant sur Besancon. Lorem ...',
            'date' => new \DateTime()
        );

        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
            'advert'=>$advert
        ));
    }

    public function deleteAction($id)
    {
        return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }
}