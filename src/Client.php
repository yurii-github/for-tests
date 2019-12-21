<?php

namespace osahp;

use osahp\exceptions\ProductUnknown;
use osahp\formatters\FormatterInterface;

class Client
{
    private $config;
    /** @var string */
    private $formatterClass;


    /**
     * Client constructor.
     * @param array $config
     * @param string|null $formatter xml|json
     */
    public function __construct(array $config = [], string $formatter = null)
    {
        $this->config = array_merge([], $config);

        $this->setFormatter($formatter);
    }


    public function setDefaultFormatter()
    {
        $this->formatterClass = $this->config['formatters']['supported'][$this->config['formatters']['default']];
    }


    public function setFormatter(?string $formatter)
    {
        if (!isset($formatter)) {
            $this->setDefaultFormatter();
            return;
        }

        if (in_array($formatter, array_keys($this->config['formatters']['supported']))) {
            $this->formatterClass = $this->config['formatters']['supported'][$formatter];
        } else {
            throw new \InvalidArgumentException();
        }
    }


    /**
     * @param object $product
     * @return string
     * @throws ProductUnknown
     */
    public function format(object $product)
    {
        $dataFunc = $this->config['formatters']['productDataFunc'][get_class($product)];

        if (!$dataFunc) {
            throw new ProductUnknown("Cannot find proper 'productDataFunc' in config mapping for " . get_class($product));
        }

        $formatter = new $this->formatterClass(['data' => call_user_func([$product, $dataFunc])]);

        if (!$formatter instanceof FormatterInterface) {
            throw new \InvalidArgumentException("MUST BE INSTANCE OF " . FormatterInterface::class);
        }

        return $formatter->format();
    }

}
