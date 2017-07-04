<?php

namespace App\SOA;

use App\Model\Account;
use App\Model\Client;
use App\Model\Deposit;
use App\Repository\AccountRepository;
use App\Repository\DepositRepository;
use Prophecy\Exception\Exception;

class DepositManager {

    private $repoClient;
    private $repoAccount;
    private $repoDeposit;

    public function __construct(DepositRepository $repoDeposit, AccountRepository $repoAccount)
    {
        $this->repoAccount = new AccountRepository();
        $this->repoDeposit = new DepositRepository();
    }


    public function addDeposit(Account $account, $name, $amount, $percent) {
        if ($account->balance < $amount) {
            throw new \Exception('FAIL');
        }

        // TODO: transaction -->
        $account->balance = $account->balance - $amount;
        $this->repoAccount->update($account);

        $deposit = new Deposit();
        $deposit->balance = $amount;
        $deposit->account = $account;
        $deposit->name = $name;
        $deposit->depositPercent = $percent;// STRATEGY
        $this->repoDeposit->insert($deposit);
        // <---
        return $deposit;
    }


    public function payEarnings(\Date $date) {

    }


}