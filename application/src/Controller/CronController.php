<?php
namespace App\Controller;

use App\Model\Account;
use App\Model\Client;
use App\Model\Deposit;
use App\Repository\AccountRepository;
use App\Repository\ClientRepository;
use App\Repository\DepositRepository;
use App\SOA\DepositManager;
use Prophecy\Exception\Exception;

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

        $date = \DateTime::createFromFormat('d/m/Y', $_POST['date']);
        //TODO: add checks
        if (!$date) {
            return $this->template = \View::factory('_json', ['content' => ['data' => 'wrong date format']]);
        }

        $this->template = \View::factory('_json', ['content' => ['data' => [
            'payed' => $this->get('deposit')->payDay($date),
            'commissioned' => $this->get('deposit')->commissionDay($date)
        ]]]);
    }

}