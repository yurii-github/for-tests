<?php
namespace Yurii\OroBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yurii\OroBundle\DependencyInjection\Compiler\RouterPass;

/**
 * Bundle.
 *
 * @author Yurii K.
 */
class OroBundle extends Bundle
{
    /**
     * {@inheritDoc}
     * @see \Symfony\Component\HttpKernel\Bundle\Bundle::build()
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RouterPass());
    }
}