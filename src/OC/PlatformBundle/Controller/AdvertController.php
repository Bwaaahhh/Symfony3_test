<?php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image ;
use OC\PlatformBundle\Entity\Application ;
use OC\PlatformBundle\Entity\AdvertSkill ;
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
        $em = $this->getDoctrine()->getManager() ;
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if(null === $advert){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas. ");
        }

        $listApplications = $em
            ->getRepository('OCPlatformBundle:Application')
            ->findBy(array('advert'=>$advert))
        ;

        $listAdvertSkills = $em
            ->getRepository('OCPlatformBundle:AdvertSkill')
            ->findBy(array('advert'=>$advert))
        ;

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'advert'=>$advert,
            'listApplications'=>$listApplications,
            'listAdvertSkills'=>$listAdvertSkills
        ));
    }

    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;

        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony.');
        $advert->setAuthor('Adeline');
        $advert->setContent('Nous recherchons un développeur Symfony débutant sur Besancon. Lorem ...');

        $listSkill = $em->getRepository('OCPlatformBundle:Skill')->findAll() ;
        foreach($listSkill as $skill){
            $advertSkill = new AdvertSkill() ;

            $advertSkill->setAdvert($advert) ;
            $advertSkill->setSkill($skill) ;
            $advertSkill->setLevel('Expert') ;

            $em->persist($advertSkill) ;
        }

        $em->persist($advert) ;
    //    $em->flush() ;

        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg') ;
        $image->setAlt('Job de reve') ;

        $advert->setImage($image) ;

        $application1 = new Application() ;
        $application1->setAuthor('Thomas');
        $application1->setContent('Je suis baleze');

        $application2 = new Application() ;
        $application2->setAuthor('Adede');
        $application2->setContent('Moi moi moi');

        $application1->setAdvert($advert) ;
        $application2->setAdvert($advert) ;


    //    $em=$this->getDoctrine()->getManager();
    //    $em->persist($advert);

        $em->persist($application1) ;
        $em->persist($application2) ;
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
        $em = $this->getDoctrine()->getManager() ;

        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id) ;

        if( null === $advert ){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $listCategories = $em->getRepository('OCPlatformBundle:Category')->findAll();

        foreach($listCategories as $category){
            $advert->addCategory($category);
        }

        $em->flush();

        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
            'advert'=>$advert
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager() ;

        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id) ;

        if( null === $advert ){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        foreach ( $advert->getCategories() as $category ){
            $advert->removeCategory($category);
        }

        $em->flush() ;

        return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }
}