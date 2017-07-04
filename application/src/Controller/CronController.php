<?php
namespace App\Controller;

use App\Model\Account;
use App\Model\Client;
use App\Model\Deposit;
use App\Repository\AccountRepository;
use App\Repository\ClientRepository;
use App\Repository\DepositRepository;
use App\SOA\DepositManager;

class CronController extends BaseController {

    public function action_index()
    {
        $this->template->set('content', \View::factory('default/cron'));
    }


    public function action_run()
    {
        if($_POST['secretkey'] != 'ZzZ') {
            return $this->template = \View::factory('_json', ['content' => []]);
        }

        $date = \DateTime::createFromFormat('dd/mm/Y', $_POST['date']);
        //TODO: add checks


        $this->template = \View::factory('_json', ['content' => ['data' => '13123123']]);

        //$this->template->set('content', \View::factory('default/cron'));
    }

}