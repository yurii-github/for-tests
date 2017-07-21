<?php


namespace Yurii\OroBundle\Tests\Functional\TestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController
{
    /**
     * @Route("/", name = "homepage")
     * @Template
     */
    public function indexAction(Request $request)
    {

        // replace this example code with whatever you need
   //     return $this->render('default/index.html.twig', [
   //         'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
   //     ]);


        $locale = method_exists($request, 'getLocale') ? $request->getLocale()
            : $request->getSession()->getLocale();

        return array('locale' => $locale);
    }
}