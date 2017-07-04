<?php

namespace OC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\HttpFoundation\Response;


class CoreController extends Controller
{
    public function indexAction()
    {
        return $this->render('OCCoreBundle:Core:index.html.twig');
    }

    public function contactAction()
    {
        $this->get('session')->getFlashBag()->add('info', 'La page de contact nest pas encore disponible') ;
        return $this->render('OCCoreBundle:Core:contact.html.twig');
    }
}
