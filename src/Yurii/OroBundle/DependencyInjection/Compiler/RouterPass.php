<?php
namespace Yurii\OroBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RouterPass implements CompilerPassInterface
{

    /**
     *
     * {@inheritdoc}
     *
     * @see \Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface::process()
     */
    public function process(ContainerBuilder $container)
    {
        $container->setAlias('router', 'oro.router');
    }

}