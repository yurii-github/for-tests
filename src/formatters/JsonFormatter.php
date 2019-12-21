<?php

namespace osahp\formatters;

final class JsonFormatter implements FormatterInterface
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    /**
     * {@inheritdoc}
     */
    public function format()
    {
        return json_encode($this->data);
    }
}
