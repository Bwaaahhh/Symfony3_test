<?php

namespace OC\PlatformBundle\Controller ;

use Symfony\Bundle\FrameworkBundle\Controller\Controller ;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\HttpFoundation\Response ;

class AdvertController extends Controller
{
    public function viewAction($id, Request $request)
    {
        $tag = $request->query->get('tag');

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'id' => $id ,
            'tag' => $tag,
        ));
    }

    public function viewSlugAction($slug , $year , $format)
    {
        return new Response(
            "On pourrait afficher l'annonce correspondant au slug
             '".$slug."', crée en ".$year." et au format ".$format."."
        );
    }

    public function indexAction()
    {
        $url = $this->get('router')->generate(
            'oc_platform_view',
            array('id' => 5 )
        );

        return new Response("L'URL de l'annonce d'id 5 est ".$url);
//        $content = $this
//            ->get('templating')
//            ->render('OCPlatformBundle:Advert:index.html.twig',array('nom'=>'Bwaaahhh'));
//
//        return new Response($content);
    }


}