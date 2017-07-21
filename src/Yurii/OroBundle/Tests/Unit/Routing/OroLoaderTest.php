<?php
namespace Yurii\OroBundle\Tests\Unit\Routing;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Yurii\OroBundle\Routing\OroLoader;

class OroLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testLoad()
    {
        $rc = new routecollection();
        $rc->add('contact', new route('/contact'));

        $loader = new oroloader();
        $new_rc = $loader->load($rc);

        $this->assertequals(3, count($new_rc->all()));

        $fr = $new_rc->get('example_com_fr_contact');
        $this->assertEquals('/fr/contact', $fr->getPath());
        $this->assertEquals('fr', $fr->getDefault('_locale'));

        $fr = $new_rc->get('example_fr_fr_contact');
        $this->assertEquals('/contact', $fr->getPath());
        $this->assertEquals('fr', $fr->getDefault('_locale'));
    }
}
