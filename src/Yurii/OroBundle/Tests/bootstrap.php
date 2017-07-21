<?php

// TODO: adjust autoload when standalone bundle
$autoloadFile = $autoloadFile = __DIR__.'/../../../../vendor/autoload.php';
$loader = require $autoloadFile;
Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));