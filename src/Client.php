<?php

namespace osahp;

use osahp\formatters\FormatterInterface;

class Client
{
    private $config;


    public function __construct(array $config = [])
    {
        //TODO: add config checks
        $this->config = array_merge([
            'formatters' => [],
            'defaults' => [
                'formatter' => null,
            ]
        ], $config);
    }


    public function format(object $product, FormatterInterface $formatter = null)
    {
        echo get_class($product);
        //TODO: add formatter
        //$formatter->format();
    }

}
