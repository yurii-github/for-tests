<?php
namespace App\Controller;

use App\SOA\DepositManager;

class BaseController extends \Controller_Template {

    private $services;

    private $knownServices = ['deposit' => DepositManager::class];

    public function & get($service) {
        if (!empty($this->services[$service])) {
            return $this->services[$service];
        }

        //$this->services[$service] = new $this->knownServices[$service]();
    }
}
