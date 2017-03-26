<?php
use Symfony\Component\Config\ConfigCache;

class AppNoCache extends AppKernel
{

    protected function initializeContainer()
    {
        // TODO: fix appDevDebugProjectContainer load fail. How to ignore cache fully?
        $class = $this->getContainerClass();
        $cache = new ConfigCache($this->getCacheDir() . '/' . $class . '.php', $this->debug);
        
        $container = $this->buildContainer();
        $container->compile();
        
        require_once $cache->getPath();
        
        $this->container = new $class();
        $this->container->set('kernel', $this);
    }
}
