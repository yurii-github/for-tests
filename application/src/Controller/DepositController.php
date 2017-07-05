<?php
namespace App\Controller;

use App\Model\Account;
use App\Model\Client;
use App\Model\Deposit;
use App\Repository\AccountRepository;
use App\Repository\ClientRepository;
use App\Repository\DepositRepository;
use App\SOA\DepositManager;

class DepositController extends BaseController
{
    public function action_addDeposit() {
        $repoClient = new ClientRepository();

        $client_id = $_POST['client_id'];
        $client = $repoClient->find($client_id);

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
        $repo = new DepositRepository();
        $deposits = $repo->findAll();

        $repoClient = new ClientRepository();
        $clients = $repoClient->findAll();


        $this->template->set('content', \View::factory('default/deposits', [
            'deposits' => $deposits,
            'clients' => $clients
        ]));
    }



    public function action_reports() {
        $repo = new DepositRepository();
        $avgByGroup = $repo->report_AverageByGroup();

        $yearmonth = $repo->report_IncomeYearmonth();

        $this->template->set('content', \View::factory('default/reports', [
            'avgByGroup' => $avgByGroup,
            'yearmonth' => $yearmonth
        ]));
    }
//        /
    //}_AverageByGroup(






}