<?php

require_once '../framework/Autoloader.php';

$cfg = array_merge(require '../app/config/config.php', require '../app/config/test.php');

framework\Application::run($cfg, false); //no response send, no real execution, just init