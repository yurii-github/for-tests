<?php

namespace App\Repository;

/*
 * NOTE: LIMITED IMPLEMENTATION!!! HARDCODED!!! NOT SOLID!!!
 *
 * but OK for test task!
 */

use App\Model\Client;
use App\Model\Deposit;

class DepositRepository
{

    public function insert(Deposit $deposit)
    {
        $result = \DB::insert('deposit',
            ['name', 'balance', 'deposit_percent'])
            ->values(
                [$deposit->name, $deposit->balance, $deposit->depositPercent])
            ->execute();

        if ($result[1] != 1) {
            throw new \Exception('FAIL');
        }

        //1to1
        $deposit->id = $result[0];
        \DB::insert('account_deposit', ['deposit_id', 'account_id'])->values([$deposit->id, $deposit->account->id])
            ->execute();
    }



    public function findAll() {
        /** @var \Database_Result_Cached $cache */

        /*
         * SELECT d.*, c.firstname, c.lastname from deposit d
JOIN account_deposit ad ON d.id = ad.deposit_id
JOIN account a ON ad.account_id = a.id
JOIN client c ON a.client_id = c.id;
         */
        $cache = \DB::select('d.*, c.firstname as c_firstname, c.lastname as c_lastname')->from(['deposit','d'])
            ->join(['account_deposit','ad'])->on('d.id','=','ad.deposit_id')
            ->join(['account','a'])->on('ad.account_id', '=', 'a.id')
            ->join(['client', 'c'])->on('a.client_id', '=', 'c.id')
            ->execute();

        $data = [];
        foreach ($cache as $row) {
            $client = new Client();
            //?TODO: FULL OBJECT LOAD
            $client->firstName = $row['c_firstname'];
            $client->lastName = $row['c_lastname'];

            $c = new Deposit();
            $c->id = $row['id'];
            $c->depositPercent = $row['deposit_percent'];
            $c->balance = $row['balance'];
            $c->name = $row['name'];
            $c->client = $client;

            $data[$row['id']] = $c;
        }

        return $data;
    }


}