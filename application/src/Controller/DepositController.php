<?php

namespace App\Controller;

use App\Model\Account;
use App\Repository\AccountRepository;
use App\Repository\ClientRepository;
use App\Repository\DepositRepository;

class DepositController extends BaseController
{
    public function action_addDeposit()
    {
        $client = (new ClientRepository())->find($_POST['client_id']);

        if (!$client) {
            throw new \Exception('FAIL');
        }

        $repoAccount = new AccountRepository();

        $account = new Account();
        $account->client = $client;
        $account->balance = $_POST['deposit_balance'];
        $repoAccount->insert($account);

        $deposit = $this->get('deposit')
            ->addDeposit($account, $_POST['deposit_name'], $account->balance, $_POST['deposit_percent'],
                \DateTime::createFromFormat('d/m/Y', $_POST['deposit_opendate']));

        \HTTP::redirect(\Route::get('deposits')->uri());
    }

    public function action_deposits()
    {
        $this->template->set('content', \View::factory('default/deposits', [
            'deposits' => (new DepositRepository())->findAll(),
            'clients' => (new ClientRepository())->findAll()
        ]));
    }

    public function action_reports()
    {
        $repo = new DepositRepository();

        $this->template->set('content', \View::factory('default/reports', [
            'avgByGroup' => $repo->report_AverageByGroup(),
            'yearmonth' => $repo->report_IncomeYearmonth()
        ]));
    }

}