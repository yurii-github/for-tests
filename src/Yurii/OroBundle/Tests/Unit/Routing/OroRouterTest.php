<?php
namespace Yurii\OroBundle\Tests\Unit\Routing;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Yurii\OroBundle\Routing\OroLoader;
use Yurii\OroBundle\Routing\OroRouter;


class OroRouterTest extends \PHPUnit_Framework_TestCase
{
    private function getRouter()
    {
        $container = new Container();
        $container->set('routing.loader', new YamlFileLoader(new FileLocator(__DIR__.'/Fixture')));
        $container->set('oro.loader', new OroLoader());

        $router = new OroRouter($container, 'routing.yml');
        $router->setOroLoader('oro.loader');

        return $router;
    }

    public function testRouteCollection()
    {
        $router = $this->getRouter();
        $rc = $router->getRouteCollection();

        $this->assertequals(3, count($rc->all()));

        $fr = $rc->get('example_com_fr_homepage');
        $this->assertEquals('/fr/contact', $fr->getPath());
        $this->assertEquals('fr', $fr->getDefault('_locale'));

        $fr = $rc->get('example_fr_fr_homepage');
        $this->assertEquals('/contact', $fr->getPath());
        $this->assertEquals('fr', $fr->getDefault('_locale'));
    }

    /**
     * test absolute url
     * @return void|OroRouter|I18nRouter
     */
    public function testGenerate()
    {
        $router = $this->getRouter();

        $this->assertEquals('http://example.com/contact', $router->generate('homepage'));
    }
}