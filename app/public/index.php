<?php
//error_reporting(E_ALL);

require_once '../../framework/Autoloader.php';

$cfg = require '../config/config.php';
@ini_set('error_log', $cfg['basePath'] . '/data/errors.log');

\framework\Application::run($cfg);