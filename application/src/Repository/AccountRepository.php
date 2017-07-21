<?php

namespace App\Repository;

/*
 * NOTE: LIMITED IMPLEMENTATION!!! HARDCODED!!! NOT SOLID!!!
 *
 * but OK for test task!
 */

use App\Model\Account;


class AccountRepository
{

    public function insert(Account $account)
    {
        $result = \DB::insert('account',
            ['balance', 'client_id'])
            ->values(
                [$account->balance, $account->client->id])
            ->execute();

        if ($result[1] != 1) {
            throw new \Exception('FAIL');
        }
        $account->id = $result[0];
    }

    public function update(Account $account)
    {
        $result = \DB::update('account')->set(['balance' => $account->balance])->where('id', '=', $account->id)->execute();

        if ($result !== 1) {
            throw new \Exception('FAIL');
        }
    }
}