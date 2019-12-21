#!/usr/bin/env php
<?php

use \osahp\Client;
use \osahp\products;

require_once 'vendor/autoload.php';

$products = [
    new products\ProductA(),
    new products\ProductB(),
    new products\ProductC(),
    //new products\ProductUnknown(),
];

echo "\n----------\nJSON:\n";
$client = new Client(require 'config.php');
foreach ($products as $product) {
    echo $client->format($product) . "\n";
}

echo "\n----------\nXML:\n";
$client = new Client(require 'config.php', 'xml');
foreach ($products as $product) {
    echo $client->format($product) . "\n";
}
