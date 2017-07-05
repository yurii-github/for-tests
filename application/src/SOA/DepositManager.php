<?php

namespace App\SOA;

use App\Model\Account;
use App\Model\Deposit;
use App\Model\TransactionLog;
use App\Repository\AccountRepository;
use App\Repository\DepositRepository;
use App\Repository\TransactionLogRepository;

class DepositManager
{

    private $repoTransactionLog;
    private $repoAccount;
    private $repoDeposit;

    public function __construct(DepositRepository $repoDeposit, AccountRepository $repoAccount, TransactionLogRepository $repoTransactionLog)
    {
        $this->repoAccount = $repoAccount;
        $this->repoDeposit = $repoDeposit;
        $this->repoTransactionLog = $repoTransactionLog;
    }


    /**
     * RULE: Клиенты могут вкладывать деньги под разные проценты. Каждый депозит находится на отдельном счету.
     *
     * @param Account $account
     * @param $name
     * @param $amount
     * @param $percent
     * @param \DateTime $cheatOpenDate
     * @return Deposit
     * @throws \Exception
     */
    public function addDeposit(Account $account, $name, $amount, $percent, \DateTime $cheatOpenDate)
    {
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
        $deposit->openDate = $cheatOpenDate;
        $this->repoDeposit->insert($deposit);
        // <---
        return $deposit;
    }


    /**
     * На все депозиты, согласно процентам, каждый месяц начисляется сумма и добавляется к сумме депозита
     * (депозит с капитализацией). Каждый депозит выдается на индивидуальных условиях.
     * @param $date
     */
    public function payDay($date)
    {

        $totalPayed = 0;
        $totalDepositCount = 0;

        // limited, loop in cron until all is done
        /**
         * @var Deposit[] $deposits
         */
        while (!empty($deposits = $this->repoDeposit->findToPayLimit100($date))) {
            foreach ($deposits as $deposit) {
                $balanceBefore = $deposit->balance;
                $amount = $deposit->balance * ($deposit->depositPercent / 100);
                $deposit->balance += $amount;

                $log = new TransactionLog();
                $log->amount = $amount;
                $log->deposit = $deposit;
                $log->depositBalanceBefore = $balanceBefore;
                $log->type = TransactionLog::TYPE_PAYMENT;
                $log->percent = $deposit->depositPercent;
                $log->logDate = $date;
                $log->client = $deposit->client;

                $this->repoDeposit->update($deposit);
                $this->repoTransactionLog->insert($log);

                $totalDepositCount++;
                $totalPayed += $amount;
            }
        }

        return ['totalCount' => $totalDepositCount, 'totalAmount' => $totalPayed];
    }

    /**
     * Со всех депозитов каждый месяц снимается комиссия за использование счета. Комиссия зависит от суммы на счету:
     * Баланс на счету: 0 - до 1000 у.е. Комиссия 5%, но не менее чем 50 у.е.
     * Баланс на счету: 1000 у.е. - до 10,000 у.е. Комисcия 6%
     * Баланс на счету: от 10,000 у.е. Комиссия 7%, но не более чем 5000 у.е.
     * @param $date
     */
    public function commissionDay($date)
    {
        $totalPayed = 0;
        $totalDepositCount = 0;

        if ($date->format('d') != 1) {
            return ['totalCount' => $totalDepositCount, 'totalAmount' => $totalPayed];
        }

        //TODO: as strategy
        $small = function ($amount) {
            $com = $amount * 0.05;
            return $com < 50 ? 50 : $com;
        };
        $medium = function ($amount) {
            $com = $amount * 0.06;
            return $com;
        };
        $big = function ($amount) {
            $com = $amount * 0.07;
            return $com > 5000 ? 5000 : $com;
        };


        /**
         * @var Deposit[] $deposits
         */
        while (!empty($deposits = $this->repoDeposit->findToCommission100($date))) {
            foreach ($deposits as $deposit) {
                $commission = 0;

                if ($deposit->balance >= 10000) {
                    $commission = $big($deposit->balance);
                } elseif ($deposit->balance < 10000 && $deposit->balance >= 1000) {
                    $commission = $medium($deposit->balance);
                } else {
                    $commission = $small($deposit->balance);
                }

                // less than month
                if ($deposit->openDate->diff($date)->m < 1) {
                    $commission = $commission / (int)$deposit->openDate->format('d');
                }

                if ($commission > $deposit->balance) {
                    $commission = $deposit->balance;
                    //TODO: no money LEFT!
                }

                $amount = $commission;

                $balanceBefore = $deposit->balance;
                $deposit->balance -= $amount;

                $log = new TransactionLog();
                $log->amount = $amount;
                $log->deposit = $deposit;
                $log->depositBalanceBefore = $balanceBefore;
                $log->type = TransactionLog::TYPE_COMMISSION;
                $log->percent = null;
                $log->logDate = $date;
                $log->client = $deposit->client;

                $this->repoDeposit->update($deposit);
                $this->repoTransactionLog->insert($log);

                $totalPayed += $amount;
                $totalDepositCount++;
            }
        }

        return ['totalCount' => $totalDepositCount, 'totalAmount' => $totalPayed];
    }


}