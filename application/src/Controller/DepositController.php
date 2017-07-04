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
        $depoManager = new DepositManager(new DepositRepository(), $repoAccount);

        $account = new Account();
        $account->client = $client;
        $account->balance = $_POST['deposit_balance'];
        $repoAccount->insert($account);

        $deposit = $depoManager->addDeposit($account, $_POST['deposit_name'], $account->balance, $_POST['deposit_percent']);

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

        //JSON $this->template = \View::factory('_json', ['content' => ]);

    }


}