<?php

return [
    'formatters' => [
        \osahp\formatters\JsonFormatter::class,
        \osahp\formatters\XmlFormatter::class,
    ],
    'defaults' => [
        'formatter' => \osahp\formatters\JsonFormatter::class
    ],
];
