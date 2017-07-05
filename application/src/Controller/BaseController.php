<?php
namespace App\Controller;

use App\Repository\AccountRepository;
use App\Repository\DepositRepository;
use App\Repository\TransactionLogRepository;
use App\SOA\DepositManager;

class BaseController extends \Controller_Template {

    private $services;

    private $knownServices = ['deposit' => DepositManager::class];

    public function & get($service) {
        if (!empty($this->services[$service])) {
            return $this->services[$service];
        }

        if (in_array($service, array_keys($this->knownServices))) {

            if ($service == 'deposit') {
                $this->services[$service] = new $this->knownServices[$service](new DepositRepository(),
                new AccountRepository(), new TransactionLogRepository());
                return $this->services[$service];
            }
        }


        throw new \Exception('FAIL');
    }
}
