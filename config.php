<?php

use osahp\products\ProductA;
use osahp\products\ProductB;
use osahp\products\ProductC;

return [

    'formatters' => [
        'supported' => [
            'json' => \osahp\formatters\JsonFormatter::class,
            'xml' => \osahp\formatters\XmlFormatter::class,
        ],
        'productDataFunc' => [
            ProductA::class => 'getDataFromA',
            ProductB::class => 'getDataFromB',
            ProductC::class => 'getDataFromC',
        ],
        'default' => 'json', // <-- SET DEFAULT FORMATTER HERE
    ],
];
