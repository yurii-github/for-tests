#!/usr/bin/env php
<?php

use \osahp\Client;
use \osahp\products;

require_once 'vendor/autoload.php';

$client = new Client(require 'config.php');

$client->format(new products\ProductA());
$client->format(new products\ProductB());
$client->format(new products\ProductC());
