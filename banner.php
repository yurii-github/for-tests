<?php
require_once 'vendor/autoload.php';

use \Test\BannerView;
use Test\Database;

// init
$config = file_exists(__DIR__.'/config.local.php') ? include __DIR__.'/config.local.php' : include __DIR__.'/config.php';
$pdo = new PDO('mysql:host=localhost;dbname=test-19-07-2017', $config['user'], $config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$db = new Database($pdo);
BannerView::setDb($db);


$view = BannerView::findByComplex(\Test\Helpers::getRealIP(), \Test\Helpers::getRequestUrl(), \Test\Helpers::getUserAgent());
if ($view === false) {
    $view = BannerView::create(\Test\Helpers::getRealIP(), \Test\Helpers::getRequestUrl(), \Test\Helpers::getUserAgent());
}
$view->incrementView(1);


header("Content-Type: image/jpeg");
echo file_get_contents(__DIR__.'/img.jpg');