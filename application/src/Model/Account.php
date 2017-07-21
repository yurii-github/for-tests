<?php

namespace App\Model;

class Account
{
    //TODO: security checks

    public $id;
    public $balance;

    /**
     * Nto1
     * @var Client
     */
    public $client;

    /**
     * 1to1
     * @var Deposit
     */
    public $deposit;
}