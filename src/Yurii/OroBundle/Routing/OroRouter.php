<?php

namespace Yurii\OroBundle\Routing;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RequestContext;

class OroRouter extends Router
{

    /**
     * our fake loader id
     *
     * @var string
     */
    private $oroLoaderId;

    /**
     * cannot access from parent, re-declare
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     *
     * @param ContainerInterface $container
     * @param unknown $resource
     * @param array $options
     * @param RequestContext $context
     */
    public function __construct(ContainerInterface $container, $resource, array $options = array(), RequestContext $context = null)
    {
        parent::__construct($container, $resource, $options, $context);
        // re-declaring container, we need it for getting our loader
        $this->container = $container;
    }

    /**
     * sets our fake loader.
     * used as method injector
     *
     * @param string $oroLoaderId
     *            loader id
     */
    public function setOroLoader($oroLoaderId)
    {
        $this->oroLoaderId = $oroLoaderId;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Symfony\Bundle\FrameworkBundle\Routing\Router::getRouteCollection()
     */
    public function getRouteCollection()
    {
        $collection = parent::getRouteCollection();
        return $this->container->get($this->oroLoaderId)->load($collection);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Routing\Router::generate()
     */
    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        $currentLocale = $this->context->getParameter('_locale');
        // force absolute
        $referenceType = self::ABSOLUTE_URL;

        //our link generator
        if ($currentLocale == 'fr') {
            if ($this->context->getHost() == 'example.fr') {
                $name = 'example_fr_fr_' . $name;
            } else {
                $name = 'example_com_fr_' . $name;
            }
        }

        return $this->getGenerator()->generate($name, $parameters, $referenceType);
    }
}