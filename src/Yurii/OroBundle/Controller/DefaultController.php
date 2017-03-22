<?php

namespace Yurii\OroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OroBundle:Default:index.html.twig');
    }
}
