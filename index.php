<?php
error_reporting(E_ALL);
ini_set('error_log', __DIR__ . '/errors.log');

require_once 'framework/Autoloader.php';

\framework\Application::run();