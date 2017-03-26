<?php
namespace Yurii\OroBundle\Routing;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class OroLoader
{

    /**
     * Fake Loader.
     * Tweaks routes
     *
     * @param RouteCollection $collection            
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function load(RouteCollection $collection)
    {
        $rc = new RouteCollection();
        
        foreach ($collection->getIterator() as $name => &$route) {
            // TODO: make as config strategies, see JMS
            
            // make default as example.com
            $host = 'example.com';
            $new = clone $route;
            $new->setHost($host);
            $rc->add($name, $new);
            
            // add example.fr
            $locale = 'fr';
            $host = 'example.fr';
            $new = clone $route;
            $new->setHost($host);
            $new->setRequirement('_locale', $locale);
            $new->setDefault('_locale', $locale);
            $new->setPath($new->getPath());
            $rc->add('example_fr_' . $locale . '_' . $name, $new);
            
            // add example.com/fr
            $locale = 'fr';
            $host = 'example.com';
            $new = clone $route;
            $new->setHost($host);
            $new->setRequirement('{_locale}', $locale);
            $new->setDefault('_locale', $locale);
            $new->setPath('/fr' . $new->getPath());
            $rc->add('example_com_fr_' . $name, $new);
        }
        
        return $rc;
    }
}