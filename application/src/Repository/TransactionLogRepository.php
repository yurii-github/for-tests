<?php

namespace App\Repository;

/*
 * NOTE: LIMITED IMPLEMENTATION!!! HARDCODED!!! NOT SOLID!!!
 *
 * but OK for test task!
 */
use App\Model\TransactionLog;

class TransactionLogRepository
{

    public function insert(TransactionLog $transactionLog)
    {
        $result = \DB::insert('transaction_log',
            ['client_id', 'type', 'percent', 'amount', 'deposit_id', 'log_date', 'deposit_balance_before'])
            ->values(
                [$transactionLog->client->id, $transactionLog->type, $transactionLog->percent,
                    $transactionLog->amount, $transactionLog->deposit->id,
                    $transactionLog->logDate->format('Y-m-d'), $transactionLog->depositBalanceBefore])
            ->execute();

        if ($result[1] != 1) {
            throw new \Exception('FAIL');
        }

    }


}