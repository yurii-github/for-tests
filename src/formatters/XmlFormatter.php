<?php

namespace osahp\formatters;

final class XmlFormatter implements FormatterInterface
{
    protected $data;


    public function __construct(array $data)
    {
        $this->data = $data;
    }


    /**
     * Converts php array to xml document
     *
     * @see https://www.geeksforgeeks.org/how-to-convert-array-to-simplexml-in-php/
     *
     * @param array $array
     * @param null $rootElement
     * @param null $xml
     * @return mixed
     */
    protected function arrayToXml(array $array, $rootElement = null, $xml = null)
    {
        $_xml = $xml;
        // If there is no Root Element then insert root
        if ($_xml === null) {
            $_xml = new \SimpleXMLElement($rootElement !== null ? $rootElement : '<root/>');
        }
        // Visit all key value pair
        foreach ($array as $k => $v) {
            // If there is nested array then
            if (is_array($v)) {
                // Call function for nested array
                $this->arrayToXml($v, $k, $_xml->addChild($k));
            } else {
                // Simply add child element.
                $_xml->addChild($k, $v);
            }
        }
        return $_xml->asXML();
    }


    protected function stripXml($xml, $rootElement = 'root')
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xml);

        /** @var \DOMElement $root */
        $root = $doc->getElementsByTagName($rootElement)->item(0);
        if (!$root->hasChildNodes()) {
            return '';
        }

        $stripped = [];
        foreach ($root->childNodes as $childNode) {
            /** @var $childNode \DOMElement */
            $nodeXML = $doc->saveXML($childNode);
            $stripped[] = $nodeXML;
        }

        return implode('', $stripped);
    }


    /**
     * {@inheritdoc}
     */
    public function format()
    {
        $xml = $this->arrayToXml($this->data);
        return $this->stripXml($xml);
    }
}
